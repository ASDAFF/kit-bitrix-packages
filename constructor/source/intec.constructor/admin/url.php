<?php

if (!isset($bIsMenu))
        IntecConstructor::Initialize();

$sUrlRoot = '/bitrix/admin';
$arUrlTemplates = array(
    'builds' => $sUrlRoot.'/constructor_builds.php?lang='.LANGUAGE_ID,
    'builds.add' => $sUrlRoot.'/constructor_builds_edit.php?lang='.LANGUAGE_ID,
    'builds.edit' => $sUrlRoot.'/constructor_builds_edit.php?build=#build#&lang='.LANGUAGE_ID,
    'builds.areas' => $sUrlRoot.'/constructor_builds_areas.php?build=#build#&lang='.LANGUAGE_ID,
    'builds.areas.add' => $sUrlRoot.'/constructor_builds_areas_edit.php?build=#build#&lang='.LANGUAGE_ID,
    'builds.areas.edit' => $sUrlRoot.'/constructor_builds_areas_edit.php?build=#build#&area=#area#&lang='.LANGUAGE_ID,
    'builds.properties' => $sUrlRoot.'/constructor_builds_properties.php?build=#build#&lang='.LANGUAGE_ID,
    'builds.properties.add' => $sUrlRoot.'/constructor_builds_properties_edit.php?build=#build#&lang='.LANGUAGE_ID,
    'builds.properties.edit' => $sUrlRoot.'/constructor_builds_properties_edit.php?build=#build#&property=#property#&lang='.LANGUAGE_ID,
    'builds.properties.export' => $sUrlRoot.'/constructor_builds_properties_export.php?build=#build#&lang='.LANGUAGE_ID,
    'builds.properties.import' => $sUrlRoot.'/constructor_builds_properties_import.php?build=#build#&lang='.LANGUAGE_ID,
    'builds.themes' => $sUrlRoot.'/constructor_builds_themes.php?build=#build#&lang='.LANGUAGE_ID,
    'builds.themes.add' => $sUrlRoot.'/constructor_builds_themes_edit.php?build=#build#&lang='.LANGUAGE_ID,
    'builds.themes.edit' => $sUrlRoot.'/constructor_builds_themes_edit.php?build=#build#&theme=#theme#&lang='.LANGUAGE_ID,
    'builds.export' => $sUrlRoot.'/constructor_builds_export.php?build=#build#&lang='.LANGUAGE_ID,
    'builds.import' => $sUrlRoot.'/constructor_builds_import.php?lang='.LANGUAGE_ID,
    'builds.templates' => $sUrlRoot.'/constructor_builds_templates.php?build=#build#&lang='.LANGUAGE_ID,
    'builds.templates.add' => $sUrlRoot.'/constructor_builds_templates_edit.php?build=#build#&lang='.LANGUAGE_ID,
    'builds.templates.edit' => $sUrlRoot.'/constructor_builds_templates_edit.php?build=#build#&template=#template#&lang='.LANGUAGE_ID,
    'builds.templates.editor' => $sUrlRoot.'/constructor_builds_templates_editor.php?build=#build#&template=#template#&lang='.LANGUAGE_ID,
    'builds.templates.copy' => $sUrlRoot.'/constructor_builds_templates_copy.php?build=#build#&template=#template#&lang='.LANGUAGE_ID,
    'builds.templates.blocks.editor' => $sUrlRoot.'/constructor_blocks_editor.php?block=#block#&lang='.LANGUAGE_ID,
    'blocks.categories' => $sUrlRoot.'/constructor_blocks_categories.php?lang='.LANGUAGE_ID,
    'blocks.categories.add' => $sUrlRoot.'/constructor_blocks_categories_edit.php?lang='.LANGUAGE_ID,
    'blocks.categories.edit' => $sUrlRoot.'/constructor_blocks_categories_edit.php?category=#category#&lang='.LANGUAGE_ID,
    'blocks.categories.export' => $sUrlRoot.'/constructor_blocks_categories_export.php?category=#category#&lang='.LANGUAGE_ID,
    'blocks.categories.import' => $sUrlRoot.'/constructor_blocks_categories_import.php?lang='.LANGUAGE_ID,
    'blocks.categories.export.all' => $sUrlRoot.'/constructor_blocks_categories_export_all.php?lang='.LANGUAGE_ID,
    'blocks.categories.import.all' => $sUrlRoot.'/constructor_blocks_categories_import_all.php?lang='.LANGUAGE_ID,
    'blocks.editor' => $sUrlRoot.'/constructor_blocks_editor.php?lang='.LANGUAGE_ID,
    'blocks.templates' => $sUrlRoot.'/constructor_blocks_templates.php?lang='.LANGUAGE_ID,
    'blocks.templates.add' => $sUrlRoot.'/constructor_blocks_templates_edit.php?lang='.LANGUAGE_ID,
    'blocks.templates.edit' => $sUrlRoot.'/constructor_blocks_templates_edit.php?template=#template#&lang='.LANGUAGE_ID,
    'blocks.templates.editor' => $sUrlRoot.'/constructor_blocks_editor.php?template=#template#&lang='.LANGUAGE_ID,
    'blocks.templates.copy' => $sUrlRoot.'/constructor_blocks_templates_copy.php?template=#template#&lang='.LANGUAGE_ID,
    'blocks.templates.export' => $sUrlRoot.'/constructor_blocks_templates_export.php?template=#template#&lang='.LANGUAGE_ID,
    'blocks.templates.import' => $sUrlRoot.'/constructor_blocks_templates_import.php?lang='.LANGUAGE_ID,
    'blocks.templates.export.all' => $sUrlRoot.'/constructor_blocks_templates_export_all.php?lang='.LANGUAGE_ID,
    'blocks.templates.import.all' => $sUrlRoot.'/constructor_blocks_templates_import_all.php?lang='.LANGUAGE_ID,
    'fonts' => $sUrlRoot.'/constructor_fonts.php?lang='.LANGUAGE_ID,
    'fonts.add' => $sUrlRoot.'/constructor_fonts_edit.php?lang='.LANGUAGE_ID,
    'fonts.edit' => $sUrlRoot.'/constructor_fonts_edit.php?font=#font#&lang='.LANGUAGE_ID
);

unset($sUrlRoot);