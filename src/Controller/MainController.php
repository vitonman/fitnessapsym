<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationUserType;
use App\Form\TestFormType;
use Doctrine\ORM\EntityManagerInterface;
use Mailgun\Mailgun;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class MainController extends AbstractController
{


    /**
     * @Route("/main", name="main")
     */
    public function index(UserInterface $user, EntityManagerInterface $manager)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        //CHECK IF ADMIN, CHECK IF USER.
        if (TRUE === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            //admin page
            return $this->render('role_admin/main.html.twig', [
                'user' => $user,
            ]);
        }
        return $this->render('role_user/index.html.twig',[
            'user' => $user
        ]);
    }

}
