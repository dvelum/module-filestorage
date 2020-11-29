<?php
return [
    'id' => 'dvelum-module-filestorage',
    'version' => '3.0.1',
    'author' => 'Kirill Yegorov',
    'name' => 'DVelum File Storage',
    'configs' => './configs',
    'locales' => './locales',
    'resources' =>'./resources',
    'vendor'=>'Dvelum',
    'autoloader'=> [
        './classes'
    ],
    'objects' =>[
        'filestorage'
    ],
    'post-install'=>'\\Dvelum\\FileStorage\\Installer'
];