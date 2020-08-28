<?php
namespace Sotbit\Origami\Config\Widgets;


use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Config\Option;
use Sotbit\Origami\Config\Widget;

class TabSort extends Widget
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
        $arStandart =[
            "ID",
            "SORT",
            "TIMESTAMP_X",
            "NAME",
            "ACTIVE_FROM",
            "ACTIVE_TO",
            "STATUS",
            "CODE",
            "IBLOCK_ID",
            "MODIFIED_BY",
            "ACTIVE",
            "SHOW_COUNTER",
            "SHOW_COUNTER_START",
            "SHOWS",
            "RAND",
            "XML_ID",
            "TAGS",
            "CREATED",
            "CREATED_DATE",
        ];
        $values = $this->getValues();
        $current = $this->getCurrentValue();
        $site = self::getSite();
        $iblock = Option::get('IBLOCK_ID', $site);

        $prop = \CIBlockProperty::GetList(array("sort"=>"asc"), array("ACTIVE"=>"Y", "IBLOCK_ID"=>$iblock));
        while ($propVal = $prop->GetNext()){
            if($propVal['PROPERTY_TYPE'] == 'S' || $propVal['PROPERTY_TYPE'] == 'N' || $propVal['PROPERTY_TYPE'] == 'L')
                $propVal['FILTER_PROPERTY'] = 'PROPERTY_'.strtoupper($propVal['CODE']);
            else
                continue;
            $arProp[] = $propVal;
        }

        $priceType = \CCatalogGroup::GetList(
            array("SORT" => "ASC"),
            array(),
            false,
            false

        );
        while ($priceVal = $priceType->Fetch()){
            $priceVal['FILTER_PROPERTY'] = 'CATALOG_PRICE_'.$priceVal['ID'];
            $arPriceType[] = $priceVal;
        }
        $priceQuantity = [
                "ID" => "-1",
                "FILTER_PROPERTY" => "CATALOG_QUANTITY",
                "NAME" => "QUANTITY",
                "NAME_LANG" => Loc::getMessage('TAB_SORT_QUANTITY_NAME'),
        ];
        array_push($arPriceType, $priceQuantity);

        if(is_array($current))
            $diff = array_diff($values,$current);

        if($diff){
            $current = array_merge($current,$diff);
        }
        if(!empty($current))
            foreach($current as $i=>$cur){
                if(!in_array($cur,$values)){
                    unset($current[$i]);
                }
            }

        if(!empty($current)){
            $values = $current;
        }

        if(!empty($iblock)) {
            ?>
            <div class="tabs">
                <div class="tabs-header">
                    <div class="tab-header-1"><?= Loc::getMessage('TAB_SORT_COLUMN_1') ?></div>
                    <div class="tab-header-2"><?= Loc::getMessage('TAB_SORT_COLUMN_2') ?></div>
                    <div class="tab-header-3"><?= Loc::getMessage('TAB_SORT_COLUMN_3') ?></div>
                    <div class="tab-header-4"><?= Loc::getMessage('TAB_SORT_COLUMN_4') ?></div>
                </div>
                <div class="tabs-body">
                    <?
                    for ( $i = 0; $i < 9; $i++){
                        $name = Option::get('NAME_TAB_' . $values[$i] . '_');
                        $sortOrder = Option::get('SORT_ORDER_TAB_' . $values[$i] . '_');
                        $def = Option::get('DEFAULT_SORT_TAB_');
                        $code = Option::get('CODE_TAB_' . $values[$i] . '_');
                        ?>
                        <div class="tab">
                            <input type="hidden" name="<?= $this->getCode() ?>[]" value="<?= $values[$i] ?>">
                            <input oninput="onChange(this)" type="text" name="NAME_TAB_<?= $values[$i] ?>_" value="<?= $name ?>">
                            <select name="CODE_TAB_<?= $values[$i] ?>_" <?=( (is_array($arProp) && count($arProp)) > 0 || (is_array($arPriceType) && count($arPriceType) > 0) ? "" : "disabled")?>>
                                <optgroup label="<?= Loc::getMessage('TAB_SORT_STANDART_PROP') ?>">
                                <?foreach ($arStandart as $standartk => $standart){?>
                                    <option value="<?=$standart?>" <?= strcmp($standart, $code) == 0 ? "selected" : "" ?>><?=$standart?></option>
                                <?}?>
                                </optgroup>
                                <optgroup label="<?= Loc::getMessage('TAB_SORT_IBLOCK_PROP') ?>">
                                <?foreach ($arProp as $propk => $prop){?>
                                    <option value="<?=$prop["FILTER_PROPERTY"]?>"
                                        <?= preg_match("/".strtoupper($prop["CODE"])."\b/", $code) === 1 ? "selected" : ""?>>
                                        <?=$prop["NAME"]." [".strtoupper($prop["CODE"])."]"?>
                                    </option>
                                <?}?>
                                </optgroup>
                                <optgroup label="<?= Loc::getMessage('TAB_SORT_PRICE_PROP') ?>">
                                <?foreach ($arPriceType as $pricek => $priceType){?>
                                    <option value="<?=$priceType["FILTER_PROPERTY"]?>"
                                        <?= strcmp("CATALOG_PRICE_".$priceType["ID"], $code) == 0 || strcmp("CATALOG_QUANTITY", $code) == 0 ?
                                            "selected" : "" ?>>
                                        <?=$priceType["NAME_LANG"]." [".strtoupper($priceType["NAME"])."]"?>
                                    </option>
                                <?}?>
                                </optgroup>
                            </select>
                            <select name="SORT_ORDER_TAB_<?= $values[$i] ?>_">
                                <option value="desc" <?= ( $sortOrder == "desc" ? "selected" : "" )?>><?= Loc::getMessage('TAB_SORT_DESC') ?></option>
                                <option value="asc" <?= ( $sortOrder == "asc" ? "selected" : "" )?>><?= Loc::getMessage('TAB_SORT_ASC') ?></option>
                            </select>
                            <input class="sort-radio" type="radio" name="DEFAULT_SORT_TAB_"
                                   value="<?= $values[$i] ?>" <?= ($def == $values[$i] ? 'checked' : ( empty($def) && $i == 0 ? 'checked' : '' )) ?><?=( empty($name) ? 'disabled' : '' )?>>
                            <div class="plus" onclick="up(this)">&#8593;</div>
                            <div class="minus" onclick="down(this)">&#8595;</div>
                        </div>
                        <?
                    }
                    ?>
                </div>
            </div>
            <script>
                function up(e) {
                    var el = $(e);
                    var block = el.parent();
                    block.insertBefore(block.prev());

                    return false;
                }

                function down(e) {
                    var el = $(e);
                    var block = el.parent();
                    block.insertAfter(block.next());

                    return false;
                }

                function onChange(e){
                    if($(e).val() === '')
                        $(e).closest('.tab').find('.sort-radio').attr('disabled', true);
                    else
                        $(e).closest('.tab').find('.sort-radio').attr('disabled', false);
                }
            </script>
            <style>
                .tabs {
                    border: 1px solid #e0e8ea;
                    display: inline-block;
                }

                .tabs-header {
                    display: flex;
                    text-align: center;
                    border-bottom: 1px solid #e0e8ea;
                    justify-content: left;
                    padding: 10px;
                }

                .tab-header-1 {
                    width: 175px !important;
                }

                .tab-header-2 {
                    width: 500px !important;
                    margin-right: 10px;
                }

                .tab-header-3 {
                    width: 120px !important;
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

                .tab optgroup {
                    background-color: #dadee0;
                }
                .tab select {
                    max-width: 500px;
                }

                .tabs-body input[type="text"] {
                    width: 160px;
                    margin-right: 10px;
                }

                .tabs-body label {
                    width: 70px !important;
                    margin-left: 17px;
                }

                .tabs-body .plus,
                .tabs-body .minus {
                    font-size: 18px;
                    width: 24px;
                    height: 24px;
                    border: 1px solid #e0e8ea;
                    cursor: pointer;
                    margin-right: 5px;
                    text-align: center;
                }
            </style>
            <?
        }
    }
    public function prepareRequest(&$request)
    {
        if($request['save']) {
            $values = $this->getValues();
            foreach ($values as $tab) {
                Option::set('NAME_TAB_'.$tab.'_',
                    $request['NAME_TAB_'.$tab.'_'],
                    $this->site);
                Option::$options['NAME_TAB_'.$tab.'_'] = $request['NAME_TAB_'.$tab.'_'];
                Option::set('CODE_TAB_'.$tab.'_',
                    $request['CODE_TAB_'.$tab.'_'],
                    $this->site);
                Option::$options['CODE_TAB_'.$tab.'_'] = $request['CODE_TAB_'.$tab.'_'];
                Option::set('SORT_ORDER_TAB_'.$tab.'_',
                    $request['SORT_ORDER_TAB_'.$tab.'_'],
                    $this->site);
                Option::$options['SORT_ORDER_TAB_'.$tab.'_'] = $request['SORT_ORDER_TAB_'.$tab.'_'];
                Option::set('DEFAULT_SORT_TAB_',
                    $request['DEFAULT_SORT_TAB_'],
                    $this->site);
                Option::$options['DEFAULT_SORT_TAB_'] = $request['DEFAULT_SORT_TAB_'];
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