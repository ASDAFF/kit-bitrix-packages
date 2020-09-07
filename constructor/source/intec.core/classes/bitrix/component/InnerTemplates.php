<?php
namespace intec\core\bitrix\component;

use CBitrixComponentTemplate;
use intec\core\base\Collection;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Type;
use intec\core\io\Path;

/**
 * Class InnerTemplates
 * @package intec\core\bitrix\component
 * @author apocalypsisdimon@gmail.com
 */
class InnerTemplates extends Collection
{
    /**
     * @param CBitrixComponentTemplate $template
     * @param Path|string $directory
     * @return static
     */
    public static function find($template, $directory)
    {
        $result = new static();

        if (!($template instanceof CBitrixComponentTemplate))
            return $result;

        $directory = Path::from('@root/'.$template->GetFolder())->add($directory);
        $entries = FileHelper::getDirectoryEntries($directory->value, false);

        foreach ($entries as $entry) {
            $path = $directory->add($entry);

            if (!FileHelper::isDirectory($path->value))
                continue;

            $instance = new InnerTemplate($path, $template, $entry);
            $result->set($instance->code, $instance);
        }

        return $result;
    }

    /**
     * @param CBitrixComponentTemplate $template
     * @param Path|string $directory
     * @param string $code
     * @return InnerTemplate|null
     * @throws \intec\core\db\Exception
     */
    public static function findOne($template, $directory, $code)
    {
        $instance = null;

        if (!($template instanceof CBitrixComponentTemplate))
            return $instance;

        if (empty($code) && !Type::isNumeric($code))
            return $instance;

        $path = Path::from('@root/'.$template->GetFolder())->add($directory)->add($code);

        if (FileHelper::isDirectory($path->value))
            $instance = new InnerTemplate($path, $template, $code);

        return $instance;
    }

    /**
     * @inheritdoc
     */
    protected function verify($item)
    {
        return ($item instanceof InnerTemplate);
    }
}