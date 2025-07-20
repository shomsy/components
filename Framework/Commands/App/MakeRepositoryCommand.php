<?php

declare(strict_types=1);

namespace Gemini\Commands\App;

use Gemini\Database\Migration\Runner\Generators\Repository\RepositoryGenerator;
use Psr\Log\LoggerInterface;
use Throwable;

final readonly class MakeRepositoryCommand
{
    public function __construct(
        private RepositoryGenerator $repositoryGenerator,
        private LoggerInterface     $logger
    ) {}

    public function execute(array $arguments) : void
    {
        $name   = $arguments['name'] ?? null;
        $entity = $arguments['entity'] ?? null;

        if (empty($name) || empty($entity)) {
            $this->logger->error(message: "Repository name and entity are required.");
            echo "Error: Repository name and entity are required.\n";

            return;
        }

        try {
            $this->repositoryGenerator->create(tableName: $name, entity: $entity);
            $this->logger->info(
                message: sprintf(
                             "Repository '%s' for entity '%s' created successfully.",
                             $name,
                             $entity
                         )
            );
        } catch (Throwable $throwable) {
            $this->logger->error(message: 'Error creating repository: ' . $throwable->getMessage());
        }
    }
}
