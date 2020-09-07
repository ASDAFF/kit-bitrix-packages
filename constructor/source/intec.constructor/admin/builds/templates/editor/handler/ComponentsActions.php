<?php
namespace intec\constructor\handlers;

use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Encoding;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\core\handling\Actions;
use intec\constructor\models\Build;
use intec\constructor\models\build\Template;
use Bitrix\Main\Loader;
use CComponentParamsManager;
use CComponentUtil;

class ComponentsActions extends Actions
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

    public function actionView()
    {
        global $APPLICATION;

        $_SESSION['SESS_CLEAR_CACHE'] = 'Y';

        $request = Core::$app->request;
        $code = $request->post('code');
        $template = $request->post('template');
        $parameters = $request->post('parameters');

        if (empty($code))
            exit();

        if (empty($template))
            $template = '';

        if (!Type::isArray($parameters))
            $parameters = [];

        Html::setIdentifier(microtime(true) * 10000);

        $code = StringHelper::convert($code, null, Encoding::UTF8);
        $template = StringHelper::convert($template, null, Encoding::UTF8);
        $parameters = ArrayHelper::convertEncoding($parameters, null, Encoding::UTF8);

        $APPLICATION->ShowAjaxHead();
        $APPLICATION->includeComponent(
            $code,
            $template,
            $parameters,
            false,
            [
                'HIDE_ICONS' => 'Y'
            ]
        );

        $_SESSION['SESS_CLEAR_CACHE'] = 'N';

        exit();
    }

    public function actionProperties()
    {
        Loader::includeModule("fileman");

        $request = Core::$app->request;
        $component = $request->post('component');
        $template = $request->post('template');
        $properties = $request->post('properties');
        $clear = $request->post('clear');
        $clear = $clear == 1;

        $component = StringHelper::convert($component, null, Encoding::UTF8);
        $template = StringHelper::convert($template, null, Encoding::UTF8);

        if (!Type::isArray($properties))
            $properties = [];

        $properties = ArrayHelper::convertEncoding($properties, null, Encoding::UTF8);
        $parameters = CComponentParamsManager::GetComponentProperties(
            $component,
            $template,
            $this->build->code,
            $properties
        );

        $description = \CComponentUtil::GetComponentDescr($component);

        $result = [];
        $result['name'] = ArrayHelper::getValue($description, 'NAME');
        $result['description'] = ArrayHelper::getValue($description, 'DESCRIPTION');
        $result['scripts'] = [];
        $result['templates'] = [];
        $result['template'] = $template;
        $result['groups'] = [];

        $data = ArrayHelper::getValue($parameters, 'templates');
        $templates = [];

        if (Type::isArray($data))
            foreach ($data as $template) {
                $array = [
                    'code' => ArrayHelper::getValue($template, 'NAME'),
                    'name' => ArrayHelper::getValue($template, 'DISPLAY_NAME')
                ];

                $templates[$array['code']] = $array;
            }

        $data = ArrayHelper::getValue($parameters, 'groups');
        $groups = [];
        $groups['COMPONENT_TEMPLATE'] = null;

        if (Type::isArray($data))
            foreach ($data as $group) {
                $array = [
                    'code' => $group['ID'],
                    'name' => $group['NAME'],
                    'sort' => Type::toInteger($group['SORT']),
                    'parameters' => []
                ];

                $groups[$array['code']] = $array;
            }

        $groups['COMPONENT_TEMPLATE'] = [
            'code' => 'COMPONENT_TEMPLATE',
            'name' => GetMessage('action.properties.group.template'),
            'sort' => 0
        ];

        if (empty($groups['ADDITIONAL_SETTINGS']))
            $groups['ADDITIONAL_SETTINGS'] = [
                'code' => 'ADDITIONAL_SETTINGS',
                'name' => GetMessage('action.properties.group.additional-settings'),
                'parameters' => [],
                'sort' => 700
            ];

        $data = ArrayHelper::getValue($parameters, 'parameters');
        $parameters = [];
        $scripts = [];
        $types = [
            'CHECKBOX',
            'STRING',
            'LIST',
            'CUSTOM',
            'COLORPICKER'
        ];

        if (Type::isArray($data))
            foreach ($data as $parameter) {
                $group = ArrayHelper::getValue($parameter, 'PARENT');
                $array = [
                    'code' => ArrayHelper::getValue($parameter, 'ID'),
                    'name' => ArrayHelper::getValue($parameter, 'NAME'),
                    'type' => ArrayHelper::getValue($parameter, 'TYPE'),
                    'default' => ArrayHelper::getValue($parameter, 'DEFAULT'),
                    'multiple' => ArrayHelper::getValue($parameter, 'MULTIPLE') == 'Y',
                    'refresh' => ArrayHelper::getValue($parameter, 'REFRESH') == 'Y',
                    'hidden' => ArrayHelper::getValue($parameter, 'HIDDEN') == 'Y',
                    'value' => null,
                    'raw' => $parameter
                ];

                $array['value'] = ArrayHelper::getValue($properties, $array['code']);

                if ($array['value'] === null)
                    $array['value'] = $array['default'];

                if (!ArrayHelper::isIn($array['type'], $types))
                    $array['type'] = 'STRING';

                if ($array['type'] == 'LIST') {
                    $values = ArrayHelper::getValue($parameter, 'VALUES');
                    $array['values'] = [];

                    if (Type::isArray($values))
                        foreach ($values as $value => $name)
                            $array['values'][] = [
                                'value' => $value,
                                'name' => $name
                            ];

                    $array['extended'] = ArrayHelper::getValue($parameter, 'ADDITIONAL_VALUES') == 'Y';
                } else if ($array['type'] == 'CUSTOM') {
                    $array['javascript'] = [
                        'file' => ArrayHelper::getValue($parameter, 'JS_FILE'),
                        'event' => ArrayHelper::getValue($parameter, 'JS_EVENT'),
                        'data' => ArrayHelper::getValue($parameter, 'JS_DATA')
                    ];

                    if (empty($array['javascript']['file']) || empty($array['javascript']['event']))
                        continue;

                    if (!ArrayHelper::isIn($array['javascript']['file'], $scripts))
                        $scripts[] = $array['javascript']['file'];
                } else if ($array['type'] == 'FILE') {
                    $array['type'] = 'STRING';
                } else if ($array['type'] == 'CHECKBOX') {
                    unset($array['multiple']);
                }

                $group = ArrayHelper::getValue($groups, $group);

                if (empty($group)) {
                    $group = ArrayHelper::getValue($groups, 'ADDITIONAL_SETTINGS');
                }

                if (!empty($group))
                    $groups[$group['code']]['parameters'][] = $array;

                $parameters[$array['code']] = $array;
            }

        if (!$clear)
            foreach ($properties as $code => $value) {
                if (ArrayHelper::keyExists($code, $parameters))
                    continue;

                if (empty($value) && !Type::isNumeric($value))
                    continue;

                $groups['ADDITIONAL_SETTINGS']['parameters'][] = [
                    'code' => $code,
                    'value' => $value,
                    'hidden' => true
                ];
            }

        if (count($groups['ADDITIONAL_SETTINGS']['parameters']) == 0)
            unset($groups['ADDITIONAL_SETTINGS']);

        $result['scripts'] = ArrayHelper::getValues($scripts);
        $result['templates'] = ArrayHelper::getValues($templates);
        $result['groups'] = ArrayHelper::getValues($groups);

        return $result;
    }

    public function actionList()
    {
        /**
         * Возвращает модель раздела из данных.
         * @param $code
         * @param $data
         * @return array|null
         */
        $getSection = function ($code, $data) use (&$getSections, &$getComponent, &$getComponents) {
            $information = ArrayHelper::getValue($data, '@');

            if (empty($code))
                return null;

            if (!Type::isArray($information))
                return null;

            $result = [];
            $result['code'] = $code;
            $result['name'] = ArrayHelper::getValue($information, 'NAME');
            $result['sort'] = Type::toInteger(
                ArrayHelper::getValue($information, 'SORT')
            );

            $result['sections'] = $getSections($data);
            $result['components'] = $getComponents($data);

            return $result;
        };

        /**
         * Возвращает список моделей раздела из данных.
         * @param $data
         * @return array
         */
        $getSections = function ($data) use (&$getSection) {
            $result = [];
            $list = ArrayHelper::getValue($data, '#');

            if (Type::isArray($list))
                foreach ($list as $code => $item) {
                    $item = $getSection($code, $item);

                    if ($item !== null)
                        $result[] = $item;
                }

            return $result;
        };

        /**
         * Возвращает модель компонента из данных.
         * @param $code
         * @param $data
         * @return array
         */
        $getComponent = function ($code, $data) {
            $result = [];

            if (empty($code))
                return null;

            $result['code'] = $code;
            $result['name'] = ArrayHelper::getValue($data, 'TITLE');
            $result['namespace'] = ArrayHelper::getValue($data, 'NAMESPACE');
            $result['description'] = ArrayHelper::getValue($data, 'DESCRIPTION');
            $result['complex'] = ArrayHelper::getValue($data, 'COMPLEX') == 'Y' ? true : false;
            $result['sort'] = Type::toInteger(ArrayHelper::getValue($data, 'SORT'));

            return $result;
        };

        /**
         * Возвращает список моделей компонента из данных.
         * @param $data
         * @return array
         */
        $getComponents = function ($data) use (&$getComponent) {
            $result = [];
            $list = ArrayHelper::getValue($data, '*');

            if (Type::isArray($list))
                foreach ($list as $code => $item) {
                    $item = $getComponent($code, $item);

                    if ($item !== null)
                        $result[] = $item;
                }

            return $result;
        };

        $tree = CComponentUtil::GetComponentsTree();
        $result = $getSections($tree);

        return $result;
    }
}