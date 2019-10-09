<?php
return array(
    'development' => array(
        'adapter' => 'Orm',
        'filepath' => DVELUM_ROOT . '/data/filestorage',
        'object' => 'filestorage',
        'check_orm_structure' => false,
        'user_id' => 0, //default user ID
        'upload_prefix' => '',
        'rename' => true,
        'uploader' => '\\Dvelum\\App\\Upload\\Uploader',
        'mkdir_mode' => 0755,
        'uploader_config' => array(
            'file' => array(
                'title' => 'Files',
                'extensions' => array(
                    '.doc',
                    '.docx',
                    '.pdf',
                    '.rar',
                    '.zip',
                    '.odt',
                    '.txt',
                    '.xls',
                    '.xlsx',
                    '.ods',
                    '.csv',
                    '.gif',
                    '.png',
                    '.jpg',
                    '.jpeg',
                    '.eml'
                )
            )
        ),
        'download' => array(
            'type' => 'native', // native / apache / nginx
            'redirect_path' => ''
        )
    ),
    'production' => array(
        'adapter' => 'Orm',
        'filepath' => DVELUM_ROOT . '/data/filestorage',
        'object' => 'filestorage',
        'check_orm_structure' => false,
        'user_id' => 0, //default user ID
        'upload_prefix' => '',
        'rename' => true,
        'uploader' => '\\Dvelum\\App\\Upload\\Uploader',
        'mkdir_mode' => 0755,
        'uploader_config' => array(
            'file' => array(
                'title' => 'Files',
                'extensions' => array(
                    '.doc',
                    '.docx',
                    '.pdf',
                    '.rar',
                    '.zip',
                    '.odt',
                    '.txt',
                    '.xls',
                    '.xlsx',
                    '.ods',
                    '.csv',
                    '.gif',
                    '.png',
                    '.jpg',
                    '.jpeg',
                    '.eml'
                )
            )
        ),
        'download' => array(
            'type' => 'native', // native / apache / nginx
            'redirect_path' => ''
        )
    ),
);