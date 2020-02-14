<?php

namespace App\Controller;

use App\Entity\GroupTraining;
use App\Entity\User;
use App\Form\GroupTrainingType;
use App\Repository\GroupTrainingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/grouptraininglist")
 */
class GroupTrainingController extends AbstractController
{
    /**
     * @Route("/", name="group_training_index", methods={"GET"})
     */
    public function index(GroupTrainingRepository $groupTrainingRepository): Response
    {
        return $this->render('group_training/index.html.twig', [
            'group_trainings' => $groupTrainingRepository->findAll()
        ]);
    }

    /**
     * @Route("/newgroup", name="newgroup", methods={"GET","POST"})
     */
    public function newgroup(Request $request): Response
    {
        $groupTraining = new GroupTraining();
        $form = $this->createForm(GroupTrainingType::class, $groupTraining);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($groupTraining);
            $entityManager->flush();

            return $this->redirectToRoute('group_training_index');
        }

        return $this->render('group_training/new.html.twig', [
            'group_training' => $groupTraining,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="group_training_show", methods={"GET"})
     */
    public function show(GroupTraining $groupTraining): Response
    {
        return $this->render('group_training/show.html.twig', [
            'group_training' => $groupTraining,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="group_training_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, GroupTraining $groupTraining): Response
    {
        $form = $this->createForm(GroupTrainingType::class, $groupTraining);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('group_training_index');
        }

        return $this->render('group_training/edit.html.twig', [
            'group_training' => $groupTraining,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="group_training_delete", methods={"DELETE"})
     */
    public function delete(Request $request, GroupTraining $groupTraining): Response
    {
        if ($this->isCsrfTokenValid('delete'.$groupTraining->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($groupTraining);
            $entityManager->flush();
        }

        return $this->redirectToRoute('group_training_index');
    }
}
