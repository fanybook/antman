<?php

namespace Antman\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class JobStopCommand extends Command
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
            ->setName('job:stop')
            ->addArgument('job', InputArgument::REQUIRED, 'the job\'s name that you want to stop')
            ->setDescription('Stop a running job.')
            ->setHelp('find/create a job in \'src/Jobs\'');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // 检查job名
        $job = null;

        $filenames = scandir(ANTMAN_PATH . '/src/Jobs');

        foreach ($filenames as $idx => $filename) {
            if (substr($filename, 0, 1) == '.') {
                continue;
            }

            try {
                $job_class = '\Antman\Jobs\\' . strstr($filename, '.', true);
                $job = new $job_class;
                if ($job->name == $input->getArgument('job')) {
                    break;
                }
            } catch(\Exception $e) {
                $console_output = sprintf("错误：%s", $e->getMessage());
                $output->writeln(utg($console_output));
            }

            $job = null;
        }

        // 挖掘作业存在，则启动引擎
        if ($job) {
            $this->rc->set('ant:'.$job->name.':status', 'stop');
            $console_output = sprintf("成功：%s作业已被叫停，请等待所有worker完成手头工作。", $job->name);
            $output->writeln(utg($console_output));

        // 否则提示错误
        } else {
            $console_output = sprintf("错误：%s作业不存在，请检查后重试！", $input->getArgument('job'));
            $output->writeln(utg($console_output));
        }
    }
}
