<?php

namespace App\Service;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;

class MicroPostService
{
    private MicroPostRepository $microPostRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(MicroPostRepository $microPostRepository, EntityManagerInterface $entityManager)
    {
        $this->microPostRepository = $microPostRepository;
        $this->entityManager = $entityManager;
    }

    public function getLatestPosts(int $limit): array
    {
        return $this->microPostRepository->findLatest($limit);
    }

    public function getPostById(int $id): ?MicroPost
    {
        return $this->microPostRepository->find($id);
    }

    public function createPost(MicroPost $post, User $user): void
    {
        $post->setUser($user);
        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }

    public function updatePost(MicroPost $post): void
    {
        $this->entityManager->flush();
    }

    public function deletePost(MicroPost $post): void
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }
}