<?php
namespace intec\core\validators;

use intec\Core;
use intec\core\base\Component;
use intec\core\base\Model;
use intec\core\base\NotSupportedException;

/**
 * Базовый класс для всех валидаторов.
 * Производит проверку свойств модели.
 *
 * Список встроенных валидаторов:
 *
 * - `boolean`: [[BooleanValidator]]
 * - `captcha`: [[\yii\captcha\CaptchaValidator]]
 * - `compare`: [[CompareValidator]]
 * - `date`: [[DateValidator]]
 * - `datetime`: [[DateValidator]]
 * - `time`: [[DateValidator]]
 * - `default`: [[DefaultValueValidator]]
 * - `double`: [[NumberValidator]]
 * - `each`: [[EachValidator]]
 * - `email`: [[EmailValidator]]
 * - `exist`: [[ExistValidator]]
 * - `file`: [[FileValidator]]
 * - `filter`: [[FilterValidator]]
 * - `image`: [[ImageValidator]]
 * - `in`: [[RangeValidator]]
 * - `integer`: [[NumberValidator]]
 * - `match`: [[RegularExpressionValidator]]
 * - `required`: [[RequiredValidator]]
 * - `safe`: [[SafeValidator]]
 * - `string`: [[StringValidator]]
 * - `trim`: [[FilterValidator]]
 * - `unique`: [[UniqueValidator]]
 * - `url`: [[UrlValidator]]
 * - `ip`: [[IpValidator]]
 *
 * @package intec\core\validators
 * @since 1.0.0
 */
class Validator extends Component
{
    /**
     * @var array list of built-in validators (name => class or configuration)
     */
    public static $builtInValidators = [
        'boolean' => 'intec\core\validators\BooleanValidator',
        'captcha' => 'intec\core\captcha\CaptchaValidator',
        'compare' => 'intec\core\validators\CompareValidator',
        'date' => 'intec\core\validators\DateValidator',
        'datetime' => [
            'class' => 'intec\core\validators\DateValidator',
            'type' => DateValidator::TYPE_DATETIME,
        ],
        'time' => [
            'class' => 'intec\core\validators\DateValidator',
            'type' => DateValidator::TYPE_TIME,
        ],
        'default' => 'intec\core\validators\DefaultValueValidator',
        'double' => 'intec\core\validators\NumberValidator',
        'each' => 'intec\core\validators\EachValidator',
        'email' => 'intec\core\validators\EmailValidator',
        'exist' => 'intec\core\validators\ExistValidator',
        'file' => 'intec\core\validators\FileValidator',
        'filter' => 'intec\core\validators\FilterValidator',
        'image' => 'intec\core\validators\ImageValidator',
        'in' => 'intec\core\validators\RangeValidator',
        'integer' => [
            'class' => 'intec\core\validators\NumberValidator',
            'integerOnly' => true,
        ],
        'match' => 'intec\core\validators\RegularExpressionValidator',
        'number' => 'intec\core\validators\NumberValidator',
        'required' => 'intec\core\validators\RequiredValidator',
        'safe' => 'intec\core\validators\SafeValidator',
        'string' => 'intec\core\validators\StringValidator',
        'trim' => [
            'class' => 'intec\core\validators\FilterValidator',
            'filter' => 'trim',
            'skipOnArray' => true,
        ],
        'unique' => 'intec\core\validators\UniqueValidator',
        'url' => 'intec\core\validators\UrlValidator',
        'ip' => 'intec\core\validators\IpValidator',
    ];
    /**
     * @var array|string attributes to be validated by this validator. For multiple attributes,
     * please specify them as an array; for single attribute, you may use either a string or an array.
     */
    public $attributes = [];
    /**
     * @var string the user-defined error message. It may contain the following placeholders which
     * will be replaced accordingly by the validator:
     *
     * - `{attribute}`: the label of the attribute being validated
     * - `{value}`: the value of the attribute being validated
     *
     * Note that some validators may introduce other properties for error messages used when specific
     * validation conditions are not met. Please refer to individual class API documentation for details
     * about these properties. By convention, this property represents the primary error message
     * used when the most important validation condition is not met.
     */
    public $message;
    /**
     * @var array|string scenarios that the validator can be applied to. For multiple scenarios,
     * please specify them as an array; for single scenario, you may use either a string or an array.
     */
    public $on = [];
    /**
     * @var array|string scenarios that the validator should not be applied to. For multiple scenarios,
     * please specify them as an array; for single scenario, you may use either a string or an array.
     */
    public $except = [];
    /**
     * @var bool whether this validation rule should be skipped if the attribute being validated
     * already has some validation error according to some previous rules. Defaults to true.
     */
    public $skipOnError = true;
    /**
     * @var bool whether this validation rule should be skipped if the attribute value
     * is null or an empty string.
     */
    public $skipOnEmpty = true;
    /**
     * @var callable a PHP callable that replaces the default implementation of [[isEmpty()]].
     * If not set, [[isEmpty()]] will be used to check if a value is empty. The signature
     * of the callable should be `function ($value)` which returns a boolean indicating
     * whether the value is empty.
     */
    public $isEmpty;
    /**
     * @var callable a PHP callable whose return value determines whether this validator should be applied.
     * The signature of the callable should be `function ($model, $attribute)`, where `$model` and `$attribute`
     * refer to the model and the attribute currently being validated. The callable should return a boolean value.
     *
     * This property is mainly provided to support conditional validation on the server-side.
     * If this property is not set, this validator will be always applied on the server-side.
     *
     * The following example will enable the validator only when the country currently selected is USA:
     *
     * ```php
     * function ($model) {
     *     return $model->country == Country::USA;
     * }
     * ```
     *
     * @see whenClient
     */
    public $when;


