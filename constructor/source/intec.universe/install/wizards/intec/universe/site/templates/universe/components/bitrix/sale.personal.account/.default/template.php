<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\Type;
use Bitrix\Main\Localization\Loc;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

if (!Type::isArray($arResult['ACCOUNT_LIST']) || empty($arResult['ACCOUNT_LIST']))
    return;
?>

<div class="ns-bitrix c-sale-personal-account c-sale-personal-account-default" id="<?= $sTemplateId ?>">
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <div class="sale-personal-account-content">
                <div class="sale-personal-account-title">
                    <?=Loc::getMessage('SPA_BILL_AT')?>
                    <?=$arResult['DATE'];?>
                </div>
                <div class="sale-personal-account-items">
                    <?php foreach($arResult['ACCOUNT_LIST'] as $accountValue) {?>
                        <div class="sale-personal-account-item ">
                            <div class="sale-personal-account-sum"><?=$accountValue['SUM']?></div>
                            <div class="sale-personal-account-currency">
                                <div class="sale-personal-account-currency-item"><?=$accountValue['CURRENCY']?></div>
                                <div class="sale-personal-account-currency-item"><?=$accountValue['CURRENCY_FULL_NAME']?></div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>