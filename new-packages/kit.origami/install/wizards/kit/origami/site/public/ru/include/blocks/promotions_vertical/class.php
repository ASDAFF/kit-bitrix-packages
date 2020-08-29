<?
use \Kit\Origami\Actions;
class PromotionsVertical extends Actions
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