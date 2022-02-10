<?php

namespace App\Controller;

use App\Entity\Pointages;
use App\Form\PointagesType;
use App\Form\UtilisateurType;
use App\Repository\ChantierRepository;
use App\Repository\PointagesRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pointages")
 */
class PointagesController extends AbstractController
{
    /**
     * @Route("/", name="pointages_index", methods={"GET"})
     */
    public function index(PointagesRepository $pointagesRepository): Response
    {
        return $this->render('pointages/index.html.twig', [
            'pointages' => $pointagesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="pointages_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, UtilisateurRepository $utilisateurRepository, ChantierRepository $chantierRepository): Response
    {
        $pointage = new Pointages();

        $form = $this->createForm(PointagesType::class, $pointage);

        $list_user = [];
        foreach ($utilisateurRepository->findAll() as $user){
            $list_user[$user->getNom()." ".$user->getPrenom()] = $user;
        }
        $form->add('id_utilisateur',ChoiceType::class, [
            'choices' => $list_user
        ]);

        $list_chantier = [];
        foreach ($chantierRepository->findAll() as $chantier){
            $list_chantier[$chantier->getNom()] = $chantier;
        }
        $form->add('id_chantier',ChoiceType::class,[
            'choices' => $list_chantier
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pointage);
            $entityManager->flush();

            return $this->redirectToRoute('pointages_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pointages/new.html.twig', [
            'pointage' => $pointage,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="pointages_show", methods={"GET"})
     */
    public function show(Pointages $pointage): Response
    {
        return $this->render('pointages/show.html.twig', [
            'pointage' => $pointage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="pointages_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Pointages $pointage,
                         EntityManagerInterface $entityManager,
                         UtilisateurRepository $utilisateurRepository,
                         ChantierRepository $chantierRepository): Response
    {
        $form = $this->createForm(PointagesType::class, $pointage);
        $list_user = [];
        foreach ($utilisateurRepository->findAll() as $user){
            $list_user[$user->getNom()." ".$user->getPrenom()] = $user;
        }
        $form->add('id_utilisateur',ChoiceType::class, [
            'choices' => $list_user
        ]);
        $list_chantier = [];
        foreach ($chantierRepository->findAll() as $chantier){
            $list_chantier[$chantier->getNom()] = $chantier;
        }
        $form->add('id_chantier',ChoiceType::class,[
            'choices' => $list_chantier
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('pointages_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pointages/edit.html.twig', [
            'pointage' => $pointage,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="pointages_delete", methods={"POST"})
     */
    public function delete(Request $request, Pointages $pointage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pointage->getId(), $request->request->get('_token'))) {
            $entityManager->remove($pointage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('pointages_index', [], Response::HTTP_SEE_OTHER);
    }
}
