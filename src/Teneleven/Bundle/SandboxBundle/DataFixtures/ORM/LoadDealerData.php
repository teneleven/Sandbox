<?php

namespace Teneleven\Bundle\SandboxBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Teneleven\Bundle\SandboxBundle\Entity\Dealer;

class LoadDealerData extends AbstractFixture implements ContainerAwareInterface
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

            $dealer = new Dealer();
            $dealer
                ->setName($this->faker->name)
                ->setStreet($this->faker->streetName)
                ->setCity($this->faker->city)
                ->setState($this->faker->stateAbbr)
                ->setZip($this->faker->postcode)
                ->setPhone($this->faker->phoneNumber)
            ;

            //some dealers won't have lats/lngs to play with geocoder
            if (rand(0, 3)) {
                $dealer
                    ->setLatitude($this->faker->latitude)
                    ->setLongitude($this->faker->longitude)
                ;
            }

            $manager->persist($dealer);
        }

        $manager->flush();
    }
}