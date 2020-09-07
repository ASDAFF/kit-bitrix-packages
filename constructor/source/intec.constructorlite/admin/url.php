<?php

if (!isset($bIsMenu))
    IntecConstructorLite::Initialize();

$sUrlRoot = '/bitrix/admin';
$arUrlTemplates = array(
    'blocks.categories' => $sUrlRoot.'/constructorlite_blocks_categories.php?lang='.LANGUAGE_ID,
    'blocks.categories.add' => $sUrlRoot.'/constructorlite_blocks_categories_edit.php?lang='.LANGUAGE_ID,
    'blocks.categories.edit' => $sUrlRoot.'/constructorlite_blocks_categories_edit.php?category=#category#&lang='.LANGUAGE_ID,
    'blocks.categories.export' => $sUrlRoot.'/constructorlite_blocks_categories_export.php?category=#category#&lang='.LANGUAGE_ID,
    'blocks.categories.import' => $sUrlRoot.'/constructorlite_blocks_categories_import.php?lang='.LANGUAGE_ID,
    'blocks.categories.export.all' => $sUrlRoot.'/constructorlite_blocks_categories_export_all.php?lang='.LANGUAGE_ID,
    'blocks.categories.import.all' => $sUrlRoot.'/constructorlite_blocks_categories_import_all.php?lang='.LANGUAGE_ID,
    'blocks.editor' => $sUrlRoot.'/constructorlite_blocks_editor.php?lang='.LANGUAGE_ID,
    'blocks.templates' => $sUrlRoot.'/constructorlite_blocks_templates.php?lang='.LANGUAGE_ID,
    'blocks.templates.add' => $sUrlRoot.'/constructorlite_blocks_templates_edit.php?lang='.LANGUAGE_ID,
    'blocks.templates.edit' => $sUrlRoot.'/constructorlite_blocks_templates_edit.php?template=#template#&lang='.LANGUAGE_ID,
    'blocks.templates.editor' => $sUrlRoot.'/constructorlite_blocks_editor.php?template=#template#&lang='.LANGUAGE_ID,
    'blocks.templates.copy' => $sUrlRoot.'/constructorlite_blocks_templates_copy.php?template=#template#&lang='.LANGUAGE_ID,
    'blocks.templates.export' => $sUrlRoot.'/constructorlite_blocks_templates_export.php?template=#template#&lang='.LANGUAGE_ID,
    'blocks.templates.import' => $sUrlRoot.'/constructorlite_blocks_templates_import.php?lang='.LANGUAGE_ID,
    'blocks.templates.export.all' => $sUrlRoot.'/constructorlite_blocks_templates_export_all.php?lang='.LANGUAGE_ID,
    'blocks.templates.import.all' => $sUrlRoot.'/constructorlite_blocks_templates_import_all.php?lang='.LANGUAGE_ID,
    'fonts' => $sUrlRoot.'/constructorlite_fonts.php?lang='.LANGUAGE_ID,
    'fonts.add' => $sUrlRoot.'/constructorlite_fonts_edit.php?lang='.LANGUAGE_ID,
    'fonts.edit' => $sUrlRoot.'/constructorlite_fonts_edit.php?font=#font#&lang='.LANGUAGE_ID
);

unset($sUrlRoot);