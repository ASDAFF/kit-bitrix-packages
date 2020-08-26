<?
use \Kit\Origami\Actions;
class Products extends Actions
{
	public function afterSaveContent()
	{
		\CBitrixComponent::clearComponentCache('bitrix:catalog.section','');
	}
	public function afterAdd()
	{
		\CBitrixComponent::clearComponentCache('bitrix:catalog.section', '');
	}
}
?>