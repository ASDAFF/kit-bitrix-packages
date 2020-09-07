<?php
namespace intec\constructor\models\build\template;

use intec\core\helpers\Type;
use intec\core\db\ActiveRecords;
use intec\core\helpers\ArrayHelper;
use intec\constructor\models\Build;
use intec\constructor\models\build\Area;
use intec\constructor\models\build\Template;
use intec\constructor\models\build\template\variator\Variant;

/**
 * Class Containers
 * @package intec\constructor\models\build\template
 */
class Containers extends ActiveRecords
{
    /**
     * @inheritdoc
     */
    protected function verify($item)
    {
        return $item instanceof Container;
    }

    /**
     * Возвращает корневой контейнер.
     * @param Area|Template $storage Хранилище, корень которого нужно вернуть.
     * @return Container|null
     */
    public function getRootContainer($storage = null) {
        /** @var Container[] $containers */
        $containers = $this->asArray();
        /** @var Container $container */
        $container = null;

        foreach ($containers as $container) {
            if ($storage instanceof Area) {
                if ($container->areaId != $storage->id) {
                    $container = null;
                    continue;
                }
            } else if ($storage instanceof Template) {
                if ($container->templateId != $storage->id) {
                    $container = null;
                    continue;
                }
            }

            $link = $container->getLink(true);

            if ($link === null)
                break;

            $container = null;
        }

        return $container;
    }

