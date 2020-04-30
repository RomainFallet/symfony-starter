<?php

namespace App\Form;

use App\Entity\Cat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CatType extends AbstractType
{
    /**
     * @param FormBuilderInterface<FormInterface> $builder
     * @param array<mixed> $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        dump($options);
        $builder
            ->add('name')
            ->add('color', ChoiceType::class, [
                'choices' => [
                    'Black' => 'black',
                    'Brown' => 'brown',
                    'White' => 'white',
                ],
            ])
            ->add('url');
    }

    /**
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cat::class,
        ]);
    }
}
