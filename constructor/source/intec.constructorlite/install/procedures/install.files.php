<?php

/**
 * @var intec_constructorlite $this
 */

use intec\constructor\structure\block\Elements;
use intec\constructor\structure\Widgets;
use intec\core\helpers\FileHelper;
use intec\core\io\Path;

$module = 'intec.constructor';
$directory = Path::from($this->GetInstallDirectory());

$directoryFrom = $directory->add('components');
$directoryTo = Path::from('@bitrix/components/' . $module);

FileHelper::removeDirectory($directoryTo->value);

if (!FileHelper::isDirectory($directoryTo->value))
    FileHelper::copyDirectory(
        $directoryFrom->value,
        $directoryTo->value
    );

$directoryFrom = $directory->add('resources');
$directoryTo = Path::from('@bitrix/resources/' . $module);

FileHelper::removeDirectory($directoryTo->value);

if (!FileHelper::isDirectory($directoryTo->value))
    FileHelper::copyDirectory(
        $directoryFrom->value,
        $directoryTo->value
    );

$directoryFrom = $directory->add('elements');
$directoryTo = null;
$entries = FileHelper::getDirectoryEntries($directoryFrom->value, false);

foreach ($entries as $entry)
    Elements::install(
        $directoryFrom->add($entry)->value,
        $module,
        $entry,
        true
    );

$directoryFrom = $directory->add('widgets');
$directoryTo = null;
$entries = FileHelper::getDirectoryEntries($directoryFrom->value, false);

foreach ($entries as $entry)
    Widgets::install(
        $directoryFrom->add($entry)->value,
        $module,
        $entry,
        true
    );

?>