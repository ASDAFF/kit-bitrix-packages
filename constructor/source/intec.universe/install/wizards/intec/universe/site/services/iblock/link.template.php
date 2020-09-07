<? include('.begin.php') ?>
<?

use intec\core\base\Collection;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\constructor\Module as Constructor;
use intec\constructor\models\Build;
use intec\constructor\models\build\Area;
use intec\constructor\models\build\Template;
use intec\constructor\models\build\template\Component;
use intec\constructor\models\build\template\Container;
use intec\constructor\models\build\template\Widget;

/**
 * @var Collection $data
 * @var CWizardBase $wizard
 * @var Closure($code, $type, $file, $fields = []) $import
 * @var CWizardStep $this
 */

$templateId = WIZARD_TEMPLATE_ID.'_'.WIZARD_SITE_ID;
$macros = $data->get('macros');
$build = null;
$buildMacrosReplace = function ($properties) use (&$buildMacrosReplace, &$macros) {
    $result = [];

    foreach ($properties as $key => $value) {
        if (Type::isArray($value)) {
            $result[$key] = $buildMacrosReplace($value);
        } else {
            $result[$key] = StringHelper::replaceMacros($value, $macros);
        }
    }

    return $result;
};

if (!Constructor::isLite()) {
    /** @var Build $build */
    $build = Build::find()->where([
        'code' => $templateId
    ])->one();
}

if (!empty($build)) {
    /** @var Area[] $buildAreas */
    $buildAreas = $build->getAreas(true);
    $buildAreasId = [];

    foreach ($buildAreas as $buildArea)
        $buildAreasId[] = $buildArea->id;

    /** @var Template[] $buildTemplates */
    $buildTemplates = $build->getTemplates(true);
    $buildTemplatesId = [];

    foreach ($buildTemplates as $buildTemplate)
        $buildTemplatesId[] = $buildTemplate->id;

    if (!empty($buildTemplatesId) || !empty($buildAreasId)) {
        $conditions = ['or'];

        if (!empty($buildAreasId))
            $conditions[] = ['areaId' => $buildAreasId];

        if (!empty($buildTemplatesId))
            $conditions[] = ['templateId' => $buildTemplatesId];

        /** @var Container[] $buildContainers */
        $buildContainers = Container::find()->where($conditions)->all();

        /** @var Component[] $buildComponents */
        $buildComponents = Component::find()->where($conditions)->all();

        /** @var Widget[] $buildWidgets */
        $buildWidgets = Widget::find()->where($conditions)->all();

        foreach ($buildContainers as $buildContainer) {
            $buildContainer->script = StringHelper::replaceMacros(
                $buildContainer->script,
                $macros
            );

            $buildContainer->save();
        }

        foreach ($buildComponents as $buildComponent) {
            $properties = $buildComponent->properties;
            $properties = $buildMacrosReplace($properties);
            $buildComponent->properties = $properties;
            $buildComponent->save();
        }

        foreach ($buildWidgets as $buildWidget) {
            $properties = $buildWidget->properties;
            $properties = $buildMacrosReplace($properties);
            $buildWidget->properties = $properties;
            $buildWidget->save();
        }

        unset($buildWidget);
        unset($buildWidgets);
        unset($buildComponent);
        unset($buildComponents);
        unset($buildContainer);
        unset($buildContainers);
        unset($conditions);
    }
}

/** CUSTOM START */
/** CUSTOM END */

?>
<? include('.end.php') ?>