<?php

namespace App\Controller;

use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/micro-posts')]
class MicroPostController extends AbstractController
{
    private MicroPostRepository $repository;
    private EntityManagerInterface $entityManager;

    public function __construct(MicroPostRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'micro_post_index')]
    public function index(): Response
    {
        $posts = $this->repository->findLatest(10);

        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/{id<\d+>}', name: 'micro_post_show')]
    public function show(int $id): Response
    {
        $post = $this->repository->find($id);

        return $this->render('micro_post/show.html.twig', [
            'post' => $post
        ]);
    }

}