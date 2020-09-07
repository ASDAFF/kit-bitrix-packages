<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $APPLICATION;

if(empty($arResult))
    return "";

$strReturn = '';
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$strReturn .= '<div class="breadcrumb_block" itemscope itemtype="http://schema.org/BreadcrumbList">';
$itemSize = count($arResult);
for($index = 0; $index < $itemSize; $index++)
{
    $title = htmlspecialcharsex($arResult[$index]["TITLE"]);
    $arrow = ($index > 0? '
		<i class="icon-nav_2"></i>' : '');

    if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1)
    {
        $strReturn .= '
			<div class="breadcrumb_block__item fonts__middle_comment" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
				'.$arrow.'
				<span >
                    <a href="'.$arResult[$index]['LINK'].'" onclick="" title="'.$title.'" itemprop="item" itemid="'.$protocol . $_SERVER['SERVER_NAME'].$arResult[$index]['LINK'].'">
                        <span itemprop="name">'.$title.'</span>
                    </a>
                    <meta itemprop="position" content="'.($index + 1).'" />
				</span>
			</div>';
    }
    else
    {
        $strReturn .= '
			<div class="breadcrumb_block__item fonts__middle_comment" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
				'.$arrow.'
				<span >
                    <span itemprop="name" class="breadcrumb_block__item_no_link" itemprop="item" itemprop="name" itemid="'.$protocol . $_SERVER['SERVER_NAME'].$arResult[$index]['LINK'].'">'.$title.'</span>
                    <meta itemprop="position" content="'.($index + 1).'" />
				</span>
			</div>';
    }
}

$strReturn .= '</div>';

return $strReturn;
