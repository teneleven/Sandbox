<?php

namespace Teneleven\Bundle\SandboxBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ApartmentLocatorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $radiusChoices = array(
            10 => '10 Miles',
            20 => '20 Miles',
            50 => '50 Miles'
        );

        $sortChoices = array(
            'distance' => 'Distance',
            'rent' => 'Rent'
        );

        $builder
            ->add('radius', 'choice', array('choices' => $radiusChoices, 'empty_value' => 'Any', 'required' => false))
            ->add('location', 'teneleven_geolocator_geocoded_address')
            ->add('maxRent', 'number', array('required' => false))
            ->add('sortBy', 'choice', array('choices' => $sortChoices, 'empty_value' => false, 'required' => false))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'teneleven_sandbox_apartment_locator';
    }
}
