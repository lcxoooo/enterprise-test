<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Repository extends Command
{
    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'new:repository';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new criteria & transformer & repository';

    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {
        $this->call('make:transformer', [
            'name'    => $this->argument('name'),
            '--force' => $this->option('force'),
        ]);

        $this->call('make:criteria', [
            'name'    => $this->argument('name'),
            '--force' => $this->option('force'),
        ]);

        $this->call('make:repository', [
            'name'        => $this->argument('name'),
            '--fillable'  => $this->option('fillable'),
            '--rules'     => $this->option('rules'),
            '--validator' => $this->option('validator'),
            '--force'     => $this->option('force')
        ]);
    }

    /**
     * The array of command arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        return [
            [
                'name',
                InputArgument::REQUIRED,
                'The name of class being generated.',
                null
            ],
        ];
    }

    /**
     * The array of command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            [
                'fillable',
                null,
                InputOption::VALUE_OPTIONAL,
                'The fillable attributes.',
                null
            ],
            [
                'rules',
                null,
                InputOption::VALUE_OPTIONAL,
                'The rules of validation attributes.',
                null
            ],
            [
                'validator',
                null,
                InputOption::VALUE_OPTIONAL,
                'Adds validator reference to the repository.',
                null
            ],
            [
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force the creation if file already exists.',
                null
            ]
        ];
    }
}
