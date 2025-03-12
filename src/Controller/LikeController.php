<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Service\LikeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LikeController extends AbstractController
{
    private LikeService $likeService;

    public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }

    #[Route('/post/{id}/like', name: 'micro_post_like', methods: ['POST'])]
    public function like(MicroPost $microPost): Response
    {
        $result = $this->likeService->toggleLike($microPost, $this->getUser());

        $this->addFlash($result['status'], $result['message']);

        return $this->redirectToRoute('micro_post_index');
    }

}
