<?php

namespace Orchestra\Canvas\Core\Concerns;

use Illuminate\Console\Concerns\CreatesMatchingTest;

trait CodeGenerator
{
    use CreatesUsingGeneratorPreset;

    /**
     * Generate code.
     *
     * @return bool
     */
    public function generateCode()
    {
        $name = $this->getNameInput();
        $force = $this->hasOption('force') && $this->option('force') === true;

        $className = $this->qualifyClass($name);
        $path = $this->getPath($this->qualifyClass($name));

        // First we need to ensure that the given name is not a reserved word within the PHP
        // language and that the class name will actually be valid. If it is not valid we
        // can error now and prevent from polluting the filesystem using invalid files.
        if ($this->isReservedName($name)) {
            $this->components->error('The name "'.$name.'" is reserved by PHP.');

            return false;
        }

        // Next, We will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if (! $force && $this->alreadyExists($name)) {
            return $this->codeAlreadyExists($className);
        }

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        $this->files->put(
            $path, $this->sortImports($this->generatingCode($this->buildClass($className), $className))
        );

        if (\in_array(CreatesMatchingTest::class, class_uses_recursive($this))) {
            $this->handleTestCreationUsingCanvas($path);
        }

        return tap($this->codeHasBeenGenerated($className), function ($exitCode) use ($className, $path) {
            $this->afterCodeHasBeenGenerated($className, $path);
        });
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
    public function codeAlreadyExists(string $className): bool
    {
        $this->components->error(sprintf('%s [%s] already exists!', $this->type, $className));

        return false;
    }

    /**
     * Code successfully generated.
     */
    public function codeHasBeenGenerated(string $className): bool
    {
        $this->components->info(sprintf('%s [%s] created successfully.', $this->type, $className));

        return true;
    }

    /**
     * Run after code successfully generated.
     */
    public function afterCodeHasBeenGenerated(string $className, string $path): void
    {
        //
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->generatorPreset()->sourcePath().'/'.str_replace('\\', '/', $name).'.php';
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return $this->generatorPreset()->rootNamespace();
    }

    /**
     * Get the model for the default guard's user provider.
     *
     * @return string|null
     */
    protected function userProviderModel()
    {
        return $this->generatorPreset()->userProviderModel();
    }

    /**
     * Get the first view directory path from the application configuration.
     *
     * @param  string  $path
     * @return string
     */
    protected function viewPath($path = '')
    {
        $views = $this->generatorPreset()->viewPath();

        return $views.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}
