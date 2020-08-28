<?
use \Sotbit\Origami\Actions;
class PopularCategories7 extends Actions
{
	public function afterSaveContent()
	{
		\CBitrixComponent::clearComponentCache('bitrix:catalog.section.list','');
	}
	public function afterAdd()
	{
		\CBitrixComponent::clearComponentCache('bitrix:catalog.section.list', '');
	}
}
?>