<?php

namespace Antman\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class JobListCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('job:list')
            ->setDescription('List the dig\'s jobs.')
            ->setHelp('find/create a job in \'src/Jobs\'');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filenames = scandir(ANTMAN_PATH . '/src/Jobs');

        foreach ($filenames as $idx => $filename) {
            if (substr($filename, 0, 1) == '.') {
                continue;
            }

            try {
                $job_class = '\Antman\Jobs\\' . strstr($filename, '.', true);
                $job = new $job_class;

                $output->writeln($job->name);
            } catch(\Exception $e) {
                $console_output = sprintf("错误：%s", $e->getMessage());
                $output->writeln(utg($console_output));
            }
        }
    }
}
