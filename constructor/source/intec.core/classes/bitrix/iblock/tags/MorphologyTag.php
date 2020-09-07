<?php
namespace intec\core\bitrix\iblock\tags;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Iblock\Template\Functions\FunctionBase;
use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Encoding;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

Loc::loadMessages(__FILE__);

if (!Loader::includeModule('iblock'))
    return;

/**
 * Класс, представляющий обработчик тегов морфологии.
 * Class MorphologyTag
 * @package intec\core\bitrix\iblock\tags
 */
class MorphologyTag extends FunctionBase
{
    /**
     * Возвращает род.
     * @return array
     */
    public static function getKinds()
    {
        return [
            Loc::getMessage('intec.core.bitrix.iblock.tags.kinds.masculine.value') => Loc::getMessage('intec.core.bitrix.iblock.tags.kinds.masculine.name'),
            Loc::getMessage('intec.core.bitrix.iblock.tags.kinds.feminine.value') => Loc::getMessage('intec.core.bitrix.iblock.tags.kinds.feminine.name'),
            Loc::getMessage('intec.core.bitrix.iblock.tags.kinds.neuter.value') => Loc::getMessage('intec.core.bitrix.iblock.tags.kinds.neuter.name')
        ];
    }

    /**
     * Возвращает значения рода.
     * @return array
     */
    public static function getKindsValues()
    {
        $values = static::getKinds();
        $values = ArrayHelper::getKeys($values);

        return $values;
    }

    /**
     * Возвращает число.
     * @return array
     */
    public static function getMultipliers()
    {
        return [
            Loc::getMessage('intec.core.bitrix.iblock.tags.multipliers.singular.value') => Loc::getMessage('intec.core.bitrix.iblock.tags.multipliers.singular.name'),
            Loc::getMessage('intec.core.bitrix.iblock.tags.multipliers.plural.value') => Loc::getMessage('intec.core.bitrix.iblock.tags.multipliers.plural.name')
        ];
    }

    /**
     * Возвращает значения числа.
     * @return array
     */
    public static function getMultipliersValues()
    {
        $values = static::getMultipliers();
        $values = ArrayHelper::getKeys($values);

        return $values;
    }

    /**
     * Возвращает падежи.
     * @return array
     */
    public static function getCases()
    {
        return [
            Loc::getMessage('intec.core.bitrix.iblock.tags.cases.nominative.value') => Loc::getMessage('intec.core.bitrix.iblock.tags.cases.nominative.name'),
            Loc::getMessage('intec.core.bitrix.iblock.tags.cases.genitive.value') => Loc::getMessage('intec.core.bitrix.iblock.tags.cases.genitive.name'),
            Loc::getMessage('intec.core.bitrix.iblock.tags.cases.dative.value') => Loc::getMessage('intec.core.bitrix.iblock.tags.cases.dative.name'),
            Loc::getMessage('intec.core.bitrix.iblock.tags.cases.accusative.value') => Loc::getMessage('intec.core.bitrix.iblock.tags.cases.accusative.name'),
            Loc::getMessage('intec.core.bitrix.iblock.tags.cases.instrumental.value') => Loc::getMessage('intec.core.bitrix.iblock.tags.cases.instrumental.name'),
            Loc::getMessage('intec.core.bitrix.iblock.tags.cases.prepositional.value') => Loc::getMessage('intec.core.bitrix.iblock.tags.cases.prepositional.name')
        ];
    }

    /**
     * Возвращает значения падежей.
     * @return array
     */
    public static function getCasesValues()
    {
        $values = static::getCases();
        $values = ArrayHelper::getKeys($values);

        return $values;
    }

    /**
     * @inheritdoc
     */
    public function calculate(array $parameters)
    {
        $result = [];
        $morphology = Core::$app->morphology;

        if (!$morphology->getIsLoaded())
            return $result;

        $parameters = $this->parametersToArray($parameters);
        $morphology = $morphology->getInstance();

        if (count($parameters) < 2)
            return $result;

        $index = 0;
        $length = count($parameters);
        $modifiers = null;
        $phrases = [];

        foreach ($parameters as $parameter) {
            $left = $length - $index - 1;

            if ($left === 0) {
                $modifiers = $parameter;
            } else {
                $phrases[] = $parameter;
            }

            $index++;
        }

        $modifiers = explode(' ', $modifiers);

        $kinds = static::getKindsValues();
        $kind = null;

        $multipliers = static::getMultipliersValues();
        $multiplier = null;

        $cases = static::getCasesValues();
        $case = null;

        foreach ($modifiers as $modifier) {
            if (ArrayHelper::isIn($modifier, $kinds)) {
                $kind = $modifier;
            } else if (ArrayHelper::isIn($modifier, $multipliers)) {
                $multiplier = $modifier;
            } else if (ArrayHelper::isIn($modifier, $cases)) {
                $case = $modifier;
            }
        }

        $modifiers = [];

        if ($kind !== null)
            $modifiers[] = $kind;

        if ($multiplier !== null)
            $modifiers[] = $multiplier;

        if ($case !== null)
            $modifiers[] = $case;

        unset($kinds, $kind, $multipliers, $multiplier, $cases, $case);

        foreach ($phrases as $index => $phrase) {
            $phrases[$index] = preg_replace_callback('/(([^\\r\\n\\t\\f\\v -.?!\\)\\(,:])+)/', function ($matches) use (&$morphology, &$modifiers) {
                $word = $matches[1];
                $result = $morphology->castFormByGramInfo(StringHelper::toUpperCase($word, Encoding::getDefault()), null, $modifiers, true);

                if (!empty($result) && (!empty($result[0]) || Type::isNumeric($result[0]))) {
                    $result = $result[0];
                } else {
                    $result = $word;
                }

                $result = StringHelper::toLowerCase($result, Encoding::getDefault());

                return $result;
            }, $phrase);
        }

        return $phrases;
    }
}