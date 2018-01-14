<?php
/**
 * Created by PhpStorm.
 * User: Lew
 * Date: 04/08/2017
 * Time: 21:18
 */

namespace Lew\MovieBundle\Form;


use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TriType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Rechercher',
                ),
                'required' => false,
            ))
            ->add('vu', ChoiceType::class, array(
                'placeholder' => '-- Tous --',
                'choices' => array(
                    'Pas Vu' => false,
                    'Déjà Vu' => true,
                ),
                'required' => false,
            ))
            ->add('genre', EntityType::class, array(
                'class' => 'Lew\ApiBundle\Entity\Genre',
                'choice_label' => 'name',
                'placeholder' => '-- Genre --',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.name', 'ASC');
                    },
                'required' => false,
                )
            )
            ->add('ordre', ChoiceType::class, array(
                'choices' => array(
                    'Titre' => 'title',
                    'Note' => 'note',
                    'Date' => 'dateSortie',
                    'Durée' => 'duree',
                    ),
                'data' => 'title'
            ))
            ->add('classement', ChoiceType::class, array(
                'choices' => array(
                    'Croissant' => 'ASC',
                    'Décroissant' => 'DESC',
                ),
                'data' => 'ASC',
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Trier',
                'attr' => array(
                    'class' => 'btn btn-info',
                )
            ));
    }
}