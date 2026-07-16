<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserPreference;
use App\Form\UserPreferenceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserPreferenceController extends AbstractController
{
    #[Route('/settings/preferences', name: 'user_preferences')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $preference = $user->getPreference();

        if ($preference === null) {
            $preference = new UserPreference();
            $preference->setUser($user);
        }

        $form = $this->createForm(
            UserPreferenceType::class,
            $preference
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($preference);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Préférences enregistrées.'
            );

            return $this->redirectToRoute('user_preferences');
        }

        return $this->render('user_preferences/index.html.twig', [
            'form' => $form,
        ]);
    }
}
