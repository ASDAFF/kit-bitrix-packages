<?php
namespace intec\constructor\handlers;

use intec\constructor\models\build\Area;
use intec\constructor\models\build\AreaLink;
use intec\constructor\models\build\template\Block;
use intec\constructor\models\build\template\ContainerLink;
use intec\constructor\models\build\template\Variator;
use intec\constructor\models\build\template\variator\Variant;
use intec\Core;
use intec\core\base\InvalidParamException;
use intec\core\db\ActiveRecords;
use intec\core\handling\Actions;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Encoding;
use intec\core\helpers\Json;
use intec\core\helpers\Type;
use intec\constructor\models\Build;
use intec\constructor\models\build\File;
use intec\constructor\models\build\Template;
use intec\constructor\models\build\template\Component;
use intec\constructor\models\build\template\Container;
use intec\constructor\models\build\template\Containers;
use intec\constructor\models\build\template\Value as TemplateValue;
use intec\constructor\models\build\template\Widget;
use intec\constructor\models\build\Theme;
use intec\constructor\models\build\theme\Value as ThemeValue;

class TemplateActions extends Actions
{
    /**
     * @var Build
     */
    public $build;
    /**
     * @var Template
     */
    public $template;

    public function beforeAction($action)
    {
        if (parent::beforeAction($action))
        {
            global $build;
            global $template;

            $this->build = $build;
            $this->template = $template;

            return true;
        }

        return false;
    }

