<?php

namespace App\Command;

use App\Entity\TaskProvider;
use App\Repository\TaskProviderRepository;
use App\Repository\TaskRepository;
use App\Service\TaskFetcher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TaskManagerCommand extends Command
{
    protected static $defaultName = 'app:task-manager';
    protected static $defaultDescription = 'Sync tasks from external API or Assign tasks to developers';

    private HttpClientInterface $client;
    private TaskRepository $taskRepository;
    private TaskProviderRepository $taskProviderRepository;

    public function __construct(TaskProviderRepository $taskProviderRepository, TaskRepository $taskRepository, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->taskRepository = $taskRepository;
        $this->taskProviderRepository = $taskProviderRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('operation', InputArgument::OPTIONAL, 'Task operation argument. Available operations: sync, assign, create-provider', 'sync');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $operation = $input->getArgument('operation');

        switch ($operation) {
            case 'sync':
                $this->sync($io);
                break;
            case 'assign':
                $io->success('Assigning tasks to developers');
                break;
            case 'create-provider':
                $this->createProvider($io);
                break;
            default:
                $io->error('Invalid operation');
                break;
        }

        return 0;
    }

    /**
     * Sync Tasks
     * @param SymfonyStyle $io
     */
    private function sync(SymfonyStyle $io)
    {
        $tasks = [];
        $providers = $this->taskProviderRepository->findAll();

        foreach ($providers as $provider) {

            $io->comment('Syncing tasks from ' . $provider->getCode());

            $fetcher = new TaskFetcher($provider, $this->client);

            $fetcher->run();

            $tasks = array_merge($tasks, $fetcher->tasks);
        }

        if (empty($tasks)) {
            $io->error($fetcher->message);
            return;
        }

        try {
            $this->taskRepository->addMultiple($tasks);
        } catch (\Exception $e) {
            $io->error('Failed to sync task: ' . $e->getMessage());
        }

        $io->success('Tasks synced successfully');
    }

    /**
     * Create Task Provider Entity and Save to Database 
     * @param SymfonyStyle $io
     * @see TaskProvider Entity Class for more details about Task Provider Entity Fields and Methods
     */
    private function createProvider(SymfonyStyle $io)
    {
        /**
         * Provider URL
         * @var string $url
         */
        $url = $io->ask("Enter provider URL", null, function ($url) {
            if (filter_var($url, FILTER_VALIDATE_URL) === false) {
                throw new \RuntimeException('Invalid URL');
            }

            if ($this->taskProviderRepository->isUrlExists($url)) {
                throw new \RuntimeException('Provider URL already exists');
            }

            return $url;
        });


        /**
         * Provider Code
         * @var string $code
         */
        $code = $io->ask("Enter provider code (4 characters)", null, function ($code) {
            if (!$code) {
                throw new \RuntimeException('Provider code is required');
            }

            if ($this->taskProviderRepository->isCodeExists($code)) {
                throw new \RuntimeException('Provider code already exists');
            }

            if (strlen($code) > 4) {
                throw new \RuntimeException('Provider code max length is 4 characters');
            }

            return $code;
        });

        /**
         * Provider ID Key
         * @var string $idKey
         */
        $idKey = $io->ask("Enter provider id key", null, function ($idKey) {
            if (!$idKey) {
                throw new \RuntimeException('Provider id key is required');
            }

            return $idKey;
        });

        /**
         * Provider Difficulty Key
         * @var string $difficultyKey
         */
        $difficultyKey = $io->ask("Enter provider difficulty key", null, function ($difficultyKey) {
            if (!$difficultyKey) {
                throw new \RuntimeException('Provider difficulty key is required');
            }

            return $difficultyKey;
        });

        /**
         * Provider Duration Key
         * @var string $durationKey
         */
        $durationKey = $io->ask("Enter provider duration key", null, function ($durationKey) {
            if (!$durationKey) {
                throw new \RuntimeException('Provider duration key is required');
            }

            return $durationKey;
        });

        // Create Provider Entity and Save
        $provider = new TaskProvider();

        $provider->setUrl($url);
        $provider->setCode($code);
        $provider->setIdKey($idKey);
        $provider->setDifficultyKey($difficultyKey);
        $provider->setDurationKey($durationKey);
        $provider->setStatus(true);

        try {
            $this->taskProviderRepository->add($provider);
            $io->success('Task Provider created successfully');
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to create Task Provider :' . $e->getMessage());
        } finally {
            $io->table(['URL', 'CODE', 'ID', 'DURATION', 'DIFFICULTY KEY'], [
                [$url, $code, $idKey, $durationKey, $difficultyKey],
            ]);

            $io->success('Task Provider created successfully');
        }
    }
}