    /**
     * Creates a validator object.
     * @param string|\Closure $type the validator type. This can be either:
     *  * a built-in validator name listed in [[builtInValidators]];
     *  * a method name of the model class;
     *  * an anonymous function;
     *  * a validator class name.
     * @param Model $model the data model to be validated.
     * @param array|string $attributes list of attributes to be validated. This can be either an array of
     * the attribute names or a string of comma-separated attribute names.
     * @param array $params initial values to be applied to the validator properties.
     * @return Validator the validator
     */
    public static function createValidator($type, $model, $attributes, $params = [])
    {
        $params['attributes'] = $attributes;

        if ($type instanceof \Closure || $model->hasMethod($type)) {
            // method-based validator
            $params['class'] = __NAMESPACE__ . '\InlineValidator';
            $params['method'] = $type;
        } else {
            if (isset(static::$builtInValidators[$type])) {
                $type = static::$builtInValidators[$type];
            }
            if (is_array($type)) {
                $params = array_merge($type, $params);
            } else {
                $params['class'] = $type;
            }
        }

        return Core::createObject($params);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->attributes = (array) $this->attributes;
        $this->on = (array) $this->on;
        $this->except = (array) $this->except;
    }

    /**
     * Validates the specified object.
     * @param Model $model the data model being validated
     * @param array|null $attributes the list of attributes to be validated.
     * Note that if an attribute is not associated with the validator, or is is prefixed with `!` char - it will be
     * ignored. If this parameter is null, every attribute listed in [[attributes]] will be validated.
     */
    public function validateAttributes($model, $attributes = null)
    {
        if (is_array($attributes)) {
            $newAttributes = [];
            foreach ($attributes as $attribute) {
                if (in_array($attribute, $this->attributes) || in_array('!' . $attribute, $this->attributes)) {
                    $newAttributes[] = $attribute;
                }
            }
            $attributes = $newAttributes;
        } else {
            $attributes = [];
            foreach ($this->attributes as $attribute) {
                $attributes[] = $attribute[0] === '!' ? substr($attribute, 1) : $attribute;
            }
        }

        foreach ($attributes as $attribute) {
            $skip = $this->skipOnError && $model->hasErrors($attribute)
                || $this->skipOnEmpty && $this->isEmpty($model->$attribute);
            if (!$skip) {
                if ($this->when === null || call_user_func($this->when, $model, $attribute)) {
                    $this->validateAttribute($model, $attribute);
                }
            }
        }
    }

