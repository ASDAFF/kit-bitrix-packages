<?php

/**
 * @var intec_constructorlite $this
 */

use intec\core\helpers\FileHelper;
use intec\core\io\Path;

$directory = Path::from($this->GetInstallDirectory());

$directoryFrom = $directory->add('admin');
$directoryWhere = Path::from('@bitrix/admin');
$entries = FileHelper::getDirectoryEntries($directoryFrom->value, false);

foreach ($entries as $entry) {
    $path = $directoryWhere->add($entry);

    if (FileHelper::isFile($path->value))
        unlink($path->value);
}

?>