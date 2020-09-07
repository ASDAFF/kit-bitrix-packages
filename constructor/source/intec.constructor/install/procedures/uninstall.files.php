<?php

/**
 * @var intec_constructor $this
 */

use intec\constructor\structure\block\Elements;
use intec\constructor\structure\Widgets;
use intec\core\helpers\FileHelper;
use intec\core\io\Path;

$module = $this->MODULE_ID;
$directory = Path::from($this->GetInstallDirectory());

$directoryFrom = $directory->add('admin');
$directoryWhere = Path::from('@bitrix/admin');
$entries = FileHelper::getDirectoryEntries($directoryFrom->value, false);

foreach ($entries as $entry) {
    $path = $directoryWhere->add($entry);

    if (FileHelper::isFile($path->value))
        unlink($path->value);
}

FileHelper::removeDirectory(Path::from('@bitrix/components/' . $module)->value);
FileHelper::removeDirectory(Path::from('@bitrix/resources/' . $module)->value);

$directoryFrom = $directory->add('elements');
$directoryWhere = null;
$entries = FileHelper::getDirectoryEntries($directoryFrom->value, false);

foreach ($entries as $entry)
    Elements::uninstall(
        $module,
        $entry
    );

$directoryFrom = $directory->add('widgets');
$directoryWhere = null;
$entries = FileHelper::getDirectoryEntries($directoryFrom->value, false);

foreach ($entries as $entry)
    Widgets::uninstall(
        $module,
        $entry
    );

?>