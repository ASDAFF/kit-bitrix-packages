<?php
namespace intec\template\pages;

use Bitrix\Main\Loader;
use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Encoding;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\core\handling\Actions;

class FormsActions extends Actions
{
    public function actionGet()
    {
        /** @var /CMain $APPLICATION */
        global $APPLICATION;

        $request = Core::$app->request;

        $id = $request->get('id');
        $template = $request->get('template');
        $parameters = $request->get('parameters');
        $fields = $request->post('fields');

        if (empty($id))
            return;

        if (empty($template))
            $template = '.default';

        if (!Type::isArray($parameters))
            $parameters = [];

        $id = StringHelper::convert($id, null, Encoding::UTF8);
        $template = StringHelper::convert($template, null, Encoding::UTF8);
        $parameters = ArrayHelper::convertEncoding($parameters, null, Encoding::UTF8);

        foreach ($parameters as $key => $parameter)
            if (StringHelper::startsWith($key, '~'))
                unset($parameters[$key]);

        if (Loader::includeModule('form')) {
            if (Type::isArray($fields)) {
                $fields = ArrayHelper::convertEncoding($fields, null, Encoding::UTF8);

                unset($_POST['fields']);
                unset($_REQUEST['fields']);

                $_POST = ArrayHelper::merge($_POST, $fields);
                $_REQUEST = ArrayHelper::merge($_REQUEST, $fields);
                $_REQUEST['WEB_FORM_ID'] = $_POST['WEB_FORM_ID'] = $id;
                $_REQUEST['web_form_submit'] = $_POST['web_form_submit'] = 'SUBMIT';
            }

            $parameters = ArrayHelper::merge([
                'SEF_MODE' => 'N',
                'START_PAGE' => 'new',
                'SHOW_LIST_PAGE' => 'N',
                'SHOW_EDIT_PAGE' => 'N',
                'SHOW_VIEW_PAGE' => 'N',
                'SUCCESS_URL' => '',
                'SHOW_ANSWER_VALUE' => 'N',
                'SHOW_ADDITIONAL' => 'N',
                'SHOW_STATUS' => 'Y',
                'EDIT_ADDITIONAL' => 'N',
                'EDIT_STATUS' => 'N',
                'NOT_SHOW_FILTER' => array(),
                'NOT_SHOW_TABLE' => array(),
                'CHAIN_ITEM_TEXT' => '',
                'CHAIN_ITEM_LINK' => '',
                'IGNORE_CUSTOM_TEMPLATE' => 'N',
                'USE_EXTENDED_ERRORS' => 'Y',
                'CACHE_TYPE' => 'A',
                'CACHE_TIME' => '3600',
                'AJAX_OPTION_ADDITIONAL' => 'FORM'
            ], $parameters, [
                'WEB_FORM_ID' => $id,
                'AJAX_MODE' => 'Y',
                'AJAX_OPTION_SHADOW' => 'N',
                'AJAX_OPTION_JUMP' => 'N',
                'AJAX_OPTION_STYLE' => 'Y'
            ]);

            $APPLICATION->ShowAjaxHead();
            $APPLICATION->IncludeComponent(
                'bitrix:form.result.new',
                $template,
                $parameters,
                null,
                ['HIDE_ICONS' => 'Y']
            );
        } else if (Loader::includeModule('intec.startshop')) {
            if (Type::isArray($fields)) {
                $fields = ArrayHelper::convertEncoding($fields, null, Encoding::UTF8);

                unset($_POST['fields']);
                unset($_REQUEST['fields']);

                $_POST = ArrayHelper::merge($_POST, $fields);
                $_REQUEST = ArrayHelper::merge($_REQUEST, $fields);
                $_REQUEST['FORM_ID'] = $_POST['FORM_ID'] = $id;
            }

            $parameters = ArrayHelper::merge([
                'SEF_MODE' => 'N',
                'START_PAGE' => 'new',
                'SHOW_LIST_PAGE' => 'N',
                'SHOW_EDIT_PAGE' => 'N',
                'SHOW_VIEW_PAGE' => 'N',
                'SUCCESS_URL' => '',
                'SHOW_ANSWER_VALUE' => 'N',
                'SHOW_ADDITIONAL' => 'N',
                'SHOW_STATUS' => 'Y',
                'EDIT_ADDITIONAL' => 'N',
                'EDIT_STATUS' => 'N',
                'NOT_SHOW_FILTER' => array(),
                'NOT_SHOW_TABLE' => array(),
                'CHAIN_ITEM_TEXT' => '',
                'CHAIN_ITEM_LINK' => '',
                'IGNORE_CUSTOM_TEMPLATE' => 'N',
                'USE_EXTENDED_ERRORS' => 'Y',
                'CACHE_TYPE' => 'A',
                'CACHE_TIME' => '3600',
                'AJAX_OPTION_ADDITIONAL' => 'FORM'
            ], $parameters, [
                'FORM_ID' => $id,
                'AJAX_MODE' => 'Y',
                'AJAX_OPTION_SHADOW' => 'N',
                'AJAX_OPTION_JUMP' => 'N',
                'AJAX_OPTION_STYLE' => 'Y',
                'FIELDS' => $fields
            ]);

            $APPLICATION->ShowAjaxHead();
            $APPLICATION->IncludeComponent(
                'intec:startshop.forms.result.new',
                $template,
                $parameters,
                null,
                ['HIDE_ICONS' => 'Y']
            );
        }
    }
}