<?php
define('EDITOR', true);

$constants = [];

if (isset($_REQUEST['constants']))
    $constants = $_REQUEST['constants'];

if (!is_array($constants))
    $constants = [];

$constants = array_merge([
    'site' => null,
    'directory' => null,
    'template' => null
], $constants);

if (!empty($constants['site']))
    define('SITE_ID', $constants['site']);

if (!empty($constants['directory']))
    define('SITE_DIR', $constants['directory']);

if (!empty($constants['template']))
    define('SITE_TEMPLATE_ID', $constants['template']);

unset($constants);