<?
use Bitrix\Main\Loader;
use Sotbit\Origami\Config\Option;

define('STOP_STATISTICS', true);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$moduleIncluded = false;
try
{
	$moduleIncluded = Loader::includeModule('sotbit.origami');
}
catch (\Bitrix\Main\LoaderException $e)
{
	echo $e->getMessage();
}
if(!$moduleIncluded)
{
	return false;
}

$theme = new \Sotbit\Origami\Front\Theme($site);
$settings = $theme->getSettings();
$dir = $theme->getFrontUser()->getFolder().'/theme';

switch ($action)
{
	case 'open':
        $settings['OPEN'] = $open;
        $theme->writeSettings($settings);
		break;
	case 'option':
		$options = json_decode($options, true);
		if(!is_array($options))
		{
			$options = [];
		}

		$addParams = json_decode($addParams,true);
		if(!is_array($addParams))
		{
			$addParams = [];
		}

		foreach($options as $code => $value)
		{
		    if(is_array($value))
            {
                $value = serialize($value);
            }
		    elseif(strpos($value,'||') !== false){
                $value = serialize(explode('||',$value));
            }
		    $settings['OPTIONS'][$code] = $value;
		}

		foreach($addParams as $code => $val)
		{
			if($val)
			{
                $settings['OPTIONS'][$code] = $val;
			}
		}
		\SotbitOrigami::genTheme(
            $settings['OPTIONS'],
            $dir
        );
        $theme->writeSettings($settings);
        echo $dir;

        \CBitrixComponent::clearComponentCache('bitrix:news.list', '');
        \CBitrixComponent::clearComponentCache('bitrix:news.detail', '');
        \CBitrixComponent::clearComponentCache('bitrix:catalog.section', '');
        \CBitrixComponent::clearComponentCache('bitrix:catalog.section.list', '');
        \CBitrixComponent::clearComponentCache('bitrix:catalog.element', '');
		break;
	case 'save':
        if($theme->getFrontUser()->isCanSave())
        {
            $addParams = json_decode($addParams, true);
            if (!is_array($addParams)) {
                $addParams = [];
            }

            foreach ($addParams as $code => $val) {
                if ($val)
                {
                    $settings['OPTIONS'][$code] = $val;
                }
            }

            if ($settings['OPTIONS'])
            {
                foreach ($settings['OPTIONS'] as $key => $value)
                {
                    Option::set($key, $value, $site);
                }
            }
            if (!is_dir($_SERVER['DOCUMENT_ROOT'].'/local/templates/sotbit_origami/theme/'))
            {
                mkdir($_SERVER['DOCUMENT_ROOT'].'/local/templates/sotbit_origami/theme/');
            }
            if (!is_dir($_SERVER['DOCUMENT_ROOT'].'/local/templates/sotbit_origami/theme/custom'))
            {
                mkdir($_SERVER['DOCUMENT_ROOT'].'/local/templates/sotbit_origami/theme/custom');
            }

            if ($settings['OPTIONS'])
            {
                \SotbitOrigami::genTheme($settings['OPTIONS'], $dir);
            }
            if (is_dir($_SERVER['DOCUMENT_ROOT'].$dir))
            {
                $files = scandir($_SERVER['DOCUMENT_ROOT'].$dir);

                foreach ($files as $file)
                {
                    if (in_array($file, [
                            '.',
                            '..',
                            'variables.scss'
                        ])
                        || strpos($file, '.css') === false
                    ) {
                        continue;
                    }
                    echo $_SERVER['DOCUMENT_ROOT'].$theme::CUSTOM_THEME.$file;

                    rename(
                        $_SERVER['DOCUMENT_ROOT'].$dir.'/'.$file,
                        $_SERVER['DOCUMENT_ROOT'].$theme::CUSTOM_THEME.'/'
                        .$file
                    );
                }
                $files = scandir($_SERVER['DOCUMENT_ROOT'].$dir);
                foreach ($files as $file)
                {
                    if (in_array($file, [
                        '.',
                        '..',
                    ])
                    ) {
                        continue;
                    }

                    unlink($_SERVER['DOCUMENT_ROOT'].$dir.'/'.$file);
                }
            }
        }
		break;
}
?>
