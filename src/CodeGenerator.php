<?php

namespace Orchestra\Canvas\Core;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Symfony\Component\Console\Command\Command;

trait CodeGenerator
{
    /**
     * Generate code.
     *
     * @return int
     */
    public function generateCode(bool $force = false)
    {
        $name = $this->getInputName();
        $className = $this->qualifyClass($name);
        $path = $this->getPath($this->qualifyClass($name));

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

        $this->files->put($path, $this->sortImports($this->buildClass($className)));

        return tap($this->codeHasBeenGenerated($className), function ($exitCode) use ($className, $path) {
            if (\in_array(CreatesMatchingTest::class, class_uses_recursive($this))) {
                $this->handleTestCreationUsingCanvas($path);
            }

            $this->afterCodeHasBeenGenerated($className, $path);
        });
    }

    /**
     * Code already exists.
     *
     * @return int
     */
    public function codeAlreadyExists(string $className)
    {
        $this->components->error(sprintf('%s [%s] already exists!', $this->type, $className));

        return Command::FAILURE;
    }

    /**
     * Code successfully generated.
     *
     * @return int
     */
    public function codeHasBeenGenerated(string $className)
    {
        $this->components->info(sprintf('%s [%s] created successfully.', $this->type, $className));

        return Command::SUCCESS;
    }

    /**
     * Run after code successfully generated.
     *
     * @return void
     */
    public function afterCodeHasBeenGenerated(string $className, string $path)
    {
        if (\in_array(CreatesMatchingTest::class, class_uses_recursive($this))) {
            $this->handleTestCreationUsingCanvas($path);
        }
    }
}
