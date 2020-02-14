<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Mailgun\Mailgun;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @Route("/", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManager): Response
    {

            //if user already logged in -> goto main
            //if there is user in session
            if($this->getUser() != null){

                $user = $this->getUser();
                $current_user = $entityManager->getRepository(User::class)->find($user);

                //IF USER IS NOT BLOCKED
                //CHECK IF ADMIN, CHECK IF USER.
                if (TRUE === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                    //admin page
                    return $this->render('role_admin/main.html.twig', [
                        'user' => $user,
                    ]);
                }else{
                    //USER
                    return $this->render('role_user/index.html.twig',[
                        'user' => $user
                    ]);
                }
            }else{
                // get the login error if there is one
                $error = $authenticationUtils->getLastAuthenticationError();
                // last username entered by the users
                $lastUsername = $authenticationUtils->getLastUsername();

                //no point here-> just return
                return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
            }

    }

    /**
     * @Route("/logout", name="app_logout")
     * @throws Exception
     */
    public function logout()
    {
        throw new Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }


}
