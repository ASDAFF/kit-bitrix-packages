<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
if($arResult['ITEMS'])
{?>
    <div class="kit-seometa-tags-row-container">
    <div class="kit-seometa-tags-container">
        <div class="kit-seometa-tags__title"><?=GetMessage('POPULAR_TAGS');?></div>
	<?foreach($arResult['ITEMS'] as $Item)
	{
		?>
		<div class="kit-seometa-tags-wrapper">
			<?
			if($Item['TITLE'] && $Item['URL'])
			{
				?>
				<div class="kit-seometa-tag">
					<a class="kit-seometa-tag-link" href="<?=$Item['URL'] ?>" title="<?=$Item['TITLE'] ?>"><?=$Item['TITLE'] ?></a>
				</div>
				<?
			}
			?>
		</div>
		<?
	}?>
    </div>
    <div class="kit-seometa-tags__hide">
        <div class="seometa-tags__hide"><?=GetMessage('POPULAR_HIDE');?><i class="fa fa-angle-up" aria-hidden="true"></i></div>
        <div class="seometa-tags__show"><?=GetMessage('POPULAR_SHOW');?><i class="fa fa-angle-down" aria-hidden="true"></i></div>
    </div>
    </div>
<?}
?>