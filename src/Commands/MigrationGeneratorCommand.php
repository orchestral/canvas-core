<?php

namespace Orchestra\Canvas\Core\Commands;

use Illuminate\Filesystem\Filesystem;
use Orchestra\Canvas\Core\Concerns\MigrationGenerator;

/**
 * @property string|null $name
 * @property string|null $description
 */
abstract class MigrationGeneratorCommand extends \Illuminate\Console\MigrationGeneratorCommand
{
    use MigrationGenerator;

    /** {@inheritDoc} */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addGeneratorPresetOptions();
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function createBaseMigration($table)
    {
        return $this->createBaseMigrationUsingCanvas($table);
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function migrationExists($table)
    {
        return $this->migrationExistsUsingCanvas($table);
    }
}
