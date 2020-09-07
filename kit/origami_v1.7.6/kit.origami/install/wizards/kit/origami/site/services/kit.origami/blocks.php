<?
if( !defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true )
    die();

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use \Bitrix\Main\UI\Uploader\Uploader;
use \Bitrix\Main\UI\FileInputUtility;

Loc::loadMessages(__FILE__);

$module = 'kit.origami';

CModule::includeModule($module);

$blockCollection = new \Kit\Origami\BlockCollection('main_' . WIZARD_SITE_ID);
$blockCollection->setPage(WIZARD_SITE_PATH);
$blockCollection->setSite(WIZARD_SITE_ID);

$block = new \Kit\Origami\Block([
    'CODE' => 'banner_side_right',
    'PART' => 'main_' . WIZARD_SITE_ID
],WIZARD_SITE_DIR);
$block->setActive('Y');
$block = $blockCollection->add($block, 0);

$block1 = new \Kit\Origami\Block([
    'CODE' => 'advantages',
    'PART' => 'main_' . WIZARD_SITE_ID
],WIZARD_SITE_DIR);
$block1->setActive('Y');
$block1 = $blockCollection->add($block1, 1);

$block2 = new \Kit\Origami\Block([
    'CODE' => 'popular_categories_simple',
    'PART' => 'main_' . WIZARD_SITE_ID
],WIZARD_SITE_DIR);
$block2->setActive('Y');
$block2 = $blockCollection->add($block2, 2);

$block3 = new \Kit\Origami\Block([
    'CODE' => 'products',
    'PART' => 'main_' . WIZARD_SITE_ID
],WIZARD_SITE_DIR);
$block3->setActive('Y');
$block3 = $blockCollection->add($block3, 3);

$block4 = new \Kit\Origami\Block([
    'CODE' => 'promotions_vertical',
    'PART' => 'main_' . WIZARD_SITE_ID
],WIZARD_SITE_DIR);
$block4->setActive('Y');
$block4 = $blockCollection->add($block4, 4);

$block5 = new \Kit\Origami\Block([
    'CODE' => 'news_images_and_list',
    'PART' => 'main_' . WIZARD_SITE_ID
],WIZARD_SITE_DIR);
$block5->setActive('Y');
$block5 = $blockCollection->add($block5, 5);

$block6 = new \Kit\Origami\Block([
    'CODE' => 'blog',
    'PART' => 'main_' . WIZARD_SITE_ID
],WIZARD_SITE_DIR);
$block6->setActive('Y');
$block6 = $blockCollection->add($block6, 6);

$block7 = new \Kit\Origami\Block([
    'CODE' => 'youtube',
    'PART' => 'main_' . WIZARD_SITE_ID
],WIZARD_SITE_DIR);
$block7->setActive('Y');
$block7 = $blockCollection->add($block7, 7);

$block8 = new \Kit\Origami\Block([
    'CODE' => 'instagram_1',
    'PART' => 'main_' . WIZARD_SITE_ID
],WIZARD_SITE_DIR);
$block8->setActive('Y');
$block8 = $blockCollection->add($block8, 8);

$block9 = new \Kit\Origami\Block([
    'CODE' => 'banner_mini',
    'PART' => 'main_' . WIZARD_SITE_ID
],WIZARD_SITE_DIR);
$block9->setActive('Y');
$block9 = $blockCollection->add($block9, 9);

$block10 = new \Kit\Origami\Block([
    'CODE' => 'about',
    'PART' => 'main_' . WIZARD_SITE_ID
],WIZARD_SITE_DIR);
$block10->setActive('Y');
$block10 = $blockCollection->add($block10, 10);

$block11 = new \Kit\Origami\Block([
    'CODE' => 'brands_slider',
    'PART' => 'main_' . WIZARD_SITE_ID
],WIZARD_SITE_DIR);

$block11->setActive('Y');
$block11 = $blockCollection->add($block11, 11);

$blockCollection->setPage(WIZARD_SITE_DIR);

$blockCollection->save();

CopyDirFiles( $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/kit.origami/install/wizards/kit/origami/site/public/ru/blocks', $_SERVER["DOCUMENT_ROOT"].WIZARD_SITE_DIR."blocks", true, true);

$path = $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.origami/install/wizards/kit/origami/images/new_bg_about.jpg";
$arFile = CFile::MakeFileArray($path);

$arFile = array(
    "name" => $arFile["name"],
    "size" => $arFile["size"],
    "tmp_name" => $arFile["tmp_name"],
    "type" => $arFile["type"],
    "MODULE_ID" => "kit.origami"
);

$fileID = CFile::SaveFile($arFile, "kit.origami");

$pathBlock = $_SERVER["DOCUMENT_ROOT"].WIZARD_SITE_DIR."blocks/about_11/settings.php";

$content = file_get_contents($pathBlock);
$content = str_replace("#ORIGAMI_BACKGROUND_ABOUT#", $fileID, $content);

file_put_contents($pathBlock, $content);

unset($_SESSION['KIT_ORIGAMI']);
?>
