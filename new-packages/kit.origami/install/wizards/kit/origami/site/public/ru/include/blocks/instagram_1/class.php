<?
use \Sotbit\Origami\Actions;
class Instagram1 extends Actions
{
	public function afterSaveContent()
	{
		\CBitrixComponent::clearComponentCache('sotbit:instagram','');
	}
	public function afterAdd()
	{
		\CBitrixComponent::clearComponentCache('sotbit:instagram', '');
	}
}
?>