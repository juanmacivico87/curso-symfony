<?php

namespace App\DataFixtures;

use App\Entity\Bookmark;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BookmarkFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $bookmark = new Bookmark();

        $bookmark->setName('Palace of the Alhambra');
        $bookmark->setURL('https://www.alhambra-patronato.es/');
        $bookmark->setIsFavourite(true);
        $bookmark->setCategory($this->getReference(CategoryFixtures::TRIPS_CATEGORY_REFERENCE));

        $manager->persist($bookmark);
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CategoryFixtures::class,
        );
    }
}
