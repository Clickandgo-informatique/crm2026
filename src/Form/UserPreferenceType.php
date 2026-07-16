<?php

namespace App\Form;

use App\Entity\UserPreference;
use App\Service\DateTime\TimeZoneService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPreferenceType extends AbstractType
{
    public function __construct(
        private readonly TimeZoneService $timeZoneService
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('timezone', ChoiceType::class, [
                'label' => 'Fuseau horaire',
                'choices' => $this->timeZoneService->getChoices(),
            ])
            ->add('locale', TextType::class, [
                'label' => 'Langue',
            ])
            ->add('dateFormat', TextType::class, [
                'label' => 'Format de date',
            ])
            ->add('timeFormat', TextType::class, [
                'label' => 'Format horaire',
            ])
            ->add('firstDayOfWeek', ChoiceType::class, [
                'label' => 'Premier jour de la semaine',
                'choices' => [
                    'Lundi' => 1,
                    'Mardi' => 2,
                    'Mercredi' => 3,
                    'Jeudi' => 4,
                    'Vendredi' => 5,
                    'Samedi' => 6,
                    'Dimanche' => 7,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserPreference::class,
        ]);
    }
}
