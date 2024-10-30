<?php

namespace App\DataFixtures;

use App\Entity\Developer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DeveloperFixtures extends Fixture
{
    /**
     * @var array
     * Default developers for the application
     */
    private array $developers = [
        [
            'name' => 'Dev 1',
            'capacity' => 1,
        ],
        [
            'name' => 'Dev 2',
            'capacity' => 2,
        ],
        [
            'name' => 'Dev 3',
            'capacity' => 3,
        ],
        [
            'name' => 'Dev 4',
            'capacity' => 4,
        ],
        [
            'name' => 'Dev 5',
            'capacity' => 5,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->developers as $developer) {
            $developerEntity = new Developer();
            $developerEntity->setName($developer['name']);
            $developerEntity->setCapacity($developer['capacity']);
            $manager->persist($developerEntity);
        }

        $manager->flush();
    }
}
