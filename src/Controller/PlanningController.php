<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/planning', name: 'planning_')]
final class PlanningController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('planning/index.html.twig');
    }

    #[Route('/mini-calendar', name: 'mini_calendar')]
    public function miniCalendar(): Response
    {
        return $this->render('planning/_mini-calendar.html.twig');
    }
}