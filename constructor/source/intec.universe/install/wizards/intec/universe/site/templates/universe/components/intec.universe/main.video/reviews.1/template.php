<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$sId = $sTemplateId.'_'.$arResult['ITEM']['ID'];
$sAreaId = $this->GetEditAreaId($sId);
$this->AddEditAction($sId, $arResult['ITEM']['EDIT_LINK']);
$this->AddDeleteAction($sId, $arResult['ITEM']['DELETE_LINK']);

$arVisual = ArrayHelper::getValue($arResult, 'VISUAL');
$arCodes = ArrayHelper::getValue($arResult, 'PROPERTY_CODES');
$arLink = ArrayHelper::getValue($arResult, ['ITEM', 'PROPERTIES', $arCodes['LINK'], 'VALUE']);

$sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
if (!empty($arResult['PICTURE']['SRC'])) {
    $sPicture = $arResult['PICTURE']['SRC'];
}
?>
<?php if ($arVisual['TEMPLATE_SHOW']) { ?>
    <div class="widget c-video c-video-template-1" id="<?= $sTemplateId ?>">
        <div class="widget-element-wrap">
            <?= Html::beginTag('div', [ /** Главный тег элемента */
                'class' => 'widget-element',
                'id' => $sAreaId,
                'data-src' => $arLink,
                'data-stellar-background-ratio' => $arVisual['PARALLAX']['USE'] ? $arVisual['PARALLAX']['RATIO'] : null,
                'style' => [
                    'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                ],
                'data' => [
                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                    'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                ],
            ]) ?>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="widget-element-icon" style="fill: <?= $arVisual['COLOR_THEME'] ?>">
                    <path d="M216 354.9V157.1c0-10.7 13-16.1 20.5-8.5l98.3 98.9c4.7 4.7 4.7 12.2 0 16.9l-98.3 98.9c-7.5 7.7-20.5 2.3-20.5-8.4zM256 8c137 0 248 111 248 248S393 504 256 504 8 393 8 256 119 8 256 8zm0 48C145.5 56 56 145.5 56 256s89.5 200 200 200 200-89.5 200-200S366.5 56 256 56z"></path>
                </svg>
            <?= Html::endTag('div') ?>
        </div>
    </div>
    <script>
        (function ($, api) {
            $(document).ready(function () {
                var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);

                <?php if (!defined('EDITOR')) { ?>
                    $('.widget-element-wrap', root).lightGallery();
                <?php } ?>
            });
        })(jQuery, intec);
    </script>
<?php } else { ?>
    <div style="text-align: center; color: red; padding: 30px;">
        <?= Loc::getMessage('C_VIDEO_TEMP1_TEMPLATE_SHOW') ?>
    </div>
<?php } ?>