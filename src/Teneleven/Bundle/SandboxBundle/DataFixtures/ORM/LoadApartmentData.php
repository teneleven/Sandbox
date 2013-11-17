<?php

namespace Teneleven\Bundle\SandboxBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Teneleven\Bundle\SandboxBundle\Entity\Apartment;

class LoadApartmentData extends AbstractFixture implements ContainerAwareInterface
{
    protected $container;

    protected $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 100; $i++) {

            $apartment = new Apartment();
            $apartment
                ->setStreet($this->faker->streetName)
                ->setCity($this->faker->city)
                ->setState($this->faker->stateAbbr)
                ->setZip($this->faker->postcode)
                ->setRent($this->faker->randomNumber(800, 3000))
                ->setLatitude($this->faker->latitude)
                ->setLongitude($this->faker->longitude)
            ;

            $manager->persist($apartment);
        }

        $manager->flush();
    }
}
