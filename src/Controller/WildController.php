<?php
// src/Controller/WildController.php
namespace App\Controller;


use App\Entity\Actor;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\CommentType;
use Doctrine\DBAL\Event\SchemaEventArgs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wild", name="wild_")
 */
class WildController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index()
    {
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/programs", name="programs")
     * @return Response
     */
    public function showPrograms()
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();
        return $this->render('wild/programs.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     * @Route("/actors", name="actors")
     * @return Response
     */
    public function showActors()
    {
        $actors = $this->getDoctrine()
            ->getRepository(Actor::class)
            ->findAll();
        return $this->render('wild/actors.html.twig', [
            'actors' => $actors,
        ]);
    }

    /**
     * @param int|null $id
     * @Route("/actor/{id}", defaults={"id" = null}, name="actor")
     * @return Response
     */
    public function showByActor(?int $id)
    {
        if (!$id) {
            throw $this
                ->createNotFoundException('No id has been sent to find a actor in actor\'s table.');
        }
        $actor = $this->getDoctrine()
            ->getRepository(Actor::class)
            ->findOneBy(['id' => $id]);
        if (!$actor) {
            throw $this->createNotFoundException(
                'No actor was found in actor\'s table.'
            );
        }

        $programs = $actor->getPrograms();

        return $this->render('wild/showbyactor.html.twig', [
            'programs' => $programs,
            'actor' => $actor,
        ]);
    }

    /**
     * @Route("/categories", name="categories")
     * @return Response
     */
    public function showCategories()
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        return $this->render('wild/categories.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @param int|null $id
     * @Route("/category/{id}", defaults={"id" = null}, name="category")
     * @return Response
     */
    public function showByCategory(?int $id)
    {
        if (!$id) {
            throw $this->createNotFoundException('No program found');
        }
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['id' => $id]);

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $category]);

        if (!$programs) {
            throw  $this->createNotFoundException(
                'This category has no program'
            );
        }
        return $this->render('wild/showbycategory.html.twig', [
            'programs' => $programs,
            'category' => $category,
        ]);
    }

    /**
     * @param int|null $id
     * @Route("/program/{id}", defaults={"id" = null}, name="program")
     * @return Response
     */
    public function showByProgram(?int $id): Response
    {
        if (!$id) {
            throw $this
                ->createNotFoundException('No id has been sent to find a program in program\'s table.');
        }
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['id' => $id]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with found in program\'s table.'
            );
        }
        $seasons = $program->getSeasons();
        return $this->render('wild/showbyprogram.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
        ]);
    }

    /**
     * @param int $id
     * @return Response
     * @Route("/season/{id}", defaults={"id" = null}, name="season")
     */
    public function showBySeason(int $id)
    {
        $season = $this->getDoctrine()->getRepository(Season::class)
            ->findOneBy(['id' => $id]);
        $program = $season->getProgram();
        $episodes = $season->getEpisodes();
        return $this->render('wild/showbyseason.html.twig', [
            'season' => $season,
            'program' => $program,
            'episodes' => $episodes,
        ]);
    }

    /**
     * @Route("/episode/{id}", name="episode")
     * @param Episode $episode
     * @param Request $request
     * @param int $id
     * @return Response
     */

    public function showByEpisode(Episode $episode, Request $request, int $id): Response

    {
        $season = $episode->getSeason();
        $program = $season->getProgram();
        $comments = $episode->getComments();

        $comment = new Comment;
        $form = $this->createForm(
            CommentType::class, $comment
        );
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $episode = $this->getDoctrine()
                ->getRepository(Episode::class)
                ->find($id);
            $author = $this->getUser();
            $comment->setAuthor($author);
            $comment->setEpisode($episode);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('wild_episode', ['id' => $id]);
        }


        return $this->render('wild/showbyepisode.html.twig', [
            'comments' => $comments,
            'episode'  => $episode,
            'program'  => $program,
            'season'   => $season,
            'form'     => $form->createView()
        ]);
    }
}