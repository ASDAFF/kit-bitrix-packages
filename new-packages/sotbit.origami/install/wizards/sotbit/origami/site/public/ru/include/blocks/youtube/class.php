<?
use \Sotbit\Origami\Actions;
class YoutubeBlock extends Actions
{
	public function afterSaveContent()
	{
		\CBitrixComponent::clearComponentCache('sotbit:youtube', '');
	}
	public function afterAdd()
	{
		\CBitrixComponent::clearComponentCache('sotbit:youtube', '');
	}
}
?>