<?php

namespace App\DataFixtures;

use App\Entity\TaskProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaskProviderFixtures extends Fixture
{
    /**
     * @var array
     * Default providers for the application
     * This can be extended by adding more providers
     */
    private array $providers = [
        [
            'url' => 'https://raw.githubusercontent.com/WEG-Technology/mock/refs/heads/main/mock-one',
            'code' => 'MOC1',
            'id_key' => 'id',
            'duration_key' => 'estimated_duration',
            'difficulty_key' => 'value',
        ],
        [
            'url' => 'https://raw.githubusercontent.com/WEG-Technology/mock/refs/heads/main/mock-two',
            'code' => 'MOC2',
            'id_key' => 'id',
            'duration_key' => 'sure',
            'difficulty_key' => 'zorluk',
        ]
    ];

    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {

        foreach ($this->providers as $provider) {
            $taskProvider = new TaskProvider();

            $taskProvider->setUrl($provider['url']);
            $taskProvider->setCode($provider['code']);
            $taskProvider->setIdKey($provider['id_key']);
            $taskProvider->setDurationKey($provider['duration_key']);
            $taskProvider->setDifficultyKey($provider['difficulty_key']);
            $taskProvider->setStatus(true);

            $manager->persist($taskProvider);
        }

        $manager->flush();
    }
}
