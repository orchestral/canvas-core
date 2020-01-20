<?php

namespace Orchestra\Canvas\Core\Presets;

use InvalidArgumentException;

class Package extends Preset
{
    /**
     * Preset name.
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
        return \sprintf(
            '%s/%s',
            $this->basePath(),
            $this->config('paths.src', 'src')
        );
    }

    /**
     * Preset namespace.
     */
    public function rootNamespace(): string
    {
        $namespace = \trim($this->config['namespace'] ?? '');

        if (empty($namespace)) {
            throw new InvalidArgumentException("Please configure namespace configuration under 'canvas.yaml'");
        }

        return $namespace;
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
}
