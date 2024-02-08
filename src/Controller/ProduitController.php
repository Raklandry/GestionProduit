<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    private $entityManager;
    private $produitRepo;

    public function __construct(EntityManagerInterface $entityManager, ProduitRepository $produitRepo)
    {
        $this->entityManager = $entityManager;
        $this->produitRepo   = $produitRepo;
    }

    /**
     * @Route("/produit", name="app_produit")
     */
    public function index(): Response
    {
        $produits = $this->produitRepo->findBy([],['id' => 'DESC']);

        return $this->render('produit/index.html.twig', [
            'produits' => $produits
        ]);
    }

    /**
     * @Route("/produit/ajout", name="ajout_produit")
     */
    public function ajout(Request $request): Response
    {
        $produit = new Produit;

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            try {
                $this->entityManager->persist($produit);
                $this->entityManager->flush();
            
                $this->addFlash(
                    'success',
                    "Le produit est enregistré"
                );

                return $this->redirectToRoute('app_produit');
    
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash(
                    'danger',
                    "Le marque existe déjà, chaque marque a un seul et unique fournisseur"
                );

                $form->get('marque')->addError(new FormError("Le marque existe déjà"));
    
                return $this->render('produit/ajout.html.twig', [
                    'form' => $form->createView()
                ]);
            } catch (\Exception $e) {
                $this->addFlash(
                    'danger',
                    "Une erreur est survenue, veuillez réessayer par la suite"
                );
        
                return $this->redirectToRoute('app_produit');
            }
        }

        return $this->render('produit/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/produit/modifier/{id}", name="modifier_produit")
     */
    public function modifier(Request $request, $id): Response
    {
        $produit = $this->produitRepo->findOneById($id);

        if($produit){
            $form = $this->createForm(ProduitType::class, $produit);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $this->entityManager->persist($produit);
                $this->entityManager->flush();
            
                $this->addFlash(
                    'success',
                    "Le produit est modifié"
                );

                return $this->redirectToRoute('app_produit');
            }
        } else {
            $this->addFlash(
                'warning',
                "Impossible de modifier cette produit"
            );

            return $this->redirectToRoute('app_produit');
        }

        return $this->render('produit/modifier.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/produit/supprimer/{id}", name="supprimer_produit")
     */
    public function supprimer($id): Response
    {
        $produit = $this->produitRepo->findOneById($id);

        if($produit){
            $this->entityManager->remove($produit);
            $this->entityManager->flush();
        
            $this->addFlash(
                'success',
                "Le produit est supprimé"
            );

            return $this->redirectToRoute('app_produit');
        } else {
            $this->addFlash(
                'warning',
                "Impossible de suppeimer cette produit"
            );

            return $this->redirectToRoute('app_produit');
        }
    }

    /**
     * @Route("/produit/stock/{id}", name="stock_produit")
     */
    public function stock(Request $request, $id): Response
    {
        $produit = $this->produitRepo->findOneById($id);

        if($produit){
            $stock = $request->request->get('stock');

            $produit->setQuantiteEnStock($stock);

            $this->entityManager->persist($produit);
            $this->entityManager->flush();
        
            $this->addFlash(
                'success',
                "La quantité du produit est modifié"
            );

            return $this->redirectToRoute('app_produit');
        } else {
            $this->addFlash(
                'warning',
                "Impossible de modifier le stock cette produit"
            );

            return $this->redirectToRoute('app_produit');
        }
    }
}
