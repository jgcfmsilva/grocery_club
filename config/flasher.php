<?php

return [
    'default' => 'theme.sapphire',

    'themes' => [
        'sapphire' => [
            'scripts' => [
                '/vendor/flasher/flasher.min.css',
                '/vendor/flasher/themes/theme.sapphire.min.js',
            ],
            'styles' => [
                '/vendor/flasher/themes/theme.sapphire.min.css',
            ],
            'options' => [
                'timeout' => 3000,
                'position' => 'bottom-right'
            ]
        ],
    ],
];
