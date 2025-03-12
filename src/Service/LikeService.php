<?php

namespace App\Service;

use App\Entity\Like;
use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class LikeService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function toggleLike(MicroPost $microPost, ?User $user): array
    {
        if (!$user instanceof User) {
            return [
                'status' => 'error',
                'message' => 'You must be logged in to like a post.'
            ];
        }

        $like = $this->entityManager->getRepository(Like::class)->findOneBy([
            'user' => $user,
            'microPost' => $microPost
        ]);

        if ($like) {
            $this->entityManager->remove($like);
            $this->entityManager->flush();
            return [
                'status' => 'success',
                'message' => 'Like removed successfully'
            ];
        } else {
            $like = new Like();
            $like->setUser($user);
            $like->setMicroPost($microPost);

            $this->entityManager->persist($like);
            $this->entityManager->flush();

            return [
                'status' => 'success',
                'message' => 'Like added successfully'
            ];
        }
    }
}