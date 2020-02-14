<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class RoleAdminController extends AbstractController
{
    /**
     * @Route("/admin", name="role_admin")
     */
    public function index()
    {
        if (TRUE === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            //admin page
            return $this->render('role_admin/main.html.twig');

        } else {
            return new Response('<html><body>DENIED</body></html>') ;
        }
    }


//    /**
//     * @Route("/admin/sendnotification", name="send_notofication")
//     */
//    public function sendNotification()
//    {
//        if (TRUE === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
//            //admin page
//            return $this->render('send_notification/index.html.twig', [
//                'controller_name' => 'RoleAdminController',
//            ]);
//
//        } else {
//            return new Response('<html><body>DENIED</body></html>') ;
//        }
//    }

    /**
     * @Route("/admin/blockUserPage", name="blockUserPage")
     */
    public function blockUserPage(UserRepository $users)
    {
        //check admin
        if (TRUE === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            //admin page
            return $this->render('role_admin/blockuser.html.twig', [
                // put all users to twightml
                'users' => $users->findAll()
            ]);

        } else {
            return new Response('<html><body>DENIED</body></html>') ;
        }
    }

    /**
     * @Route("/admin/blockUserAccount/{userId}", name="blockUserAccount")
     */
    public function blockUserAccount(User $userId, EntityManagerInterface $entityManager, UserRepository $users)
    {

        $userId->setBlocked(true);
        $entityManager->flush();
        return $this->render('role_admin/blockuser.html.twig', [
            // put all users to twightml
            'users' => $users->findAll()
        ]);

    }

    /**
     * @Route("/admin/unblockAccount/{userId}", name="unBlockAccount")
     */
    public function unBlockAccount(User $userId, EntityManagerInterface $entityManager, UserRepository $users)
    {

        $userId->setBlocked(false);
        $entityManager->flush();
        return $this->render('role_admin/blockuser.html.twig', [
            // put all users to twightml
            'users' => $users->findAll()
        ]);
    }

}
