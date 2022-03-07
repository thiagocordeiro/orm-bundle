<?php

declare(strict_types=1);

namespace Orm\OrmBundle;

use Orm\Builder\RepositoryResolver;
use Symfony\Component\HttpKernel\CacheClearer\CacheClearerInterface;
use Throwable;

/**
 * Do not inject things from container to avoid environment variable errors
 *
 * @phpstan-type EntityConfig array{
 *      factory: ?callable,
 *      repository: ?class-string,
 *      table: ?string,
 *      order: ?array<string, string>,
 * }
 */
class OrmCacheClearer implements CacheClearerInterface
{
    /** @var array<class-string> */
    private array $entities;

    private RepositoryResolver $resolver;
    private string $cacheDir;
    private string $fileUser;
    private string $fileGroup;

    /**
     * @param array<class-string, EntityConfig> $entityConfig
     */
    public function __construct(string $cacheDir, string $fileUser, string $fileGroup, array $entityConfig)
    {
        $this->cacheDir = $cacheDir;
        $this->fileUser = $fileUser;
        $this->fileGroup = $fileGroup;

        $this->entities = array_keys($entityConfig);
        $this->resolver = new RepositoryResolver($cacheDir, true, $entityConfig, $fileUser, $fileGroup);
    }

    /**
     * @inheritdoc
     * @throws Throwable
     */
    public function clear(string $cacheDir): void
    {
        $this->create();

        foreach ($this->entities as $class) {
            $this->resolver->resolve($class);
        }
    }

    protected function create(string $subdir = ''): void
    {
        $directory = "{$this->cacheDir}{$subdir}";

        if (false === is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        @chown($directory, $this->fileUser);
        @chgrp($directory, $this->fileGroup);
    }
}
