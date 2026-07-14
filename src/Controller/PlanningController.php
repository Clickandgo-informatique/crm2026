<?php

namespace App\Controller;

use App\Repository\CalendarEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    #[Route('/view/{view}', name: 'view_fragment')]
    public function view(string $view): Response
    {
        return match ($view) {
            'week' => $this->render(
                'planning/views/_week-view.html.twig',
                ['title' => 'Planning hebdomadaire']
            ),
            'three-days' => $this->render(
                'planning/views/_three-days-view.html.twig',
                ['title' => 'Planning à 3 jours']
            ),
            'day' => $this->render(
                'planning/views/_day-view.html.twig',
                ['title' => 'Planning jour']
            ),
            'list' => $this->render(
                'planning/views/_list-view.html.twig',
                ['title' => 'Vue liste']
            ),
            default => throw $this->createNotFoundException()
        };
    }
    #[Route('/events/list', name: 'events_list')]
    public function eventList(
        Request $request,
        CalendarEventRepository $calendarEventRepository
    ): Response {
        $date =
            $request->query->get('date');
        if (!$date) {
            $date =
                (new \DateTimeImmutable())
                ->format('Y-m-d');
        }
        $date =
            new \DateTimeImmutable($date);
        return $this->render(
            'planning/_event-list.html.twig',
            [
                'events' =>
                $calendarEventRepository->findForDay($date)
            ]
        );
    }
    #[Route('/events/week', name: 'events_week')]
    public function weekEvents(
        Request $request,
        CalendarEventRepository $calendarEventRepository
    ): JsonResponse {
        $dateParam =
            $request->query->get('date');
        $date =
            $dateParam
            ? new \DateTimeImmutable($dateParam)
            : new \DateTimeImmutable();
        return $this->json(
            $calendarEventRepository->findForWeek($date)
        );
    }
    #[Route('/events/render', name: 'events_render', methods: ['POST'])]
    public function renderEvents(
        Request $request
    ): Response {
        $events =
            json_decode(
                $request->getContent(),
                true
            );
        if (!is_array($events)) {
            $events = [];
        }
        return $this->render(
            'planning/components/_calendar-events.html.twig',
            [
                'events' => $events
            ]
        );
    }
}
