<?php

namespace HB\Bundle\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentaireType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder // on commente les champs qui ne doivent pas s'afficher dans le formulaire
            //->add('dateCreation', 'datetime')
            ->add('titre', 'text')
            ->add('contenu', 'textarea')
            ->add('auteur', 'entity', array(
					  'class'        => 'HBBlogBundle:Auteur',
					  'property'     => 'pseudo')
            		)
            /*->add('article', 'entity', array(
					  'class'        => 'HBBlogBundle:Article',
					  'property'     => 'titre')
            		)*/
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HB\Bundle\BlogBundle\Entity\Commentaire'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'hb_bundle_blogbundle_commentaire';
    }
}
