<?php

namespace App\Controller;

use App\Entity\GroupTraining;
use App\Repository\GroupTrainingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SendNotificationController extends AbstractController
{
    /**
     * @Route("/notification", name="send_notification")
     */
    public function index(GroupTrainingRepository $groupTrainingRepository)
    {
        //send groups to twig
        return $this->render('send_notification/index.html.twig',[
                'groups' => $groupTrainingRepository->findAll()
            ]);
    }

    /**
     * @Route("/notification/sendNotificationPage/{groupId}", name="sendnotificationtousers")
     */
    public function sendNotificationPage(GroupTraining $groupId, Request $request, GroupTrainingRepository $groupTrainingRepository)
    {

        $defaultData = ['message' => 'Type your message here, like (your training will start at 18:00'];
        $form = $this->createFormBuilder($defaultData)
            ->add('message', TextareaType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

                    //DO STUFF HERE
            $message_get = $form["message"]->getData();

            $users = $groupTrainingRepository->find($groupId)->getUsers();
            foreach ($users as $user){

                $message = "Dear " . $user->getName() . ", " . $message_get;
                //TESTED OK.
                //HERE SOME KIND WITH RabbitMQ
                //http://domain.ru/?phone=+79876543210&amp;message=Сообщение
        //        if getemail == true, then send via email
        //        if not send via email
                if($user->getMailOrNot() === true){
                    //IF USER choose a email notification

                    sendNotificationEmail($user, $user->getEmail(), $message);

                    echo "email to " . $user->getEmail() . ", ";
                }else{
                    //if user choose a sms notification
                    //put there user
                    sendSmsNotification($user, $message);

                    echo "sms to " . $user->getEmail() . ", ";

                }

            }
            return new Response('<html><body>saved</body></html>');

        }

        return $this->render('users/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }





}
