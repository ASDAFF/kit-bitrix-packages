<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\JavaScript;

/**
 * @var string $sTemplateId
 */

?>
<script type="text/javascript">
    (function ($, api) {
        var handler;

        handler = function () {
            var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
            var buttons;
            var modal;
            var window;
            var data;

            data = <?= JavaScript::toObject([
                'id' => $sTemplateId.'-modal',
                'title' => Loc::getMessage('W_HEADER_S_A_F_AUTH_FORM_TITLE')
            ]) ?>;

            modal = $('[data-role="modal"]', root);
            modal.open = function () {
                window.setContent(modal.clone().get(0));
                window.show();
            };

            window = new BX.PopupWindow(data.id, null, {
                'content': null,
                'title': data.title,
                'closeIcon': {
                    'right': '20px',
                    'top': '22px'
                },
                'zIndex': 0,
                'offsetLeft': 0,
                'offsetTop': 0,
                'width': 800,
                'overlay': true,
                'titleBar': {
                    'content': BX.create('span', {
                        'html': data.title,
                        'props': {
                            'className': 'access-title-bar'
                        }
                    })
                }
            });

            buttons = {};
            buttons.login = $('[data-action="login"]', root);
            buttons.login.on('click', modal.open);
        };

        $(document).on('ready', handler);
        BX.addCustomEvent('onFrameDataReceived' , handler);
    })(jQuery, intec);
</script>
