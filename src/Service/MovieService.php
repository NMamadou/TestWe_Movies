<?php

namespace App\Service;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;

class MovieService
{
    /** @var EntityManagerInterface */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function saveMovie(Movie $movie) {
        $this->em->persist($movie);
        $this->em->flush();
    }

    public function getAllMovies() {
        return $this->getMovieRepository()->getAllMovies();
    }

    public function getMovieRepository() {
        return $this->em->getRepository(Movie::class);
    }

    public function saveNewMovie($data) {
        $response = new class {
            public $body = [];
            public $code = Response::HTTP_OK;
        };
        $inputErrors = [];

        foreach ([
            'title',
            'duration',
            'types'
        ] as $key) {
            if (!isset($data[$key])) {
                $inputErrors[] = [
                    $key => [
                        "message" => "Missing mandatory field $key",
                        "code" => "missing_mandatory_field_$key",
                    ]
                ];
                $$key = null;
            } else {
                $$key = $data[$key];
            }
        }

        if (count($inputErrors) > 0) {
            $response->body = [
                'message' => 'Invalid form',
                'code' => "invalid_form",
                'payload' => $inputErrors
            ];

            $response->code = Response::HTTP_BAD_REQUEST;

            return $response;
        }

        $response->body = [
            'message' => 'Movie created',
            'code' => 'movie_created',
            'payload' => []
        ];

        return $response;
    }
}