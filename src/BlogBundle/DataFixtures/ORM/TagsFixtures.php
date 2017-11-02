<?php

namespace BlogBundle\DataFixtures\ORM;

use BlogBundle\Entity\Tag;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class TagsFixtures extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        $tagsList = [
            'dolor',
            'ullamcorper',
            'suspendisse',
            'pellentesque',
            'maecenas',
            'malesuada',
            'ultricies',
            'etiam',
            'quisque',
            'fringilla',
            'eleifend',
            'bibendum',
            'faucibus',
            'luctus',
            'vestibulum'
        ];

        foreach ($tagsList as $key => $name) {
            $tag = new Tag();
            $tag
                ->setName($name);

            $manager->persist($tag);
            $this->addReference('tag_' . $name, $tag);
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