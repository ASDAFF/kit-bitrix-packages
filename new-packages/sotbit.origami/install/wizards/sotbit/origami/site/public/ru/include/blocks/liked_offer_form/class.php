<?
use \Sotbit\Origami\Actions;
class Brands extends Actions
{
    public function afterSaveContent()
    {
        \CBitrixComponent::clearComponentCache('bitrix:form.result.new','');
    }
    public function afterAdd()
    {
        \CBitrixComponent::clearComponentCache('bitrix:form.result.new', '');
    }
}
?>
