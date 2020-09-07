<?
namespace intec\core\bitrix;

use CBitrixComponent;
use CBitrixComponentTemplate;
use CComponentUtil;
use Closure;
use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * Class Component
 * @package intec\core\bitrix
 * @author apocalypsisdimon@gmail.com
 */
class Component
{
    /**
     * Возвращает уникальный идентификатор для компонента.
     * @param CBitrixComponent|CBitrixComponentTemplate $component
     * @param boolean $random Случайное значение
     * @param boolean $prefix Префикс в виде имени комопнента или компонента и шаблона.
     * @param int $length Длина
     * @return string|null
     */
    public static function getUniqueId($component, $random = false, $prefix = true, $length = 12)
    {
        $sId = null;

        if ($length < 1)
            return $sId;

        if ($component instanceof CBitrixComponent || $component instanceof CBitrixComponentTemplate) {
            if (!$random) {
                $sId = $component->randString($length);
            } else {
                $length = ceil($length / 2);
                $length = Type::toInteger($length);
                $sId = $component->randString($length);
                $sId = $sId.Core::$app->security->generateRandomString($length);
            }
        } else {
            return $sId;
        }

        if ($prefix) {
            if ($component instanceof CBitrixComponent) {
                $sId = $component->getName().'-'.$sId;
            } else {
                $sId = $component->getComponent()->getName().'.'.$component->GetName().'.'.$sId;
            }
        }

        return $sId;
    }

    /**
     * Режим: Параметры компонента и шаблона.
     */
    const PARAMETERS_MODE_BOTH = 0;
    /**
     * Режим: Только параметры компонента.
     */
    const PARAMETERS_MODE_COMPONENT = 1;
    /**
     * Режим: Только параметры шаблона.
     */
    const PARAMETERS_MODE_TEMPLATE = 2;

    /**
     * Возвращает массив параметров компонента.
     * @param string $component Компонент.
     * @param array|string $templates Шаблоны компонента.
     * @param string $siteTemplate Шаблон сайта.
     * @param array $values Значения.
     * @param string|null $prefix Префикс.
     * @param Closure $handler Пользовательский обработчик параметров.
     * @param int $mode Режим.
     * @return array
     */
    public static function getParameters(
        $component,
        $templates,
        $siteTemplate,
        $values = [],
        $prefix = null,
        $handler = null,
        $mode = self::PARAMETERS_MODE_BOTH
    ) {
        $result = [];
        $valuesCurrent = [];

        if (!empty($prefix) || Type::isNumeric($prefix)) {
            foreach ($values as $key => $value) {
                if (
                    !StringHelper::startsWith($key, $prefix) &&
                    !ArrayHelper::keyExists($key, $valuesCurrent)
                ) {
                    $valuesCurrent[$key] = $value;
                } else {
                    $key = StringHelper::cut($key, StringHelper::length($prefix));
                    $valuesCurrent[$key] = $value;
                }
            }
        }

        $values = $valuesCurrent;
        $parameters = [];

        unset($valuesCurrent);

        if (!empty($templates) && ($mode == self::PARAMETERS_MODE_BOTH || $mode == self::PARAMETERS_MODE_TEMPLATE)) {
            if (!Type::isArrayable($templates))
                $templates = [$templates];

            foreach ($templates as $template)
                $parameters = ArrayHelper::merge($parameters, CComponentUtil::GetTemplateProps(
                    $component,
                    $template,
                    $siteTemplate,
                    $values
                ));
        }

        if ($mode == self::PARAMETERS_MODE_BOTH || $mode == self::PARAMETERS_MODE_COMPONENT) {
            $parameters = CComponentUtil::GetComponentProps(
                $component,
                $values,
                $parameters
            );

            $parameters = $parameters['PARAMETERS'];
        }

        foreach ($parameters as $key => $parameter) {
            $include = true;

            if ($handler instanceof Closure)
                $include = $handler($key, $parameter);

            if (!empty($prefix))
                $key = $prefix.$key;

            if ($include)
                $result[$key] = $parameter;
        }

        return $result;
    }

    /**
     * @param array $element Элемент, системные свойства которых необходимо установить.
     * @param array $comparison Какие свойства будут системными.
     * Вид: array(Ключ свойства => Ключ системного свойства)
     * @param Closure|null $handler Обработчик для свойств.
     * @param string $field Поле, в котором будут храниться системные свойства.
     * @return array
     * @deprecated
     */
    public static function SetElementProperties($element, $comparison, $handler = null, $field = 'SYSTEM_PROPERTIES')
    {
        $element[$field] = [];

        foreach ($comparison as $key => $value) {
            $property = ArrayHelper::getValue($element, ['PROPERTIES', $key]);

            if (!empty($property)) {
                if (Type::isFunction($handler))
                    $property = $handler($element, $value, $property);

                $element[$field][$value] = $property;
            } else {
                $element[$field][$value] = null;
            }
        }

        return $element;
    }

    /**
     * @param array $elements Список элементов, системные свойства которых необходимо установить.
     * @param array $comparison Какие свойства будут системными.
     * Вид: array(Ключ свойства => Ключ системного свойства)
     * @param Closure|null $handler Обработчик для свойств.
     * @param string $field Поле, в котором будут храниться системные свойства.
     * @return array
     * @deprecated
     */
    public static function SetElementsProperties($elements, $comparison, $handler = null, $field = 'SYSTEM_PROPERTIES')
    {
        $result = [];

        foreach ($elements as $elementKey => $element) {
            $result[$elementKey] = static::SetElementProperties(
                $element,
                $comparison,
                $handler,
                $field
            );
        }

        return $result;
    }
}