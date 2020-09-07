<?
use \Kit\Origami\Actions;
class Instagram1 extends Actions
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