<?php
namespace intec\core;

use Exception;
use Bitrix\Main\Config\Option;
use intec\core\base\Component;

/**
 * Class Parameters
 * @property boolean $minimizationUse Импользование минимизации.
 * @property boolean $minimizationSpaces Импользование минимизации пробелов и переносов.
 * @property boolean $minimizationTags Импользование минимизации тегов и атрибутов.
 * @property boolean $minimizationCommentaries Импользование минимизации комментариев.
 * @property boolean $minimizationContent Импользование минимизации контента.
 * @package intec\core
 * @author apocalypsisdimon@gmail.com
 */
class Parameters extends Component
{
    /**
     * Возвращает значение для указанного параметра.
     * @param string $name Наименование параметра.
     * @param string $default Значение по умолчанию.
     * @param string|boolean|false $siteId Идентификатор сайта.
     * @return string|null
     */
    protected function get($name, $default = '', $siteId = false)
    {
        try {
            return Option::get('intec.core', $name, $default, $siteId);
        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     * Устанавливает значение для указанного параметра.
     * @param string $name Наименование параметра.
     * @param string $value Значение параметра.
     * @param string|boolean|false $siteId Идентификатор сайта.
     */
    protected function set($name, $value, $siteId = false)
    {
        try {
            Option::set('intec.core', $name, $value, $siteId);
        } catch (Exception $exception) {}
    }

    /**
     * Возвращает статус минимизации.
     * @param string|boolean|null $siteId
     * @return boolean
     */
    public function getMinimizationUse($siteId = false)
    {
        return $this->get('minimizationUse', 'N', $siteId) === 'Y';
    }

    /**
     * Устанавливает статус минимизации.
     * @param boolean $value
     * @param string|boolean|null $siteId
     */
    public function setMinimizationUse($value, $siteId = false)
    {
        $this->set('minimizationUse', $value ? 'Y' : 'N', $siteId);
    }

    /**
     * Возвращает статус минимизации пробелов и переносов.
     * @param string|boolean|null $siteId
     * @return boolean
     */
    public function getMinimizationSpaces($siteId = false)
    {
        return $this->get('minimizationSpaces', 'N', $siteId) === 'Y';
    }

    /**
     * Устанавливает статус минимизации пробелов и переносов.
     * @param boolean $value
     * @param string|boolean|null $siteId
     */
    public function setMinimizationSpaces($value, $siteId = false)
    {
        $this->set('minimizationSpaces', $value ? 'Y' : 'N', $siteId);
    }

    /**
     * Возвращает статус минимизации тэгов и атрибутов.
     * @param string|boolean|null $siteId
     * @return boolean
     */
    public function getMinimizationTags($siteId = false)
    {
        return $this->get('minimizationTags', 'N', $siteId) === 'Y';
    }

    /**
     * Устанавливает статус минимизации тэгов и атрибутов.
     * @param boolean $value
     * @param string|boolean|null $siteId
     */
    public function setMinimizationTags($value, $siteId = false)
    {
        $this->set('minimizationTags', $value ? 'Y' : 'N', $siteId);
    }

    /**
     * Возвращает статус минимизации комментариев.
     * @param string|boolean|null $siteId
     * @return boolean
     */
    public function getMinimizationCommentaries($siteId = false)
    {
        return $this->get('minimizationCommentaries', 'N', $siteId) === 'Y';
    }

    /**
     * Устанавливает статус минимизации комментариев.
     * @param boolean $value
     * @param string|boolean|null $siteId
     */
    public function setMinimizationCommentaries($value, $siteId = false)
    {
        $this->set('minimizationCommentaries', $value ? 'Y' : 'N', $siteId);
    }

    /**
     * Возвращает статус минимизации контента.
     * @param string|boolean|null $siteId
     * @return boolean
     */
    public function getMinimizationContent($siteId = false)
    {
        return $this->get('minimizationContent', 'N', $siteId) === 'Y';
    }

    /**
     * Устанавливает статус минимизации контента.
     * @param boolean $value
     * @param string|boolean|null $siteId
     */
    public function setMinimizationContent($value, $siteId = false)
    {
        $this->set('minimizationContent', $value ? 'Y' : 'N', $siteId);
    }
}