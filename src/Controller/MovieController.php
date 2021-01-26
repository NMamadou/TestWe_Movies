<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Service\MovieService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/movie")
 *
 * Class MovieController
 * @package App\Controller
 */
class MovieController extends AbstractController
{
    /**
     * @Route(
     *   "/",
     *   name = "listMovie",
     *   methods = "GET"
     * )
     * @param MovieService $movieService
     * @return JsonResponse
     */
    public function index(MovieService $movieService): JsonResponse
    {
        $movies = $movieService->getAllMovies();

        return new JsonResponse($movies);
    }

    /**
     * @Route("/new", name="newMovie")
     * @param MovieService $movieService
     * @param Request $request
     * @return Response
     */
    public function new(MovieService $movieService, Request $request): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movieService->saveMovie($movie);
            return $this->redirectToRoute('listMovie');
        }

        return $this->render('movie/edit.html.twig', ['form_movie' => $form->createView()]);
    }

    /**
     * @Route("/edit/{movie}", name="editMovie")
     * @param Movie $movie
     * @param Request $request
     * @param MovieService $movieService
     * @return Response
     */
    public function edit(Movie $movie, Request $request, MovieService $movieService): Response
    {
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movieService->saveMovie($movie);
            return $this->redirectToRoute('listMovie');
        }

        return $this->render('movie/edit.html.twig', ['form_movie' => $form->createView()]);
    }
}
