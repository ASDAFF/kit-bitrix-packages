<?php
namespace intec\core\base;

use ArrayAccess;
use ArrayObject;
use ArrayIterator;
use ReflectionClass;
use IteratorAggregate;
use intec\core\helpers\Inflector;
use intec\core\validators\RequiredValidator;
use intec\core\validators\Validator;

/**
 * Model это базовый класс модели данных.
 * Class Model
 * @property Validator[] $activeValidators Валидаторы, применяемые к текущему [[$scenario]].
 * Только для чтения.
 * @property array $attributes Значения атрибутов (пара ключ - значение).
 * @property array $errors Массив ошибок для всех аттрибутов. Только для чтения.
 * @property array $firstErrors Массив первых ошибок для аттрибутов. Только для чтения.
 * @property ArrayIterator $iterator Итератор для перемещения свойств в массив. Только для чтения.
 * @property string $scenario Сценарий, в котором находится модель. По умолчанию [[SCENARIO_DEFAULT]].
 * @property ArrayObject|Validator[] $validators Все валидаторы, объявленные в модели.
 * Только для чтения.
 * @package intec\core\base
 * @since 1.0.0
 */
class Model extends Component implements IteratorAggregate, ArrayAccess, Arrayable
{
    use ArrayableTrait;

    /**
     * Название сценария.
     * @since 1.0.0
     */
    const SCENARIO_DEFAULT = 'default';
    /**
     * Событие, возникающее вначале вызова [[validate()]]. Вы можете установить
     * [[ModelEvent::isValid]] в `false` для остановки проверки.
     * @event ModelEvent
     * @since 1.0.0
     */
    const EVENT_BEFORE_VALIDATE = 'beforeValidate';
    /**
     * Событие, возникающее вконце [[validate()]]
     * @event Event
     * @since 1.0.0
     */
    const EVENT_AFTER_VALIDATE = 'afterValidate';

    /**
     * Ошибки проверки.
     * Вид: [Аттрибут => [Ошибки]]
     * @var array
     * @since 1.0.0
     */
    private $_errors;
    /**
     * Список валидаторов.
     * @var ArrayObject
     * @since 1.0.0
     */
    private $_validators;
    /**
     * Текущий сценарий.
     * @var string
     * @since 1.0.0
     */
    private $_scenario = self::SCENARIO_DEFAULT;


    /**
     * Правила проверки.
     * @return array Массив правил проверки.
     * @see scenarios()
     * @since 1.0.0
     */
    public function rules()
    {
        return [];
    }

    /**
     * Возвращает список сценариев.
     * @return array Список сценариев.
     * @since 1.0.0
     */
    public function scenarios()
    {
        $scenarios = [self::SCENARIO_DEFAULT => []];
        foreach ($this->getValidators() as $validator) {
            foreach ($validator->on as $scenario) {
                $scenarios[$scenario] = [];
            }
            foreach ($validator->except as $scenario) {
                $scenarios[$scenario] = [];
            }
        }
        $names = array_keys($scenarios);

        foreach ($this->getValidators() as $validator) {
            if (empty($validator->on) && empty($validator->except)) {
                foreach ($names as $name) {
                    foreach ($validator->attributes as $attribute) {
                        $scenarios[$name][$attribute] = true;
                    }
                }
            } elseif (empty($validator->on)) {
                foreach ($names as $name) {
                    if (!in_array($name, $validator->except, true)) {
                        foreach ($validator->attributes as $attribute) {
                            $scenarios[$name][$attribute] = true;
                        }
                    }
                }
            } else {
                foreach ($validator->on as $name) {
                    foreach ($validator->attributes as $attribute) {
                        $scenarios[$name][$attribute] = true;
                    }
                }
            }
        }

        foreach ($scenarios as $scenario => $attributes) {
            if (!empty($attributes)) {
                $scenarios[$scenario] = array_keys($attributes);
            }
        }

        return $scenarios;
    }

    /**
     * Возвращает наименование формы, которое использует данная модель.
     * @return string Наименование формы данной модели.
     * @see load()
     * @since 1.0.0
     */
    public function formName()
    {
        $reflector = new ReflectionClass($this);
        return $reflector->getShortName();
    }

