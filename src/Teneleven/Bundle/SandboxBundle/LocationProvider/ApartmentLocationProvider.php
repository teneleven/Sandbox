<?php

namespace Teneleven\Bundle\SandboxBundle\LocationProvider;

use Symfony\Component\Form\Form;
use Teneleven\Bundle\GeolocatorBundle\Provider\LocationProvider;

class ApartmentLocationProvider extends LocationProvider
{
    /**
     * {@inheritdoc}
     */
    public function getFilterFormType()
    {
        return 'teneleven_sandbox_apartment_locator';
    }

    /**
     * {@inheritdoc}
     */
    public function findLocations(Form $form)
    {
        $searchCenter = $form->get('location')->getData();
        $this->radius = $form->get('radius')->getData();

        $queryBuilder = $this->getQueryBuilder($searchCenter);

        $sortFields = array(
            'distance' => 'distance',
            'rent' => 'l.rent'
        );

        $queryBuilder->orderBy($sortFields[$form->get('sortBy')->getData()]);

        if ($rentLimit = $form->get('maxRent')->getData()) {
            $queryBuilder
                ->andWhere('l.rent <= :rent_limit')
                ->setParameter('rent_limit', $rentLimit)
            ;
        }

        $results = $queryBuilder->getQuery()->execute();

        return $this->decorateResults($searchCenter, $results);
    }
}
