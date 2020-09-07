<?php
namespace Kit\Origami\Config\Widgets;

use Bitrix\Main\Localization\Loc;
use Kit\Origami\Config\Widget;

class Code extends Widget
{
    private $path = '';

	public function show()
	{
		echo "<a class=\"adm-btn\" href=\"javascript: new BX.CAdminDialog({'content_url':'/bitrix/admin/public_file_edit.php?path=".$this->getPath()."&lang=ru&noeditor=Y','width':'1009','height':'503'}).Show();\" name=\"TEMPLATE_".$this->getCode()."_s1\" title=\"".Loc::getMessage('EDIT_LINK_TITLE')."\">".Loc::getMessage('EDIT_LINK_TITLE')."</a>";
	}
    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }
}
?>