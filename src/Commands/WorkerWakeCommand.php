<?php

namespace Antman\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class WorkerWakeCommand extends Command
{
    protected $rc = null;

    public function __construct()
    {
        parent::__construct();
        $this->rc = new \Predis\Client();
    }

    protected function configure()
    {
        $this
            ->setName('worker:wake')
            ->addArgument('job', InputArgument::REQUIRED, 'the job\'s name what you want to worker bind')
            ->setDescription('Wake a worker bind job.')
            ->setHelp('find/create a job in \'src/Jobs\'');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $job = $input->getArgument('job');
        sleep($job);
        $process = new Process('echo ' .$job. ' > ' .$job. '.txt');
        $process->run();
    }
}
