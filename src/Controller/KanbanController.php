<?php

namespace App\Controller;

use App\Entity\KanbanBoard;
use App\Repository\KanbanBoardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

final class KanbanController extends AbstractController
{
    // liste des tableaux
    #[Route('/kanban', name: 'kanban_boards', methods: ['GET'])]
    public function boards(KanbanBoardRepository $kanbanBoardRepository): Response
    {
        $boards = $kanbanBoardRepository->findAll();

        return $this->render('kanban/boards.html.twig', [
            'title' => 'Tableaux Kanban',
            'boards' => $boards,
        ]);
    }

    // affichage d'un tableau
    #[Route('/kanban/{id}', name: 'kanban_board_show', methods: ['GET'])]
    public function show(KanbanBoard $board): Response
    {
        return $this->render('kanban/board_show.html.twig', [
            'title' => $board->getName(),
            'board' => $board,
        ]);
    }

    // création d'un tableau
    #[Route('/api/kanban-boards', name: 'api_kanban_board_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return $this->json([
                'success' => false,
                'message' => 'Le JSON est invalide.',
            ], 400);
        }

        $name = trim($data['name'] ?? '');

        if ($name === '') {
            return $this->json([
                'success' => false,
                'message' => 'Le nom du tableau est obligatoire.',
            ], 400);
        }

        $board = new KanbanBoard();
        $board->setName($name);
        $board->setDetails($data['details'] ?? '');
        $board->setPosition((int) ($data['position'] ?? 0));
        $board->setSlug($slugger->slug($board->getName()));

        $entityManager->persist($board);
        $entityManager->flush();

        return $this->json([
            'success' => true,
            'id' => $board->getId(),
            'slug' => $board->getSlug(),
            'message' => 'Tableau créé avec succès.',
        ], 201);
    }
}
