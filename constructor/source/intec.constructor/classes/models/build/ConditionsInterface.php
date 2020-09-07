<?php
namespace intec\constructor\models\build;

interface ConditionsInterface
{
    /**
     * Тип условия: Группа
     */
    const CONDITION_TYPE_GROUP = 'group';
    /**
     * Тип условия: Путь
     */
    const CONDITION_TYPE_PATH = 'path';
    /**
     * Тип условия: Регулярное выражение
     */
    const CONDITION_TYPE_MATCH = 'match';
    /**
     * Тип условия: Параметр GET
     */
    const CONDITION_TYPE_PARAMETER_GET = 'parameter.get';
    /**
     * Тип условия: Параметр страницы
     */
    const CONDITION_TYPE_PARAMETER_PAGE = 'parameter.page';
    /**
     * Тип условия: Параметр шаблона
     */
    const CONDITION_TYPE_PARAMETER_TEMPLATE = 'parameter.template';
    /**
     * Тип условия: Выражение PHP
     */
    const CONDITION_TYPE_EXPRESSION = 'expression';
    /**
     * Тип условия: Сайт
     */
    const CONDITION_TYPE_SITE = 'site';

    /**
     * Тип регулярного выражения: Url
     */
    const CONDITION_MATCH_URL = 'url';
    /**
     * Тип регулярного выражения: Схема
     */
    const CONDITION_MATCH_SCHEME = 'scheme';
    /**
     * Тип регулярного выражения: Хост
     */
    const CONDITION_MATCH_HOST = 'host';
    /**
     * Тип регулярного выражения: Путь
     */
    const CONDITION_MATCH_PATH = 'path';
    /**
     * Тип регулярного выражения: Строка запроса
     */
    const CONDITION_MATCH_QUERY = 'query';

    /**
     * Логика: Больше
     */
    const CONDITION_LOGIC_MORE = '>';
    /**
     * Логика: Больше или равно
     */
    const CONDITION_LOGIC_MORE_OR_EQUAL = '>=';
    /**
     * Логика: Меньше или равно
     */
    const CONDITION_LOGIC_LESS = '<';
    /**
     * Логика: Меньше
     */
    const CONDITION_LOGIC_LESS_OR_EQUAL = '<=';
    /**
     * Логика: Не равно
     */
    const CONDITION_LOGIC_NOT_EQUAL = '!';
    /**
     * Логика: Равно
     */
    const CONDITION_LOGIC_EQUAL = '=';

    /**
     * Оператор: И
     */
    const CONDITION_OPERATOR_AND = 'and';
    /**
     * Оператор: ИЛИ
     */
    const CONDITION_OPERATOR_OR = 'or';
}