<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('name', 'text', array(
                'label' => 'app.entity.profile.name.label',
                'required' => true
            ))
            ->add('surname', 'text', array(
                'label' => 'app.entity.profile.surname.label',
                'required' => true
            ))
            ->add('description', 'textarea', array(
                'label' => 'app.entity.profile.description.label',
                'required' => true
            ))
            ->add('profileExtended', new ProfileExtendedType, array(
                'label' => 'app.entity.profile.profileExtended.label',
                'required' => true
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Profile',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_profile';
    }
}
