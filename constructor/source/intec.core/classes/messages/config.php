<?php

return [
    'sourcePath' => __DIR__ . '/..',
    'messagePath' => __DIR__,
    'languages' => ['ru'],
    'translator' => 'intec\Core::t',
    'sort' => false,
    'overwrite' => true,
    'removeUnused' => false,
    'markUnused' => true,
    'except' => [
        '.svn',
        '.git',
        '.gitignore',
        '.gitkeep',
        '.hgignore',
        '.hgkeep',
        '/messages',
    ],
    'only' => ['*.php'],
    'format' => 'php'
];
