<?php

namespace Antman\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

//use Symfony\Component\Process\Process;
//use Symfony\Component\Process\Exception\ProcessFailedException;

class JobStartCommand extends Command
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
            ->setName('job:start')
            ->addArgument('job', InputArgument::REQUIRED, 'the job\'s name what you want to dig')
            ->setDescription('Let ant execute a dig\'s job.')
            ->setHelp('find/create a job in \'src/Jobs\'');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $console_output = sprintf("挖掘作业：开始 %s", date('Y-m-d H:i:s', intval(ANTMAN_START)));
        $output->writeln(utg($console_output));

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
                $console_output = sprintf("挖掘作业：错误 %s", $e->getMessage());
                $output->writeln(utg($console_output));
            }

            $job = null;
        }

        // 挖掘作业存在，则启动引擎
        if ($job) {
            $master = new \Antman\Master\Master();
            $master->start($job, $console = [
                'output' => $output,
                'rc' => $this->rc,
            ]);

        // 否则提示错误
        } else {
            $console_output = sprintf("挖掘作业：错误 %s作业不存在，请检查后重试！", $input->getArgument('job'));
            $output->writeln(utg($console_output));
        }

        $console_output = sprintf("挖掘作业：结束 %s", date('Y-m-d H:i:s'));
        $output->writeln(utg($console_output));

//        $process = [];
//        for ($i = 0; $i < 10; $i++) {
//            $process[$i] = new Process('ls -l |iconv -f utf-8 -t gbk');
//            $process[$i]->start();
//
//            // executes after the command finishes
//            $self = $process[$i];
//            $process[$i]->wait(function() use($self) {
//                sleep(3);
//                echo $self->getOutput();
//            });
//        }

//        $this->rc->hmset('user:0', [
//            'name' => 'fanybook',
//            'email' => 'fanybook@126.com',
//            'fav' => 'football',      //['football', 'muisc']
//        ]);
//
//        $this->rc->hmset('user:1', [
//            'name' => 'fanybook',
//            'email' => 'fanybook@126.com',
//            'fav' => 'muisc',      //['football', 'muisc']
//        ]);
//        var_dump( $this->rc->hgetall('user:0') );
//        var_dump( $this->rc->keys('*') );
//        $keys = $this->rc->keys('*');
//        foreach ($keys as $idx) {
//            $this->rc->del($idx);
//        }
//var_dump( $this->rc->keys('*') );
//        'ant:job:156(worker_id).status' = 'active, over';     string
//        'ant:job:156(worker_id).log' = 'active, over';     string
////        'ant:job:url_queue' = 'active, over'; list
////        'ant:job:url_swap' = 'active, over'; list
////        'ant:job:log' = 'active, over';     string
////        'ant:job:workers' = 'active, over'; list
    }
}