    public function actionSave()
    {
        ini_set('max_execution_time', 0);

        $request = Core::$app->request;
        $data = $request->post('data');

        try {
            $data = Json::decode($data);
        } catch (InvalidParamException $exception) {
            return false;
        }

        if (!Type::isArray($data))
            return false;

        $data = ArrayHelper::convertEncoding($data, null, Encoding::UTF8);

        $container = ArrayHelper::getValue($data, 'container');
        $scheme = ArrayHelper::getValue($data, 'scheme');
        $settings = ArrayHelper::getValue($data, 'settings');

        $areas = $this->build->getAreas(true);
        $areas->indexBy('id');
        $areasId = [];

        foreach ($areas as $area)
            $areasId[] = $area->id;

        /** Собираем условие для выборки */
        $conditions = [
            'or',
            ['templateId' => $this->template->id]
        ];

        /** Если есть зоны, выбираем из них тоже */
        if (!empty($areasId))
            $conditions[] = ['areaId' => $areasId];

        /** @var Containers $containers */
        $containers = Container::find()
            ->with(['link'])
            ->where($conditions)
            ->all();

        $containers->indexBy('id');
        $containersId = [];

        /** @var ActiveRecords $components */
        $components = Component::find()
            ->where($conditions)
            ->all();

        $components->indexBy('id');

        /** @var ActiveRecords $widgets */
        $widgets = Widget::find()
            ->where($conditions)
            ->all();

        $widgets->indexBy('id');

        /** @var ActiveRecords $blocks */
        $blocks = Block::find()
            ->where(ArrayHelper::merge($conditions, [
                [
                    'templateId' => null,
                    'areaId' => null
                ]
            ]))
            ->all();

        $blocks->indexBy('id');

        /** @var ActiveRecords $variators */
        $variators = Variator::find()
            ->where($conditions)
            ->with(['variants'])
            ->all();

        $variators->indexBy('id');
        $variatorsVariantsId = [];

        /** @var ActiveRecords $themes */
        $themes = $this->build->getThemes(true);
        $themes->indexBy('code');

        /** @var ActiveRecords $themes */
        $properties = $this->build->getProperties(true);
        $properties->indexBy('code');

        $areasId = [];

        /**
         * Сохранение контейнера.
         * @param array $data
         * @param Container|null $parent
         */
        $saveContainer = function ($data, $parent = null) use (
            &$saveArea,
            &$saveContainer,
            &$saveContainerProperty,
            &$saveComponent,
            &$saveWidget,
            &$saveBlock,
            &$saveVariator,
            &$containers,
            &$containersId
        ) {
            /** @var Container $container */
            $container = null;

            $id = ArrayHelper::getValue($data, 'id');
            $code = ArrayHelper::getValue($data, 'code');
            $type = ArrayHelper::getValue($data, 'type');
            $display = ArrayHelper::getValue($data, 'display');
            $order = ArrayHelper::getValue($data, 'order');
            $condition = ArrayHelper::getValue($data, 'condition');
            $script = ArrayHelper::getValue($data, 'script');
            $children = ArrayHelper::getValue($data, 'containers');
            $area = ArrayHelper::getValue($data, 'area');
            $component = ArrayHelper::getValue($data, 'component');
            $widget = ArrayHelper::getValue($data, 'widget');
            $block = ArrayHelper::getValue($data, 'block');
            $variator = ArrayHelper::getValue($data, 'variator');

            if (!empty($id))
                if ($containers->exists($id))
                    $container = $containers->get($id);

            if (empty($container)) {
                $container = new Container();
            }

            $container->templateId = null;
            $container->areaId = null;

            if (!empty($parent)) {
                if ($parent instanceof Container || $parent instanceof Variant) {
                    $container->templateId = $parent->templateId;
                    $container->areaId = $parent->areaId;
                } else if ($parent instanceof Area) {
                    $container->areaId = $parent->id;
                }
            } else {
                $container->templateId = $this->template->id;
            }

            $container->code = $code;
            $container->type = $type;
            $container->display = $display;
            $container->order = !empty($parent) ? $order : 0;
            $container->condition = $condition;
            $container->script = $script;
            $container->properties = ArrayHelper::getValue($data, 'properties');

            if (!$container->save()) {
                return;
            }

            if (!empty($parent)) {
                $link = $container->getLink(true);

                if ($parent instanceof Area) {
                    if (!empty($link))
                        $link->delete();
                } else {
                    if (empty($link)) {
                        $link = new ContainerLink();
                        $link->containerId = $container->id;
                    }

                    if ($parent instanceof Container) {
                        $link->parentId = $parent->id;
                        $link->parentType = Container::TYPE;
                    } else if ($parent instanceof Variant) {
                        $link->parentId = $parent->id;
                        $link->parentType = Variant::TYPE;
                    }

                    $link->save();
                }
            }

            $containersId[] = $container->id;

            if (!empty($component)) {
                $saveComponent($component, $container);
            } else if (!empty($widget)) {
                $saveWidget($widget, $container);
            } else if (!empty($block)) {
                $saveBlock($block, $container);
            } else if (!empty($variator)) {
                $saveVariator($variator, $container);
            } else if (!empty($area)) {
                $saveArea($area, $container);
            } else if (Type::isArrayable($children)) {
                foreach ($children as $child) {
                    $saveContainer($child, $container);
                }
            }
        };

        /**
         * Сохранение компонента контейнера.
         * @param array $data
         * @param Container $parent
         */
        $saveComponent = function ($data, $parent) use (&$components) {
            /** @var Component $component */
            $component = null;

            $id = ArrayHelper::getValue($data, 'id');
            $code = ArrayHelper::getValue($data, 'code');
            $properties = ArrayHelper::getValue($data, 'properties');
            $template = ArrayHelper::getValue($data, 'template');

            if (!empty($id))
                if ($components->exists($id))
                    $component = $components->get($id);

            if (empty($component)) {
                $component = new Component();
                $component->containerId = $parent->id;
            }

            $component->templateId = $parent->templateId;
            $component->areaId = $parent->areaId;
            $component->code = $code;
            $component->template = $template;
            $component->properties = $properties;
            $component->save();
        };

        /**
         * Сохранение виджета контейнера.
         * @param array $data
         * @param Container $parent
         */
        $saveWidget = function ($data, $parent) use (&$widgets) {
            /** @var Widget $widget */
            $widget = null;

            $id = ArrayHelper::getValue($data, 'id');
            $code = ArrayHelper::getValue($data, 'code');
            $properties = ArrayHelper::getValue($data, 'properties');
            $template = ArrayHelper::getValue($data, 'template');

            if (!empty($id))
                if ($widgets->exists($id))
                    $widget = $widgets->get($id);

            if (empty($widget)) {
                $widget = new Widget();
                $widget->templateId = $parent->templateId;
                $widget->containerId = $parent->id;
            }

            $widget->templateId = $parent->templateId;
            $widget->areaId = $parent->areaId;
            $widget->code = $code;
            $widget->template = $template;
            $widget->properties = $properties;
            $widget->save();
        };

        /**
         * Сохраняет блок контейнера.
         * @param array $data
         * @param Container $parent
         */
        $saveBlock = function ($data, $parent) use (&$blocks) {
            /** @var Block $block */
            $block = null;

            $id = ArrayHelper::getValue($data, 'id');
            $name = ArrayHelper::getValue($data, 'name');

            if (empty($id))
                return;

            $block = $blocks->get($id);

            if (empty($block))
                return;

            $block->templateId = $parent->templateId;
            $block->areaId = $parent->areaId;
            $block->containerId = $parent->id;
            $block->name = $name;
            $block->save();
        };

        /**
         * Сохраняет вариатор контейнера.
         * @param array $data
         * @param Container $parent
         */
        $saveArea = function ($data, $parent) use (&$areas, &$areasId, &$saveContainer) {
            /** @var Area $area */
            $area = null;

            $id = ArrayHelper::getValue($data, 'id');
            $container = ArrayHelper::getValue($data, 'container');

            if (!empty($id))
                if ($areas->exists($id))
                    $area = $areas->get($id);

            if (empty($area))
                return;

            $areaLink = AreaLink::find()
                ->where([
                    'containerId' => $parent->id
                ])
                ->one();

            if (empty($areaLink)) {
                $areaLink = new AreaLink();
                $areaLink->containerId = $parent->id;
            }

            $areaLink->areaId = $area->id;

            if (!$areaLink->save())
                return;

            if (!ArrayHelper::isIn($area->id, $areasId))
                $areasId[] = $area->id;

            if (!empty($container))
                $saveContainer($container, $area);
        };

        /**
         * Сохраняет вариатор контейнера.
         * @param array $data
         * @param Container $parent
         */
        $saveVariator = function ($data, $parent) use (&$variators, &$saveVariant) {
            /** @var Variator $variator */
            $variator = null;

            $id = ArrayHelper::getValue($data, 'id');
            $variant = ArrayHelper::getValue($data, 'variant');
            $variants = ArrayHelper::getValue($data, 'variants');

            if (!empty($id))
                if ($variators->exists($id))
                    $variator = $variators->get($id);

            if (empty($variator)) {
                $variator = new Variator();
                $variator->containerId = $parent->id;
            }

            $variator->templateId = $parent->templateId;
            $variator->areaId = $parent->areaId;
            $variator->variant = $variant;

            if (!$variator->save()) {
                return;
            }

            if (!empty($variants))
                foreach ($variants as $variant)
                    $saveVariant($variant, $variator);
        };

        /**
         * Сохраняет вариант вариатора.
         * @param array $data
         * @param Variator $parent
         */
        $saveVariant = function ($data, $parent) use (&$saveContainer, &$variatorsVariantsId) {
            if (!$parent instanceof Variator)
                return;

            $variants = $parent->getVariants(true);
            $variants->indexBy('id');

            /** @var Variant $variant */
            $variant = null;

            $id = ArrayHelper::getValue($data, 'id');
            $code = ArrayHelper::getValue($data, 'code');
            $order = ArrayHelper::getValue($data, 'order');
            $name = ArrayHelper::getValue($data, 'name');
            $container = ArrayHelper::getValue($data, 'container');

            if (!empty($id))
                if ($variants->exists($id))
                    $variant = $variants->get($id);

            if (empty($variant)) {
                $variant = new Variant();
                $variant->variatorId = $parent->id;
            }

            $variant->code = $code;
            $variant->templateId = $parent->templateId;
            $variant->areaId = $parent->areaId;
            $variant->order = $order;
            $variant->name = $name;

            if (!$variant->save())
                return;

            if (!ArrayHelper::isIn($variant->id, $variatorsVariantsId))
                $variatorsVariantsId[] = $variant->id;

            if (!empty($container))
                $saveContainer($container, $variant);
        };

        if (Type::isArray($container)) {
            $saveContainer($container);
        }

        /** Собираем условие для выборки контейнеров */
        $conditions = [
            'or',
            ['templateId' => $this->template->id]
        ];

        /** Если есть зоны, выбираем контейнеры из них тоже */
        if (!empty($areasId))
            $conditions[] = ['areaId' => $areasId];

        $containers = Container::find()
            ->where(['NOT IN', 'id', $containersId])
            ->andWhere($conditions)
            ->all();
        /** @var Containers $containers */

        $blocks = Block::find()
            ->where([
                'containerId' => 0
            ])
            ->all();
        /** @var ActiveRecords $blocks */

        $variatorsVariants = Variant::find()
            ->where(['NOT IN', 'id', $variatorsVariantsId])
            ->andWhere($conditions)
            ->all();
        /** @var ActiveRecords $variatorsVariants */

        /**
         * Удаление контейнеров, которых нет в полученных данных.
         * @var Container $container
         */
        foreach ($containers as $container)
            $container->delete();

        /**
         * Удаление блоков, которые не привязаны к контейнеру.
         * @var Block $block
         */
        foreach ($blocks as $block)
            $block->delete();

        /**
         * Удаление вариантов вариаторов, которые не привязаны к вариатору.
         * @var Variant $variatorVariant
         */
        foreach ($variatorsVariants as $variatorVariant)
            $variatorVariant->delete();

        /** Сохранение настроек схемы. */
        if (Type::isArray($scheme)) {
            $themesData = ArrayHelper::getValue($scheme, 'themes');

            if (Type::isArray($themesData))
                foreach ($themesData as $themeData) {
                    $code = ArrayHelper::getValue($themeData, 'code');
                    $valuesData = ArrayHelper::getValue($themeData, 'values');

                    if ((!empty($code) && !$themes->exists($code)) || !Type::isArray($valuesData))
                        continue;

                    $theme = null;
                    $values = null;

                    if (!empty($code)) {
                        /** @var Theme $theme */
                        $theme = $themes->get($code);

                        if (!empty($theme)) {
                            $values = $theme->getValues(true);
                        } else {
                            continue;
                        }
                    } else {
                        $values = $this->template->getValues(true);
                    }

                    $values->indexBy('propertyCode');
                    /** @var ActiveRecords $values */

                    foreach ($valuesData as $valueData) {
                        $code = ArrayHelper::getValue($valueData, 'code');
                        $value = null;

                        if (!$values->exists($code)) {
                            if (empty($theme)) {
                                $value = new TemplateValue();
                                $value->templateId = $this->template->id;
                            } else {
                                $value = new ThemeValue();
                                $value->themeCode = $theme->code;
                            }

                            $value->buildId = $this->build->id;
                            $value->propertyCode = $code;
                        } else {
                            $value = $values->get($code);
                        }

                        $value->value = ArrayHelper::getValue($valueData, 'value');
                        $value->save();
                    }
                }

            $this->template->themeCode = null;
            $theme = ArrayHelper::getValue($scheme, 'theme');
            $theme = $themes->get($theme);
            /** @var Theme $theme */

            if ($theme)
                $this->template->themeCode = $theme->code;
        }

        $this->template->settings = $settings;
        $this->template->save();

        return true;
    }

    public function actionStyles()
    {
        global $APPLICATION;

        $APPLICATION->ShowAjaxHead();

        $request = Core::$app->request;
        $template = $this->template;
        $build = $this->build;
        $files = $build->getFiles();
        $themes = $template->getThemes(true);
        $themes->indexBy('code');
        $theme = $request->post('theme');
        $theme = $themes->get($theme);
        $values = $request->post('values');

        if (!Type::isArray($values))
            $values = [];

        $properties = $template->getPropertiesValues($theme);
        $properties = ArrayHelper::merge($properties, $values);

        Core::$app->web->css->addString($template->getCss());
        Core::$app->web->css->addString($template->getLess($properties));

        foreach ($files as $file)
            if ($file->getType() == File::TYPE_SCSS)
                Core::$app->web->css->addString(
                    Core::$app->web->scss->compileFile(
                        $file->getPath(),
                        null,
                        $properties
                    )
                );

        exit();
    }
}