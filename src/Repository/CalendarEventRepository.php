<?php

namespace App\Repository;

use App\Entity\CalendarEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CalendarEventRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry
    ) {
        parent::__construct(
            $registry,
            CalendarEvent::class
        );
    }


    public function findForDay(
        \DateTimeImmutable $date
    ): array {

        $start =
            $date->setTime(
                0,
                0
            );

        $end =
            $start->modify(
                '+1 day'
            );


        return $this->createQueryBuilder('event')
            ->andWhere(
                'event.startAt >= :start'
            )
            ->andWhere(
                'event.startAt < :end'
            )
            ->setParameter(
                'start',
                $start
            )
            ->setParameter(
                'end',
                $end
            )
            ->orderBy(
                'event.startAt',
                'ASC'
            )
            ->getQuery()
            ->getResult();
    }
}
