<?php
// src/Controller/WildController.php
namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wild", name="wild_")
 */

class WildController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index() :Response
    {
        return $this->render('wild/index.html.twig', [
            'website' => 'Wild Séries',
        ]);
    }

    /**
     * @Route("/show/{page<^[a-z0-9]+(?:-[a-z0-9]+)*$>}", name="show")
     * @param string $page
     * @return Response
     */
    public function show(string $page = " "): Response
    {
        if($page === " ") {
             $page = "Aucune série sélectionnée, veuillez choisir une série";
        }else{
            $page = ucwords(str_replace('-', ' ', $page));

        }return $this->render('wild/show.html.twig', ['page' => $page]);
    }
}