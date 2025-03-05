<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{

    /**
     * @var array<int, array<string, mixed>>
     */
    private array $users = [
        [
            'id' => 1,
            'name' => 'Rui Fernandes',
        ],
        [
            'id' => 2,
            'name' => 'André Guimarães',
        ],
        [
            'id' => 3,
            'name' => 'Cleiton Sorrilha',
        ],
        [
            'id' => 4,
            'name' => 'Armando Contreras',
        ]
    ];

    #[Route('/users', name: 'user.index')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $this->users
        ]);
    }

    #[Route('/users/{id<\d+>}', name: 'user.show')]
    public function show(int $id): Response
    {
        $user = array_values(array_filter($this->users, fn($user) => $user['id'] === $id))[0] ?? null;

        if (empty($user)) {
            throw $this->createNotFoundException('User not found');
        }

        return $this->render('user/show.html.twig', [
            'user' => $user
        ]);
    }
}
