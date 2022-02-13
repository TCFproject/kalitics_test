<?php

namespace App\Controller;

use App\Entity\Chantier;
use App\Form\ChantierType;
use App\Repository\ChantierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/chantier")
 */
class ChantierController extends AbstractController
{
    /**
     * @Route("/", name="chantier_index", methods={"GET"})
     */
    public function index(ChantierRepository $chantierRepository): Response
    {
        $nb_de_utilisateur_par_chantier = [];
        $nb_duree = [];
        foreach ($chantierRepository->findAll() as $chantier){
            $nb_de_utilisateur = [];
            $totalSeconde = 0;
            $totalMinute = 0;
            $totalHeure = 0;
            foreach ($chantier->getPointages() as $pointage){
                if (!in_array($pointage->getIdUtilisateur()->getNom(),$nb_de_utilisateur)){
                    $nb_de_utilisateur[] = $pointage->getIdUtilisateur()->getNom();
                }
                $totalHeure += intval( $pointage->getDuree()->format('H'));
                $totalMinute += intval( $pointage->getDuree()->format('i'));
                $totalSeconde += intval($pointage->getDuree()->format('s'));
            }
            $string_duree_create = strval($totalHeure)." : ";
            if ($totalMinute<10){
                $string_duree_create.="0".strval($totalMinute);
            }else{
                $string_duree_create.=strval($totalMinute);
            }
            $string_duree_create.=" : 0".strval($totalSeconde);
            $nb_duree[$chantier->getNom()] = $string_duree_create;
            $nb_de_utilisateur_par_chantier[$chantier->getNom()] = $nb_de_utilisateur;
        }

        return $this->render('chantier/index.html.twig', [
            'chantiers' => $chantierRepository->findAll(),
            'nb_user' => $nb_de_utilisateur_par_chantier,
            'total_duree' => $nb_duree
        ]);
    }

    /**
     * @Route("/new", name="chantier_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chantier = new Chantier();
        $form = $this->createForm(ChantierType::class, $chantier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chantier);
            $entityManager->flush();

            return $this->redirectToRoute('chantier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chantier/new.html.twig', [
            'chantier' => $chantier,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="chantier_show", methods={"GET"})
     */
    public function show(Chantier $chantier): Response
    {
        $nb_de_utilisateur = [];
        foreach ($chantier->getPointages() as $pointage){
            if (!in_array($pointage->getIdUtilisateur()->getNom(),$nb_de_utilisateur)){
                $nb_de_utilisateur[] = $pointage->getIdUtilisateur()->getNom();
            }
        }

        return $this->render('chantier/show.html.twig', [
            'chantier' => $chantier,
            'nb_utilisateur'=>$nb_de_utilisateur
        ]);
    }

    /**
     * @Route("/{id}/edit", name="chantier_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Chantier $chantier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChantierType::class, $chantier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('chantier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chantier/edit.html.twig', [
            'chantier' => $chantier,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="chantier_delete", methods={"POST"})
     */
    public function delete(Request $request, Chantier $chantier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chantier->getId(), $request->request->get('_token'))) {
            $entityManager->remove($chantier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('chantier_index', [], Response::HTTP_SEE_OTHER);
    }
}
