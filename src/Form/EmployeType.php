<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\Entreprise;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('adresse', TextType::class)
            ->add('cp', TextType::class, [
                'label' => 'Code postal',
            ])
            ->add('ville', TextType::class)
            ->add('dateEmbauche', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'raisonSociale',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                    ->orderBy('e.raisonSociale', 'ASC');
                },
            ])

            ->add('image', FileType::class, [
                'label' => 'Photo de profil',

                // unmapped veut dire que le champ n'est associé a aucune propriété de l'entité
                'mapped' => false,

                // Rend le champ optionel
                'required' => false,

                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/pdf',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Veuillez choisir une image valide.',
                    ]),
                ],
            ])
            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
