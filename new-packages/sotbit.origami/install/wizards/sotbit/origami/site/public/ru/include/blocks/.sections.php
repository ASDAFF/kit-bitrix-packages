<?
use Bitrix\Main\Localization\Loc;
Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/modules/sotbit.origami/blocks/.sections.php');
return array(
	'banners' => Loc::getMessage('LD_BLOCK_SECTION_BANNERS'),
	'popular_categories' => Loc::getMessage('LD_BLOCK_SECTION_POPULAR_CATEGORIES'),
	//'services' => Loc::getMessage('LD_BLOCK_SECTION_SERVICES'),
	'brands' => Loc::getMessage('LD_BLOCK_SECTION_BRANDS'),
	'advantages' => Loc::getMessage('LD_BLOCK_SECTION_ADVANTAGES'),
	'blog' => Loc::getMessage('LD_BLOCK_SECTION_BLOG'),
	'news' => Loc::getMessage('LD_BLOCK_SECTION_NEWS'),
	'promotions' => Loc::getMessage('LD_BLOCK_SECTION_PROMOTIONS'),
	'products' => Loc::getMessage('LD_BLOCK_SECTION_PRODUCTS'),
	'soc' => Loc::getMessage('LD_BLOCK_SECTION_SOC'),
	'about' => Loc::getMessage('LD_BLOCK_SECTION_ABOUT'),
    'other' => Loc::getMessage('LD_BLOCK_SECTION_OTHER'),
);
?>
