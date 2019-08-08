<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/user/{id}", name="admin_user_details")
     */
    public function details(User $user)
    {
        return $this->render('admin_user/details.html.twig', [
            'user' => $user,
        ]);
    }
}
