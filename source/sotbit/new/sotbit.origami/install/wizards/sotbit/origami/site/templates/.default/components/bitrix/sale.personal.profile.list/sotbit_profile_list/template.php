<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

if(strlen($arResult["ERROR_MESSAGE"])>0)
{
	ShowError($arResult["ERROR_MESSAGE"]);
}
if(strlen($arResult["NAV_STRING"]) > 0)
{
	?>
	<p><?=$arResult["NAV_STRING"]?></p>
	<?
}

if (count($arResult["PROFILES"]))
{
	?>
    <div class="sale-personal-profile__techno_block">
        <div class="table sale-personal-profile-list-container">
            <div class="sale-personal-profile-list-container-row">
                <?
                $dataColumns = array(
                    "ID", "DATE_UPDATE", "NAME", "PERSON_TYPE_ID"
                );
                foreach ($dataColumns as $column)
                {
                    ?>
                    <div class="sale-personal-profile-list-container-item">
                        <?=Loc::getMessage("P_".$column)?>
                        <a class="sale-personal-profile-list-arrow-up" href="<?=$arResult['URL']?>by=<?=$column?>&order=asc#nav_start">
                            <i class="fas fa-long-arrow-alt-up"></i>
                        </a>
                        <a class="sale-personal-profile-list-arrow-down" href="<?=$arResult['URL']?>by=<?=$column?>&order=desc#nav_start">
                            <i class="fas fa-long-arrow-alt-down"></i>
                        </a>
                    </div>
                    <?
                }
                ?>
                <div class="sale-personal-profile-list-container-item"><?=Loc::getMessage("SALE_ACTION")?></div>
            </div>
            <?foreach($arResult["PROFILES"] as $val)
            {
                ?>
                <div class="sale-personal-profile-list-container-row">
                    
                    <div class="sale-personal-profile-list-container-item">
                        <span class="comment"><?=Loc::getMessage("P_ID")?></span>
                        <b><?= $val["ID"] ?></b></div>
                    <div class="sale-personal-profile-list-container-item">
                        <span class="comment"><?=Loc::getMessage("P_DATE_UPDATE")?></span>
                        <?= $val["DATE_UPDATE"] ?>
                    </div>
                    <div class="sale-personal-profile-list-container-item">
                        <span class="comment"><?=Loc::getMessage("P_NAME")?></span>
                        <?= $val["NAME"] ?></div>
                    <div class="sale-personal-profile-list-container-item">
                        <span class="comment"><?=Loc::getMessage("P_PERSON_TYPE_ID")?></span>
                        <?= $val["PERSON_TYPE"]["NAME"] ?></div>
                    <div class="sale-personal-profile-list-container-item sale-personal-profile-list-actions">
                        <a class="sale-personal-profile-list-change-button" title="<?= Loc::getMessage("SALE_DETAIL_DESCR") ?>"
                            href="<?= $val["URL_TO_DETAIL"] ?>"><?= GetMessage("SALE_DETAIL") ?>
                        </a>
                        <span class="sale-personal-profile-list-border"></span>
                        <a class="sale-personal-profile-list-close-button" title="<?= Loc::getMessage("SALE_DELETE_DESCR") ?>"
                            href="javascript:if(confirm('<?= Loc::getMessage("STPPL_DELETE_CONFIRM") ?>')) window.location='<?= $val["URL_TO_DETELE"] ?>'">
                            <?= Loc::getMessage("SALE_DELETE") ?>
                        </a>
                    </div>
                </div>
                <?
            }?>
        </div>
    </div>
	<?
	if(strlen($arResult["NAV_STRING"]) > 0)
	{
		?>
		<p><?=$arResult["NAV_STRING"]?></p>
		<?
	}
}
else
{
	?>
	<h3><?=Loc::getMessage("STPPL_EMPTY_PROFILE_LIST") ?></h3>
	<?
}
?>
