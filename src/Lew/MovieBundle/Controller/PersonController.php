<?php

namespace Lew\MovieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PersonController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createForm('Lew\MovieBundle\Form\SearchType');
        $form->handleRequest($request);
        $persons = null;
        $count = 0;

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
            $repo = $this->getDoctrine()->getRepository('LewApiBundle:Person');
            $persons = $repo->findPerson($search);
            $count = count($persons);
        }
        return $this->render('LewMovieBundle:Person:index.html.twig', array(
            'form' => $form->createView(),
            'persons' => $persons,
            'count' => $count
        ));
    }

    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $this->getDoctrine()->getRepository('LewApiBundle:Person')->findOneBy(array('id' => $id));
        $metiers = $this->getDoctrine()->getRepository('LewApiBundle:Person')->getMetiers($id);
        $roles = [];
        foreach ($metiers as $metier) {
            $roles[$metier['metier']] = $this->getDoctrine()->getRepository('LewApiBundle:Casting')->getRole($id, $metier);
        }

        return $this->render('LewMovieBundle:Person:show.html.twig', array(
            'person' => $person,
            'metiers' => $metiers,
            'roles' => $roles,
        ));
    }

}
