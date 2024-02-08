<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Form\FournisseurType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FournisseurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FournisseurController extends AbstractController
{
    private $entityManager;
    private $fournisseurRepo;

    public function __construct(EntityManagerInterface $entityManager, FournisseurRepository $fournisseurRepo)
    {
        $this->entityManager   = $entityManager;
        $this->fournisseurRepo = $fournisseurRepo;
    }

    /**
     * @Route("/fournisseur", name="app_fournisseur")
     */
    public function index(): Response
    {
        $fournisseurs = $this->fournisseurRepo->findBy([],['id' => 'DESC']);

        return $this->render('fournisseur/index.html.twig', [
            'fournisseurs' => $fournisseurs
        ]);
    }

    /**
     * @Route("/fournisseur/ajout", name="ajout_fournisseur")
     */
    public function ajout(Request $request): Response
    {
        $fournisseur = new Fournisseur;

        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($fournisseur);
            $this->entityManager->flush();
        
            $this->addFlash(
                'success',
                "Le fournisseur est enregistré"
            );

            return $this->redirectToRoute('app_fournisseur');
        }

        return $this->render('fournisseur/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/fournisseur/modifier/{id}", name="modifier_fournisseur")
     */
    public function modifier(Request $request, $id): Response
    {
        $fournisseur = $this->fournisseurRepo->findOneById($id);

        if($fournisseur){
            $form = $this->createForm(FournisseurType::class, $fournisseur);
            $form->handleRequest($request);
    
            if($form->isSubmitted() && $form->isValid()){
                $this->entityManager->persist($fournisseur);
                $this->entityManager->flush();
            
                $this->addFlash(
                    'success',
                    "Le fournisseur est modifié"
                );
    
                return $this->redirectToRoute('app_fournisseur');
            }
        } else {
            $this->addFlash(
                'warning',
                "Impossible de modifier cette fournisseur"
            );

            return $this->redirectToRoute('app_fournisseur');
        }


        return $this->render('fournisseur/modifier.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/fournisseur/supprimer/{id}", name="supprimer_fournisseur")
     */
    public function supprimer($id): Response
    {
        $fournisseur = $this->fournisseurRepo->findOneById($id);

        if($fournisseur){
            $this->entityManager->remove($fournisseur);
            $this->entityManager->flush();
        
            $this->addFlash(
                'success',
                "Le fournisseur est supprimé"
            );

            return $this->redirectToRoute('app_fournisseur');
        } else {
            $this->addFlash(
                'warning',
                "Impossible de suppeimer cette fournisseur"
            );

            return $this->redirectToRoute('app_fournisseur');
        }
    }
}
