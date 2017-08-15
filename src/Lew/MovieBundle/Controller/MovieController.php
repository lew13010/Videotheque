<?php

namespace Lew\MovieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MovieController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createForm('Lew\MovieBundle\Form\TriType');
        $form->handleRequest($request);

        $repo = $this->getDoctrine()->getRepository('LewApiBundle:Movie');

        if ($form->isSubmitted() && $form->isValid()) {
            $ordre = $form->getData()['ordre'];
            $tri = $form->getData()['classement'];

            if ($form->getData()['search'] != '') {
                $title = $form->getData()['search'];
            } else {
                $title = null;
            }

            if ($form->getData()['genre'] != null) {
                $genre = $form->getData()['genre']->getId();
                $films = $repo->searchMoviesByGenre($title, $genre, $ordre, $tri);
                $aleatoires = $repo->aleatoires(6, $genre);
            }else{
                $aleatoires = $repo->aleatoires(6);
                $films = $repo->searchMovies($title, $ordre, $tri);
            }

            if (empty($films)) {
                $this->addFlash('warning', 'Aucun film ne correspond à la recherche');
            }
        } else {
            $aleatoires = $repo->aleatoires(6);
            $films = $repo->findBy(array(), array('title' => 'ASC'));
        }

        $count = count($films);

        return $this->render('LewMovieBundle:Movie:index.html.twig', array(
            'films' => $films,
            'form' => $form->createView(),
            'count' => $count,
            'aleatoires' => $aleatoires,
        ));
    }

    public function showAction(Request $request, $movie)
    {
        $similars = $this->getDoctrine()->getRepository('LewApiBundle:Movie')->getSimilarMovie($movie);

        $film = $this->getDoctrine()->getRepository('LewApiBundle:Movie')->find($movie);
        $realisateurs = $this->getDoctrine()->getRepository('LewApiBundle:Movie')->getRealisateurs($movie);
        $acteurs = $this->getDoctrine()->getRepository('LewApiBundle:Movie')->getActeurs($movie);
        $staffs = $this->getDoctrine()->getRepository('LewApiBundle:Movie')->getStaffs($movie);


        return $this->render('LewMovieBundle:Movie:show.html.twig', array(
            'film' => $film,
            'realisateurs' => $realisateurs,
            'acteurs' => $acteurs,
            'staffs' => $staffs,
            'similars' => $similars,
        ));
    }
}
