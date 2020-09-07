<?php
namespace intec\template\pages;

use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Encoding;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\core\handling\Actions;

class ComponentsActions extends Actions
{
    public function actionGet ()
    {
        /** @var /CMain $APPLICATION */
        global $APPLICATION;

        $request = Core::$app->request;

        $component = $request->get('component');
        $template = $request->get('template', '.default');
        $parameters = $request->get('parameters', array());

        if (empty($component))
            return;

        if (!Type::isArray($parameters))
            $parameters = [];

        $component = StringHelper::convert($component, null, Encoding::UTF8);
        $template = StringHelper::convert($template, null, Encoding::UTF8);
        $parameters = ArrayHelper::convertEncoding($parameters, null, Encoding::UTF8);

        foreach ($parameters as $key => $parameter)
            if (StringHelper::startsWith($key, '~'))
                unset($parameters[$key]);

        $parameters = ArrayHelper::merge([
                'AJAX_MODE' => 'Y',
                'AJAX_OPTION_ADDITIONAL' => 'COMPONENT',
                'AJAX_OPTION_SHADOW' => 'N',
                'AJAX_OPTION_JUMP' => 'N',
                'AJAX_OPTION_STYLE' => 'Y'
            ],
            $parameters
        );

        $APPLICATION->ShowAjaxHead();
        $APPLICATION->IncludeComponent(
            $component,
            $template,
            $parameters,
            null,
            ['HIDE_ICONS' => 'Y']
        );
    }
}