<?php

/*
 * This file is part of the WouterJEloquentBundle package.
 *
 * (c) 2014 Wouter de Jong
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WouterJ\EloquentBundle\Migrations;

use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Illuminate\Database\Migrations\Migrator as LaravelMigrator;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * The LaravelMigrator without the dependency on Laravel filesystem.
 *
 * @author Wouter J <wouter@wouterj.nl>
 */
class Migrator extends LaravelMigrator
{
    public function __construct(MigrationRepositoryInterface $repository, Resolver $resolver)
    {
        $this->repository = $repository;
        $this->resolver = $resolver;
    }

    public function getMigrationFiles($path)
    {
        if (0 === count((array) $path)) {
            return [];
        }

        $files = Finder::create()->name('*_*.php')->in($path)->sortByName();

        if (0 === count($files)) {
            return [];
        }

        $migrations = [];
        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            $migrations[str_replace('.php', '', $file->getBasename())] = $file->getRealPath();
        }

        return $migrations;
    }

    public function requireFiles(array $files)
    {
        foreach ($files as $file) {
            require_once $file;
        }
    }
}
