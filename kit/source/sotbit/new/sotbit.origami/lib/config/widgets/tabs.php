<?php
namespace Sotbit\Origami\Config\Widgets;


use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Config\Option;
use Sotbit\Origami\Config\Widget;

class Tabs extends Widget
{
	protected $template;
	protected $site;
    public function setValues($values = array())
    {
        foreach($values as $key => $value)
        {
            $this->setValue($key,$value);
        }
    }
	public function show()
	{
	    $values = $this->getValues();
	    $current = $this->getCurrentValue();
	    $diff = array_diff($values,$current);


	    if($diff){
            $current = array_merge($current,$diff);
	    }

	    foreach($current as $i=>$cur){
	    	if(!in_array($cur,$values)){
	    		unset($current[$i]);
		    }
	    }

	    if($current){
	    	$values = $current;
	    }
	    ?>
		<div class="tabs">
			<div class="tabs-header">
				<div class="tab-header-1"><?=Loc::getMessage('TAB_COLUMN_1')?></div>
				<div class="tab-header-2"><?=Loc::getMessage('TAB_COLUMN_2')?></div>
				<div class="tab-header-3"><?=Loc::getMessage('TAB_COLUMN_3')?></div>
			</div>
	        <div class="tabs-body">
	            <?
	            foreach ($values as $i => $tab){
	                $active = Option::get('ACTIVE_TAB_'.$tab.'_'.$this->template,$this->site);
	                $name = Option::get('NAME_TAB_'.$tab.'_'.$this->template,$this->site);
	                ?>
	                <div class="tab">
		                <div class="tab_name"><?=Loc::getMessage('ELEMENT_TAB_'.$tab)?></div>
	                    <input type="hidden" name="<?=$this->getCode()?>[]" value="<?=$tab?>">
		                <input type="text" name="NAME_TAB_<?=$tab . '_' . $this->template?>" value="<?=$name?>">
		                <input type="checkbox" name="ACTIVE_TAB_<?=$tab . '_' . $this->template?>" value="Y" <?=($active == 'Y')?'checked':''?>>
	                    <div class="plus" onclick="up(this)">&#8593;</div>
	                    <div class="minus" onclick="down(this)">&#8595;</div>
	                </div>
	                <?
	            }
	            ?>
	        </div>
		</div>
        <script>
            function up(e){
                var el = $(e);
                var block = el.parent();
	            block.insertBefore(block.prev());

                return false;
            }
            function down(e){
	            var el = $(e);
	            var block = el.parent();
                block.insertAfter(block.next());

	            return false;
            }
        </script>
		<style>
			.tabs{
				border:1px solid #e0e8ea;
				display: inline-block;
			}
			.tabs-header{
				display:flex;
				text-align: center;
				border-bottom:1px solid #e0e8ea;
				justify-content: left;
				padding: 10px;
			}
			.tab-header-1{
				width:120px;
			}
			.tab-header-2{
				width:160px;
				margin-right:10px;
			}
			.tab-header-3{
				width:70px;
			}
			.tabs-body{
				padding: 10px;
			}
			.tab{
				display: flex;
				align-items: center;
				margin-bottom: 10px;
				justify-content: left;
			}
			.tab_name{
				width:120px;
			}
			.tabs-body input[type="text"]{
				width:160px;
				margin-right:10px;
			}
			.tabs-body label{
				width:70px!important;
				margin-left:17px;
			}
			.tabs-body .plus,
			.tabs-body .minus
			{
				font-size: 18px;
				width:24px;
				height:24px;
				border:1px solid #e0e8ea;
				cursor: pointer;
				margin-right: 5px;
				text-align: center;
			}
		</style>
        <?
	}
    public function prepareRequest(&$request)
    {
        if($request['save']) {
            //$this->template = $request['DETAIL_TEMPLATE'];
            $values = $this->getValues();
            foreach ($values as $tab) {
                Option::set('ACTIVE_TAB_'.$tab.'_'.$this->template,
                    $request['ACTIVE_TAB_'.$tab.'_'.$this->template],
                    $this->site);
                Option::$options['ACTIVE_TAB_'.$tab.'_'.$this->template] = $request['ACTIVE_TAB_'.$tab.'_'.$this->template];
                Option::set('NAME_TAB_'.$tab.'_'.$this->template,
                    $request['NAME_TAB_'.$tab.'_'.$this->template],
                    $this->site);
                Option::$options['NAME_TAB_'.$tab.'_'.$this->template] = $request['NAME_TAB_'.$tab.'_'.$this->template];
            }
        }
    	$request[$this->getCode()] = serialize($request[$this->getCode()]);
    }

    public function setCurrentValue($value)
    {
        $value = unserialize($value);
        if(!is_array($value))
        {
            $value = [];
        }
        $this->currentValue = $value;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @param mixed $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }


}
?>
