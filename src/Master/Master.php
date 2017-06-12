<?php

namespace Antman\Master;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Master
{
    protected $job_space = [];

    // 这些中间件是负责处理爬行前调度的

    public function __construct()
    {
        $this->job_space = [
            'request_queue' => [],
            'request_queue_lock' => false,
            'scheduler_temp' => [],
            'scheduler_trace' => [],
        ];

        $this->base_config = require(ANTMAN_PATH . '/src/Config/base_middlewares.php');
    }

    private function _validate()
    {
        // nothing
    }

    private function _mergeMiddleware()
    {
        if (!empty($this->job->config['master_middlewares'])) {
            $this->job->config['master_middlewares'] = array_merge(
                $this->base_config['master_middlewares'],
                $this->job->config['master_middlewares']
            );
            asort($this->job->config['master_middlewares']);
        }

        if (!empty($this->job->config['worker_middlewares'])) {
            $this->job->config['worker_middlewares'] = array_merge(
                $this->base_config['worker_middlewares'],
                $this->job->config['worker_middlewares']
            );
            asort($this->job->config['worker_middlewares']);
        }
    }

    private function _processStartUrls()
    {
        $new_urls = [];
        foreach ($this->job->start_urls as $idx => $item) {
            if (is_numeric($idx)) {
                $new_urls[] = $item;
                echo $item,"\n";
            } else {
                list($min, $max) = explode('-', $idx);
                for ($i = $min; $i <= $max; $i++) {
                    $new_urls[] = preg_replace('/({\$\w+})/', $i, $item);
                    echo preg_replace('/({\$\w+})/', $i, $item),"\n";
                }
            }
        }

        $this->job->start_urls = $new_urls;
    }

    private function _createRequest()
    {
        // nothing
    }

    public function start($job, $console)
    {
        $console['rc']->set('ant:'.$job->name.':status', 'working');

        $this->job = $job;
        $this->_validate();
        $this->_mergeMiddleware();
        $this->_processStartUrls();
        $this->_createRequest();

        $i = 0;
        do {
            $process = new Process('nohup php ant worker:wake '.$i);
            $process->disableOutput();
            $process->start();
            $console['output']->writeln($i .'-start'. microtime(true));

            $i++;
        } while ($console['rc']->get('ant:'.$job->name.':status') != 'stop');
//        var_dump($this->job->start_urls);

        // 如果 config 里设置了延迟，那么全部变成单线程
        // 否则变成多线程，一次10个，可以配置

        // $request = new Request();
        // $request->url = $this->job->url; // 多个循环，或通过规则生成多个入口url
        // Scheduler($request, $this->job);

    }

    public function push($request)
    {
        // push 给 Scheduler
    }

    public function stop($job_name = null)
    {
        // 去redis里，找到任务的url栈，或者改一下任务状态
        // 让采集热停止下来，把url栈备份一下
    }
}
