<?php
namespace intec\constructor\structure;

use intec\constructor\models\Build;
use intec\constructor\models\build\Template;
use intec\core\helpers\Type;

trait SnippetHandleTrait
{
    /**
     * Возвращает данные для виджета.
     * @param array $properties Свойства виджета.
     * @param Build $build Сборка.
     * @param Template $template Шаблон.
     * @return array
     */
    public function getData($properties, $build, $template)
    {
        if (!$template instanceof Template)
            return null;

        if (!$build instanceof Build)
            $build = $template->getBuild(true);

        if (!$build instanceof Build)
            return null;

        if (!Type::isArrayable($properties))
            $properties = [];

        $data = $this->data(
            $properties,
            $build,
            $template
        );

        if (!Type::isArrayable($data))
            $data = [];

        return $data;
    }

    /**
     * Создает набор данных для виджета.
     * @param array $properties Свойства виджета.
     * @param Build $build Сборка.
     * @param Template $template Шаблон.
     * @return array
     */
    protected function data($properties, $build, $template)
    {
        return [];
    }

    /**
     * Запускает обработку данных для виджета.
     * @param array $parameters Дополнительные параметры.
     * @param array $properties Свойства виджета.
     * @param Build $build Сборка.
     * @param Template $template Шаблон.
     * @return array
     */
    public function runHandler($parameters = [], $properties = [], $build = null, $template = null)
    {
        if (!$template instanceof Template)
            return null;

        if (!$build instanceof Build)
            $build = $template->getBuild(true);

        if (!Type::isArrayable($parameters))
            $parameters = [];

        if (!Type::isArrayable($properties))
            $properties = [];

        $result = $this->handle(
            $parameters,
            $properties,
            $build,
            $template
        );

        if (!Type::isArrayable($result))
            $result = [];

        return $result;
    }

    /**
     * Обрабатыв
     * @param array $parameters Дополнительные параметры.
     * @param array $properties Свойства виджета.
     * @param Build $build Сборка.
     * @param Template $template Шаблон.
     * @return array
     */
    protected function handle($parameters, $properties, $build, $template)
    {
        return [];
    }
}