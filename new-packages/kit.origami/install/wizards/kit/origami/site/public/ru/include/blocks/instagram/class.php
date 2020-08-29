<?
use \Sotbit\Origami\Actions;
class Instagram extends Actions
{
	public function afterSaveContent()
	{
		\CBitrixComponent::clearComponentCache('kit:instagram','');
	}
	public function afterAdd()
	{
		\CBitrixComponent::clearComponentCache('kit:instagram', '');
	}
}
?>