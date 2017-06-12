<?php

namespace Antman\Jobs;

class Get1688Job
{
    public $name = 'get1688';
    public $allowed_domains = ['1688.com'];
    public $start_urls = [
        'https://mizi512.1688.com/page/offerlist.htm',
        'https://detail.1688.com/offer/536452856256.html',
        '1-73' => 'https://detail.1688.com/offer/536452856256.html?page={$page}',
    ];

    public $config = [
        'depth' => 8,       // 爬行深度
        'certificate.cacert' => '', // SSL证书存放的位置
        'robotstxt_obey' => false,   // 爬行谁会在乎robots.txt
        'download_delay' => 3,      // 下载间隔，主要是防反爬
        'master_middlewares' => [
            'class_name' => 301,    // 自定义中间件进行加塞
        ],
        // 某些url交给parse2，parse3（例如：详细页交给parse_detail）
        // '正则' => 'function'
    ];

    public $rules = [
        // url 默认交给parse
        // 某些url交给parse2，parse3（例如：详细页交给parse_detail）
        // '正则' => 'function:parse_detail'
        // '正则' => 'follow:false'
        // 或者 没列出的正则 不爬
    ];

    public function parse($response)
    {
        // 解析html 成为想要的数组
        // 打印到控制台，或者保存到数据库
        // https://github.com/FluentDOM/FluentDOM 通过这个解析html
    }
}