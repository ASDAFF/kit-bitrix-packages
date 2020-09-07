<?php
namespace intec\constructor\models\build;
IncludeModuleLangFile(__FILE__);

use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\RegExp;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\core\net\Url;
use intec\constructor\models\Build;
use intec\constructor\models\build\Template;

trait ConditionsTrait
{
    /**
     * Возвращает список типов условия.
     * @return array
     */
    public static function getConditionTypes()
    {
        return [
            static::CONDITION_TYPE_GROUP => GetMessage('intec.constructor.models.builds.condition-trait.type.group'),
            static::CONDITION_TYPE_PATH => GetMessage('intec.constructor.models.builds.condition-trait.type.path'),
            static::CONDITION_TYPE_MATCH => GetMessage('intec.constructor.models.builds.condition-trait.type.match'),
            static::CONDITION_TYPE_PARAMETER_GET => GetMessage('intec.constructor.models.builds.condition-trait.type.parameter.get'),
            static::CONDITION_TYPE_PARAMETER_PAGE => GetMessage('intec.constructor.models.builds.condition-trait.type.parameter.page'),
            static::CONDITION_TYPE_PARAMETER_TEMPLATE => GetMessage('intec.constructor.models.builds.condition-trait.type.parameter.template'),
            static::CONDITION_TYPE_EXPRESSION => GetMessage('intec.constructor.models.builds.condition-trait.type.expression'),
            static::CONDITION_TYPE_SITE => GetMessage('intec.constructor.models.builds.condition-trait.type.site')
        ];
    }

    /**
     * Возвращает список значений типов условия.
     * @return array
     */
    public static function getConditionTypesValues()
    {
        $values = static::getConditionTypes();
        $values = ArrayHelper::getKeys($values);
        return $values;
    }

    /**
     * Возвращает список сравнений регулярного выражения.
     * @return array
     */
    public static function getConditionMatches()
    {
        return [
            static::CONDITION_MATCH_URL => GetMessage('intec.constructor.models.builds.condition-trait.match.url'),
            static::CONDITION_MATCH_SCHEME => GetMessage('intec.constructor.models.builds.condition-trait.match.scheme'),
            static::CONDITION_MATCH_HOST => GetMessage('intec.constructor.models.builds.condition-trait.match.host'),
            static::CONDITION_MATCH_PATH => GetMessage('intec.constructor.models.builds.condition-trait.match.path'),
            static::CONDITION_MATCH_QUERY => GetMessage('intec.constructor.models.builds.condition-trait.match.query')
        ];
    }

    /**
     * Возвращает список значений сравнений регулярного выражения.
     * @return array
     */
    public static function getConditionMatchesValues()
    {
        $values = static::getConditionMatches();
        $values = ArrayHelper::getKeys($values);
        return $values;
    }

    /**
     * Возвращает список возможных логических операторов.
     * @return array
     */
    public static function getConditionLogics()
    {
        return [
            static::CONDITION_LOGIC_EQUAL => GetMessage('intec.constructor.models.builds.condition-trait.logic.equal'),
            static::CONDITION_LOGIC_NOT_EQUAL => GetMessage('intec.constructor.models.builds.condition-trait.logic.not.equal'),
            static::CONDITION_LOGIC_MORE => GetMessage('intec.constructor.models.builds.condition-trait.logic.more'),
            static::CONDITION_LOGIC_MORE_OR_EQUAL => GetMessage('intec.constructor.models.builds.condition-trait.logic.more.or.equal'),
            static::CONDITION_LOGIC_LESS => GetMessage('intec.constructor.models.builds.condition-trait.logic.less'),
            static::CONDITION_LOGIC_LESS_OR_EQUAL => GetMessage('intec.constructor.models.builds.condition-trait.logic.less.or.equal')
        ];
    }

    /**
     * Возвращает список значений возможных логических операторов.
     * @return array
     */
    public static function getConditionLogicsValues()
    {
        $values = static::getConditionLogics();
        $values = ArrayHelper::getKeys($values);
        return $values;
    }

    /**
     * Возвращает список операторов условия.
     * @return array
     */
    public static function getConditionOperators()
    {
        return [
            static::CONDITION_OPERATOR_AND => GetMessage('intec.constructor.models.builds.condition-trait.operator.and'),
            static::CONDITION_OPERATOR_OR => GetMessage('intec.constructor.models.builds.condition-trait.operator.or')
        ];
    }

    /**
     * Возвращает список значений операторов условия.
     * @return array
     */
    public static function getConditionOperatorsValues()
    {
        $values = static::getConditionOperators();
        $values = ArrayHelper::getKeys($values);
        return $values;
    }

