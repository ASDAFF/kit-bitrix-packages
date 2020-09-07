<?php 
if( !defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true )
    die();

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$module = 'sotbit.origami';

CModule::includeModule($module);


changeMacro(WIZARD_SITE_DIR);
function changeMacro($sitePath = '/', $dir = ''){
    
    if(!$dir)
    {
        $arFolderName = [
            $_SERVER["DOCUMENT_ROOT"].'/local'
        ];
        $public = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/sotbit.origami/install/wizards/sotbit/origami/site/public/ru';
        $dir = opendir($public);
        while (($file = readdir($dir)) !== false)
        {
            if($file != "." && $file != ".." && $file != "local" && is_dir($public.'/'.$file)){
                $arFolderName[] = $_SERVER['DOCUMENT_ROOT'].$sitePath.$file;
                
            }
        }
    }
    else
    {
        $arFolderName = [$dir];
    }
    
    foreach($arFolderName as $folderName)
    {
        if(!empty($folderName)){
            $dir = opendir($folderName);
            while (($file = readdir($dir)) !== false){
                
                if($file != "." && $file != ".."){
                    if(is_file($folderName."/".$file) && (stripos($file, ".php")!==false)){
                        $bool = false;
                        $text = file_get_contents($folderName."/".$file);
                        
                        if(strpos($text,'IncludeComponent') !== false)
                        {
                            //iblock
                            preg_match_all("#IBLOCK_ID(.*),#", $text, $matches);
                            if($matches[0])
                            {
                                foreach($matches[0] as $match)
                                {
                                    preg_match('!\d+!', $match,$digitals);
                                    if($digitals[0] && $_SESSION['SOTBIT_ORIGAMI_WIZARD_CHANGE'][$digitals[0]])
                                    {
                                        $matchnew = str_replace($digitals[0],$_SESSION['SOTBIT_ORIGAMI_WIZARD_CHANGE'][$digitals[0]],$match);
                                        $text = str_replace($match,$matchnew,$text);
                                        $bool = true;
                                    }
                                }
                            }
                            //stores
                            preg_match_all("#STORES(.*)\s*(.*)#", $text, $matches);
                            if($matches[0])
                            {
                                foreach($matches[0] as $match)
                                {
                                    $matchnew = str_replace(6,$_SESSION['NEW_STORE_ID'],$match);
                                    $text = str_replace($match,$matchnew,$text);
                                    $bool = true;
                                    break;
                                }
                            }
                            //site_dir
                            preg_match_all("#SEF_FOLDER(.*),#", $text, $matches);
                            if($matches[0])
                            {
                                foreach($matches[0] as $match)
                                {
                                    preg_match('#/(.*)/#', $match,$path);
                                    if($path[0] )
                                    {
                                        $matchnew = str_replace($path[0],WIZARD_SITE_DIR.substr($path[0],1,strlen($path[0])-1),$match);
                                        $text = str_replace($match,$matchnew,$text);
                                        $bool = true;
                                    }
                                }
                            }
                            //basket_dir
                            preg_match_all("#BASKET_URL(.*),#", $text, $matches);
                            if($matches[0])
                            {
                                foreach($matches[0] as $match)
                                {
                                    if(strpos($match,'SITE_DIR') === false)
                                    {
                                        preg_match('#/(.*)/#', $match,$path);
                                        if($path[0] )
                                        {
                                            $matchnew = str_replace($path[0],WIZARD_SITE_DIR.substr($path[0],1,strlen($path[0])-1),$match);
                                            $text = str_replace($match,$matchnew,$text);
                                            $bool = true;
                                        }
                                    }
                                }
                            }
                            //
                            preg_match_all("#PATH_TO_CATALOG(.*),#", $text, $matches);
                            if($matches[0])
                            {
                                foreach($matches[0] as $match)
                                {
                                    if(strpos($match,'SITE_DIR') === false)
                                    {
                                        preg_match('#/(.*)/#', $match,$path);
                                        if($path[0] )
                                        {
                                            $matchnew = str_replace($path[0],WIZARD_SITE_DIR.substr($path[0],1,strlen($path[0])-1),$match);
                                            $text = str_replace($match,$matchnew,$text);
                                            $bool = true;
                                        }
                                    }
                                }
                            }
                            //
                            preg_match_all("#PATH_TO_CONTACT(.*),#", $text, $matches);
                            if($matches[0])
                            {
                                foreach($matches[0] as $match)
                                {
                                    if(strpos($match,'SITE_DIR') === false)
                                    {
                                        preg_match('#/(.*)/#', $match,$path);
                                        if($path[0] )
                                        {
                                            $matchnew = str_replace($path[0],WIZARD_SITE_DIR.substr($path[0],1,strlen($path[0])-1),$match);
                                            $text = str_replace($match,$matchnew,$text);
                                            $bool = true;
                                        }
                                    }
                                }
                            }
                            //forms
                            preg_match_all("#WEB_FORM_ID(.*),#", $text, $matches);
                            if($matches[0])
                            {
                                foreach($matches[0] as $match)
                                {
                                    preg_match('!\d+!', $match,$digitals);
                                    if($digitals[0] && $_SESSION['SOTBIT_ORIGAMI_WIZARD_CHANGE'][$digitals[0]])
                                    {
                                        $matchnew = str_replace($digitals[0],$_SESSION['SOTBIT_ORIGAMI_WIZARD_FORMS'][$digitals[0]],$match);
                                        $text = str_replace($match,$matchnew,$text);
                                        $bool = true;
                                    }
                                }
                            }
                        }
                        if($bool)
                        {
                            file_put_contents($folderName."/".$file, $text);
                        }
                    }
                    if(is_dir($folderName."/".$file)) changeMacro($sitePath, $folderName."/".$file);
                }
            }
            closedir($dir);
        }
    }
}
?>