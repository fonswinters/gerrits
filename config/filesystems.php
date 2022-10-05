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
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | https://laravel.com/docs/9.x/upgrade#flysystem-3
    | Before using the S3, FTP, or SFTP drivers, you will need to install the appropriate package via the Composer package manager:
    |
    | Amazon S3:    composer require -W league/flysystem-aws-s3-v3 "^3.0"
    | FTP:          composer require league/flysystem-ftp "^3.0"
    | SFTP:         composer require league/flysystem-sftp-v3 "^3.0"
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root'   => storage_path('app'),
            'throw' => true,
        ],

        'rackspace' => [
            'driver'    => 'rackspace',
            'username'  => 'your-username',
            'key'       => 'your-key',
            'container' => 'your-container',
            'endpoint'  => 'https://identity.api.rackspacecloud.com/v2.0/',
            'region'    => 'IAD',
            'url_type'  => 'publicURL',
        ],

    ],

];
