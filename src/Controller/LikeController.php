<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LikeController extends AbstractController
{
    #[Route('/post/{id}/like', name: 'micro_post_like', methods: ['POST'])]
    public function like(MicroPost $microPost, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            $this->addFlash('error', 'You must be logged in to like a post.');
            return $this->redirectToRoute('micro_post_index');
        }

        if ($microPost->getUser() === $user) {
            $this->addFlash('error', 'You cannot like your own post.');
            return $this->redirectToRoute('micro_post_index');
        }

        $like = $entityManager->getRepository(Like::class)->findOneBy([
            'user' => $user,
            'microPost' => $microPost
        ]);

        if ($like) {
            $entityManager->remove($like);
            $entityManager->flush();
            $this->addFlash('success', 'Like removed.');
        } else {
            $like = new Like();
            $like->setUser($user);
            $like->setMicroPost($microPost);

            $entityManager->persist($like);
            $entityManager->flush();

            $this->addFlash('success', 'Like added.');
        }

        return $this->redirectToRoute('micro_post_index');
    }

}
