<?php
namespace intec\core\base;

use intec\Core;
use intec\core\base\condition\DataProvider;
use intec\core\base\condition\ResultModifier;

/**
 * Класс, представляющий.
 * Class Condition
 * @property boolean $isFulfilled Выполнено ли условие. Только для чтения.
 * @package intec\core\bitrix\comparing
 * @author apocalypsisdimon@gmail.com
 */
abstract class Condition extends BaseObject
{
    /**
     * Создает объект условия из данных.
     * @param array $data
     * @return Condition|null
     */
    public static function create($data)
    {
        $class = self::className();

        if (
            !is_array($data) ||
            empty($data['class']) ||
            !is_string($data['class']) || !(
                $data['class'] === $class ||
                is_subclass_of($data['class'], $class)
            )
        ) return null;

        return Core::createObject($data);
    }

    /**
     * Проверяет, выполнено ли условие.
     * @param DataProvider $provider Данные.
     * @param ResultModifier $modifier Модификатор результата.
     * @return boolean
     */
    public function getIsFulfilled($provider, $modifier = null)
    {
        if (!($provider instanceof DataProvider))
            throw new InvalidParamException('Provider is not instance of "'.DataProvider::className().'"');

        if ($modifier !== null)
            if (!($modifier instanceof ResultModifier))
                throw new InvalidParamException('Modifier is not instance of "'.ResultModifier::className().'"');

        return false;
    }

    /**
     * Возвращает условие в виде массива.
     * @return array
     */
    public function export()
    {
        return [
            'class' => self::className()
        ];
    }
}