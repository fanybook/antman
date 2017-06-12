<?php

return [
    'master_middlewares' => [
        'HttpErrorMiddleware'       => 50,      // http错误
        'OffsiteMiddleware'         => 500,     // 该中间件过滤出所有主机名不在job属性 allowed_domains 的request
        'RefererMiddleware'         => 700,     // 生成，Request的referer字段
        'UrlLengthMiddleware'       => 800,     // url长度
        'DepthMiddleware'           => 900,     // 爬行深度
    ],

    'worker_middlewares' => [
        'RobotsTxtMiddleware'       => 100,     // robots.txt
        'HttpAuthMiddleware'        => 300,     // auth的应对
        'DownloadTimeoutMiddleware' => 350,     // 下载超时处理
        'DefaultHeadersMiddleware'  => 400,     // 默认的请求头
        'UserAgentMiddleware'       => 500,     // ua模拟
        'RetryMiddleware'           => 550,     // 重试处理，处理优先级放入堆栈
        'AjaxCrawlMiddleware'       => 560,     // Ajax的爬行
        'MetaRefreshMiddleware'     => 580,     // meta刷新的应对
        'HttpCompressionMiddleware' => 590,     // http压缩的应对
        'RedirectMiddleware'        => 600,     // 301的应对
        'CookiesMiddleware'         => 700,     // cookies的处理
        'HttpProxyMiddleware'       => 750,     // 代理的使用
        'DownloaderStats'           => 850,     // 下载状态
        'HttpCacheMiddleware'       => 900,     // http缓存
    ],
];