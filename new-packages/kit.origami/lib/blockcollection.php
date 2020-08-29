<?php

namespace Sotbit\Origami;

use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Front\User;
use Sotbit\Origami\Internals\BlockTable;

class BlockCollection implements \IteratorAggregate
{
    private $blocks;
    private $page;
    private $part;
    private $site;

    public function __construct($part = '', Block ...$blocks)
    {
        $this->blocks = $blocks;
        $this->setPage();
        $this->setPart($part);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->blocks);
    }

    public function show($canChange = true)
    {
        if ($this->getCount() > 0)
        {
            foreach ($this->blocks as $block)
            {
                if ($block->isDeleted()) {
                    continue;
                }
                if (!$block->isActive() && !$canChange)
                {
                    continue;
                }

                $block->includeHeadAssets();
                if ($canChange) {
                    $block->designShow();
                }
                $block->show($canChange, $hideAssets = true);
            }
        } elseif ($canChange)
        {
            echo '<div data-id="create_action" class="landing-ui-panel landing-ui-panel-create-action landing-ui-show">
			<button type="button" class="landing-ui-button landing-ui-button-plus" data-id="insert_after">
				<span class="landing-ui-button-text">
					'.Loc::getMessage(\SotbitOrigami::moduleId.'_ADD').'
				</span>
			</button>
		</div>';
        }
    }

    public function copyBlock(Block $block)
    {
        $return = $block;
        if ($block->getId() && strpos($block->getId(), 'tmp') === false) {
            foreach ($this->blocks as $i => $bblock)
            {
                if($bblock->getId() == $block->getId())
                {
                    $nBlock = new \Sotbit\Origami\Block([
                        'CODE' => $block->getCode(),
                        'PART' => $block->getPart(),
                    ], $this->getPage());
                    $nBlock->setActive('Y');
                    unset($this->blocks[$i]);
                    $nBlock = $this->add($nBlock,$i);
                    ksort($this->blocks);
                    $nBlock->setCopyOf($block->getId());
                    $this->blocks[$i] = $nBlock;

                    $return = $nBlock;
                    break;
                }
            }
        }
        return $return;
    }

    public function add(Block $block, $index = 0)
    {
        if ($index >= count($this->blocks)) {
            $block->setSort($index);
            $this->blocks[$index] = $block;
        } else {

            if(!$this->blocks[$index])
            {
                $block->setSort($index);
                $this->blocks[$index] = $block;
            }
            else {
                $tmp = $this->blocks;
                $this->blocks = [];
                $sort = 0;
                foreach ($tmp as $i => $b) {
                    if ($i == $index) {
                        $this->blocks[$sort] = $block;
                        $block->setSort($sort);
                        ++$sort;
                    }
                    $b->setSort($sort);
                    $this->blocks[$sort] = $b;
                    ++$sort;
                }
            }
        }
        return $block;
    }

    public function remake($action, $id)
    {
        $remaked = false;
        switch ($action) {
            case 'down':
                $tmp = $this->blocks;
                $this->blocks = [];
                foreach ($tmp as $i => $block) {
                    if ($block->getId() == $id) {
                        $j = $i + 1;
                        if ($tmp[$j]) {
                            $tmp[$j]->setSort($i);
                            $this->blocks[$i] = $tmp[$j];
                            $block->setSort($j);
                            $this->blocks[$j] = $block;
                            $remaked = true;
                        }
                    } elseif (!$this->blocks[$i]) {
                        $block->setSort($i);
                        $this->blocks[$i] = $block;
                    }
                }
                break;
            case 'up':
                $tmp = $this->blocks;
                $this->blocks = [];
                foreach ($tmp as $i => $block) {
                    if ($block->getId() == $id) {
                        $j = $i - 1;
                        if ($tmp[$j]) {
                            $tmp[$j]->setSort($i);
                            $this->blocks[$i] = $tmp[$j];
                            $block->setSort($j);
                            $this->blocks[$j] = $block;
                            $remaked = true;
                        }
                    } elseif (!$this->blocks[$i]) {
                        $block->setSort($i);
                        $this->blocks[$i] = $block;
                    }
                }
                break;
            case 'remove':
                $tmp = $this->blocks;
                $this->blocks = [];
                $sort = 0;
                foreach ($tmp as $i => $block) {
                    if ($block->getId() == $id) {
                        $block->setDeleted(true);
                        $remaked = true;
                    }

                    $block->setSort($sort);
                    $this->blocks[$sort] = $block;
                    ++$sort;
                }

                break;
        }

        return $remaked;
    }

    public function getCount()
    {
        $cnt = 0;
        foreach ($this->blocks as $block) {
            if ($block->isDeleted()) {
                continue;
            }
            ++$cnt;
        }

        return $cnt;
    }

    public function save()
    {
        if (!is_dir($_SERVER['DOCUMENT_ROOT'].$this->getPage().'blocks')) {
            mkdir($_SERVER['DOCUMENT_ROOT'].$this->getPage().'blocks');
        }
        //clear not used
        $rs = BlockTable::getList(['filter' => ['PART' => $this->getPart()]]);
        while ($blockData = $rs->fetch()) {
            $founded = false;
            if ($this->blocks) {
                foreach ($this->blocks as $i => $block) {
                    if ($block->getId() == $blockData['ID']) {
                        $founded = true;
                        break;
                    }
                }
            }
            if (!$founded) {
                $block = new Block($blockData, $this->getPage());
                $block->delete();
            }
        }
        if ($this->blocks) {
            foreach ($this->blocks as $i => $block) {
                if ($block->isDeleted()) {
                    $block->delete();
                    unset($this->blocks[$i]);
                } else {
                    $sort = $block->getSort();
                    if (is_null($sort)) {
                        continue;
                        unset($this->blocks[$i]);
                    }
                    $block->save($this->getPage());
                }
            }
        }

        $FrontUser = new User($this->getSite());
        $dir = $FrontUser->getFolder();
        if(file_exists($_SERVER['DOCUMENT_ROOT'].$dir.'/blocks/'.$this->getPart().'/blockcollection.php'))
        {
            unlink($_SERVER['DOCUMENT_ROOT'].$dir.'/blocks/'.$this->getPart().'/blockcollection.php');
        }

        $this->rmUnUsedBlocks();
    }

    /**
     *
     */
    protected function rmUnUsedBlocks(){
        $existBlocks = [];
        $rs = BlockTable::getList(['filter' => ['PART' => $this->getPart()]]);
        while($block = $rs->fetch()){
            $existBlocks[] = $block['CODE'].'_'.$block['ID'];
        }
        $dir =  $_SERVER['DOCUMENT_ROOT'].$this->getPage().'/blocks';
        if (is_dir($dir)) {
            $blocks = scandir($dir);
            foreach ($blocks as $block) {
                if ($block != "." && $block != ".." && !in_array($block, $existBlocks)) {
                    $files = scandir($dir.'/'.$block);
                    foreach($files as $file){
                        if($file != '.' && $file != '..'){
                            unlink($dir.'/'.$block.'/'.$file);
                        }
                    }
                    rmdir($dir.'/'.$block);
                }
            }
        }
    }
    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param string $page
     *
     * @throws \Bitrix\Main\SystemException
     */
    public function setPage($page = '')
    {
        if (!$page) {
            $context = \Bitrix\Main\Application::getInstance()->getContext();
            $request = $context->getRequest();
            $page = $request->getScriptFile();
            $page = str_replace('index.php','',$page);
            if (!$page) {
                $page = '/';
            }
            $this->page = $page;
        } else {
            $this->page = $page;
        }
    }

    /**
     * @return Block[]
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * @return string
     */
    public function getPart()
    {
        return $this->part;
    }

    /**
     * @param string $part
     */
    public function setPart($part)
    {
        $this->part = $part;
    }

    /**
     * @return mixed
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param mixed $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

    public function setBlock(Block $block,$i)
    {
        $this->blocks[$i] = $block;
    }
}

?>