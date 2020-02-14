<?php

namespace App\Controller;

use App\Entity\GroupTraining;
use App\Entity\User;
use App\Form\PassTestType;
use App\Form\RegistrationUserType;
use App\Repository\GroupTrainingRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class RoleUserController extends AbstractController
{
    /**
     * @Route("/role/users", name="role_user")
     */
    public function index(UserInterface $user, EntityManagerInterface $manager)
    {
        //$current_user = $manager->getRepository(User::class)->find($userId);
        //dd($current_user);

        return $this->render('role_user/index.html.twig', [
            'user' => $user,
        ]);
    }


    /**
 * @Route("/user/profile", name="userProfile")
 */
    public function userProfile(UserInterface $user, EntityManagerInterface $manager, UserRepository $userRepository)
    {
        return $this->render('role_user/userview/userprofile.html.twig', [
            'user_id' => $user->getId(),
            'user' => $userRepository->find($user)
        ]);
    }

    /**
 * @Route("/user/pass", name="changePassword")
 */
    public function changePassword(Request $request, UserInterface $user, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(PassTestType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // whatever *your* User object is

            $plainpassword = $form->get('password')->getData();
            //dump($plainpassword) ;

            $encoded = $encoder->encodePassword($user, $plainpassword);
            $user->setPassword($encoded);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('role_user');
    }
        return $this->render('users/new.html.twig', [
            'users' => $user,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/user/allgroups", name="allGroups")
     */
    public function allGroups(UserInterface $user, GroupTrainingRepository $groupTrainingRepository)
    {
        $userId = $user->getId();
        //dd($current_user);

        return $this->render('role_user/userview/index.html.twig', [
            'user_id' => $userId,
            'group_trainings' => $groupTrainingRepository->findAll()
        ]);
    }


    /**
     * @Route("/user/mygroups", name="userGroups")
     */
    public function userGroups(UserInterface $user, GroupTrainingRepository $groupTrainingRepository, EntityManagerInterface $entityManager)
    {
        $current_user = $entityManager->getRepository(User::class)->find($user);

        return $this->render('role_user/userview/groupconnected.html.twig', [
            'user' => $current_user,
            'group_trainings' => $groupTrainingRepository
        ]);
    }

    /**
     * @Route("/user/connectToGroup/{userId}/{groupTrainingId}", name="connectToGroup")
     */
    public function connectToGroup(GroupTraining $groupTrainingId, User $userId, EntityManagerInterface $entityManager)
    {
        $userId->addRelation($groupTrainingId);
        $entityManager->flush();
        return new Response('<html><body>saved</body></html>');
    }

    /**
     * @Route("/user/disconnectFromGroup/{userId}/{groupTrainingId}", name="disconnectFromGroup")
     */
    public function disconnectFromGroup(GroupTraining $groupTrainingId, UserInterface $user, EntityManagerInterface $entityManager)
    {

        $user->removeRelation($groupTrainingId);
        $entityManager->flush();
        return $this->render('role_user/index.html.twig');
    }

    /**
     * @Route("/user/verification/{userId}", name="verification_user_controller")
     */
    public function verificationUser(User $userId, EntityManagerInterface $entityManager)
    {

        //NEXT STEP IN functions.php -> verification()

        $current_user = $entityManager->getRepository(User::class)->find($userId);
        $current_user->setVerified(true);
        $entityManager->flush();

        echo $current_user->getEmail();

        return new Response('<html><body> your account is activated, now you can login.</body></html>');
    }




}
