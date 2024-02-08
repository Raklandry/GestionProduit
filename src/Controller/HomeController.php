<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $produitRepo;

    public function __construct(ProduitRepository $produitRepo)
    {
        $this->produitRepo   = $produitRepo;
    }

    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        if($this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('home/index.html.twig', [
            'produits' => $this->produitRepo->findAll(),
        ]);
    }

    /**
     * @Route("/admin", name="app_admin")
     */
    public function admin(): Response
    {
        return $this->render('home/admin.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