    /**
     * @param string|null $directory Префикс пути до сайта. Директория, в которой находится сайт относительно корня сайта.
     * @param string|null $path Путь до страницы относительно корня сайта (SITE_DIR).
     * @param string|null $url Url адрес страницы.
     * @param array|null $parametersGet GET параметры.
     * @param array|null $parametersPage Параметры страницы.
     * @param array|null $parametersTemplate Параметры шаблона.
     * @param string|null $site Сайт.
     * @return bool
     */
    public function isConditioned($directory = null, $path = null, $url = null, $parametersGet = null, $parametersPage = null, $parametersTemplate = null, $site = SITE_ID)
    {
        $condition = $this->condition;

        if (!Type::isArray($condition))
            $condition = [];

        $condition['type'] = 'group';

        if ($directory === null)
            $directory = SITE_DIR;

        if ($path === null) {
            $path = '/'.Core::$app->request->getPathInfo();
            $path = RegExp::replaceBy(
                '/^'.RegExp::escape($directory).'/',
                '/',
                $path
            );
        }

        if ($url === null)
            $url = Core::$app->request->getAbsoluteUrl();

        $url = new Url($url);
        $url->setPathString(RegExp::replaceBy(
            '/^'.RegExp::escape($directory).'/',
            '/',
            $url->getPathString()
        ));

        if ($parametersGet === null)
            $parametersGet = Core::$app->request->get();

        $build = Build::getCurrent();

        if ($parametersPage === null)
            $parametersPage = $build
                ->getPage()
                ->getProperties()
                ->asArray();

        if (!($this instanceof Template))
            if ($parametersTemplate === null) {
                $template = $build->getTemplate();

                if (!empty($template))
                    $parametersTemplate = $template
                        ->getPropertiesValues()
                        ->asArray();
            }

        if ($parametersTemplate === null)
            $parametersTemplate = [];

        $function = function ($condition) use (&$function, &$path, &$url, &$parametersGet, &$parametersPage, &$parametersTemplate, &$site) {
            $type = ArrayHelper::getValue($condition, 'type', static::CONDITION_TYPE_GROUP);
            $value = ArrayHelper::getValue($condition, 'value');
            $operator = ArrayHelper::getValue($condition, 'operator', static::CONDITION_OPERATOR_OR);
            $result = ArrayHelper::getValue($condition, 'result', true);
            $result = Type::toInteger($result);
            $result = Type::toBoolean($result);
            $conditions = ArrayHelper::getValue($condition, 'conditions');
            $return = true;

            if ($type == static::CONDITION_TYPE_GROUP) {
                if (Type::isArray($conditions))
                    foreach ($conditions as $child) {
                        if ($operator == static::CONDITION_OPERATOR_AND) {
                            /** Все условия (выполнены/не выполнены) */
                            $return = $return && ($result ? $function($child) : !$function($child));
                        } else {
                            $return = $function($child);

                            if ($result) {
                                /** Одно из условий выполнено */
                                if ($return) break;
                            } else {
                                /** Одно из условий не выполнено */
                                if (!$return) {
                                    $return = true;
                                    break;
                                }

                                $return = false;
                            }
                        }
                    }
            } else if ($type == static::CONDITION_TYPE_PATH) {
                $return = $path === $value;
            } else if ($type == static::CONDITION_TYPE_PARAMETER_GET) {
                $return = false;
                $key = ArrayHelper::getValue($condition, 'key');

                if (!empty($key)) {
                    $return = ArrayHelper::getValue($parametersGet, $key) === $value;
                }
            } else if (
                $type == static::CONDITION_TYPE_PARAMETER_PAGE ||
                $type == static::CONDITION_TYPE_PARAMETER_TEMPLATE
            ) {
                $return = false;
                $key = ArrayHelper::getValue($condition, 'key');
                $parameters = $parametersTemplate;
                $logic = ArrayHelper::getValue($condition, 'logic');

                if ($type == static::CONDITION_TYPE_PARAMETER_PAGE)
                    $parameters = $parametersPage;

                $parameter = ArrayHelper::getValue($parameters, $key);

                if (!empty($key))
                    if ($logic == static::CONDITION_LOGIC_MORE) {
                        $return = $parameter > $value;
                    } else if ($logic == static::CONDITION_LOGIC_MORE_OR_EQUAL) {
                        $return = $parameter >= $value;
                    } else if ($logic == static::CONDITION_LOGIC_LESS) {
                        $return = $parameter < $value;
                    } else if ($logic == static::CONDITION_LOGIC_LESS_OR_EQUAL) {
                        $return = $parameter <= $value;
                    } else if ($logic == static::CONDITION_LOGIC_NOT_EQUAL) {
                        $return = $parameter != $value;
                    } else {
                        $return = $parameter == $value;
                    }
            } else if ($type == static::CONDITION_TYPE_MATCH) {
                $match = ArrayHelper::getValue($condition, 'match');
                $match = ArrayHelper::fromRange(static::getConditionMatchesValues(), $match, true);

                switch ($match) {
                    case static::CONDITION_MATCH_URL: $match = $url->build(); break;
                    case static::CONDITION_MATCH_SCHEME: $match = $url->getScheme(); break;
                    case static::CONDITION_MATCH_HOST: $match = $url->getHost(); break;
                    case static::CONDITION_MATCH_PATH: $match = $url->getPathString(); break;
                    case static::CONDITION_MATCH_QUERY: $match = $url->getQueryString(); break;
                }

                $return = RegExp::isMatchBy('/'.$value.'/', $match);
            } else if ($type == static::CONDITION_TYPE_EXPRESSION) {
                $return = true;
            } else if ($type == static::CONDITION_TYPE_SITE) {
                if (empty($site)) {
                    $return = false;
                } else {
                    $return = $site == $value;
                }
            }

            if (!$result && $type != static::CONDITION_TYPE_GROUP)
                $return = !$return;

            return $return;
        };

        return $function($condition);
    }
}