    /**
     * Возвращает список аттрибутов.
     * @return array Список аттрибутов.
     * @since 1.0.0
     */
    public function attributes()
    {
        $class = new ReflectionClass($this);
        $names = [];
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic()) {
                $names[] = $property->getName();
            }
        }

        return $names;
    }

    /**
     * Возвращает наименования аттрибутов.
     * @return array Наименования аттрибутов
     * Вид: [Аттрибут => Наименование]
     * @see generateAttributeLabel()
     * @since 1.0.0
     */
    public function attributeLabels()
    {
        return [];
    }

    /**
     * Возвращает подсказки для аттрибутов.
     * @return array Подсказки для аттрибутов
     * Вид: [Аттрибут - Подсказка]
     * @since 1.0.0
     */
    public function attributeHints()
    {
        return [];
    }

    /**
     * Запускает проверку данных для модели.
     * @param array $attributeNames Список атрибутов, которые должны быть проверены.
     * Если параметр пустой, то будут проверены поля по сценарию.
     * @param bool $clearErrors Определяет вызов [[clearErrors()]] перед запуском проверки.
     * @return bool Проверка пройдена успешно без ошибок.
     * @throws InvalidParamException Если сценарий неизвестен.
     * @since 1.0.0
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        if ($clearErrors) {
            $this->clearErrors();
        }

        if (!$this->beforeValidate()) {
            return false;
        }

        $scenarios = $this->scenarios();
        $scenario = $this->getScenario();
        if (!isset($scenarios[$scenario])) {
            throw new InvalidParamException("Unknown scenario: $scenario");
        }

        if ($attributeNames === null) {
            $attributeNames = $this->activeAttributes();
        }

        foreach ($this->getActiveValidators() as $validator) {
            $validator->validateAttributes($this, $attributeNames);
        }
        $this->afterValidate();

        return !$this->hasErrors();
    }

    /**
     * Данный метод вызывается перед началом проверки.
     * @return bool Проверка должна быть выполнена. По умолчанию `true`.
     * Если возвращено `false`, то проверка будет остановлена и не пройдена.
     * @since 1.0.0
     */
    public function beforeValidate()
    {
        $event = new ModelEvent;
        $this->trigger(self::EVENT_BEFORE_VALIDATE, $event);

        return $event->isValid;
    }

    /**
     * Этот метод будет вызван после окончания проверки.
     */
    public function afterValidate()
    {
        $this->trigger(self::EVENT_AFTER_VALIDATE);
    }

    /**
     * Возвращает все валидаторы, объявленные в [[rules()]].
     * @return ArrayObject|Validator[] Все валидаторы, объявленные в [[rules()]].
     * @since 1.0.0
     */
    public function getValidators()
    {
        if ($this->_validators === null) {
            $this->_validators = $this->createValidators();
        }
        return $this->_validators;
    }

    /**
     * Возвращает валидаторы, применяемые к текущему [[scenario]].
     * @param string $attribute Аттрибут, валидаторы для которого будут возвращены.
     * Если `null`, валидаторы всех аттрибутов будут возвращены.
     * @return Validator[] Валидаторы, применяемые к текущему [[scenario]].
     * @since 1.0.0
     */
    public function getActiveValidators($attribute = null)
    {
        $validators = [];
        $scenario = $this->getScenario();
        foreach ($this->getValidators() as $validator) {
            if ($validator->isActive($scenario) && ($attribute === null || in_array($attribute, $validator->attributes, true))) {
                $validators[] = $validator;
            }
        }
        return $validators;
    }

    /**
     * Создает валидаторы, базирующихся на правилах из [[rules()]].
     * @return ArrayObject Валидаторы
     * @throws InvalidConfigException Если какое-либо из правил валидации неверно.
     * @since 1.0.0
     */
    public function createValidators()
    {
        $validators = new ArrayObject;
        foreach ($this->rules() as $rule) {
            if ($rule instanceof Validator) {
                $validators->append($rule);
            } elseif (is_array($rule) && isset($rule[0], $rule[1])) { // attributes, validator type
                $validator = Validator::createValidator($rule[1], $this, (array) $rule[0], array_slice($rule, 2));
                $validators->append($validator);
            } else {
                throw new InvalidConfigException('Invalid validation rule: a rule must specify both attribute names and validator type.');
            }
        }
        return $validators;
    }

    /**
     * Возвращает значение, которое отражает, является ли аттрибут обязательным.
     * @param string $attribute Аттрибут.
     * @return bool Аттрибут обязательный.
     * @since 1.0.0
     */
    public function isAttributeRequired($attribute)
    {
        foreach ($this->getActiveValidators($attribute) as $validator) {
            if ($validator instanceof RequiredValidator && $validator->when === null) {
                return true;
            }
        }
        return false;
    }

    /**
     * Возвращает значение, которое отражает, является ли аттрибут безопасным для массовых операций.
     * @param string $attribute Аттрибут.
     * @return bool Аттрибут безопасен для массовых операций.
     * @see safeAttributes()
     * @since 1.0.0
     */
    public function isAttributeSafe($attribute)
    {
        return in_array($attribute, $this->safeAttributes(), true);
    }

    /**
     * Возвращает значение, которое отражает активность аттрибута в текущем сценарии.
     * @param string $attribute Аттрибут.
     * @return bool Аттрибут активен в текущем сценарии.
     * @see activeAttributes()
     * @since 1.0.0
     */
    public function isAttributeActive($attribute)
    {
        return in_array($attribute, $this->activeAttributes(), true);
    }

    /**
     * Возвращает наименование для аттрибута.
     * @param string $attribute Аттрибут.
     * @return string Наименование аттрибута.
     * @see generateAttributeLabel()
     * @see attributeLabels()
     * @since 1.0.0
     */
    public function getAttributeLabel($attribute)
    {
        $labels = $this->attributeLabels();
        return isset($labels[$attribute]) ? $labels[$attribute] : $this->generateAttributeLabel($attribute);
    }

    /**
     * Возвращает подсказки для аттрибута.
     * @param string $attribute Аттрибут.
     * @return string Подсказка для аттрибута.
     * @see attributeHints()
     * @since 1.0.0
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : '';
    }

    /**
     * Возвращает значение, которое сообщает о наличии ошибок при проверке.
     * @param string|null $attribute Аттрибут.
     * @return bool Имеются ошибки.
     * @since 1.0.0
     */
    public function hasErrors($attribute = null)
    {
        return $attribute === null ? !empty($this->_errors) : isset($this->_errors[$attribute]);
    }

    /**
     * Возвращает все ошибки для аттрибута или аттрибутов.
     * @param string $attribute Аттрибут. Если `null`, то все аттрибуты.
     * @return array Все ошибки для аттрибута или аттрибутов. Пустой масиив - ошибок нет.
     * @see getFirstErrors()
     * @see getFirstError()
     * @since 1.0.0
     */
    public function getErrors($attribute = null)
    {
        if ($attribute === null) {
            return $this->_errors === null ? [] : $this->_errors;
        }
        return isset($this->_errors[$attribute]) ? $this->_errors[$attribute] : [];
    }

    /**
     * Возвращает первые ошибки для каждого аттрибута.
     * @return array Первые ошибки каждого аттрибута.
     * Вид: [Аттрибут => Ошибка]
     * @see getErrors()
     * @see getFirstError()
     * @since 1.0.0
     */
    public function getFirstErrors()
    {
        if (empty($this->_errors)) {
            return [];
        }

        $errors = [];
        foreach ($this->_errors as $name => $es) {
            if (!empty($es)) {
                $errors[$name] = reset($es);
            }
        }
        return $errors;
    }

    /**
     * Возвращает первую ошибку для аттрибута.
     * @param string $attribute Аттрибут.
     * @return string Сообщение об ошибке. Будет возвращен `null`, если ошибки нет.
     * @see getErrors()
     * @see getFirstErrors()
     * @since 1.0.0
     */
    public function getFirstError($attribute)
    {
        return isset($this->_errors[$attribute]) ? reset($this->_errors[$attribute]) : null;
    }

    /**
     * Добавляет новую ошибку для аттрибута.
     * @param string $attribute Аттрибут.
     * @param string $error Сообщение.
     * @since 1.0.0
     */
    public function addError($attribute, $error = '')
    {
        $this->_errors[$attribute][] = $error;
    }

    /**
     * Добавляет список ошибок.
     * @param array $items Список ошибок. Ключи массива должны быть аттрибутами.
     * Значения должны быть строками или массивом строк, если аттрибут имеет несколько ошибок.
     * @since 1.0.0
     */
    public function addErrors(array $items)
    {
        foreach ($items as $attribute => $errors) {
            if (is_array($errors)) {
                foreach ($errors as $error) {
                    $this->addError($attribute, $error);
                }
            } else {
                $this->addError($attribute, $errors);
            }
        }
    }

    /**
     * Удаляет ошибки аттрибута или всех аттрибутов.
     * @param string $attribute Аттрибут. Если `null`, то удаляются все ошибки.
     */
    public function clearErrors($attribute = null)
    {
        if ($attribute === null) {
            $this->_errors = [];
        } else {
            unset($this->_errors[$attribute]);
        }
    }

    /**
     * Генерирует наименование аттрибута.
     * @param string $name Аттрибут.
     * @return string Наименование аттрибута.
     * @since 1.0.0
     */
    public function generateAttributeLabel($name)
    {
        return Inflector::camel2words($name, true);
    }

    /**
     * Возвращает значения аттрибутов.
     * @param array $names Список аттрибутов, значения которых необходимо вернуть.
     * Если `null`, то будут возвращены все аттрибуты из [[attributes()]].
     * @param array $except Список аттрибутов, значения которых не должны быть возвращены.
     * @return array Значения аттрибутов.
     * Вид: [Аттрибут => Значение].
     * @since 1.0.0
     */
    public function getAttributes($names = null, $except = [])
    {
        $values = [];
        if ($names === null) {
            $names = $this->attributes();
        }
        foreach ($names as $name) {
            $values[$name] = $this->$name;
        }
        foreach ($except as $name) {
            unset($values[$name]);
        }

        return $values;
    }

    /**
     * Устанавливает аттрибуты массово.
     * @param array $values Значения аттрибутов.
     * Вид: [Аттрибут => Значение]
     * @param bool $safeOnly Только безопасные аттрибуты.
     * @see safeAttributes()
     * @see attributes()
     * @since 1.0.0
     */
    public function setAttributes($values, $safeOnly = true)
    {
        if (is_array($values)) {
            $attributes = array_flip($safeOnly ? $this->safeAttributes() : $this->attributes());
            foreach ($values as $name => $value) {
                if (isset($attributes[$name])) {
                    $this->$name = $value;
                } elseif ($safeOnly) {
                    $this->onUnsafeAttribute($name, $value);
                }
            }
        }
    }

    /**
     * Данный метод вызывается, когда небезопасный аттрибут используется в массовых операциях.
     * @param string $name Аттрибут.
     * @param mixed $value Значение аттрибута.
     * @since 1.0.0
     */
    public function onUnsafeAttribute($name, $value)
    {

    }

    /**
     * Сценарий, который используется в модели.
     * @return string Сценарий. По умолчанию [[SCENARIO_DEFAULT]].
     * @since 1.0.0
     */
    public function getScenario()
    {
        return $this->_scenario;
    }

    /**
     * Устанавливает сценарий для модели.
     * @param string $value Сценарий.
     * @since 1.0.0
     */
    public function setScenario($value)
    {
        $this->_scenario = $value;
    }

    /**
     * Возвращает аттрибуте, которые являются безопасными в текущем сценарии.
     * @return string[] Безопасные аттрибуты.
     * @since 1.0.0
     */
    public function safeAttributes()
    {
        $scenario = $this->getScenario();
        $scenarios = $this->scenarios();
        if (!isset($scenarios[$scenario])) {
            return [];
        }
        $attributes = [];
        foreach ($scenarios[$scenario] as $attribute) {
            if ($attribute[0] !== '!' && !in_array('!' . $attribute, $scenarios[$scenario])) {
                $attributes[] = $attribute;
            }
        }

        return $attributes;
    }

    /**
     * Возвращает аттрибуты для валидации, определенные текущим сценарием.
     * @return string[] Активные аттрибуты.
     * @since 1.0.0
     */
    public function activeAttributes()
    {
        $scenario = $this->getScenario();
        $scenarios = $this->scenarios();
        if (!isset($scenarios[$scenario])) {
            return [];
        }
        $attributes = $scenarios[$scenario];
        foreach ($attributes as $i => $attribute) {
            if ($attribute[0] === '!') {
                $attributes[$i] = substr($attribute, 1);
            }
        }

        return $attributes;
    }

    /**
     * Загружает данные модели из массива.
     * @param array $data Массив с данными.
     * @param string $formName Наименование формы.
     * Если не используется, то берется из [[formName()]].
     * @return bool Форма найдена в массиве данных.
     * @since 1.0.0
     */
    public function load($data, $formName = null)
    {
        $scope = $formName === null ? $this->formName() : $formName;
        if ($scope === '' && !empty($data)) {
            $this->setAttributes($data);

            return true;
        } elseif (isset($data[$scope])) {
            $this->setAttributes($data[$scope]);

            return true;
        }
        return false;
    }

    /**
     * Загружает данные для массива моделей
     * @param array $models Модели для заполнения.
     * @param array $data Массив данных.
     * @param string $formName Наименование формы.
     * Если не используется, то берется из [[formName()]] первой модели.
     * @return bool Хотя бы одна модель была заполнена.
     * @since 1.0.0
     */
    public static function loadMultiple($models, $data, $formName = null)
    {
        if ($formName === null) {
            /* @var $first Model */
            $first = reset($models);
            if ($first === false) {
                return false;
            }
            $formName = $first->formName();
        }

        $success = false;
        foreach ($models as $i => $model) {
            /* @var $model Model */
            if ($formName == '') {
                if (!empty($data[$i])) {
                    $model->load($data[$i], '');
                    $success = true;
                }
            } elseif (!empty($data[$formName][$i])) {
                $model->load($data[$formName][$i], '');
                $success = true;
            }
        }

        return $success;
    }

    /**
     * Проверяет список моделей.
     * @param array $models Модели для проверки.
     * @param array $attributeNames Список аттрибутов, которые должны быть проверены.
     * Если `null`, то проверка будет идти по доступным правилам из [[rules()]].
     * @return bool Все модели проверены.
     * @since 1.0.0
     */
    public static function validateMultiple($models, $attributeNames = null)
    {
        $valid = true;
        /* @var $model Model */
        foreach ($models as $model) {
            $valid = $model->validate($attributeNames) && $valid;
        }

        return $valid;
    }

    /**
     * Список полей, которые будут возвращены функцией toArray().
     * @return array Список полей.
     * @see toArray()
     * @since 1.0.0
     */
    public function fields()
    {
        $fields = $this->attributes();

        return array_combine($fields, $fields);
    }

    /**
     * Возвращает итератор массива.
     * @return ArrayIterator Итератор.
     * @since 1.0.0
     */
    public function getIterator()
    {
        $attributes = $this->getAttributes();
        return new ArrayIterator($attributes);
    }

    /**
     * Базовый метод итератора.
     * @param mixed $offset Смещение.
     * @return bool Смещение существует.
     * @since 1.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }

    /**
     * Базовый метод итератора.
     * @param mixed $offset Смещение.
     * @return mixed Значение смещения.
     * @since 1.0.0
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    /**
     * Базовый метод итератора.
     * @param int $offset Смещение.
     * @param mixed $item Устанавливаемое значение.
     * @since 1.0.0
     */
    public function offsetSet($offset, $item)
    {
        $this->$offset = $item;
    }

    /**
     * Базовый метод итератора.
     * @param mixed $offset Удаляемое смещение.
     * @since 1.0.0
     */
    public function offsetUnset($offset)
    {
        $this->$offset = null;
    }
}
