<?php

namespace Sotbit\Origami\Front;

use Bitrix\Main\Context;
use Sotbit\Origami\BlockCollection;
use Sotbit\Origami\Internals\BlockTable;

class Block
{
    /**
     * @var User
     */
    private $frontUser;

    /**
     * @var BlockCollection
     */
    private $blockCollection;

    /**
     * @var string
     */
    private $page;

    public function __construct($site = 's1')
    {
        $this->frontUser = new User($site);
    }

    /**
     * @return BlockCollection
     */
    public function getBlockCollection()
    {
        return $this->blockCollection;
    }

    /**
     * @return User
     */
    public function getFrontUser()
    {
        return $this->frontUser;
    }

    /**
     * @param BlockCollection $blockcollection
     */
    public function writeBlockCollection($blockcollection)
    {
        $dir = $this->getFrontUser()->getFolder().'/blocks/';

        if (!is_dir($_SERVER['DOCUMENT_ROOT'].$dir)) {
            mkdir($_SERVER['DOCUMENT_ROOT'].$dir);
        }
        if (!is_dir($_SERVER['DOCUMENT_ROOT'].$dir
            .$blockcollection->getPart())
        ) {
            mkdir($_SERVER['DOCUMENT_ROOT'].$dir.$blockcollection->getPart());
        }
        file_put_contents(
            $_SERVER['DOCUMENT_ROOT'].$dir.$blockcollection->getPart()
            .'/blockcollection.php',
            serialize($blockcollection)
        );
    }

    public function setBlockCollection($part = '')
    {
        $dir = $this->getFrontUser()->getFolder().'/blocks/'.$part;
        if ($dir) {
            if (file_exists(
                $_SERVER['DOCUMENT_ROOT'].$dir.'/blockcollection.php'
            )
            ) {
                $blockCollection = unserialize(
                    file_get_contents(
                        $_SERVER['DOCUMENT_ROOT'].$dir.'/blockcollection.php'
                    )
                );
            }
        }
        if (
            !isset($blockCollection)
            || !$blockCollection instanceof BlockCollection
        ) {
            $filter = ['PART' => $part];
            $blockCollection = new BlockCollection($part);
            $blockCollection->setPage($this->getPage());
            $blockCollection->setSite($this->getFrontUser()->getSite());
            $rs = BlockTable::getList([
                'filter' => $filter,
                'order'  => ['SORT' => 'asc'],
            ]);
            $i = 0;
            while ($block = $rs->fetch()) {
                $block = new \Sotbit\Origami\Block($block, $this->getPage());
                $blockCollection->add($block, $i);
                ++$i;
            }

        }
        $this->blockCollection = $blockCollection;
    }

    /**
     * @return string
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param string $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }
}