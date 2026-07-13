<?php

namespace App\Controller;

use App\Entity\CalendarEvent;
use App\Repository\CalendarEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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


    #[Route('/view/{view}', name: '_view_fragment')]
    public function view(string $view): Response
    {
        return match ($view) {

            'week' => $this->render(
                'planning/views/_week-view.html.twig',
                ['title' => "Planning hebdomadaire"]
            ),

            'three-days' => $this->render(
                'planning/views/_three-days-view.html.twig',
                ['title' => "Planning à 3 jours"]
            ),

            'day' => $this->render(
                'planning/views/_day-view.html.twig',
                ['title' => "Planning jour"]
            ),

            'list' => $this->render(
                'planning/views/_list-view.html.twig',
                ['title' => "Vue liste"]
            ),

            default => throw $this->createNotFoundException()
        };
    }
    //liste d'évènements
    #[Route(
        '/events/list',
        name: 'planning_events_list'
    )]
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
                $calendarEventRepository->findForDay(
                    $date
                )
            ]
        );
    }
}
