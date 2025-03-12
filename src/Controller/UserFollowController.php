<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserFollowService;
use Exception;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
class UserFollowController extends AbstractController
{
    private UserFollowService $userFollowService;

    public function __construct(UserFollowService $userFollowService)
    {
        $this->userFollowService = $userFollowService;
    }

// src/Controller/UserFollowController.php

    /**
     * @throws Exception
     */
    #[Route('/user/follow/{id}', name: 'user_follow_toggle', methods: ['POST'])]
    public function toggleFollow(User $userToFollow, UserFollowService $userFollowService): RedirectResponse
    {
        $follower = $this->getUser(); // Logged-in user

        if ($follower === $userToFollow) {
            $this->addFlash('error', 'You cannot follow yourself.');
            return $this->redirectToRoute('micro_post_index');
        }

        try {
            if (!$follower instanceof User) {
                throw new Exception('User is not logged in');
            }
            if ($follower->isFollowing($userToFollow)) {

                $userFollowService->unfollowUser($follower, $userToFollow);
                $this->addFlash('success', 'You have unfollowed this user.');
            } else {
                $userFollowService->followUser($follower, $userToFollow);
                $this->addFlash('success', 'You are now following this user.');
            }
        } catch (InvalidArgumentException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('micro_post_index');
    }
}