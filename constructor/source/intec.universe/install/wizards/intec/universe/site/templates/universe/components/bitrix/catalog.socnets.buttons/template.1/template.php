<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Json;
use intec\core\net\Url;

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$arVisual = $arResult['VISUAL'];

$arSocials = [
    'VK' => [
        'URL' => 'https://vk.com/share.php',
        'PARAMETERS' => [
            'url' => $arResult['URL_TO_LIKE'],
            'title' => $arResult['TITLE'],
            'description' => $arResult['DESCRIPTION'],
            'image' => $arResult["IMAGE"],
            'noparse' => 'true'
        ],
        'SHOW' => $arVisual['VK']['SHOW'],
        'ICON' => '<i class="fab fa-vk" style="color: #2991CB"></i>'
    ],
    'TW' => [
        'URL' => 'https://twitter.com/intent/tweet',
        'PARAMETERS' => [
            'text' => $arResult['TITLE'],
            'url' => $arResult['URL_TO_LIKE'],
            'via' => '',
        ],
        'SHOW' => $arVisual['TW']['SHOW'],
        'ICON' => '<i class="fab fa-twitter" style="color: #11CBF3"></i>'
    ],
    'FB' => [
        'URL' => 'https://www.facebook.com/sharer/sharer.php',
        'PARAMETERS' => [
            'u' => $arResult['URL_TO_LIKE'],
        ],
        'SHOW' => $arVisual['FB']['SHOW'],
        'ICON' => '<i class="fab fa-facebook-square" style="color: #0085FF;"></i>'
    ],
    'OK' => [
        'URL' => 'https://connect.ok.ru/offer',
        'PARAMETERS' => [
            'url' => $arResult['URL_TO_LIKE'],
            'title' => $arResult['TITLE'],
            'imageUrl' => $arResult['IMAGE'],
        ],
        'SHOW' => $arVisual['OK']['SHOW'],
        'ICON' => '<i class="fab fa-odnoklassniki" style="color: #f7931e"></i>'
    ],
    'PINTEREST' => [
        'URL' => 'https://pinterest.com/pin/create/button/',
        'PARAMETERS' => [
            'url' => $arResult['URL_TO_LIKE'],
            'description' => $arResult['DESCRIPTION'],
            'media' => $arResult["IMAGE"],
        ],
        'SHOW' => $arVisual['PINTEREST']['SHOW'],
        'ICON' => '<i class="fab fa-pinterest" style="color: #e60023"></i>'
    ],
];

?>
<!--noindex-->
<div class="ns-bitrix c-catalog-socnets-buttons c-catalog-socnets-buttons-template-1" id="<?= $sTemplateId ?>">
    <button class="catalog-socnets-buttons-share" data-role="button">
        <i class="fas fa-share-alt"></i>
    </button>
    <div class="catalog-socnets-buttons-items-wrap" data-role="shares">
        <div class="catalog-socnets-buttons-items">
            <div class="intec-grid intec-grid-nowrap intec-grid-i-10">
                <?php foreach($arSocials as $k => $arSocial) { ?>
                    <?php if ($arSocial['SHOW']) { ?>
                        <div class="intec-grid-item-auto">
                            <?= Html::tag('div', $arSocial['ICON'], [
                                'class' => 'catalog-socnets-buttons-item',
                                'data' => [
                                    'role' => 'share',
                                    'link' => $arSocial['URL'],
                                    'params' => Json::encode($arSocial['PARAMETERS'], JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true)
                                ],
                                'rel' => 'nofollow'
                            ]) ?>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <script>
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var share = $('[data-role="share"]', root);

        var button = $('[data-role="button"]', root);
        var shares = $('[data-role="shares"]', root);

        button.on('click', function(){
            shares.toggleClass('active');
        });

        function getParams(params){
            var p = params || {},
                keys = Object.keys(p),
                i,
                str = keys.length > 0 ? '?' : '';
            for (i = 0; i < keys.length; i++) {
                if (str !== '?') {
                    str += '&';
                }
                if (p[keys[i]]) {
                    str += keys[i] + '=' + encodeURIComponent(p[keys[i]]);
                }
            }

            return str;
        }

        share.on('click', function(){
            var params = getParams($(this).data('params'));

            shareLink = $(this).data('link');
            shareLink = shareLink += params;

            window.open(
                shareLink,
                'displayWindow',
                'width=700,height=400,left=200,top=100,location=no, directories=no,status=no,toolbar=no,menubar=no'
            );

            return false;
        });
    </script>
</div>
<!--/noindex-->