    /**
     * Validates a single attribute.
     * Child classes must implement this method to provide the actual validation logic.
     * @param Model $model the data model to be validated
     * @param string $attribute the name of the attribute to be validated.
     */
    public function validateAttribute($model, $attribute)
    {
        $result = $this->validateValue($model->$attribute);
        if (!empty($result)) {
            $this->addError($model, $attribute, $result[0], $result[1]);
        }
    }

    /**
     * Validates a given value.
     * You may use this method to validate a value out of the context of a data model.
     * @param mixed $value the data value to be validated.
     * @param string $error the error message to be returned, if the validation fails.
     * @return bool whether the data is valid.
     */
    public function validate($value, &$error = null)
    {
        $result = $this->validateValue($value);
        if (empty($result)) {
            return true;
        }

        list($message, $params) = $result;
        $params['attribute'] = Core::t('core', 'the input value');
        if (is_array($value)) {
            $params['value'] = 'array()';
        } elseif (is_object($value)) {
            $params['value'] = 'object';
        } else {
            $params['value'] = $value;
        }
        //$error = Core::$app->getI18n()->format($message, $params, Core::$app->language);

        return false;
    }

    /**
     * Validates a value.
     * A validator class can implement this method to support data validation out of the context of a data model.
     * @param mixed $value the data value to be validated.
     * @return array|null the error message and the parameters to be inserted into the error message.
     * Null should be returned if the data is valid.
     * @throws NotSupportedException if the validator does not supporting data validation without a model
     */
    protected function validateValue($value)
    {
        throw new NotSupportedException(get_class($this) . ' does not support validateValue().');
    }

    /**
     * Returns a value indicating whether the validator is active for the given scenario and attribute.
     *
     * A validator is active if
     *
     * - the validator's `on` property is empty, or
     * - the validator's `on` property contains the specified scenario
     *
     * @param string $scenario scenario name
     * @return bool whether the validator applies to the specified scenario.
     */
    public function isActive($scenario)
    {
        return !in_array($scenario, $this->except, true) && (empty($this->on) || in_array($scenario, $this->on, true));
    }

    /**
     * Adds an error about the specified attribute to the model object.
     * This is a helper method that performs message selection and internationalization.
     * @param Model $model the data model being validated
     * @param string $attribute the attribute being validated
     * @param string $message the error message
     * @param array $params values for the placeholders in the error message
     */
    public function addError($model, $attribute, $message, $params = [])
    {
        $params['attribute'] = $model->getAttributeLabel($attribute);
        if (!isset($params['value'])) {
            $value = $model->$attribute;
            if (is_array($value)) {
                $params['value'] = 'array()';
            } elseif (is_object($value) && !method_exists($value, '__toString')) {
                $params['value'] = '(object)';
            } else {
                $params['value'] = $value;
            }
        }

        $model->addError($attribute, Core::$app->getI18n()->format($message, $params, Core::$app->language));
    }

    /**
     * Checks if the given value is empty.
     * A value is considered empty if it is null, an empty array, or an empty string.
     * Note that this method is different from PHP empty(). It will return false when the value is 0.
     * @param mixed $value the value to be checked
     * @return bool whether the value is empty
     */
    public function isEmpty($value)
    {
        if ($this->isEmpty !== null) {
            return call_user_func($this->isEmpty, $value);
        } else {
            return $value === null || $value === [] || $value === '';
        }
    }
}
