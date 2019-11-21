<?php
// src/Controller/WildController.php
namespace App\Controller;


use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wild", name="wild_")
 */
class WildController extends AbstractController
{
    /**
     * @Route("/",name="index")
     *
     */


    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();
        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }

        return $this->render(
            'wild/index.html.twig',
            ['programs' => $programs]
        );
    }

    /**
     * @Route("/show/{slug<^[a-z0-9]+(?:-[a-z0-9]+)*$>}", name="show")
     * @param string $slug
     * @return Response
     */
    public function show(?string $slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException("Aucune série sélectionnée, veuillez choisir une série");
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with ' . $slug . ' title, found in program\'s table.');
        }
        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug' => $slug,
        ]);
    }

    /**
     * @param string|null $categoryName
     * @Route("/category/{categoryName}", defaults={"categoryName" = null}, name="show_category")
     * @return Response
     */

    public function showByCategory(?string $categoryName)
    {
        if (!$categoryName){
            throw $this->createNotFoundException('Aucune catégorie selectionnée');
        }
        $category = $this->getDoctrine()->getRepository(Category::class)
            ->findOneBy(['name' => mb_strtolower($categoryName)]);

        $programs = $this->getDoctrine()->getRepository(Program::class)
            ->findBy(['category' => $category], ['id' => 'desc'], 3);

        if (!$programs) {
            throw  $this->createNotFoundException('Aucune série dans la catégorie ' . $categoryName);
        }
        return $this->render('wild/category.html.twig',[
            'programs' => $programs,
            'category' => $category,
        ]);
    }
}