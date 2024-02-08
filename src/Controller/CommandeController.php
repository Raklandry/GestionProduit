<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Commande;
use App\Entity\CommandeDetail;
use App\Repository\CommandeDetailRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{

    private $cart;
    private $entityManager;
    private $commandeRepo;

    public function __construct(
        Cart $cart,
        EntityManagerInterface $entityManager,
        CommandeRepository $commandeRepo)
    {
        $this->cart               = $cart;
        $this->entityManager      = $entityManager;
        $this->commandeRepo       = $commandeRepo;
    }

    /**
     * @Route("/commande", name="app_commande")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $commandes = $this->commandeRepo->findByUser($user);
        
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    /**
     * @Route("/commande/detail/{id}", name="detail_commande")
     */
    public function detail($id): Response
    {
        $commande = $this->commandeRepo->findOneById($id);

        if(!$commande){
            $this->addFlash(
                'warning',
                "Cette commande n'exite pas"
            );

            return $this->redirectToRoute('app_commande');
        }

        return $this->render('commande/detail.html.twig', [
            'commande' => $commande,
        ]);
    }

    /**
     * @Route("/commande/valider", name="valider_commande")
     */
    public function valider(): Response
    {
    
        try {
            $user    = $this->getUser();

            $commade = new Commande;
            
            $carts = $this->cart->getFull();

            $total = null;
            foreach ($carts as $cart) {
                $commadeDetail = new CommandeDetail;
                
                $commadeDetail->setProduit($cart['produit']->getTitre());
                $commadeDetail->setPrix($cart['produit']->getPrixTtc());
                $commadeDetail->setQuantity($cart['quantity']);
                $commadeDetail->setCommande($commade);
                
                $subTotal = $cart['produit']->getPrixTtc() * $cart['quantity'];
                $commadeDetail->setPrixTotal($subTotal);
                
                $total += $subTotal;
                
                $this->entityManager->persist($commadeDetail);
            };
            
            $commade->setUser($user);
            $commade->setTotal($total);
    
            $this->entityManager->persist($commade);
    
            $this->entityManager->flush();
            $this->cart->removeAll();

            $this->addFlash(
                'success',
                "Votre commande est enregistrée"
            );

            return $this->redirectToRoute('app_commande');

        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                "Votre commande n'a pas pu être enregistrée."
            );

            return $this->redirectToRoute('app_commande');
        }

        return $this->redirectToRoute('app_commande');
    }

    /* -----------------Commande Admin------------------ */

    /**
     * @Route("/commande/liste", name="admin_commande")
     */
    public function liste(): Response
    {
        $commandes = $this->commandeRepo->findAll();
        
        return $this->render('commande/liste.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    /**
     * @Route("/commande-detail/{id}", name="admin_detail_commande")
     */
    public function commandeDetail($id): Response
    {
        $commande = $this->commandeRepo->findOneById($id);

        if(!$commande){
            $this->addFlash(
                'warning',
                "Cette commande n'exite pas"
            );

            return $this->redirectToRoute('admin_commande');
        }

        return $this->render('commande/detail_commande.html.twig', [
            'commande' => $commande,
        ]);
    }
}
