<?php

namespace HB\Bundle\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AuteurType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 'text')
            ->add('prenom', 'text')
            ->add('pseudo', 'text')
            ->add('password', 'repeated', array(
				    'type' => 'password',
				    'invalid_message' => 'Les mots de passe doivent correspondre',
				    'options' => array('required' => true),
				    'first_options'  => array('label' => 'Mot de passe'),
				    'second_options' => array('label' => 'Mot de passe (validation)'),
            		))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HB\Bundle\BlogBundle\Entity\Auteur'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'hb_bundle_blogbundle_auteur';
    }
}
