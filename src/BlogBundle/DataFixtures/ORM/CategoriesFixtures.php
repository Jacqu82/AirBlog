<?php

namespace BlogBundle\DataFixtures\ORM;

use BlogBundle\Entity\Category;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class CategoriesFixtures extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        $categoriesList = [
            'osobowe' => 'Samoloty osobowe i pasażerskie',
            'odrzutowe' => 'Samoloty odrzutowe',
            'wojskowe' => 'Samoloty wojskowe',
            'kosmiczne' => 'Promy kosmiczne',
            'tajne' => 'Tajne rozwiązania'
            //'nowa' => 'Nowa kategoria'
        ];

        foreach ($categoriesList as $key => $name) {
            $category = new Category();
            $category
                ->setName($name);

            $manager->persist($category);
            $this->addReference('category_' . $key, $category);
        }

        $manager->flush();

    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 0;
    }
}