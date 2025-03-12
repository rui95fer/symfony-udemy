<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserFollow;
use App\Repository\UserFollowRepository;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

class UserFollowService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserFollowRepository   $userFollowRepository
    )
    {
    }

    public function followUser(User $follower, User $userToFollow): void
    {
        if ($follower === $userToFollow) {
            throw new InvalidArgumentException('You cannot follow yourself.');
        }

        // Check if the follow relationship already exists
        $existingFollow = $this->userFollowRepository->findOneBy([
            'follower' => $follower,
            'followed' => $userToFollow,
        ]);

        if ($existingFollow) {
            throw new InvalidArgumentException('You are already following this user.');
        }

        // Create and persist the follow relationship
        $follow = new UserFollow($follower, $userToFollow);
        $this->entityManager->persist($follow);
        $this->entityManager->flush();
    }

    public function unfollowUser(User $follower, User $userToUnfollow): void
    {
        $follow = $this->userFollowRepository->findOneBy([
            'follower' => $follower,
            'followed' => $userToUnfollow,
        ]);

        if ($follow) {
            $this->entityManager->remove($follow);
            $this->entityManager->flush();
        }
    }
}