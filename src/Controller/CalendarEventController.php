<?php

namespace App\Controller;

use App\Entity\CalendarEvent;
use App\Form\CalendarEventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CalendarEventController extends AbstractController
{
    #[Route('/calendar/event/new', name: 'calendar_event_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CalendarEventType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = new CalendarEvent();

            $em->persist($event);
            $em->flush();
        }
        return $this->render('calendar_event/edit.html.twig', []);
    }
}
