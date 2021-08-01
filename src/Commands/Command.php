<?php

namespace Orchestra\Canvas\Core\Commands;

use Illuminate\Console\Concerns\CallsCommands;
use Illuminate\Console\Concerns\HasParameters;
use Illuminate\Console\Concerns\InteractsWithIO;
use Illuminate\Console\OutputStyle;
use Orchestra\Canvas\Core\Presets\Preset;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends \Symfony\Component\Console\Command\Command
{
    use CallsCommands,
        HasParameters,
        InteractsWithIO;

    /**
     * Canvas preset.
     *
     * @var \Orchestra\Canvas\Core\Presets\Preset
     */
    protected $preset;

    /**
     * Construct a new generator command.
     */
    public function __construct(Preset $preset)
    {
        $this->preset = $preset;

        parent::__construct();

        $this->specifyParameters();
    }

    /**
     * Run the console command.
     *
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        $this->output = new OutputStyle($input, $output);

        return parent::run(
            $this->input = $input, $this->output
        );
    }

    /**
     * Resolve the console command instance for the given command.
     *
     * @param  \Symfony\Component\Console\Command\Command|string  $command
     *
     * @return \Symfony\Component\Console\Command\Command
     */
    protected function resolveCommand($command)
    {
        return $this->getApplication()->find($command);
    }
}
