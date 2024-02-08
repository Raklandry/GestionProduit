<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $session;
    private $cart;
    private $produitRepo;

    public function __construct(SessionInterface $session, Cart $cart, ProduitRepository $produitRepo)
    {
        $this->session     = $session;
        $this->cart        = $cart;
        $this->produitRepo = $produitRepo;
    }

    /**
     * @Route("/panier", name="app_cart")
     */
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'carts' => $this->cart->getFull()
        ]);
    }

    /**
     * @Route("/panier/ajout/{id}", name="add_cart")
     */
    public function add($id): Response
    {
        $this->cart->add($id);

        return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/panier/supprimer/{id}", name="remove_cart")
     */
    public function remove($id): Response
    {
        $this->cart->remove($id);

        return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/panier/supprimer-tout", name="remove_all_cart")
     */
    public function removeAll(): Response
    {
        $this->cart->removeAll();

        return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/panier/decrement/{id}", name="decrement_cart")
     */
    public function decrement($id): Response
    {
        $this->cart->decrement($id);

        return $this->redirectToRoute('app_cart');
    }
}
