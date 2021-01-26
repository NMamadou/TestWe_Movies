<?php

namespace App\Repository;

use App\Entity\Movie;
use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    /**
     *
     * @return Movie[]
     */
    public function getAllMovies(): array
    {
        $sql = " SELECT 
                    M.*,
                    (
                        SELECT STRING_AGG(T.NAME, ', ') AS TYPE
                        FROM TYPE T
                        INNER JOIN MOVIE_HAS_TYPE MT ON
                            MT.TYPE_ID = T.ID
                        AND MT.MOVIE_ID = M.ID
                    ),
                    (
                        SELECT STRING_AGG(P.FIRSTNAME || ' ' || P.LASTNAME, ', ') AS PEOPLE
                        FROM PEOPLE P
                        INNER JOIN MOVIE_HAS_PEOPLE MP ON
                            MP.PEOPLE_ID = P.ID
                        AND MP.MOVIE_ID = M.ID
                    )
                FROM
                    MOVIE M ";

        $stmt = $this->_em->getConnection()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * @param Person $person
     * @return Person[]
     */
    public function getMoviesOf(Person $person): array
    {
        $qb = $this->createQueryBuilder('m');

        $qb
            ->join('m.movieHasPeople', 'p')
            ->where($qb->expr()->eq('p.person', ':person'))
            ->setParameter('person', $person)
        ;

        return $qb->getQuery()->getResult();
    }
}
