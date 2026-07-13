<?php

namespace App\DataFixtures;

use App\Entity\CalendarEvent;
use App\Entity\Enum\EventType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CalendarEventFixtures extends Fixture
{
    public function load(
        ObjectManager $manager
    ): void {

        $today =
            new \DateTimeImmutable(
                'today'
            );


        $events = [

            [
                'title' => 'Réunion équipe',
                'description' => 'Point hebdomadaire',
                'start' => '09:00',
                'end' => '10:00',
                'type' => EventType::APPOINTMENT,
            ],

            [
                'title' => 'Appel fournisseur',
                'description' => 'Validation commande',
                'start' => '11:30',
                'end' => '12:00',
                'type' => EventType::CALL,
            ],

            [
                'title' => 'Intervention technique',
                'description' => 'Maintenance prévue',
                'start' => '14:00',
                'end' => '16:00',
                'type' => EventType::INTERVENTION,
            ],

            [
                'title' => 'Relance dossier',
                'description' => 'Vérification documents',
                'start' => '16:30',
                'end' => '17:00',
                'type' => EventType::TASK,
            ],

        ];


        foreach ($events as $data) {

            $event =
                new CalendarEvent();

            $event->setTitle(
                $data['title']
            );

            $event->setDescription(
                $data['description']
            );

            $event->setStartAt(
                new \DateTimeImmutable(
                    $today->format('Y-m-d')
                    . ' '
                    . $data['start']
                )
            );

            $event->setEndAt(
                new \DateTimeImmutable(
                    $today->format('Y-m-d')
                    . ' '
                    . $data['end']
                )
            );

            $event->setAllDay(
                false
            );

            $event->setType(
                $data['type']
            );

            $manager->persist(
                $event
            );
        }


        $manager->flush();
    }
}