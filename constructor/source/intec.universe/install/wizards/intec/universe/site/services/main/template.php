<? include(__DIR__.'/../.begin.php') ?>
<?

use Bitrix\Main\Localization\Loc;
use intec\core\base\Collection;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Encoding;
use intec\core\helpers\FileHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\core\io\Path;
use intec\constructor\Module as Constructor;
use intec\constructor\models\Build;
use intec\constructor\models\build\Area;
use intec\constructor\models\build\Template;
use intec\constructor\models\build\template\Block;
use intec\constructor\models\build\template\Component;
use intec\constructor\models\build\template\Container;
use intec\constructor\models\build\template\Widget;

/**
 * @var Collection $data
 * @var CWizardBase $wizard
 * @var CWizardStep $this
 */

Loc::loadMessages(__FILE__);

$templateId = WIZARD_TEMPLATE_ID.'_'.WIZARD_SITE_ID;
$templatePath = Path::from('@root/'.WizardServices::GetTemplatesPath(WIZARD_RELATIVE_PATH.'/site').'/'.WIZARD_TEMPLATE_ID);
$templatePathTo = Path::from('@root/'.BX_PERSONAL_ROOT.'/templates/'.$templateId);
$macros = $data->get('macros');

unset($macros['SITE_DIR']);

if (FileHelper::isDirectory($templatePath->getValue('/'))) {
    CopyDirFiles(
        $templatePath->getValue('/'),
        $templatePathTo->getValue('/'),
        $rewrite = true,
        $recursive = true,
        $remove = false
    );

    $template = CSiteTemplate::GetList([], [
        'ID' => $templateId
    ])->Fetch();

    if (empty($template))
        die(Loc::getMessage('wizard.services.main.template.errors.template'));

    if (!Constructor::isLite()) {
        $buildPath = Path::from(WIZARD_ABSOLUTE_PATH.'/site/builds/'.WIZARD_TEMPLATE_ID);

        if (FileHelper::isDirectory($buildPath->getValue('/'))) {
            $build = new Build();
            $build->code = WIZARD_TEMPLATE_ID.'_'.WIZARD_SITE_ID;
            $build->name = $template['NAME'].' ('.WIZARD_SITE_ID.')';
            $buildResourcesPath = $buildPath->add('resources');
            $buildBlocksResourcesCounter = 0;
            $buildBlocksResourcesPath = $buildResourcesPath->add('blocks');
            $buildBlocksResourcesPathTo = $templatePathTo->add(Block::getResourcesDirectoryPrefix());

            $buildExists = Build::find()->where([
                'code' => $build->code
            ])->one();

            if (!empty($buildExists) && $wizard->GetVar('systemReplaceTemplate') == 'Y') {
                $buildExists->delete();
                $buildExists = null;
            }

            if ($buildExists === null && $build->save()) {
                /**
                 * Загрузка части сборки.
                 * @param string $path Относительный путь от сборки.
                 * @return array|null
                 */
                $buildLoadPart = function ($path) use (&$buildPath) {
                    $result = null;

                    if (FileHelper::isFile($buildPath->getValue('/').'/'.$path))
                        $result = include($buildPath->getValue('/').'/'.$path);

                    if ($result !== null)
                        $result = Encoding::convert($result, null, Encoding::UTF8);

                    return $result;
                };

                /**
                 * Загрузка частей сборки из директории.
                 * @param string $section Относительный путь директории от сборки.
                 * @return array
                 */
                $buildLoadParts = function ($section) use (&$buildPath, &$buildLoadPart) {
                    $result = [];

                    $directory = $buildPath->getValue('/').'/'.$section;
                    $entries = FileHelper::getDirectoryEntries($directory, false);

                    foreach ($entries as $entry) {
                        if (FileHelper::isFile($directory.'/'.$entry)) {
                            $entryResult = $buildLoadPart($section . '/' . $entry);

                            if ($entryResult !== null)
                                $result[] = $entryResult;
                        }
                    }

                    return $result;
                };

                /**
                 * Замена макросов в свойствах.
                 * @param array $properties Свойства.
                 * @return array
                 */
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

                /**
                 * Импорт ресурсов блоков.
                 * @param array $container
                 * @throws \intec\core\base\ErrorException
                 */
                $buildBlocksResourcesImport = function ($container) use (
                    &$buildBlocksResourcesCounter,
                    &$buildBlocksResourcesImport,
                    &$buildBlocksResourcesPath,
                    &$buildBlocksResourcesPathTo
                ) {
                    $block = ArrayHelper::getValue($container, 'block');
                    $variator = ArrayHelper::getValue($container, 'variator');
                    $containers = ArrayHelper::getValue($container, 'containers');

                    if (!empty($block)) {
                        $pathFrom = $buildBlocksResourcesPath->add($buildBlocksResourcesCounter);
                        $pathTo = $buildBlocksResourcesPathTo->add($block['id']);

                        if (FileHelper::isDirectory($pathFrom->value)) {
                            FileHelper::removeDirectory($pathTo->value);
                            FileHelper::copyDirectory($pathFrom->value, $pathTo->value);
                        }

                        $buildBlocksResourcesCounter++;
                    } else if (!empty($variator)) {
                        $variants = ArrayHelper::getValue($variator, 'variants');

                        foreach ($variants as $variant) {
                            $child = ArrayHelper::getValue($variant, 'container');

                            if (!empty($child))
                                $buildBlocksResourcesImport($child);
                        }
                    } else if (!empty($containers)) {
                        foreach ($containers as $child)
                            $buildBlocksResourcesImport($child);
                    }
                };

                $buildAreas = $buildLoadParts('areas');
                $buildTemplates = $buildLoadParts('templates');

                usort($buildAreas, function ($areaLeft, $areaRight) {
                    if (!empty($areaLeft['sort']) && !empty($areaRight['sort'])) {
                        return $areaLeft['sort'] - $areaRight['sort'];
                    } else if (!empty($areaLeft['sort'])) {
                        return 1;
                    } else if (!empty($areaRight['sort'])) {
                        return -1;
                    }

                    return 0;
                });

                usort($buildTemplates, function ($areaLeft, $areaRight) {
                    if (!empty($areaLeft['sort']) && !empty($areaRight['sort'])) {
                        return $areaLeft['sort'] - $areaRight['sort'];
                    } else if (!empty($areaLeft['sort'])) {
                        return 1;
                    } else if (!empty($areaRight['sort'])) {
                        return -1;
                    }

                    return 0;
                });

                foreach ($buildAreas as $buildArea) {
                    $indexes = [];
                    $area = Area::create();
                    $area->buildId = $build->id;
                    $area->import($buildArea, $indexes);

                    if (!empty($indexes['container']))
                        $buildBlocksResourcesImport($indexes['container']);
                }

                foreach ($buildTemplates as $buildTemplate) {
                    $indexes = [];
                    $template = Template::create();
                    $template->buildId = $build->id;
                    $template->import($buildTemplate, $indexes);

                    if (!empty($indexes['container']))
                        $buildBlocksResourcesImport($indexes['container']);
                }

                unset($indexes);
                unset($buildTemplate);
                unset($buildTemplates);
                unset($buildArea);
                unset($buildAreas);

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

                    /** @var Widget[] $widgets */
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

            unset($buildExists);
        }
    }

    $site = CSite::GetList($by = 'def', $order = 'desc', ['LID' => WIZARD_SITE_ID]);
    $site = $site->Fetch();

    if (!empty($site)) {
        (new CSite())->Update($site['ID'], [
            'NAME' => $wizard->GetVar('siteName'),
            'TEMPLATE' => [[
                'CONDITION' => '',
                'SORT' => 150,
                'TEMPLATE' => $templateId
            ]]
        ]);
    }
} else {
    die(Loc::getMessage('wizard.services.main.template.errors.template'));
}

?>
<? include(__DIR__.'/../.end.php') ?>