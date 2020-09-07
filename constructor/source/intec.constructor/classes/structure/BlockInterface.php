<?php
namespace intec\constructor\structure;

use intec\constructor\structure\block\Resources;

/**
 * Interface BlockInterface
 * @package intec\constructor\structure
 */
interface BlockInterface
{
    /**
     * Возвращает ресурсы блока.
     * @return Resources
     */
    public function getResources();

    /**
     * Возвращает данные блока.
     * @return array|null
     */
    public function getData();
}