<?php

namespace Orchestra\Canvas\Core\Recipes;

use InvalidArgumentException;

class Package extends Recipe
{
    /**
     * Recipe name.
     */
    public function name(): string
    {
        return 'package';
    }

    /**
     * Get the path to the source directory.
     */
    public function sourcePath(): string
    {
        return sprintf(
            '%s/%s',
            $this->basePath(),
            $this->config('paths.src', 'src')
        );
    }

    /**
     * Root namespace.
     */
    public function rootNamespace(): string
    {
        $namespace = trim($this->config['namespace'] ?? '');

        if (empty($namespace)) {
            throw new InvalidArgumentException("Please configure namespace configuration under 'canvas.yaml'");
        }

        return $namespace;
    }

    /**
     * Command namespace.
     */
    public function commandNamespace(): string
    {
        return $this->config('console.namespace', $this->rootNamespace().'\Console');
    }

    /**
     * Model namespace.
     */
    public function modelNamespace(): string
    {
        return $this->config('model.namespace', $this->rootNamespace());
    }

    /**
     * Provider namespace.
     */
    public function providerNamespace(): string
    {
        return $this->config('provider.namespace', $this->rootNamespace());
    }

    /**
     * Testing namespace.
     */
    public function testingNamespace(): string
    {
        return $this->config('testing.namespace', $this->rootNamespace().'\Tests');
    }

    /**
     * Get custom stub path.
     */
    public function getCustomStubPath(): ?string
    {
        return null;
    }
}
