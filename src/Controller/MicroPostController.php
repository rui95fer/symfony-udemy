<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use App\Service\BreadcrumbService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/micro-posts')]
class MicroPostController extends AbstractController
{
    private MicroPostRepository $repository;
    private EntityManagerInterface $entityManager;
    private BreadcrumbService $breadcrumbService;

    public function __construct(MicroPostRepository $repository, EntityManagerInterface $entityManager, BreadcrumbService $breadcrumbService)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->breadcrumbService = $breadcrumbService;
    }

    #[Route('/', name: 'micro_post_index')]
    public function index(): Response
    {
        $this->breadcrumbService->clear();
        $this->breadcrumbService->add('Micro Posts', $this->generateUrl('micro_post_index'));

        $posts = $this->repository->findLatest(10);

        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts,
            'breadcrumbs' => $this->breadcrumbService->getBreadcrumbs(),
        ]);
    }

    #[Route('/{id<\d+>}', name: 'micro_post_show')]
    public function show(int $id): Response
    {
        $this->breadcrumbService->clear();
        $this->breadcrumbService->add('Micro Posts', $this->generateUrl('micro_post_index'));
        $this->breadcrumbService->add('Show Post', $this->generateUrl('micro_post_show', ['id' => $id]));

        $post = $this->repository->find($id);

        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
            'breadcrumbs' => $this->breadcrumbService->getBreadcrumbs(),
        ]);
    }

    #[Route('/create', name: 'micro_post_create')]
    public function create(Request $request): Response
    {
        $this->breadcrumbService->clear();
        $this->breadcrumbService->add('Micro Posts', $this->generateUrl('micro_post_index'));
        $this->breadcrumbService->add('Create Post', $this->generateUrl('micro_post_create'));

        $post = new MicroPost();

        $form = $this->createForm(MicroPostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($post);
            $this->entityManager->flush();

            return $this->redirectToRoute('micro_post_show', ['id' => $post->getId()]);
        }

        return $this->render('micro_post/create.html.twig', [
            'form' => $form->createView(),
            'breadcrumbs' => $this->breadcrumbService->getBreadcrumbs(),
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'micro_post_edit')]
    public function edit(Request $request, MicroPost $post): Response
    {
        $this->breadcrumbService->clear();
        $this->breadcrumbService->add('Micro Posts', $this->generateUrl('micro_post_index'));
        $this->breadcrumbService->add('Edit Post', $this->generateUrl('micro_post_edit', ['id' => $post->getId()]));

        $form = $this->createForm(MicroPostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('micro_post_show', ['id' => $post->getId()]);
        }

        return $this->render('micro_post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'breadcrumbs' => $this->breadcrumbService->getBreadcrumbs(),
        ]);
    }

    #[Route('/{id<\d+>}/delete', name: 'micro_post_delete', methods: ['POST'])]
    public function delete(MicroPost $post): Response
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();

        return $this->redirectToRoute('micro_post_index');
    }

}