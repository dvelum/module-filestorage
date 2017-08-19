<?php
return [
    'id' => 'dvelum_filestorage',
    'version' => '1.0.0',
    'author' => 'Kirill Egorov',
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
    'post-install'=>'Dvelum_Backend_Articles_Installer'
];