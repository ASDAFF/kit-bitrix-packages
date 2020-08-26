<?php
namespace Kit\Origami\Config\Widgets;


use Bitrix\Main\Localization\Loc;
use Kit\Origami\Config\Option;
use Kit\Origami\Config\Widget;

class TabCount extends Widget
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
        ?>
        <div class="tabs">
            <div class="tabs-body">
                <?
                for ( $i = 0; $i < 5; $i++){
                    $count = Option::get('COUNT_COUNT_TAB_' . $i . '_');
                    $def = Option::get('DEFAULT_COUNT_TAB_');
                    ?>
                    <div class="tab">
                        <input oninput="onChange(this)"  type="text" name="COUNT_COUNT_TAB_<?= $i ?>_" value="<?= $count ?>">
                        <input class="sort-radio" type="radio" name="DEFAULT_COUNT_TAB_" value="<?= $i ?>" <?= ($def == $i ? 'checked' : '') ?> <?=(empty($count) ? 'disabled' : '')?>>
                    </div>
                    <?
                }
                ?>
            </div>
        </div>
        <style>
            .tabs {
                border: 1px solid #e0e8ea;
                display: inline-block;
            }


            .tabs-body {
                padding: 10px;
            }

            .tab {
                display: flex;
                align-items: center;
                margin-bottom: 10px;
                justify-content: left;
            }

            .tabs-body input[type="text"] {
                width: 160px;
                margin-right: 10px;
            }

            .tabs-body label {
                width: 70px !important;
                margin-left: 17px;
            }

        </style>
        <?
    }

    public function prepareRequest(&$request)
    {
        if($request['save']) {
            for ($i = 0; $i < 5; $i++){
                Option::set('COUNT_COUNT_TAB_'.$i.'_',
                    $request['COUNT_COUNT_TAB_'.$i.'_'],
                    $this->site);
                Option::$options['COUNT_COUNT_TAB_'.$i.'_'] = $request['COUNT_COUNT_TAB_'.$i.'_'];
                Option::set('DEFAULT_COUNT_TAB_',
                    $request['DEFAULT_COUNT_TAB_'],
                    $this->site);
                Option::$options['DEFAULT_COUNT_TAB_'] = $request['DEFAULT_COUNT_TAB_'];
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

    public function getSite(){
        return $this->site;
    }

}
?>