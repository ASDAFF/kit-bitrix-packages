<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if ($arResult) {

    $bWasSelected = false;
    $arParents = array();
    $depth = 1;

    foreach($arResult as $i=>$arMenu)
    {
        $depth = $arMenu['DEPTH_LEVEL'];

        //if($arMenu['IS_PARENT'])
        {
            $arParents[$arMenu['DEPTH_LEVEL']-1] = $i;
        }
        //else
        if($arMenu['SELECTED'])
        {
            $bWasSelected = true;
            break;
        }
    }

    if($bWasSelected)
    {
        for($i=0; $i<$depth-1; $i++)
            $arResult[$arParents[$i]]['CHILD_SELECTED'] = true;
    }

    $oMenu = new \Sotbit\Origami\Helper\Menu();
    $menu = [];
    foreach ($arResult as $i => $li) {
        if ($li['DEPTH_LEVEL'] == 1) {
            $children = $oMenu->findMenuChildren($i, $arResult);
            $li['CHILDREN'] = $children;
            $menu[] = $li;
        }
    }
    $arResult = $menu;
}
?>