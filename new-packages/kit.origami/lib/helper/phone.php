<?php

namespace Sotbit\Origami\Helper;

/**
 * Class Phone
 *
 * @package Sotbit\Origami\Helper
 * @author  Sergey Danilkin <s.danilkin@kit.ru>
 */
class Phone
{
    /**
     * @var string
     */
    private $mask = '';

    public function __construct($mask = '')
    {
        $this->setMask($mask);
    }

    /**
     * @param string $template
     *
     * @return string
     */
    public function genHtmlMask($template = '')
    {
        $return = '';
        if ($template) {
            $symbols = str_split($template);
            foreach ($symbols as &$symbol) {
                switch ($symbol) {
                    case '+':
                        $symbol = '[\+]';
                        break;
                    case '(':
                        $symbol = '\s?[(]{0,1}';
                        break;
                    case ')':
                        $symbol = '[)]{0,1}\s?';
                        break;
                    case '-':
                        $symbol = '[-]{0,1}';
                        break;
                    case '9':
                        $symbol = '[0-9]';
                        break;
                }
            }
            $return = implode("", $symbols);
        }

        return $return;
    }

    public function changeHtmlField($field = '')
    {
        $field = str_replace(
            'type="text"',
            'type="tel" pattern="'.$this->genHtmlMask($this->getMask()).'" placeholder="'.$this->getMask().'"',
            $field
        );
        return $field;
    }

    /**
     * @return string
     */
    public function getMask()
    {
        return $this->mask;
    }

    /**
     * @param string $mask
     */
    public function setMask($mask)
    {
        $this->mask = $mask;
    }
}