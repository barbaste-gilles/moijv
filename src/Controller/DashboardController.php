<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(UserRepository $userRepository)
    {
        // SELECT * FORM user
        $users = $userRepository->findAll();
        return $this->render('admin/index.html.twig', [
            'users' => $users
        ]);
    }
}
