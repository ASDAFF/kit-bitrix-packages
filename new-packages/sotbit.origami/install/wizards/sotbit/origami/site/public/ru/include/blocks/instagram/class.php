<?
use \Sotbit\Origami\Actions;
class Instagram extends Actions
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