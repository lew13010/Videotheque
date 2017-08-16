<?php

namespace Lew\ApiBundle\Repository;

/**
 * MovieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MovieRepository extends \Doctrine\ORM\EntityRepository
{
    public function searchMoviesByGenre($title, $genre, $ordre, $tri)
    {
        $qb = $this->createQueryBuilder('m');

        $qb
            ->innerJoin('m.genres', 'g', 'WITH', 'g.id = :genre')
            ->where('m.title LIKE :title')
            ->setParameter('title', '%' . $title . '%')
            ->setParameter('genre', $genre)
            ->orderBy('m.' . $ordre, $tri);

        return $qb->getQuery()->getResult();
    }

    public function searchMovies($title, $ordre, $tri)
    {
        $qb = $this->createQueryBuilder('m');

        $qb
            ->where('m.title LIKE :title')
            ->setParameter('title', '%' . $title . '%')
            ->orderBy('m.' . $ordre, $tri);

        return $qb->getQuery()->getResult();
    }

    public function aleatoires($nb, $genre = null)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->select('m');

        if ($genre != null) {
            $qb->innerJoin('m.genres', 'g', 'WITH', 'g.id = :genre')
                ->setParameter('genre', $genre);
        }
        $qb
            ->orderBy('RAND()')
            ->setMaxResults($nb);
        return $qb->getQuery()->getResult();
    }

    public function getRealisateurs($id)
    {
        $qb = $this->createQueryBuilder('m');
        $qb
            ->select('p.id, p.name, p.image, p.sexe')
            ->innerJoin('m.castings', 'c')
            ->innerJoin('c.person', 'p')
            ->where('m.id = :id')
            ->andWhere('c.metier = :value')
            ->setParameter('id', $id)
            ->setParameter('value', 'Director');
        return $qb->getQuery()->getResult();
    }

    public function getActeurs($id)
    {
        $qb = $this->createQueryBuilder('m');
        $qb
            ->select('p.id, p.name, p.image, p.sexe, c.role')
            ->innerJoin('m.castings', 'c')
            ->innerJoin('c.person', 'p')
            ->where('m.id = :id')
            ->andWhere('c.metier = :value')
            ->setParameter('id', $id)
            ->setParameter('value', 'Acteur')
            ->orderBy('c.id', 'ASC');
        return $qb->getQuery()->getResult();
    }

    public function getStaffs($id)
    {
        $qb = $this->createQueryBuilder('m');
        $qb
            ->select('p.id, p.name, c.metier')
            ->innerJoin('m.castings', 'c')
            ->innerJoin('c.person', 'p')
            ->where('m.id = :id')
            ->andWhere('c.metier != :value1')
            ->andWhere('c.metier != :value2')
            ->setParameter('id', $id)
            ->setParameter('value1', 'Acteur')
            ->setParameter('value2', 'Director');
        return $qb->getQuery()->getResult();
    }

    public function getSimilarMovie($id)
    {
        $genres = $this->createQueryBuilder('movie');
        $genres
            ->select('genres.id')
            ->innerJoin('movie.genres', 'genres')
            ->where('movie.id = :id')
            ->setParameter('id', $id);
        $results = $genres->getQuery()->getResult();

        $qb = $this->createQueryBuilder('m');
        $qb
            ->select('m')
            ->leftJoin('m.genres', 'g')
        ;

        $i = 0;
        foreach ($results as $result) {
            $idResult = $result['id'];
            $subQuery = $this->createQueryBuilder("m$i");
            $subQuery
                ->select("m$i.id")
                ->innerJoin("m$i.genres", "g$i")
                ->where("g$i.id = :name$i")
                ;
            $qb->andWhere($qb->expr()->in('m.id', $subQuery->getDQL()));
            $qb->setParameter("name$i", $idResult);
            $i++;
        }

        $qb
            ->andWhere('m.id != :movie')
            ->setParameter(':movie', $id)
            ->orderBy('m.title', 'asc')
        ;

        return $qb->getQuery()->getResult();
    }

    public function getRecentlyMovies()
    {
        $date = new \DateTime();
        $week = date_sub($date, date_interval_create_from_date_string('7 days'));

        $qb = $this->createQueryBuilder('m');
        $qb
            ->select('m')
            ->where('m.dateAjout > :week')
            ->setParameter(':week', $week)
            ->orderBy('m.dateAjout', 'desc')
        ;
        return $qb->getQuery()->getResult();
    }

    public function tops()
    {
        $qb = $this->createQueryBuilder('m');
        $qb
            ->select('m')
            ->orderBy('m.note', 'desc')
            ->setMaxResults(12)
        ;
        return $qb->getQuery()->getResult();
    }
}
