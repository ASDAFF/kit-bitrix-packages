<?
use \Sotbit\Origami\Actions;
class AdvantagesDescription extends Actions
{
	public function afterSaveContent()
	{
		\CBitrixComponent::clearComponentCache('bitrix:news.list','');
	}
	public function afterAdd()
	{
		\CBitrixComponent::clearComponentCache('bitrix:news.list', '');
	}
}
?>