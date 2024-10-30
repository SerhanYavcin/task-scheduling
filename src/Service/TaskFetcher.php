<?php

namespace App\Service;

use App\Entity\Task;
use App\Entity\TaskProvider;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TaskFetcher
{
    private TaskProvider $provider;
    private HttpClientInterface $client;

    /**
     * @var Task[]
     */
    public array $tasks = [];

    /**
     * @var string
     */
    public string $message = '';

    public function __construct(TaskProvider $provider, HttpClientInterface $client)
    {
        $this->provider = $provider;
        $this->client = $client;
    }

    public function run()
    {
        $tasks = $this->fetch();

        if (empty($tasks)) {
            $this->message = 'No tasks found';
            return;
        }

        $this->transform($tasks);
    }

    private function fetch(): array
    {
        $response = $this->client->request('GET', $this->provider->getUrl());

        if ($response->getStatusCode() !== 200) {
            $this->message = 'Failed to fetch tasks';
        }

        return $response->toArray();
    }

    private function transform(array $tasks): void
    {

        foreach ($tasks as $task) {
            $code = $this->provider->getCode() . '-' . $task[$this->provider->getIdKey()];
            $duration = $task[$this->provider->getDurationKey()];
            $difficulty = $task[$this->provider->getDifficultyKey()];

            $task = new Task();

            $task->setCode($code);
            $task->setDuration($duration);
            $task->setDifficulty($difficulty);
            $task->setComplexity($duration * $difficulty);

            $this->tasks[] = $task;
        }
    }
}
