<?php

namespace App\Classe;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private $session;
    private $produitRepo;

    public function __construct(SessionInterface $session, ProduitRepository $produitRepo)
    {
        $this->session     = $session;
        $this->produitRepo = $produitRepo;
    }

    public function get(){
        return $this->session->get('cart');
    }

    public function getFull(){
        $carts = [];

        if ($this->get()) {
            foreach($this->get() as $id => $quantity){
                $produit = $this->produitRepo->findOneById($id);

                if(!$produit){
                    $this->remove($id);
                    continue;
                }

                $carts[] = [
                    'produit'  => $produit,
                    'quantity' => $quantity
                ];
            }
        }

        return $carts;
    }

    public function add($id){
        $cart = $this->session->get('cart', []);

        if(!empty($cart[$id])){
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }

    public function remove($id){
        $cart = $this->session->get('cart', []);
        unset($cart[$id]);

        return $this->session->set('cart', $cart);
    }

    public function removeAll(){
        return $this->session->remove('cart');
    }

    public function decrement($id){
        $cart = $this->session->get('cart', []);

        if($cart[$id] > 1){
            $cart[$id]--;
        }
        
        return $this->session->set('cart', $cart);
    }
}