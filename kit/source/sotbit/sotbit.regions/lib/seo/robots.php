<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 29-Jan-18
 * Time: 3:06 PM
 */

namespace Sotbit\Regions\Seo;

use Bitrix\Main\Error;
use Bitrix\Main\SiteTable;
use Sotbit\Regions\Config\Option;

class Robots extends File
{
	/**
	 * @var string
	 */
	public $txtFile;
	/**
	 * @var string
	 */
	public $phpFile;

	/**
	 * Robots constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->txtFile = $_SERVER['DOCUMENT_ROOT'] . '/robots.txt';
		$this->phpFile = $_SERVER['DOCUMENT_ROOT'] . '/robots.php';
	}

	public function run()
	{
		if(file_exists($this->txtFile))
		{
			$newRobots = '<?php
use Bitrix\Main\Loader;
require_once ($_SERVER[\'DOCUMENT_ROOT\'].\'/bitrix/modules/main/include/prolog_before.php\');
if(!Loader::includeModule("sotbit.regions"))
{
    return false;
}
$domain = new \Sotbit\Regions\Location\Domain();
$domainCode = $domain->getProp("CODE");
if(!empty($domain->getProp("UF_ROBOTS")))
    $domainRobots = $domain->getProp("UF_ROBOTS");
?>
';
			$robots = file_get_contents($this->txtFile);
			$domains = array();

			$rs = SiteTable::getList(
				array(
					'select' => array('SERVER_NAME'),
					'filter' => array('ACTIVE' => 'Y')
				)
			);
			while ($site = $rs->fetch())
			{
				$domains[] = $site['SERVER_NAME'];
			}
			$domains = array_unique($domains);
			if($domains)
			{
				foreach($domains as $domain)
				{
					$robots = str_replace(
						$domain,
						'<?=str_replace(array("http://","https://"),"",$domainCode)?>',
						$robots);
				}
			}
			$newRobots.=$robots;

			// UF_ROBOTS
            $newRobots .= '
<?=(!empty($domainRobots) ? $domainRobots : "")?>';


			$newFile = $this->genNewFile($this->txtFile, $newRobots);
			$this->addRuleToHtaccess(
				'robots.txt',
				str_replace($_SERVER['DOCUMENT_ROOT'].'/', '', $newFile));
		}
		else
		{
            $error = new Error('',1);
            $this->result->addError($error);
		}
		return $this->result;
	}
}