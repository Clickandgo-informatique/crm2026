<?php

namespace App\Controller;

use App\Entity\CalendarEvent;
use App\Entity\StaffMember;
use App\Repository\CalendarEventRepository;
use App\Repository\StaffMemberRepository;
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
                'planning/layouts/_week.html.twig',
                [
                    'title' => 'Planning hebdomadaire',
                ]
            ),
            'three-days' => $this->render(
                'planning/layouts/_three-days.html.twig',
                [
                    'title' => 'Planning à 3 jours',
                ]
            ),
            'day' => $this->render(
                'planning/layouts/_day.html.twig',
                [
                    'title' => 'Planning jour',
                ]
            ),
            'list' => $this->render(
                'planning/layouts/_list.html.twig',
                [
                    'title' => 'Vue liste',
                ]
            ),
            'resources' => $this->render(
                'planning/layouts/_resources.html.twig',
                [
                    'title' => 'Vue ressources',
                ]
            ),
            default => throw $this->createNotFoundException(),
        };
    }

    #[Route('/events/list', name: 'events_list')]
    public function eventList(
        Request $request,
        CalendarEventRepository $calendarEventRepository
    ): Response {
        $date = $this->getRequestedDate($request);

        return $this->render(
            'planning/_event-list.html.twig',
            [
                'events' => $calendarEventRepository->findForDay($date),
            ]
        );
    }

    #[Route('/events/week', name: 'events_week')]
    public function weekEvents(
        Request $request,
        CalendarEventRepository $calendarEventRepository
    ): JsonResponse {
        $date = $this->getRequestedDate($request);

        return $this->json(
            $this->formatEvents(
                $calendarEventRepository->findForWeek($date)
            )
        );
    }

    #[Route('/events/day', name: 'events_day')]
    public function dayEvents(
        Request $request,
        CalendarEventRepository $calendarEventRepository
    ): JsonResponse {
        $date = $this->getRequestedDate($request);

        return $this->json(
            $this->formatEvents(
                $calendarEventRepository->findForDay($date)
            )
        );
    }

    #[Route('/events/three-days', name: 'events_three_days')]
    public function threeDaysEvents(
        Request $request,
        CalendarEventRepository $calendarEventRepository
    ): JsonResponse {
        $date = $this->getRequestedDate($request);

        return $this->json(
            $this->formatEvents(
                $calendarEventRepository->findForThreeDays($date)
            )
        );
    }

    #[Route('/events/render', name: 'events_render', methods: ['POST'])]
    public function renderEvents(
        Request $request
    ): Response {
        $events = json_decode(
            $request->getContent(),
            true
        );

        if (!is_array($events)) {
            $events = [];
        }

        return $this->render(
            'planning/components/_calendar-events.html.twig',
            [
                'events' => $events,
            ]
        );
    }

    /**
     * Retourne la date demandée par le frontend.
     */
    private function getRequestedDate(Request $request): \DateTimeImmutable
    {
        $date = $request->query->get('date');

        return $date
            ? new \DateTimeImmutable($date)
            : new \DateTimeImmutable();
    }

    /**
     * Transforme les entités CalendarEvent en données compatibles JS.
     */
    private function formatEvents(array $events): array
    {
        return array_map(
            static function (CalendarEvent $event): array {
                return [
                    'id' => $event->getId(),
                    'title' => $event->getTitle(),
                    'description' => $event->getDescription(),

                    'startAt' => $event->getStartAt()?->format(DATE_ATOM),
                    'endAt' => $event->getEndAt()?->format(DATE_ATOM),

                    'allDay' => $event->isAllDay(),

                    'type' => $event->getType()->value,
                    'status' => $event->getStatus()->value,

                    // Ressource utilisée par la vue planning ressources
                    'resourceId' => $event->getStaffMember()?->getId(),

                    // Contexte métier
                    'calendarId' => $event->getCalendar()?->getId(),
                    'organizationId' => $event->getOrganization()?->getId(),
                    'dossierId' => $event->getDossier()?->getId(),
                ];
            },
            $events
        );
    }

    /**
     * Retourne les ressources affichables dans le planning.
     */
    #[Route('/resources', name: 'resources')]
    public function resources(
        StaffMemberRepository $repository
    ): JsonResponse {
        return $this->json(
            array_map(
                static function (StaffMember $staffMember): array {
                    return [
                        'id' => $staffMember->getId(),
                        'label' => $staffMember->getFullName(),
                    ];
                },
                $repository->findBy([
                    'active' => true,
                ])
            )
        );
    }
}
