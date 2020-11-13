<?php

return [
    [
        'pattern' => '/chat',
        'method' => 'GET',
        'namespace' => 'A7Pro\Chat\Presentation\Controllers',
        'controller' => 'chat',
        'action' => 'getChatList'
    ],
    [
        'pattern' => '/chat/{receiver_id}',
        'method' => 'GET',
        'namespace' => 'A7Pro\Chat\Presentation\Controllers',
        'controller' => 'chat',
        'action' => 'getChatHistory'
    ],
    [
        'pattern' => '/chat/{receiver_id}',
        'method' => 'POST',
        'namespace' => 'A7Pro\Chat\Presentation\Controllers',
        'controller' => 'chat',
        'action' => 'sendChat'
    ],
];
