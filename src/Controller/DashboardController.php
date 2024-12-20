<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function userProfile(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('user/profile.html.twig', [
            'controller_name' => 'User Profile',
        ]);
    }
}