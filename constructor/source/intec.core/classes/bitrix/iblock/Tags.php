<?php
namespace intec\core\bitrix\iblock;

use Bitrix\Main\Event;
use Bitrix\Main\EventResult;
use Bitrix\Iblock\Template\Functions\FunctionBase;

/**
 * Класс, необходимый для разрешения обработчиков тегов.
 * Class Tags
 * @package intec\core
 */
class Tags extends FunctionBase
{
    /**
     * Разрешает обработчик тегов.
     * @param Event $event
     * @return EventResult|null
     */
    public static function resolve($event)
    {
        $result = null;
        $tag = $event->getParameter(0);

        if ($tag === 'morphology') {
            $result = new EventResult(EventResult::SUCCESS, '\\intec\\core\\bitrix\\iblock\\tags\\MorphologyTag');
        }

        return $result;
    }
}