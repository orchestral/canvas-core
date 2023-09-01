<?php

namespace Orchestra\Canvas\Core\Commands;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Contracts\Console\PromptsForMissingInput;
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
abstract class Generator extends Command implements GeneratesCodeListener, PromptsForMissingInput
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
     * Reserved names that cannot be used for generation.
     *
     * @var array<int, string>
     */
    protected array $reservedNames = [
        '__halt_compiler',
        'abstract',
        'and',
        'array',
        'as',
        'break',
        'callable',
        'case',
        'catch',
        'class',
        'clone',
        'const',
        'continue',
        'declare',
        'default',
        'die',
        'do',
        'echo',
        'else',
        'elseif',
        'empty',
        'enddeclare',
        'endfor',
        'endforeach',
        'endif',
        'endswitch',
        'endwhile',
        'enum',
        'eval',
        'exit',
        'extends',
        'false',
        'final',
        'finally',
        'fn',
        'for',
        'foreach',
        'function',
        'global',
        'goto',
        'if',
        'implements',
        'include',
        'include_once',
        'instanceof',
        'insteadof',
        'interface',
        'isset',
        'list',
        'match',
        'namespace',
        'new',
        'or',
        'print',
        'private',
        'protected',
        'public',
        'readonly',
        'require',
        'require_once',
        'return',
        'self',
        'static',
        'switch',
        'throw',
        'trait',
        'true',
        'try',
        'unset',
        'use',
        'var',
        'while',
        'xor',
        'yield',
        '__CLASS__',
        '__DIR__',
        '__FILE__',
        '__FUNCTION__',
        '__LINE__',
        '__METHOD__',
        '__NAMESPACE__',
        '__TRAIT__',
    ];

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
        // First we need to ensure that the given name is not a reserved word within the PHP
        // language and that the class name will actually be valid. If it is not valid we
        // can error now and prevent from polluting the filesystem using invalid files.
        if ($this->isReservedName($name = $this->generatorName())) {
            $this->components->error('The name "'.$name.'" is reserved by PHP.');

            return Command::FAILURE;
        }

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

    /**
     * Checks whether the given name is reserved.
     *
     * @param  string  $name
     * @return bool
     */
    protected function isReservedName($name)
    {
        $name = strtolower($name);

        return in_array($name, $this->reservedNames);
    }
}
