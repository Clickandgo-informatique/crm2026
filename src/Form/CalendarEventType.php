<?php

namespace App\Form;

use App\Entity\Calendar;
use App\Entity\CalendarEvent;
use App\Entity\Dossier;
use App\Entity\Enum\EventStatus;
use App\Entity\Enum\EventType;
use App\Entity\Organization;
use App\Entity\StaffMember;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => ['placeholder' => "Titre de l'évènement"]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['placeholder' => "Description de l'évènement"]
            ])
            ->add('startAt', null, [
                'widget' => 'single_text',
            ])
            ->add('endAt', null, [
                'widget' => 'single_text',
            ])
            ->add('allDay', CheckboxType::class, [
                'label' => 'Journée entière',
                'required' => false
            ])
            ->add('externalId')
            ->add('status', EnumType::class, [
                'class' => EventStatus::class,
                'label' => 'Statut',
                'choice_label' => fn(EventStatus $status) => $status->label(),
            ])
            ->add('type', EnumType::class, [
                'class' => EventType::class,
                'label' => 'Type',
                'choice_label' => fn(EventType $type) => $type->label(),
            ])
            ->add('createdAt', null, [
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text',
                'required' => false
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
