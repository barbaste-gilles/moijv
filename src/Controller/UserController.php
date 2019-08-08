<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterFormType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/signup", name="signup")
     */
    public function signup(
        Request $request,
        ObjectManager $objectManager,
        UserPasswordEncoderInterface $encoder
    )
    {
        $user = new User();

        $signupForm = $this->createForm(RegisterFormType::class, $user);

        $signupForm->handleRequest($request);

        // $user a reçu toutes les données du formulaire SI celui-ci a été envoyé

        // si le formulaire a été envoyé et qu'il est valide
        if($signupForm->isSubmitted() && $signupForm->isValid()){
            // on encode le mot de passe avant d'ajouter l'utilisateur en base
            $encodedPassword = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encodedPassword);

            // on ajoute le rôle ROLE_USER à notre utilisateur
            $user->setRole('ROLE_USER');
            // on ajoute l'utilisateur à la base
            $objectManager->persist($user);
            $objectManager->flush();
            // on redirige l'utilisateur vers le formulaire de connexion
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/signup.html.twig', [
            'signup_form' => $signupForm->createView(),
        ]);
    }
}
