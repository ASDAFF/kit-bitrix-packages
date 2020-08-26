<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
if($arResult['ITEMS'])
{?>
    <div class="sotbit-seometa-tags-row-container">
    <div class="sotbit-seometa-tags-container">
        <div class="sotbit-seometa-tags__title"><?=GetMessage('POPULAR_TAGS');?></div>
	<?foreach($arResult['ITEMS'] as $Item)
	{
		?>
		<div class="sotbit-seometa-tags-wrapper">
			<?
			if($Item['TITLE'] && $Item['URL'])
			{
				?>
				<div class="sotbit-seometa-tag">
					<a class="sotbit-seometa-tag-link" href="<?=$Item['URL'] ?>" title="<?=$Item['TITLE'] ?>"><?=$Item['TITLE'] ?></a>
				</div>
				<?
			}
			?>
		</div>
		<?
	}?>
    </div>
    <div class="sotbit-seometa-tags__hide">
        <div class="seometa-tags__hide"><?=GetMessage('POPULAR_HIDE');?><i class="fa fa-angle-up" aria-hidden="true"></i></div>
        <div class="seometa-tags__show"><?=GetMessage('POPULAR_SHOW');?><i class="fa fa-angle-down" aria-hidden="true"></i></div>
    </div>
    </div>
<?}
?>