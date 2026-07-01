<?php

namespace App\Controller;

use App\Repository\KanbanBoardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class KanbanController extends AbstractController
{
    #[Route('/kanban', name: 'kanban_boards')]
    public function boards(KanbanBoardRepository $kanbanBoardRepo): Response
    {
        $boards = $kanbanBoardRepo->findall();
        return $this->render('kanban/boards.html.twig', ['title' => 'Tableaux Kanban', 'boards' => $boards]);
    }
    #[Route('/kanban/show', name: 'kanban_board_show')]
    public function index(): Response
    {
        return $this->render('kanban/board_show.html.twig', ['title' => 'Tableau ']);
    }
}
