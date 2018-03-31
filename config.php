<?php
return [
    'id' => 'dvelum-module-filestorage',
    'version' => '1.0.2',
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