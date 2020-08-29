<?
use \Kit\Origami\Actions;
class YoutubeBlock extends Actions
{
	public function afterSaveContent()
	{
		\CBitrixComponent::clearComponentCache('kit:youtube', '');
	}
	public function afterAdd()
	{
		\CBitrixComponent::clearComponentCache('kit:youtube', '');
	}
}
?>