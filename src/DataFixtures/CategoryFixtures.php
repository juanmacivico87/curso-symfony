<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const TRIPS_CATEGORY_REFERENCE = 'trips-category';

    public function load(ObjectManager $manager)
    {
        $category = new Category();

        $category->setName('Trips');
        $category->setColour('#B316AC');

        $manager->persist($category);
        $manager->flush();

        $this->addReference(self::TRIPS_CATEGORY_REFERENCE, $category);
    }
}
