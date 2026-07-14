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


    public function findForPeriod(
        \DateTimeImmutable $start,
        \DateTimeImmutable $end
    ): array {
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


    public function findForDay(
        \DateTimeImmutable $date
    ): array {
        $start = $date->setTime(
            0,
            0
        );

        $end = $start->modify(
            '+1 day'
        );

        return $this->findForPeriod(
            $start,
            $end
        );
    }


    public function findForThreeDays(
        \DateTimeImmutable $date
    ): array {
        $start = $date->setTime(
            0,
            0
        );

        $end = $start->modify(
            '+3 days'
        );

        return $this->findForPeriod(
            $start,
            $end
        );
    }


    public function findForWeek(
        \DateTimeImmutable $date
    ): array {
        $start = $date
            ->modify('monday this week')
            ->setTime(
                0,
                0
            );

        $end = $start->modify(
            '+7 days'
        );

        return $this->findForPeriod(
            $start,
            $end
        );
    }
}