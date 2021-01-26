<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Service\TypeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/type")
 *
 * Class TypeController
 * @package App\Controller
 */
class TypeController extends AbstractController
{
    /**
     * @Route(
     *   "/",
     *   name = "listType",
     *   methods = "GET"
     * )
     * @param TypeService $typeService
     * @return JsonResponse
     */
    public function index(TypeService $typeService): JsonResponse
    {
        $types = $typeService->getAllTypes();

        return new JsonResponse($types);
    }
}
