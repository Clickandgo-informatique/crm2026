<?php

namespace App\Form;

use App\Entity\Calendar;
use App\Entity\CalendarEvent;
use App\Entity\Dossier;
use App\Entity\Organization;
use App\Entity\StaffMember;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('startAt', null, [
                'widget' => 'single_text',
            ])
            ->add('endAt', null, [
                'widget' => 'single_text',
            ])
            ->add('allDay')
            ->add('externalId')
            ->add('status')
            ->add('type')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('calendar', EntityType::class, [
                'class' => Calendar::class,
                'choice_label' => 'id',
            ])
            ->add('organization', EntityType::class, [
                'class' => Organization::class,
                'choice_label' => 'id',
            ])
            ->add('assignedTo', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('dossier', EntityType::class, [
                'class' => Dossier::class,
                'choice_label' => 'id',
            ])
            ->add('staffMember', EntityType::class, [
                'class' => StaffMember::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CalendarEvent::class,
        ]);
    }
}
