<?php

namespace App\Form;

use App\Entity\Quiz;
use App\Quizes\QuizBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;

class QuizAnswerCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('answers', CollectionType::class, [
                'entry_type' => QuizUserAnswerType::class,
                'allow_add' => true,
                'constraints' =>[
                    new Count([
                        'min' => QuizBuilder::QUIZ_SIZES[Quiz::TYPE_EXAM]-1,
                        'max' => QuizBuilder::QUIZ_SIZES[Quiz::TYPE_EXAM],
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }
}
