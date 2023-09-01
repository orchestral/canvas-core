<?php

namespace Orchestra\Canvas\Core\Commands;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Orchestra\Canvas\Core\CodeGenerator;
use Orchestra\Canvas\Core\Contracts\GeneratesCodeListener;
use Orchestra\Canvas\Core\GeneratesCode;
use Orchestra\Canvas\Core\Presets\Preset;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @property string|null  $name
 * @property string|null  $description
 */
abstract class Generator extends Command implements GeneratesCodeListener
{
    use CodeGenerator;

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The type of class being generated.
     */
    protected string $type;

    /**
     * The type of file being generated.
     */
    protected string $fileType = 'class';

    /**
     * Generator processor.
     *
     * @var class-string<\Orchestra\Canvas\Core\GeneratesCode>
     */
    protected string $processor = GeneratesCode::class;

    /**
     * Construct a new generator command.
     */
    public function __construct(Preset $preset)
    {
        $this->files = $preset->filesystem();

        parent::__construct($preset);
    }

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->ignoreValidationErrors();

        $this->setName($this->getName())
            ->setDescription($this->getDescription())
            ->addArgument('name', InputArgument::REQUIRED, "The name of the {$this->fileType}");

        if (\in_array(CreatesMatchingTest::class, class_uses_recursive($this))) {
            /** @phpstan-ignore-next-line */
            $this->addTestOptions();
        }
    }

    /**
     * Execute the command.
     *
     * @return int 0 if everything went fine, or an exit code
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $force = $this->hasOption('force') && $this->option('force') === true;

        return $this->generateCode($force);
    }

    /**
     * Handle generating code.
     */
    public function generatingCode(string $stub, string $className): string
    {
        return $stub;
    }

    /**
     * Code already exists.
     */
    public function codeAlreadyExists(string $className): int
    {
        $this->components->error(sprintf('%s [%s] already exists!', $this->type, $className));

        return static::FAILURE;
    }

    /**
     * Code successfully generated.
     */
    public function codeHasBeenGenerated(string $className): int
    {
        $this->components->info(sprintf('%s [%s] created successfully.', $this->type, $className));

        return static::SUCCESS;
    }

    /**
     * Run after code successfully generated.
     */
    public function afterCodeHasBeenGenerated(string $className, string $path): void
    {
        if (\in_array(CreatesMatchingTest::class, class_uses_recursive($this))) {
            /** @phpstan-ignore-next-line */
            $this->handleTestCreation($path);
        }
    }

    /**
     * Get the published stub file for the generator.
     */
    public function getPublishedStubFileName(): ?string
    {
        return null;
    }

    /**
     * Get the desired class name from the input.
     */
    public function generatorName(): string
    {
        return transform($this->argument('name'), function ($name) {
            /** @var string $name */
            return trim($name);
        });
    }
}
