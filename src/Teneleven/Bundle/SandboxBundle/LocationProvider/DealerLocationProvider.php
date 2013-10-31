<?php

namespace Teneleven\Bundle\SandboxBundle\LocationProvider;


use Doctrine\ORM\EntityManager;
use Teneleven\Bundle\GeolocatorBundle\Provider\AbstractLocationProvider;

class DealerLocationProvider extends AbstractLocationProvider
{
    protected $manager;

    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function findLocations($criteria)
    {
        return $this->manager->getRepository('TenelevenSandboxBundle:Dealer')->findAll();
    }

    /**
     * {@inheritdoc}
     */
    public function getUncodedLocations()
    {
        return $this->manager->getRepository('TenelevenSandboxBundle:Dealer')->findBy(array('latitude' => null));
    }

    /**
     * {@inheritdoc}
     */
    public function extractAddress($object)
    {
        /* @var $object \Teneleven\Bundle\SandboxBundle\Entity\Dealer */
        $bits[] = $object->getStreet();
        $bits[] = $object->getCity();
        $bits[] = $object->getState();
        $bits[] = $object->getZip();

        return implode(' ', $bits);
    }
}