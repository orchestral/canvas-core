<?php

namespace Orchestra\Canvas\Core\Recipes;

class Laravel extends Recipe
{
    /**
     * Recipe name.
     */
    public function name(): string
    {
        return 'laravel';
    }

    /**
     * Get the path to the source directory.
     */
    public function sourcePath(): string
    {
        return implode('/', [$this->basePath(), $this->config('paths.src', 'app')]);
    }

    /**
     * Root namespace.
     */
    public function rootNamespace(): string
    {
        return $this->config['namespace'] ?? 'App';
    }

    /**
     * Command namespace.
     */
    public function commandNamespace(): string
    {
        return $this->config('console.namespace', $this->rootNamespace().'\Console\Commands');
    }

    /**
     * Model namespace.
     */
    public function modelNamespace(): string
    {
        return $this->config('model.namespace', $this->rootNamespace().'\Models');
    }

    /**
     * Provider namespace.
     */
    public function providerNamespace(): string
    {
        return $this->config('provider.namespace', $this->rootNamespace().'\Providers');
    }

    /**
     * Testing namespace.
     */
    public function testingNamespace(): string
    {
        return $this->config('testing.namespace', 'Tests');
    }

    /**
     * Get custom stub path.
     */
    public function getCustomStubPath(): ?string
    {
        return sprintf('%s/%s', $this->basePath(), 'stubs');
    }
}
