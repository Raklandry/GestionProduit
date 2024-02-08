<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $entityManager;
    private $userRepo;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepo)
    {
        $this->entityManager = $entityManager;
        $this->userRepo      = $userRepo;
    }

    /**
     * @Route("/accueil", name="app_accueil")
     */
    public function index(): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('user/index.html.twig');
    }

    /**
     * @Route("/inscription", name="app_register")
     */
    public function inscription(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User;
        
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            try {
                $user     = $form->getData();
                $password = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);

                $this->entityManager->persist($user);
                $this->entityManager->flush();
            
                $this->addFlash(
                    'success',
                    "Merci, Votre inscription a été effectuée avec succès"
                );

                return $this->redirectToRoute('app_login');
    
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash(
                    'danger',
                    "Cette adresse email existe déjà, chaque utilisateur a un seul et unique adresse email"
                );

                $form->get('email')->addError(new FormError("L'adresse email existe déjà"));
    
                return $this->render('user/inscription.html.twig', [
                    'form' => $form->createView()
                ]);
            } catch (\Exception $e) {
                $this->addFlash(
                    'danger',
                    "Une erreur est survenue, veuillez réessayer par la suite"
                );
        
                return $this->redirectToRoute('app_home');
            }
        }
        return $this->render('user/inscription.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
