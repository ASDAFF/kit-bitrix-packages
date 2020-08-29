<?
use Bitrix\Main\Loader;
use Kit\Origami\Helper\Config;

$showFooter = false;
if ($_REQUEST['ajax_mode'] == 'Y') {
   require $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php";
   if ($USER->GetID()) {
      echo '<script>setTimeout(function(){ location.reload(); }, 0);</script>';
   } else {
      $APPLICATION->AuthForm('', false, false, 'N', false);
   }
   die;
} elseif (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
   require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
   $showFooter = true;
   LocalRedirect('/');
}
CJSCore::Init(['phone_number']);
CJSCore::Init(["popup", "jquery"]);

?>
<?if (!$USER->IsAuthorized()):

    Loader::includeModule('kit.origami');
    $telMask = \Kit\Origami\Config\Option::get('MASK', SITE_ID);?>
    <?$jsAuthVariable = 'fix' . \Bitrix\Main\Security\Random::getString(20)?>
    <?
    global $APPLICATION;
    $templateTitle = COption::GetOptionString('main', 'auth_components_template');
    if ($templateTitle == "")
        $templateTitle = ".default";

    $APPLICATION->SetAdditionalCSS(SITE_DIR . "local/templates/kit_origami/components/bitrix/system.auth.registration/" . $templateTitle . "/style.css");
    $APPLICATION->SetAdditionalCSS(SITE_DIR . "local/templates/kit_origami/components/bitrix/system.auth.forgotpasswd/" . $templateTitle . "/style.css");
    $APPLICATION->SetAdditionalCSS(SITE_DIR . "local/templates/kit_origami/components/bitrix/system.auth.authorize/" . $templateTitle . "/style.css");
    $APPLICATION->SetAdditionalCSS(SITE_DIR . "local/templates/kit_origami/components/bitrix/socserv.auth.form/" . $templateTitle . "/style.css");

    ?>

<?if(Config::get('HEADER') == 1):?>
    <a href="#" onclick="<?=$jsAuthVariable?>.showPopup('/auth/')" rel="nofollow"><?=GetMessage('LOGIN')?></a>
<?elseif(Config::get('HEADER') == 5):?>
    <a class="header-three__personal-link" href="#" onclick="<?=$jsAuthVariable?>.showPopup('/auth/')" rel="nofollow">
        <div class="header-three__personal-photo">
            <img src="" alt="">
        </div>
        <span> <?=GetMessage('LOGIN')?></span>
    </a>
<?else:?>
    <a href="#" onclick="<?=$jsAuthVariable?>.showPopup('/auth/')" rel="nofollow">
        <svg width="18" height="20">
            <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_login"></use>
        </svg>
        <?=GetMessage('LOGIN')?>
    </a>
<?endif;?>

   <script>
        let <?=$jsAuthVariable?> = {
            id: "modal_auth",
            popup: null,
            convertLinks: function() {
                let links = $("#" + this.id + " a");
                links.each(function (i) {
                    $(this).attr('onclick', "<?=$jsAuthVariable?>.set('" + $(this).attr('href') + "')");
                });
                links.attr('href', '#');

                let form = $("#" + this.id + " form");
                form.attr('onsubmit', "<?=$jsAuthVariable?>.submit('" + form.attr('action') + "');return false;");
            },
            showPopup: function(url) {
                let app = this;
                this.popup = BX.PopupWindowManager.create(this.id, '', {
                    closeIcon: true,
                    autoHide: true,
                    draggable: {
                        restrict: true
                    },
                    closeByEsc: true,
                    content: this.getForm(url),
                    overlay: {
                        backgroundColor: 'black',
                        opacity: '20'
                    },
                    events: {
                        onPopupClose: function(PopupWindow) {
                            PopupWindow.destroy();
                        },
                        onAfterPopupShow: function (PopupWindow) {
                            app.convertLinks();
                        }
                    }
                });

                this.popup.show();
            },

            getForm: function(url) {
                let content = null;
                url += (url.includes("?") ? '&' : '?') + 'ajax_mode=Y';
                BX.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'html',
                    async: false,
                    preparePost: false,
                    start: true,
                    processData: false,
                    skipAuthCheck: true,
                    onsuccess: function(data) {
                        let html = BX.processHTML(data);
						content = html.HTML;
                        html.SCRIPT.forEach((item) => {
                            console.log(item.JS)
                            BX.evalGlobal(item.JS)
                        })
                    },
                    onfailure: function(html, e) {
                        console.error('getForm onfailure html', html, e, this);
                    }
                });

                return content;
            },

            set: function(url) {
                let form = this.getForm(url);
                this.popup.setContent(form);
                this.popup.adjustPosition();
                this.convertLinks();
				if (document.querySelector('.js-phone')) {
				    $(document).ready(function () {
        				$('.js-phone').inputmask("<?= $telMask ?>");
					});
				}
            },
 
            submit: function(url) {
                let app = this;
                let form = document.querySelector("#" + this.id + " form");
                let data = BX.ajax.prepareForm(form).data;
                data.ajax_mode = 'Y';

                BX.ajax({
                    url: url,
                    data: data,
                    method: 'POST',
                    preparePost: true,
                    dataType: 'html',
                    async: false,
                    start: true,
                    processData: true,
                    skipAuthCheck: true,
                    onsuccess: function(data) {
                        let html = BX.processHTML(data);
                        app.popup.setContent(html.HTML);
                        app.convertLinks();
                    },
                    onfailure: function(html, e) {
                        console.error('getForm onfailure html', html, e, this);
                    }
                });
            }
        };
   </script>
<?endif?>
<?if ($showFooter) require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
