<?
define('STOP_STATISTICS', true);
define("NOT_CHECK_PERMISSIONS", true);

use Bitrix\Main\Context;
use Bitrix\Main\Loader;


$siteId = isset($_REQUEST['site']) && is_string($_REQUEST['site']) ? $_REQUEST['site'] : '';
$siteId = substr(preg_replace('/[^a-z0-9_]/i', '', $siteId), 0, 2);
if (!empty($siteId) && is_string($siteId))
{
    define('SITE_ID', $siteId);
}

if (isset($_REQUEST['siteTemplate'])) {
    define('SITE_TEMPLATE_ID', $_REQUEST['siteTemplate']);
}
require($_SERVER["DOCUMENT_ROOT"]
    ."/bitrix/modules/main/include/prolog_before.php");

if (!Loader::includeModule('sotbit.origami')) {
    return false;
}

try {
    Loader::includeModule('iblock');
    Loader::includeModule('catalog');
    Loader::includeModule('sale');
} catch (\Bitrix\Main\LoaderException $e) {
}

$FrontBlock = new \Sotbit\Origami\Front\Block($site);
$FrontBlock->setPage($page);
$FrontBlock->setBlockCollection($part);
$blockCollection = $FrontBlock->getBlockCollection($part);

switch ($action)
{
    case 'add':
        if ($blockCollection->getCount() > 0)
        {
            if (!isset($after)) {
                $after = $blockCollection->getBlocks()[0]->getId();
            }
            foreach ($blockCollection as $i => $block)
            {
                if ($after == $block->getId() || $id === $block->getCopyOf())
                {
                    $block = new \Sotbit\Origami\Block([
                        'CODE' => $code,
                        'PART' => $part,
                    ], $page);

                    $block->setActive('Y');
                    $block = $blockCollection->add($block, ++$i);
                    $class = $block->getClass();
                    $actions = new $class();
                    $actions->afterAdd();
                    $block->designShow();
                    $block->show();
                    break;
                }
            }
        } else {
            $block = new \Sotbit\Origami\Block([
                'CODE' => $code,
                'PART' => $part,
            ], $page);
            $block->setActive('Y');
            $block = $blockCollection->add($block, 0);
            $block->designShow();
            $block->show();
        }
        $FrontBlock->writeBlockCollection($blockCollection);
        break;
    case 'down':
        echo $blockCollection->remake('down', $id);
        $FrontBlock->writeBlockCollection($blockCollection);
        break;
    case 'up':
        echo $blockCollection->remake('up', $id);
        $FrontBlock->writeBlockCollection($blockCollection);
        break;
    case 'remove':
        $return = $blockCollection->remake('remove', $id);
        if ($blockCollection->getCount() == 0) {
            $return = $blockCollection->show(true);
        }
        echo $return;
        $FrontBlock->writeBlockCollection($blockCollection);
        break;
    case 'save_block_content':
        parse_str($data, $data);
        if (is_array($data))
        {
            if ($blockCollection->getCount() > 0)
            {
                foreach ($blockCollection as $i => $block)
                {
                    if ($id == $block->getId() || $id === $block->getCopyOf())
                    {
                        $block = $blockCollection->copyBlock($block);
                        $settings = $block->getSettings();
                        foreach ($data as $code => $value) {
                            if(ToLower(LANG_CHARSET) == 'windows-1251')
                            {
                                $value = iconv('utf-8' , 'windows-1251' , $value);
                            }
                            $settings['fields'][$code]['value'] = $value;
                        }
                        $block->setSettings($settings);
                        $class = $block->getClass();
                        $actions = new $class();
                        $actions->afterSaveContent();
                        $block->show();
                    }
                }
            }
        }
        $FrontBlock->writeBlockCollection($blockCollection);
        break;
    case 'padding-top':
    case 'padding-bottom':
    case 'padding-left':
    case 'padding-right':
    case 'background-color':
    case 'background-position':
    case 'background-repeat':
    case 'background-size':
    case 'background-attachment':
    case 'background-clip':
        if ($blockCollection->getCount() > 0)
        {
            foreach ($blockCollection as $i => $block)
            {
                if ($id == $block->getId() || $id === $block->getCopyOf())
                {
                    $block = $blockCollection->copyBlock($block);
                    $settings = $block->getSettings();
                    $settings['style'][$action]['value'] = $value;
                    $block->setSettings($settings);
                    $block->show();
                    break;
                }
            }
        }
        $FrontBlock->writeBlockCollection($blockCollection);
        break;

    case 'background-image':
        if ($blockCollection->getCount() > 0)
        {
            foreach ($blockCollection as $i => $block)
            {
                if ($id == $block->getId() || $id === $block->getCopyOf())
                {
                    $block = $blockCollection->copyBlock($block);
                    $settings = $block->getSettings();
                    $settings['style'][$action]['value'] = $value;
                    $block->setSettings($settings);
                    $block->show();
                    break;
                }
            }
        }
        $FrontBlock->writeBlockCollection($blockCollection);
        break;

        break;
    case 'l-d-lg-none':
    case 'l-d-md-none':
    case 'l-d-xs-none':
        if ($blockCollection->getCount() > 0)
        {
            foreach ($blockCollection as $i => $block)
            {
                if ($id == $block->getId() || $id === $block->getCopyOf())
                {
                    $block = $blockCollection->copyBlock($block);
                    $settings = $block->getSettings();

                    if ($settings['style'][$action]['value'])
                    {
                        $settings['style'][$action]['value'] = false;
                    } else {
                        $settings['style'][$action]['value'] = true;
                    }

                    $block->setSettings($settings);
                    $block->show();
                    break;
                }
            }
        }
        $FrontBlock->writeBlockCollection($blockCollection);
        break;
    case 'hide_block':
        if ($blockCollection->getCount() > 0)
        {
            foreach ($blockCollection as $i => $block)
            {
                if ($id == $block->getId() || $id === $block->getCopyOf())
                {
                    $block = $blockCollection->copyBlock($block);
                    $block->setActive('N');
                    $block->show();
                    break;
                }
            }
        }
        $FrontBlock->writeBlockCollection($blockCollection);
        break;
    case 'show_block':
        if ($blockCollection->getCount() > 0)
        {
            foreach ($blockCollection as $i => $block)
            {
                if ($id == $block->getId() || $id === $block->getCopyOf())
                {
                    $block = $blockCollection->copyBlock($block);
                    $block->setActive('Y');
                    $block->show();
                    break;
                }
            }
        }
        $FrontBlock->writeBlockCollection($blockCollection);
        break;
    case 'paste':
        $cut = false;
        if (strpos($id, '_cut') !== false)
        {
            $cut = true;
            $id = str_replace('_cut', '', $id);
        }
        if ($blockCollection->getCount() > 0)
        {
            foreach ($blockCollection as $i => $block)
            {
                if ($id == $block->getId() || $id === $block->getCopyOf())
                {
                    $needBlock = $block;
                    if ($cut) {
                        $blockCollection->remake('remove', $id);
                    }
                    break;
                }
            }
            if ($needBlock) {
                foreach ($blockCollection as $i => $block)
                {
                    if ($after == $block->getId() || $id === $block->getCopyOf())
                    {
                        if ($cut) {
                            $newBlock = $needBlock;
                        } else {
                            $newBlock = new \Sotbit\Origami\Block([
                                'CODE' => $needBlock->getCode(),
                                'PART' => $needBlock->getPart(),
                            ], $page);
                            $newBlock->setActive($needBlock->getActive());
                            $newBlock->setSettings($needBlock->getSettings());
                        }

                        $newBlock = $blockCollection->add($newBlock, ++$i);
                        $newBlock->designShow();
                        $newBlock->show();
                        break;
                    }
                }
            }
        }
        $FrontBlock->writeBlockCollection($blockCollection);
        break;
    case 'save':
        if($FrontBlock->getFrontUser()->isCanSave())
        {
            $blockCollection->setPage($page);
            $blockCollection->save();
        }
        break;
}
?>