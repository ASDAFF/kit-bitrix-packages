<?
namespace Sotbit\Origami\Sale\Basket;

/**
 * Class Compare
 * @package Sotbit\Origami\Sale\Basket
 * @author Sergey Danilkin <s.danilkin@sotbit.ru>
 */
class Compare extends Base
{
	/**
	 * @return int
	 */
	public function getCompared()
	{
		$count = 0;
		if($_SESSION['CATALOG_COMPARE_LIST'])
		{
			foreach($_SESSION['CATALOG_COMPARE_LIST'] as $iblockId => $compare)
			{
			    if($compare['ITEMS']) {
                    foreach ($compare['ITEMS'] as $k => $item) {
                        ++$count;
                    }
                }
			}
		}
		return $count;
	}

    public function getCompareItems()
    {
        $arItems = array();
        if($_SESSION['CATALOG_COMPARE_LIST'])
        {
            foreach($_SESSION['CATALOG_COMPARE_LIST'] as $iblockId => $compare)
            {

                $arTmp = array_keys($compare['ITEMS']);
                $arItems = array_merge($arItems, $arTmp);
            }
        }
        return $arItems;
    }
}
?>