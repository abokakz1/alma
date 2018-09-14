<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. A "local" driver, as well as a variety of cloud
    | based drivers are available for your choosing. Just store away!
    |
    | Supported: "local", "ftp", "s3", "rackspace"
    |
    */

    'default' => 'local',

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => 's3',

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => 'your-key',
            'secret' => 'your-secret',
            'region' => 'your-region',
            'bucket' => 'your-bucket',
        ],
        'news_photo' => [
            'driver' => 'local',
            'root'   => public_path() .'/news_photo',
        ],
        'blog_photo' => [
            'driver' => 'local',
            'root'   => public_path() .'/blog_photo',
        ],
        'event_photo' => [
            'driver' => 'local',
            'root'   => public_path() .'/event_photo',
        ],
        'programm_photo' => [
            'driver' => 'local',
            'root'   => public_path() .'/programm_photo',
        ],
        'tv_programm_photo' => [
            'driver' => 'local',
            'root'   => public_path() .'/tv_programm_photo',
        ],
        'category_photo' => [
            'driver' => 'local',
            'root'   => public_path() .'/category_photo',
        ],
        'video_archive_photo' => [
            'driver' => 'local',
            'root'   => public_path() .'/video_archive_photo',
        ],
        'user_photo' => [
            'driver' => 'local',
            'root'   => public_path() .'/user_photo',
        ],
        'response_files' => [
            'driver' => 'local',
            'root'   => public_path() .'/response_files',
        ],
        'adv' => [
            'driver' => 'local',
            'root'   => public_path() .'/adv',
        ],
        'archive_news_kz' => [
            'driver' => 'local',
            'root'   => public_path() .'/archive_news_kz',
        ],
        'archive_news_ru' => [
            'driver' => 'local',
            'root'   => public_path() .'/archive_news_ru',
        ],

    ],

];
