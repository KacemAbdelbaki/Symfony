<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('category',ChoiceType::class,[
                    'choices'=>[
                        'Science-Fiction'=>'Science-Fiction',
                        'Mystery'=>'Mystery',
                        'Autobiography'=>'Autobiography'
                    ]
                ]
            )
            ->add('publicationDate')
            ->add('published')
            ->add('ref')
            ->add('authors', EntityType::class, [
                'class'=> 'App\Entity\Author',
                'choice_label'=>'username',
                'placeholder'=> 'Select an author',
                'required'=>'true'
            ])
            ->add('button', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
