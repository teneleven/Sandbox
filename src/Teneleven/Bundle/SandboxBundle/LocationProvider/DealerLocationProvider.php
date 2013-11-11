<?php

namespace Teneleven\Bundle\SandboxBundle\LocationProvider;

use Doctrine\ORM\EntityManager;
use Teneleven\Bundle\GeolocatorBundle\Model\GeolocatorResult;
use Teneleven\Bundle\GeolocatorBundle\Provider\AbstractLocationProvider;
use Teneleven\Bundle\GeolocatorBundle\Util\UnitConverter;

/**
 * LocationProvider implementation which locates Dealers
 */
class DealerLocationProvider extends AbstractLocationProvider
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
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
        /* @var $criteria \Geocoder\Result\Geocoded */
        $radius = 300; //in miles

        $queryBuilder = $this->manager->getRepository('TenelevenSandboxBundle:Dealer')->createQueryBuilder('d');

        $queryBuilder->select('d, GEO_DISTANCE(:latitude, :longitude, d.latitude, d.longitude) AS distance')
            ->where($queryBuilder->expr()->isNotNull('d.latitude'))
            ->andWhere($queryBuilder->expr()->isNotNull('d.longitude'))
            ->setParameter('latitude', $criteria->getLatitude())
            ->setParameter('longitude', $criteria->getLongitude())
            ->orderBy('distance');

        if ($radius) {
            $queryBuilder
                ->having('distance <= :radius')
                ->setParameter('radius', UnitConverter::milesToKm($radius))
            ;
        }

        $results = $queryBuilder->getQuery()->execute();

        $locations = array();

        foreach ($results as $result) {
            $distanceInMiles = UnitConverter::kmToMiles($result['distance']);
            //$distanceInMiles = $result['distance'];
            $locations[$result[0]->getId()] = new GeolocatorResult($result[0], $distanceInMiles);
        }

        return $locations;
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