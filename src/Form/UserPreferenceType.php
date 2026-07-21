<?php

namespace App\Form;

use App\Entity\UserPreference;
use App\Service\DateTime\LocaleService;
use App\Service\DateTime\TimeZoneService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPreferenceType extends AbstractType
{
    public function __construct(
        private readonly TimeZoneService $timeZoneService,
        private readonly LocaleService $localeService
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('timezone', ChoiceType::class, [
                'label' => 'Fuseau horaire',
                'choices' => $this->timeZoneService->getChoices(),
            ])
            ->add('locale', ChoiceType::class, [
                'label' => 'Langue',
                'choices' => $this->localeService->getChoices(),
            ])
            ->add('firstDayOfWeek', ChoiceType::class, [
                'label' => 'Premier jour de la semaine',
                'choices' => [
                    'Lundi' => 1,
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
