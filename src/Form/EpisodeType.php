<?php

namespace App\Form;

use App\Entity\Episode;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EpisodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('season', null, [
                'choice_label' => 'id'
            ])
            ->add('title', null,  [
                'attr' => [
                    'placeholder' => 'Title of the episode'
                ]
            ])
            ->add('number')
            ->add('synopsis');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Episode::class,
        ]);
    }
}
