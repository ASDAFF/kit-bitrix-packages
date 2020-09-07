<?php
namespace intec\core;

use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use intec\Core;
use intec\core\base\BaseObject;
use intec\core\base\InvalidConfigException;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;

Loader::includeModule('intec.core');

/**
 * Класс, хранящий методы для вызова из событий системы.
 * Class Callbacks
 * @package intec\core
 * @author apocalypsisdimon@gmail.com
 */
class Callbacks extends BaseObject
{
    /**
     * Событие. Возникает после отрисовки контента.
     * @param string $content Отрисованный контент страницы.
     */
    public static function mainOnEndBufferContent(&$content)
    {
        try {
            $context = Context::getCurrent();
            $request = $context->getRequest();
            $site = $context->getSite();

            if ($request->isAdminSection())
                return;

            if (StringHelper::startsWith(Core::$app->request->getUrl(), '/bitrix/'))
                return;

            if (!empty($site)) {
                $parameters = Core::$app->getParameters();

                if ($parameters->getMinimizationUse($site)) {
                    $mode = 0;

                    if ($parameters->getMinimizationSpaces($site))
                        $mode = $mode | Html::MINIMIZE_SPACES;

                    if ($parameters->getMinimizationTags($site))
                        $mode = $mode | Html::MINIMIZE_TAGS;

                    if ($parameters->getMinimizationCommentaries($site))
                        $mode = $mode | Html::MINIMIZE_COMMENTARIES;

                    if ($parameters->getMinimizationContent($site))
                        $mode = $mode | Html::MINIMIZE_CONTENT;

                    $content = Html::minimize($content, $mode);
                }
            }
        } catch (InvalidConfigException $exception) {}
    }

    /**
     * Событие. Возникает при построении главного меню.
     * @param array $arGlobalMenu Массив глобального меню.
     * @param array $arModuleMenu Массив меню модуля.
     */
    public static function mainOnBuildGlobalMenu(&$arGlobalMenu, &$arModuleMenu)
    {
        Core::$app->web->css->addFile(Core::getAlias('@intec/core/resources/css/icons.css'));

        $arGlobalMenu['global_intec'] = [
            'menu_id' => 'global_intec',
            'text' => 'Intec',
            'title' => 'Intec',
            'sort' => 300,
            'items_id' => 'global_intec',
            'items' => []
        ];
    }
}