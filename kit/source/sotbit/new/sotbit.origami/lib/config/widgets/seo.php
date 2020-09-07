<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 22-Jan-18
 * Time: 2:15 PM
 */

namespace Sotbit\Origami\Config\Widgets;

use Bitrix\Main\Loader;
use Sotbit\Origami\Config\Widget;
use Bitrix\Main\Localization\Loc;

class Seo extends Widget
{
    protected $iblock = 0;
    public function __construct($code, $settings = array())
    {
        $this->setCode($code);
        $this->setSettings($settings);
    }
    public function show()
    {
        ?>
        <div style="display: flex;width:100%">
            <div style="width:100%"><textarea style="width:90%" name="<?=$this->getCode()?>"><?=$this->getCurrentValue()?></textarea></div>
            <div><?=$this->getButton()?></div>
        </div>
        <style>
            .count_symbol_print {
                font-size: 12px;
                color: gray;
                width: 92%;
            }
            .count_symbol_print span {
                display: inline-block;
                width: 20px;
                float: right;
                text-align: right;
            }
            .progressbar{
                display: inline-block;
                height: 3px;
                width: 100px;
                float: right;
                margin-top: 4px;
            }
            .orange-color {
                color: orange;
            }
            .orange-color-bg {
                background: orange;
            }
            .green-color {
                color: green;
            }
            .green-color-bg {
                background: green;
            }
            .red-color {
                color: red;
            }
            .red-color-bg {
                background: red;
            }
            ul.navmenu-v
            {
                position:absolute;
                margin: 0;
                border: 0 none;
                padding: 0;
                list-style: none;
                z-index:9999;
                display:none;
                right:20px;
            }
            ul.navmenu-v li,
            ul.navmenu-v ul {
                margin: 0;
                border: 0 none;
                padding: 0;
                list-style: none;
                z-index:9999;
            }
            ul.navmenu-v li:hover
            {
                background:#ebf2f4;
            }
            ul.navmenu-v:after {
                clear: both;
                display: block;
                font: 1px/0px serif;
                content: " . ";
                height: 0;
                visibility: hidden;
            }

            ul.navmenu-v li {
                font-size:13px;
                font-weight:normal;
                font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;
                white-space:nowrap;
                height:30px;
                line-height:27px;
                padding-left:21px;
                padding-right:21px;
                text-shadow:0 1px white;
                display: block;
                position: relative;
                background: #FFF;
                color: #303030;
                text-decoration: none;
                cursor:pointer;
            }
            ul.navmenu-v,
            ul.navmenu-v ul,
            ul.navmenu-v ul ul,
            ul.navmenu-v ul ul ul {
                border:1px solid #d5e1e4;
                border-radius:4px;
                box-shadow:0 18px 20px rgba(72, 93, 99, 0.3);
                background:#FFF;
            }


            ul.navmenu-v ul,
            ul.navmenu-v ul ul,
            ul.navmenu-v ul ul ul {
                display: none;
                position: absolute;
                top: 0;
                right: 292px;
            }


            ul.navmenu-v li:hover ul ul,
            ul.navmenu-v li:hover ul ul ul,
            ul.navmenu-v li.iehover ul ul,
            ul.navmenu-v li.iehover ul ul ul {
                display: none;
            }

            ul.navmenu-v li:hover ul,
            ul.navmenu-v ul li:hover ul,
            ul.navmenu-v ul ul li:hover ul,
            ul.navmenu-v li.iehover ul,
            ul.navmenu-v ul li.iehover ul,
            ul.navmenu-v ul ul li.iehover ul {
                display: block;
            }
        </style>
        <script>
            $(document).on('click','#SotbitSeoMenuButton',function(){
                var NavMenu=$(this).siblings( '.navmenu-v' );
                if(NavMenu.css('display')=='none')
                {
                    $('.navmenu-v').css('display','none');
                    NavMenu.css('display','block');
                    NavMenu.find('ul').css('right',NavMenu.innerWidth());
                }
                else
                {
                    $('.navmenu-v').css('display','none');
                    NavMenu.css('display','none');
                }
            });

            $(document).on('click','.navmenu-v li.with-prop ',function(){
                if($(this).data( 'prop' )!== 'undefined')
                {
                    if($(this).closest('tr').find('iframe').length>0)
                    {
                        $(this).closest('tr').find('iframe').contents().find('body').append($(this).data( 'prop' ));
                        $(this).closest('tr').find('textarea').insertAtCaret($(this).data( 'prop' ));
                    }
                    else
                    {
                        $(this).closest('tr').find('textarea').insertAtCaret($(this).data( 'prop' ));
                        $(this).closest('tr').find('input[name=\"META_TEMPLATE[TEMPLATE_NEW_URL]\"]').insertAtCaret($(this).data( 'prop' ));
                        if($(this).closest('tr').find('textarea').length > 0)
                            triggerTextarea($(this).closest('tr').find('textarea'));
                    }

                }
            });

            //For add in textarea in focus place
            jQuery.fn.extend({
                insertAtCaret: function(myValue){
                    return this.each(function(i) {
                        if (document.selection) {
                            // Internet Explorer
                            this.focus();
                            var sel = document.selection.createRange();
                            sel.text = myValue;
                            this.focus();
                        }
                        else if (this.selectionStart || this.selectionStart == '0') {
                            //  Firefox and Webkit
                            var startPos = this.selectionStart;
                            var endPos = this.selectionEnd;
                            var scrollTop = this.scrollTop;
                            this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
                            this.focus();
                            this.selectionStart = startPos + myValue.length;
                            this.selectionEnd = startPos + myValue.length;
                            this.scrollTop = scrollTop;
                        } else {
                            this.value += myValue;
                            this.focus();
                        }
                    })
                }
            });
        </script>
        <?
    }
    public function setIblock($iblock = 0){
        $this->iblock = $iblock;
    }
    protected function getButton(){
        
        $return = '';
        
        // Find Iblocks product and offers
        $return .= '<input type="button" value="..." id="SotbitSeoMenuButton" style="float:left;">
                <div style="clear:both"></div>
                <ul class="navmenu-v">';
        
        
        $return .= '
                    <li class="with-prop" data-prop="{=product.Name}">' . GetMessage( "ORIGAMI_MENU_ELEMENT_FIELDS" ) . '
                    </li>
                    ';
        
        $return .= '
                    <li class="with-prop" data-prop="{=offer.Name}">' . GetMessage( "ORIGAMI_MENU_SKU_FIELDS" ) . '
                    </li>
                    ';
        $return .= '
                    <li class="with-prop" data-prop="{=prop.Name}">' . GetMessage( "ORIGAMI_MENU_SKU_FIELDS_NAME" ) . '
                    </li>
                    ';
        $return .= '
                    <li class="with-prop" data-prop="{=prop.Value}">' . GetMessage( "ORIGAMI_MENU_SKU_FIELDS_VALUE" ) . '
                    </li>
                    ';
        
        $return .= "</ul>";
        return $return;
    }
}
?>