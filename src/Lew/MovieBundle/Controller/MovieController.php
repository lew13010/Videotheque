<?php

namespace Lew\MovieBundle\Controller;

use Lew\ApiBundle\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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

            $vu = $form->getData()['vu'];


            if ($form->getData()['genre'] != null) {
                $genre = $form->getData()['genre']->getId();
                $films = $repo->searchMoviesByGenre($title, $genre, $ordre, $tri, $vu);
                $aleatoires = $repo->aleatoires(6, $genre);
            } else {
                $aleatoires = $repo->aleatoires(6);
                $films = $repo->searchMovies($title, $ordre, $tri, $vu);
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

    public function showAction(Request $request, Movie $movie)
    {
        $form = $this->get('form.factory')->createBuilder(FormType::class, $movie)
            ->add('vu', ChoiceType::class, array(
                'choices' => array(
                    'Déjà vu' => true,
                    'Pas Vu' => false,
                ),
                'mapped' => true,
                'multiple' => false,
                'expanded' => true,
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();
        }

        $deleteForm = $this->createDeleteForm($movie);

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
            'delete_form' => $deleteForm->createView(),
            'vu_form' => $form->createView(),
        ));
    }

    /**
     * Deletes a movie entity.
     *
     */
    public function deleteAction(Request $request, Movie $movie)
    {
        $form = $this->createDeleteForm($movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($movie);
            $em->flush();
        }

        return $this->redirectToRoute('lew_movie_homepage');
    }

    /**
     * Creates a form to delete a lineUp entity.
     *
     * @param Movie $movie The Movie entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Movie $movie)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('lew_movie_delete', array('id' => $movie->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
