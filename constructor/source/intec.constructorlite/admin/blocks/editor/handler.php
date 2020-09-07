<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php
use intec\core\handling\Handler;
use intec\core\web\Request;

/**
 * @var string $action
 * @var Request $request
 */

$action = $request->post('action');

if (empty($action))
    return;
$directory = __DIR__.DIRECTORY_SEPARATOR.'handler';
$handler = new Handler(
    $directory,
    'intec\constructor\handlers'
);

$response = $handler->handle($action);