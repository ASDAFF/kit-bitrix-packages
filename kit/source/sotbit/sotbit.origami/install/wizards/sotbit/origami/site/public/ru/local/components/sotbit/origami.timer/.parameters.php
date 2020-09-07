<?php

$arComponentParameters = array(
    "GROUPS" => array(
        "BASE" => array(
            "SORT" => 110,
            "NAME" => GetMessage('TIMER_BASE'),
        ),
        "SETTINGS" => array(
            "SORT" => 115,
            "NAME" => GetMessage('TIMER_SETTINGS'),
        ),
    ),
    "PARAMETERS" => array(
        "ACTIVATE" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage('TIMER_ACTIVATE'),
            "TYPE" => "CHECKBOX",
        ),

        "TIMER_DATE_END" => array(
            "PARENT" => "SETTINGS",
            "NAME" => GetMessage('TIMER_DATE_END'),
            "TYPE" => 'CUSTOM',
            'JS_FILE' => SITE_DIR . '/local/components/sotbit/origami.timer/templates/origami_default/settings/settings.js',
            // функция из подключенного скрипта JS_FILE, вызывается при отрисовке окна настроек
            'JS_EVENT' => 'OnMySettingsEdit',
            // доп. данные, передаются в функцию из JS_EVENT
            'JS_DATA' => GetMessage('TIMER_MY_PARAM_SET'),
        ),

        "TIMER_SIZE" => array(
            "PARENT" => "SETTINGS",
            "NAME" => GetMessage('TIMER_TIMER_SIZE'),
            "TYPE" => "LIST",
            "VALUES" => array(
                'sm' => GetMessage('TIMER_SIZE_SMALL'),
                'md' => GetMessage('TIMER_SIZE_MIDDLE'),
                'lg' => GetMessage('TIMER_SIZE_LARGE')
            ),
            "DEFAULT" => 'md'
        ),
    )
);
