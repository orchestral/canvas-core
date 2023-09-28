<?php

namespace Orchestra\Canvas\Core\Concerns;

use Illuminate\Console\Concerns\CreatesUsingGeneratorPreset;

trait MigrationGenerator
{
    use CreatesUsingGeneratorPreset;

    /**
     * Create a base migration file for the table.
     */
    protected function createBaseMigrationUsingCanvas(string $table): string
    {
        return $this->laravel['migration.creator']->create(
            "create_{$table}_table", $this->generatorPreset()->migrationPath()
        );
    }
}
