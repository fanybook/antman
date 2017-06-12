<?php

namespace Antman;

class Engine
{
    public $job_middlewares = [
        'job_middleware_01' => 1,
        'job_middleware_02' => 2,
        'job_middleware_03' => 3,
        'job_middleware_04' => 4,
        'job_middleware_05' => 5,
    ];

    public $scheduler_middlewares = [
        'scheduler_middleware_01' => 1,
        'scheduler_middleware_02' => 2,
        'scheduler_middleware_03' => 3,
        'scheduler_middleware_04' => 4,
        'scheduler_middleware_05' => 5,
    ];

    public $downloader_middlewares = [
        'downloader_middleware_01' => 1,
        'downloader_middleware_02' => 2,
        'downloader_middleware_03' => 3,
        'downloader_middleware_04' => 4,
        'downloader_middleware_05' => 5,
    ];

    public function start($job = null)
    {
        if (!$job) {
            die('爬行作业为空，请检查后再试！');
        }

        $this->job = $job;

        $this->_validate()
        // 把$job里的middlewares和引擎自带的进行合并 $this->_merge()
        // 在的话，把job交给scheduler(scheduler把job里的url变成一个个的request，放入队列，交给下载器)
        // 不在就报错
    }

    public function stop($job_name = null)
    {
        // 去redis里，找到任务的url栈，或者改一下任务状态
        // 让采集热停止下来，把url栈备份一下
    }

    private function _validate ()
    {
        // nothing
    }

    private function _mergeMiddleware ()
    {
        if (!empty($this->job->config['job_middlewares'])) {
            $this->job->config['job_middlewares'] = array_merge($this->job_middlewares, $this->job->config['job_middlewares']);
        }

        if (!empty($this->job->config['scheduler_middlewares'])) {
            $this->job->config['scheduler_middlewares'] = array_merge($this->scheduler_middlewares, $this->job->config['scheduler_middlewares']);
        }

        if (!empty($this->job->config['downloader_middlewares'])) {
            $this->job->config['downloader_middlewares'] = array_merge($this->downloader_middlewares, $this->job->config['downloader_middlewares']);
        }
    }
}