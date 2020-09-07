<?php

/**
 * @var intec_constructorlite $this
 */

use intec\core\helpers\FileHelper;
use intec\core\io\Path;

$directory = Path::from($this->GetInstallDirectory());

$directoryFrom = $directory->add('admin');
$directoryTo = Path::from('@bitrix/admin');
$entries = FileHelper::getDirectoryEntries($directoryFrom->value, false);

foreach ($entries as $entry) {
    $pathFrom = $directoryFrom->add($entry);
    $pathTo = $directoryTo->add($entry);

    if (!FileHelper::isFile($pathTo->value))
        copy($pathFrom->value, $pathTo->value);
}

?>