    /**
     * Заполняет все зависимости и формирует дерево.
     * Началом дерева является корневой контейнер.
     * Все объекты клонированы. Могут использоваться отдельно.
     * @param Build|null $build
     * @param Area|Template|null $storage
     * @return Container|null
     */
    public function getTree($build = null, $storage = null)
    {
        $containers = clone $this;
        $containers->indexBy('id');
        $container = $this->getRootContainer($storage);

        if (empty($container))
            return null;

        if (
            !($storage instanceof Template) &&
            !($storage instanceof Area)
        ) {
            if (!empty($container->templateId))
                $storage = $container->getStorageTemplate(true);

            if (empty($container->areaId))
                $storage = $container->getStorageArea(true);
        }

        if (empty($storage))
            return null;

        if (!$build instanceof Build)
            $build = $storage->getBuild(true);

        if (empty($build))
            return null;

        $storage->populateRelation('build', $build);

        /** Клонируем корневой контейнер */
        $container = clone $container;

        /** Установим общий шаблон если он имеется */
        /** @var Template $template */
        $template = null;

        if ($storage instanceof Template)
            $template = $storage;

        /**
         * Функция для построения дерева контейнеров.
         * Также привязывает все зависимые элементы.
         * @param Container $container
         * @param Area|Template $storage
         * @param Container|null $parent
         */
        $function = function ($container, $parent = null, $storage = null) use (&$function, &$build, &$template, &$containers) {
            /** Привязываем шаблон к контейнеру */
            $container->populateRelation('storageTemplate', $template);
            /** Привязываем зону к контейнеру */
            $container->populateRelation('storageArea', null);
            /** Привязываем родителя (Контейнер) к контейнеру */
            $container->populateRelation('parentContainer', null);
            /** Привязываем родителя (Вариант) к контейнеру */
            $container->populateRelation('parentVariant', null);
            /** Указываем, что дочерних контейнеров пока нет */
            $container->populateRelation('containers', []);

            if ($storage instanceof Area)
                $container->populateRelation('storageArea', $storage);

            if ($parent instanceof Container) {
                $container->populateRelation('parentContainer', $parent);
            } else if ($parent instanceof Variant) {
                $container->populateRelation('parentVariant', $parent);
            }

            $area = $container->getArea(true);
            $component = $container->getComponent(true);
            $widget = $container->getWidget(true);
            $block = $container->getBlock(true);
            $variator = $container->getVariator(true);

            if (!empty($area)) {
                /** Клонируем зону */
                $area = clone $area;
                /** Привязываем сборку к зоне */
                $area->populateRelation('build', $build);
                /** Привязываем шаблон к зоне */
                $area->setTemplate($template);
                /** Привязываем контейнер к зоне */
                $area->setParentContainer($container);
                /** Привязываем дочерний контейнер к зоне **/
                $area->setContainer(null);

                /**
                 * Идем по всем контейнерам шаблона и ищем корневой контейнер.
                 * @var int $key
                 * @var Container $child
                 */
                foreach ($containers as $key => $child) {
                    $link = $child->getLink(true);

                    if ($child->areaId == $area->id && $link === null) {
                        /** Удаляем $child из общего списка контейнеров */
                        unset($containers[$key]);

                        /** Клонируем дочерний контейнер */
                        $child = clone $child;

                        /** Проделываем те-же операции для дочернего контейнера */
                        $function($child, $area, $area);
                        $area->setContainer($child);

                        break;
                    }
                }
            }

            $container->populateRelation('area', $area);

            if (!empty($component)) {
                /** Клонируем компонент */
                $component = clone $component;
                /** Привязываем шаблон к компоненту */
                $component->populateRelation('template', $template);
                /** Привязываем контейнер к компоненту */
                $component->populateRelation('container', $container);

                $component->populateRelation('area', null);

                /** Привязываем зону к компоненту */
                if ($storage instanceof Area)
                    $component->populateRelation('area', $storage);
            }

            $container->populateRelation('component', $component);

            if (!empty($widget)) {
                /** Клонируем виджет */
                $widget = clone $widget;
                /** Привязываем шаблон к виджету */
                $widget->populateRelation('template', $template);
                /** Привязываем контейнер к виджету */
                $widget->populateRelation('container', $container);

                $widget->populateRelation('area', null);

                /** Привязываем зону к виджету */
                if ($storage instanceof Area)
                    $widget->populateRelation('area', $storage);
            }

            $container->populateRelation('widget', $widget);

            if (!empty($block)) {
                /** Клонируем блок */
                $block = clone $block;
                /** Привязываем шаблон к виджету */
                $block->populateRelation('template', $template);
                /** Привязываем контейнер к виджету */
                $block->populateRelation('container', $container);

                $block->populateRelation('area', null);

                /** Привязываем зону к блоку */
                if ($storage instanceof Area)
                    $block->populateRelation('area', $storage);
            }

            $container->populateRelation('block', $block);

            if (!empty($variator)) {
                /** Клонируем вариатор */
                $variator = clone $variator;
                /** Привязываем шаблон к вариатору */
                $variator->populateRelation('template', $template);
                /** Привязываем контейнер к вариатору */
                $variator->populateRelation('container', $container);
                /** Получаем структуру вариантов */
                $variants = $variator->getVariants(true);

                $variator->populateRelation('area', null);

                /** Привязываем зону к вариатору */
                if ($storage instanceof Area)
                    $variator->populateRelation('area', $storage);

                foreach ($variants as $index => $variant) {
                    $variant = clone $variant;
                    /** Привязываем шаблон к варианту */
                    $variant->populateRelation('template', $template);

                    $variant->populateRelation('area', null);

                    /** Привязываем зону к варианту */
                    if ($storage instanceof Area)
                        $variant->populateRelation('area', $storage);

                    foreach ($containers as $key => $child) {
                        $link = $child->getLink(true);

                        if ($link !== null && $link->parentId == $variant->id && $link->parentType === Variant::TYPE) {
                            /** Удаляем $child из общего списка контейнеров */
                            unset($containers[$key]);

                            /** Клонируем дочерний контейнер */
                            $child = clone $child;

                            /** Проделываем те-же операции для дочернего контейнера */
                            $function($child, $variant);
                            $variant->populateRelation('container', $child);

                            break;
                        }
                    }

                    $variants[$index] = $variant;
                }

                /** Привязываем варианты к вариатору */
                $variator->populateRelation('variants', $variants);
            }

            $container->populateRelation('variator', $variator);

            /** Если нет компонента и виджета, можно искать дочерние контейнеры */
            if (empty($area) && empty($component) && empty($widget) && empty($block) && empty($variator)) {
                $children = [];

                /**
                 * Идем по всем контейнерам шаблона и ищем дочерние элементы.
                 * @var int $key
                 * @var Container $child
                 */
                foreach ($containers as $key => $child) {
                    $link = $child->getLink(true);

                    /** Если $child является потомком $container */
                    if ($link !== null && $link->parentId == $container->id && $link->parentType === Container::TYPE) {
                        /** Удаляем $child из общего списка контейнеров */
                        unset($containers[$key]);

                        /** Клонируем дочерний контейнер */
                        $child = clone $child;

                        /** Проделываем те-же операции для дочернего контейнера */
                        $function($child, $container);
                        $children[$child->id] = $child;
                    }
                }

                /** Указываем найденные дочерние контейнеры */
                $container->populateRelation('containers', $children);
            }
        };

        $function($container);
        return $container;
    }
}