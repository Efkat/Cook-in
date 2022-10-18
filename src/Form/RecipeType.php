<?php

namespace App\Form;



use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormBuilderInterface;

class RecipeType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('title', TextType::class, [
            'label' => "Titre : ",
            'attr' => [
                'placeholder' => ""
            ]
        ])
            ->add('content', TextareaType::class, [
                'label' => "Recette : ",
                'attr' => [
                    'placeholder' => ""
                ]
            ])
            ->add('picture', FileType::class, [
                'label' => "Image de la recette : ",
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => "FAKE"
                    ])
                ]
            ])
            ->add('Difficulty', ChoiceType::class,[
                "choices" => [
                    "⭐" => 1,
                    "⭐⭐" => 2,
                    "⭐⭐⭐" => 3,
                    "⭐⭐⭐⭐" => 4,
                    "⭐⭐⭐⭐⭐" => 5
                ]
            ])
            ->add('Tags', EntityType::class,[
                'class' => Tag::class,
                'choice_label' => "name",
                'multiple' => true
            ])
            ->add('preparationTime', IntegerType::class)
            ->add('cookingTime', IntegerType::class)
            ->add('Submit', SubmitType::class);
    }
}