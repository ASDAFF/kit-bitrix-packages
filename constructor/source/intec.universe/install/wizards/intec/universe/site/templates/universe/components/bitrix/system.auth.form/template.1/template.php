<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
use Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

$strMainID = $this->GetEditAreaId($arResult['ID']);

$arAuthOptions = [
    'component' => 'bitrix:system.auth.authorize',
    'template' => 'template.1',
    'parameters' => [
        "AUTH_URL" => $arParams['PROFILE_URL'],
        "BACKURL" => $arResult['BACKURL'],
        "AUTH_REGISTER_URL" => $arParams["REGISTER_URL"],
        "AUTH_FORGOT_PASSWORD_URL" => $arParams['FORGOT_PASSWORD_URL'],
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => $strMainID."_auth_form",
        'AUTH_RESULT' => $APPLICATION->arAuthResult
    ]
];

$bUserPhoneRequired = COption::GetOptionString('main','new_user_phone_auth');
$arRegFields = [
    'EMAIL',
    'NAME'
];
if ($bUserPhoneRequired !== 'Y')
    $arRegFields[] = 'PERSONAL_PHONE';

$arRegOptions = [
    'component' => 'bitrix:main.register',
    'template' => 'template.1',
    'parameters' => [
        "SHOW_FIELDS" => $arRegFields,
        "REQUIRED_FIELDS" => array(
            0 => "EMAIL",
            1 => "NAME",
        ),
        "AUTH" => "Y",
        "USE_BACKURL" => "Y",
        "SUCCESS_PAGE" => "",
        "SET_TITLE" => "N",
        "USER_PROPERTY" => array(
        ),
        "USER_PROPERTY_NAME" => "",
        "COMPONENT_TEMPLATE" => "template.1",
        "AJAX_MODE" => "Y",
        "AJAX_OPTION_ADDITIONAL" => $strMainID."_register_form"
    ]
];
?>
<div class="ns-bitrix c-system-auth-form c-system-auth-form-template-1" id="<?= $strMainID ?>">
    <div class="system-auth-form-tabs-panel intec-grid intec-grid-wrap intec-grid-a-v-center">
        <div class="system-auth-form-tabs-panel-item tab-auth intec-grid-item active"
             onclick="universe.components.get(
                    <?=CUtil::PhpToJSObject($arAuthOptions)?>,
                    function(popup){
                        $('#<?= $strMainID ?> .system-auth-form-body').html(popup);
                    })">
            <?= Loc::getMessage('SYSTEM_AUTH_FORM_TEMPLATE1_AUTHORIZATION') ?>
        </div>
        <div class="system-auth-form-tabs-panel-item tab-reg intec-grid-item"
             onclick="universe.components.get(
                <?=CUtil::PhpToJSObject($arRegOptions)?>,
                function(popup){
                $('#<?= $strMainID ?> .system-auth-form-body').html(popup);
                })">
            <?= Loc::getMessage('SYSTEM_AUTH_FORM_TEMPLATE1_REGISTRATION') ?>
        </div>
    </div>
    <div class="system-auth-form-body">
        <? $APPLICATION->IncludeComponent(
                $arAuthOptions['component'],
                $arAuthOptions['template'],
                $arAuthOptions['parameters'],
                false
        )?>
    </div>
</div>

<script>
    $('.system-auth-form-tabs-panel-item').click(function(){
        $('.system-auth-form-tabs-panel-item').removeClass('active');
        $(this).addClass('active');
    });
</script>