<?php

namespace Kit\Origami\Config\Widgets;

use Kit\Origami\Config\Widget;

class File extends Widget
{
	public function show()
	{
		\Bitrix\Main\Loader::includeModule("fileman");
		echo \Bitrix\Main\UI\FileInput::createInstance([
			"name" => $this->getCode(),
			"description" => true,
			"upload" => true,
			"allowUpload" => "",
			"medialib" => true,
			"fileDialog" => '',
			"cloud" => true,
			"delete" => true,
			"maxCount" => 1
		])->show($this->getCurrentValue());
	}

	public function prepareRequest(&$request)
	{
		if($request[$this->getCode() . '_del'])
		{
			$request[$this->getCode()] = 0;
		}
		if($request[$this->getCode()])
		{
			if($request[$this->getCode()] && is_array($request[$this->getCode()]))
			{
				$file = \CIBlock::makeFileArray(
					$request[$this->getCode()],
					false,
					$request[$this->getCode() . '_descr']
				);
				$fid = \CFile::SaveFile($file, 'kit.origami');
				$request[$this->getCode()] = $fid;
			}
		}
	}
}
?>