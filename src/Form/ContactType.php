<?php

namespace App\Form;
//namespace Aurel\ContactBundle\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom', TextType::class,['attr' => ['placeholder' => 'prénom']])
            ->add('nom', TextType::class, ['attr'=>['placeholder'=> 'nom']])
            ->add('mail',EmailType::class, ['attr'=>['placeholder'=> 'email']])
            ->add('description', TextareaType::class, ['attr'=>['placeholder'=> 'description' ]])
            ->getForm();
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
