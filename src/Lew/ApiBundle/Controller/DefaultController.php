<?php

namespace Lew\ApiBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Lew\ApiBundle\Entity\Casting;
use Lew\ApiBundle\Entity\Genre;
use Lew\ApiBundle\Entity\Movie;
use Lew\ApiBundle\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createForm('Lew\ApiBundle\Form\SearchMovieType');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movie = $form->getData()['movie'];
            return $this->redirectToRoute('lew_api_search', ['movie' => $movie]);
        }
        return $this->render('LewApiBundle:Default:index.html.twig', ['form' => $form->createView()]);
    }

    public function searchAction(Request $request, $movie, $page = 1)
    {
        $form = $this->createForm('Lew\ApiBundle\Form\SearchMovieType');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movie = $form->getData()['movie'];
            return $this->redirectToRoute('lew_api_search', ['movie' => $movie, 'page' => $page]);
        }

        if (!$this->get('lew_api.service.api_request')->checkMovie($movie)) {
            return $this->redirectToRoute('lew_api_homepage');
        }

        $results = $this->get('lew_api.service.api_request')->searchMovie($movie, $page);
        $pagination = $results->total_pages;


        return $this->render('LewApiBundle:Default:listresult.html.twig', [
            'results' => $results,
            'movie' => $movie,
            'page' => $page,
            'pagination' => $pagination,
            'form' => $form->createView()
        ]);
    }

    public function addMovieAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repoGenre = $em->getRepository('LewApiBundle:Genre');
        $repoPays = $em->getRepository('LewApiBundle:Country');

        $movie = $this->get('lew_api.service.api_request')->getMovie($id);

        try {
            $film = new Movie();

            if (isset($movie->release_date)) {
                $film->setDateSortie($movie->release_date);
            } else {
                $film->setDateSortie(null);
            }
            $film->setId($id);
            $film->setTitle($movie->title);
            $film->setDuree($movie->runtime);
            $film->setNote($movie->vote_average);
            $film->setImage($movie->poster_path);
            $film->setResume($movie->overview);
            foreach ($movie->production_countries as $country) {
                $pays = $repoPays->findOneBy(array('alphaCode' => $country->iso_3166_1));
                $film->addCountry($pays);
            }
            foreach ($movie->genres as $genre) {
                $g = $repoGenre->findOneBy(array('id' => $genre->id));
                $film->addGenre($g);
            }
            $em->persist($film);
            $em->flush();
        } catch (UniqueConstraintViolationException $exception) {
            $this->getDoctrine()->resetManager();
        }

        $credits = $this->get('lew_api.service.api_request')->getCredits($id);
        foreach ($credits->cast as $cast) {
            $this->addPerson($cast->id);
            $this->addCasting($id, $cast->id, 'Acteur', $cast->character);
        }

        foreach ($credits->crew as $crew) {
            $this->addPerson($crew->id);
            $this->addCasting($id, $crew->id, $crew->job);
        }

        $this->addFlash('info', 'Film ajouté avec succès !');

        return $this->redirectToRoute('lew_api_homepage');
    }

    public function addPerson($idPerson)
    {
        $em = $this->getDoctrine()->getManager();
        $repoPays = $em->getRepository('LewApiBundle:Country');
        $person = $this->get('lew_api.service.api_request')->getPerson($idPerson);

        try {
            $personne = new Person();
            if (isset($person->birthday)) {
                $personne->setBirthDate($person->birthday);
            } else {
                $personne->setBirthDate(null);
            }
            if (isset($person->deathday)) {
                $personne->setDeathDate($person->deathday);
            } else {
                $personne->setDeathDate(null);
            }
            if (isset($person->place_of_birth)) {
                $birthPlace = $person->place_of_birth;
            } else {
                $birthPlace = null;
            }
            $personne->setId($person->id);
            $personne->setName($person->name);
            $personne->setSexe($person->gender);
            $personne->setImage($person->profile_path);
            $personne->setBirthPlace($birthPlace);
            $personne->setBiographie($person->biography);

            $em->persist($personne);
            $em->flush();
        } catch (UniqueConstraintViolationException $exception) {
        }
        $this->getDoctrine()->resetManager();
        return true;
    }

    public function addCasting($idMovie, $idPerson, $metier, $role = null)
    {
        $em = $this->getDoctrine()->getManager();
        $movie = $em->getRepository('LewApiBundle:Movie')->find($idMovie);
        $person = $em->getRepository('LewApiBundle:Person')->find(($idPerson));


        try {
            $casting = new Casting();
            $casting->setMetier($metier);
            $casting->setRole($role);
            $casting->setPerson($person);
            $casting->setMovie($movie);

            $em->persist($casting);
            $em->flush();
        } catch (UniqueConstraintViolationException $exception) {
        }
        $this->getDoctrine()->resetManager();
        return true;
    }

    public function updateAction()
    {
        if($this->get('lew_api.service.api_request')->getUpdateMovie()){
            $this->addFlash('success', 'Base de données mise à jour');
        }else{
            $this->addFlash('danger', 'Echecs de la mise à jour de la base de données');
        }
        return $this->redirectToRoute('lew_movie_homepage');
    }

    public function testAction()
    {
        $genre = new Genre();
        $genre->setId(1);
        $genre->setName('test');

        $em = $this->getDoctrine()->getManager();
        $em->persist($genre);
        $em->flush();

        return true;
    }
}
