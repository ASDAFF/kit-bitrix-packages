<?php
namespace Sotbit\Origami\Config;

use Bitrix\Main\Localization\Loc;
/**
 * Class Admin
 * @package Sotbit\Origami\Config
 * @author Sergey Danilkin <s.danilkin@sotbit.ru>
 */
class Admin extends \SotbitOrigami
{
	/**
	 * @var \Sotbit\Origami\Collection
	 */
	protected     $tabs;
	/**
	 * @var string
	 */
	protected     $site = '';

	/**
	 * Admin constructor.
	 * @param $site
	 */
	public function __construct($site)
	{
		$this->tabs = new \Sotbit\Origami\Collection();
		$this->site = $site;
	}
	public function show()
	{
		if (parent::getDemo() == 2)
		{
			?>
			<div class="adm-info-message-wrap adm-info-message-red">
				<div class="adm-info-message">
					<div class="adm-info-message-title"><?=Loc::getMessage( parent::moduleId."_DEMO")?></div>
					<div class="adm-info-message-icon"></div>
				</div>
			</div>
			<?
		}
		if (parent::getDemo() == 3)
		{
			?>
			<div class="adm-info-message-wrap adm-info-message-red">
				<div class="adm-info-message">
					<div class="adm-info-message-title"><?=Loc::getMessage( parent::moduleId."_DEMO_END")?></div>
					<div class="adm-info-message-icon"></div>
				</div>
			</div>
			<?
		}

        $tabControl = $this->getAdminTabControl();
		global $APPLICATION;
		if($_REQUEST['update'] == 'Y' && $_REQUEST['mid'] == parent::moduleId)
		{
		    $this->handleRequest($_REQUEST);
            LocalRedirect( $APPLICATION->GetCurPageParam($tabControl->ActiveTabParam()));
		}

		$tabControl->Begin();
		echo $this->openForm();
		foreach($this->getTabs()->getItems() as $tab)
		{
			$tabControl->BeginNextTab();
			foreach($tab->getGroups()->getItems() as $group)
			{
				echo '<tr class="heading"><td colspan="'.$group->getSetting('COLSPAN').'">'.
					Loc::getMessage(parent::moduleId.'_GROUP_'.$group->getCode()).
					'</td></tr>';
				foreach($group->getWidgets()->getItems() as $widget)
				{
					$value = Option::get($widget->getCode(), $this->site);

					if(!is_null($value))
					{
						$widget->setCurrentValue($value);
					}

					$widget->setValues();

					$widgetSettings = $widget->getSettings();
					if($widgetSettings['CUSTOM_ROW'])
					{
						$widget->show();
					}
					else
					{
						$colspan = $widget->getSetting('COLSPAN');
						echo '<tr><td width="50%" colspan="'.$colspan[0].'">';
						echo Loc::getMessage(parent::moduleId.'_WIDGET_'.$widget->getCode());
						echo ':</td><td width="50%" colspan="'.$colspan[1].'">';
						$widget->show();
						echo '</td></tr>';
						$note = $widget->getSetting('NOTE');
						if($note)
						{
							echo '<tr><td align="center" colspan="2">
										<div align="center" class="adm-info-message-wrap">
											<div class="adm-info-message">
												'.$note.'
											</div>
										</div>
									</td></tr>';
						}
					}
				}
			}

		}
		$tabControl->Buttons();
		echo $this->closeForm();
		bitrix_sessid_post();
		$tabControl->End();
	}
	/**
	 * @return string
	 */
	private function openForm()
	{
		global $APPLICATION;

		return '<form name="' . parent::moduleId . '" method="POST" action="' . $APPLICATION->GetCurPage() . '?mid='
			. parent::moduleId . '&lang=' . LANGUAGE_ID . '&site='.$this->site.'" enctype="multipart/form-data">' .
			bitrix_sessid_post();
	}

	private function closeForm()
	{
		return '<input 
					type="hidden" 
					value="' . $_REQUEST["tabControl_active_tab"] . '" 
					name="tabControl_active_tab" 
					id="tabControl_active_tab" />
				<input 
					type="hidden" 
					name="update" 
					value="Y" />
				<input 
					type="submit" 
					name="save" 
					value="' . Loc::getMessage(parent::moduleId . "_MAIN_SAVE") . '" />				
				</form>';
	}
	/**
	 * @return \Sotbit\Origami\Collection
	 */
	public function getTabs()
	{
		return $this->tabs;
	}

	/**
	 * @param $request
	 * @throws \Bitrix\Main\ArgumentException
	 * @throws \Bitrix\Main\Db\SqlQueryException
	 */
	private function handleRequest($request)
	{
		foreach ($this->getTabs()->getItems() as $tab)
		{
			foreach ($tab->getGroups()->getItems() as $group)
			{
				foreach ($group->getWidgets()->getItems() as $widget)
				{
					$widget->prepareRequest($request);

					if(isset($request[$widget->getCode()]))
					{
						//if($request['save'])
						{
							Option::set(
								$widget->getCode(),
								$request[$widget->getCode()],
								$this->site);
						}
						//Option::$options[$widget->getCode()] = $request[$widget->getCode()];
					}
				}
			}
		}
		\SotbitOrigami::genTheme(Option::$options,'/local/templates/sotbit_origami/theme/custom');
		if($_SESSION['SOTBIT_ORIGAMI_THEME']['TMP'])
		{
			$files = scandir($_SERVER['DOCUMENT_ROOT'] .$_SESSION['SOTBIT_ORIGAMI_THEME']['TMP']);

			foreach ($files as $file)
			{
				if(in_array($file, [
						'.',
						'..',
						'variables.scss'
					]) || strpos($file, '.css') === false)
				{
					continue;
				}
				unlink($_SERVER['DOCUMENT_ROOT'] . $_SESSION['SOTBIT_ORIGAMI_THEME']['TMP'] . '/' . $file);
			}
			rmdir($_SERVER['DOCUMENT_ROOT'] . $_SESSION['SOTBIT_ORIGAMI_THEME']['TMP']);
		}
		unset($_SESSION['SOTBIT_ORIGAMI_THEME']);
	}
	/**
	 * @return \CAdminTabControl
	 */
	private function getAdminTabControl()
	{
		$tabs = array();
		foreach ($this->getTabs()->getItems() as $tab)
		{
			$tabs[] = array(
				'DIV' => 'edit_access_tab_'.$tab->getCode(),
				'TAB' => Loc::getMessage(parent::moduleId . "_TAB_" . $tab->getCode()),
				'ICON' => '',
				'TITLE' => Loc::getMessage(parent::moduleId . "_TAB_" . $tab->getCode())
			);
		}
		return new \CAdminTabControl('tabControl', $tabs);
	}
	private function showDemo()
	{

	}
}
?>