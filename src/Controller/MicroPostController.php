<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Form\MicroPostType;
use App\Service\MicroPostService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/micro-posts')]
class MicroPostController extends AbstractController
{
    private MicroPostService $microPostService;

    public function __construct(MicroPostService $microPostService)
    {
        $this->microPostService = $microPostService;
    }

    #[Route('/', name: 'micro_post_index')]
    public function index(): Response
    {
        $posts = $this->microPostService->getLatestPosts(10);

        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'micro_post_show')]
    public function show(int $id): Response
    {
        $post = $this->microPostService->getPostById($id);

        if (!$post) {
            throw $this->createNotFoundException('The post does not exist');
        }

        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/create', name: 'micro_post_create')]
    #[IsGranted('MICROPOST_CREATE')]
    public function create(Request $request, Security $security): Response
    {
        $post = new MicroPost();
        $form = $this->createForm(MicroPostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $security->getUser();

            if ($user instanceof User) {
                $this->microPostService->createPost($post, $user);
                return $this->redirectToRoute('micro_post_show', ['id' => $post->getId()]);
            } else {
                $this->addFlash('error', 'You need to be logged in to create a post.');
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('micro_post/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'micro_post_edit')]
    #[IsGranted('MICROPOST_EDIT', 'post')]
    public function edit(Request $request, MicroPost $post): Response
    {
        $form = $this->createForm(MicroPostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->microPostService->updatePost($post);
            return $this->redirectToRoute('micro_post_show', ['id' => $post->getId()]);
        }

        return $this->render('micro_post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id<\d+>}/delete', name: 'micro_post_delete', methods: ['POST'])]
    #[IsGranted('MICROPOST_DELETE', 'post')]
    public function delete(MicroPost $post): Response
    {
        $this->microPostService->deletePost($post);
        return $this->redirectToRoute('micro_post_index');
    }
}