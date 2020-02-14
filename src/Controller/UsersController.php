<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationUserType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Mailgun\Mailgun;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Mailer\Bridge\Mailgun\Transport\MailgunSmtpTransport;


//CONTROLLER ABOUT CREATE;EDIT AND DELETE IOFORMATION BY ADMINISTRATOR
//ABOUT
//CONTROLLER ABOUT CREATE;EDIT AND DELETE IOFORMATION BY ADMINISTRATOR

/**
 * @Route("/users")
 */
class UsersController extends AbstractController
{
    /**
     * @Route("/users", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('users/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        //creating users variable
        $user = new User();
        $form = $this->createForm(RegistrationUserType::class, $user);
        $form->handleRequest($request);

        dump(getenv('MAILGUN_DOMAIN_USER'));
        if ($form->isSubmitted() && $form->isValid()) {

            //FROM Modelservices generated password(firs password)
            $plainPassword = generateRandomString(10);
            //encode here this password
            //$plainPassword = "123";

            //Encoding password and saving inHASH
            $encoded = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);

            //SET BASIC PARAMS, role-user, not blocked, not verified
            //, user want to get notification by sms (0 = sms, 1 = mail)'
            $user->setRoles(['ROLE_USER']);
            $user->setBlocked(0);
            $user->setVerified(0);
            $user->setMailOrNot(0);
            //SET BASIC PARAMS

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            //READY TO GO
            $user_email = $user->getEmail();
            $user_id = $user->getId();
            //READY TO GO

            verification($plainPassword,$user_email,$user_id);

            return $this->redirectToRoute('user_index');
        }

        return $this->render('users/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('users/show.html.twig', [
            'users' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('users/edit.html.twig', [
            'users' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
