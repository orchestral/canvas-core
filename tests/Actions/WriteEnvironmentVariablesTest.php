<?php

namespace Orchestra\Canvas\Core\Tests\Actions;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Orchestra\Canvas\Core\Actions\WriteEnvironmentVariables;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;

use function Orchestra\Sidekick\Filesystem\join_paths;

class WriteEnvironmentVariablesTest extends TestCase
{
    /** {@inheritDoc} */
    #[\Override]
    protected function setUp(): void
    {
        $this->afterApplicationCreated(function () {
            copy(join_paths(__DIR__, 'stubs', '.env'), join_paths(__DIR__, 'tmp', '.env'));
        });

        $this->beforeApplicationDestroyed(function () {
            @unlink(join_paths(__DIR__, 'tmp', '.env'));
        });

        parent::setUp();
    }

    #[Test]
    public function it_can_write_to_environment_file()
    {
        $filesystem = new Filesystem;

        $action = new WriteEnvironmentVariables($filesystem, join_paths(__DIR__, 'tmp', '.env'));

        $action->handle([
            'APP_NAME' => 'Workbench',
            'APP_KEY' => 'Hello World',
            'APP_DEBUG' => true,
            'TELESCOPE_ENABLED' => false,
            'NOVA_DOMAIN' => null,
        ]);

        $this->assertSame(
            implode(PHP_EOL, [
                'APP_NAME=Laravel',
                'APP_KEY="Hello World"',
                'APP_DEBUG=(true)',
                '',
                'TELESCOPE_ENABLED=(false)',
                '',
                'NOVA_DOMAIN=(null)',
            ]),
            file_get_contents(join_paths(__DIR__, 'tmp', '.env'))
        );
    }

    #[Test]
    public function it_can_write_and_replace_existing_variable_to_environment_file()
    {
        $filesystem = new Filesystem;

        $action = new WriteEnvironmentVariables($filesystem, join_paths(__DIR__, 'tmp', '.env'));

        $action->handle(['APP_NAME' => 'Workbench', 'APP_KEY' => 'Hello World'], overwrite: true);

        $this->assertSame(
            implode(PHP_EOL, [
                'APP_NAME=Workbench',
                'APP_KEY="Hello World"',
            ]).PHP_EOL,
            $filesystem->get(join_paths(__DIR__, 'tmp', '.env'))
        );
    }

    #[Test]
    #[TestWith([false])]
    #[TestWith([null])]
    #[TestWith(['./invalid-env-file'])]
    public function it_throws_exception_when_env_file_is_not_available(mixed $filename)
    {
        $this->expectException(FileNotFoundException::class);
        $this->expectExceptionMessage(\is_string($filename) ? "The file [{$filename}] does not exist." : '');

        $filesystem = new Filesystem;

        $action = new WriteEnvironmentVariables($filesystem, $filename);

        $action->handle(['APP_NAME' => 'Laravel']);
    }
}
