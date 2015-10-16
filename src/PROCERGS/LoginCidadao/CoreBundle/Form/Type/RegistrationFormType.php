<?php

namespace PROCERGS\LoginCidadao\CoreBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, array('required' => true, 'label' => 'form.firstName', 'translation_domain' => 'FOSUserBundle'))
            ->add('surname', null, array('required' => true, 'label' => 'form.surname', 'translation_domain' => 'FOSUserBundle'))
            ->add('email', 'email', array('required' => true, 'label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
            ->add('plainPassword', 'password', array('required' => true, 'label' => 'form.password', 'translation_domain' => 'FOSUserBundle'));
    }

    public function getName()
    {
        return 'procergs_person_registration';
    }
}
