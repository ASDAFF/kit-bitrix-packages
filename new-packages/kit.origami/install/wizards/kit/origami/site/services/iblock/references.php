<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
    die();

if (!IsModuleInstalled("highloadblock") && file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/highloadblock/"))
{
    $installFile = $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/highloadblock/install/index.php";
    if (!file_exists($installFile))
        return false;
    
    include_once($installFile);
    
    $moduleIdTmp = str_replace(".", "_", "highloadblock");
    if (!class_exists($moduleIdTmp))
        return false;
    
    $module = new $moduleIdTmp;
    if (!$module->InstallDB())
        return false;
    $module->InstallEvents();
    if (!$module->InstallFiles())
        return false;
}

if (!CModule::IncludeModule("highloadblock"))
    return;


if(COption::GetOptionString("sotbit.origami", "wizard_installed", "N", WIZARD_SITE_ID) == "Y" && !WIZARD_INSTALL_DEMO_DATA)
    return;


$arrCreateHL = array(
    'TSVET' => array(
        'NAME' => 'TSVET',
        'TABLE_NAME' => 'b_tsvet',
    ),
    'RAZMER' => array(
        'NAME' => 'RAZMER',
        'TABLE_NAME' => 'b_razmer',
    ),
    'CHASTOTAPROTSESSORA' => array(
        'NAME' => 'CHASTOTAPROTSESSORA',
        'TABLE_NAME' => 'b_chastota_protsessora',
    ),
    'OBEMOPERATIVNOYPAMYATI' => array(
        'NAME' => 'OBEMOPERATIVNOYPAMYATI',
        'TABLE_NAME' => 'b_obem_operativnoy_pamyati',
    ),
    'PROTSESSOR' => array(
        'NAME' => 'PROTSESSOR',
        'TABLE_NAME' => 'b_protsessor',
    ),
);

$arrCreateHLitem = array(
    'TSVET' => array(
        'GOLD' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_GOLD"),'UF_XML_ID'=>'6c789306-cddd-11e8-bae8-e0d55e7b67e6', 'UF_FILE'=> array('name'=>	'2f995ca0bb44fe1a9cb77b1145570c0a.png','type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/704/7046ff36bc52421093dbe29c6200483a.png')),
        'BLACK_WHITE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_BLACK_WHITE"),'UF_XML_ID'=>'6c789307-cddd-11e8-bae8-e0d55e7b67e6', 'UF_FILE'=> array('name'=>'626739115310694c83f866fe552d1928.png','type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/2f9/2f995ca0bb44fe1a9cb77b1145570c0a.png')),
        'BLUE_ULTRA' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_BLUE_ULTRA"),'UF_XML_ID'=>'6c789308-cddd-11e8-bae8-e0d55e7b67e6', 'UF_FILE'=> array('name'=>	'3767cdd30c588da4b1bfe7cda127beb7.png','type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/626/626739115310694c83f866fe552d1928.png')),
        'SILK_WHITE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_SILK_WHITE"),'UF_XML_ID'=>'6c789309-cddd-11e8-bae8-e0d55e7b67e6', 'UF_FILE'=> array('name'=>	'72e0123462304d3dfcebd1faaf50dae3.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/376/3767cdd30c588da4b1bfe7cda127beb7.png')),
        'MAT_BLACK' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_MAT_BLACK"),'UF_XML_ID'=>'6c78930a-cddd-11e8-bae8-e0d55e7b67e6', 'UF_FILE'=> array('name'=>	'77299fa24ed7658dbb577ba4071a3f02.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/72e/72e0123462304d3dfcebd1faaf50dae3.png')),
        'ZERC_BLACK' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_ZERC_BLACK"),'UF_XML_ID'=>'6c78930b-cddd-11e8-bae8-e0d55e7b67e6', 	 'UF_FILE'=> array('name'=>	'9861bfbb0aa6d3c32dc28345f3895dfd.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/772/77299fa24ed7658dbb577ba4071a3f02.png')),
        'COLORS' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_COLORS"),'UF_XML_ID'=>'6c78930c-cddd-11e8-bae8-e0d55e7b67e6', 'UF_FILE'=> array('name'=>	'335a36fae21f250d0fce87d1f58fd2b5.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/986/9861bfbb0aa6d3c32dc28345f3895dfd.png')),
        'BRILL_BLACK' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_BRILL_BLACK"),'UF_XML_ID'=>'6c78930d-cddd-11e8-bae8-e0d55e7b67e6	', 'UF_FILE'=> array('name'=>	'4ac2f5b3ad092c37bc7a4646308d2547.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/335/335a36fae21f250d0fce87d1f58fd2b5.png')),
        'ULTRA' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_ULTRA"),'UF_XML_ID'=>'6c78930e-cddd-11e8-bae8-e0d55e7b67e6',  'UF_FILE'=> array('name'=>	'47e399f477deec45642ea9ae6873ccbe.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/4ac/4ac2f5b3ad092c37bc7a4646308d2547.png')),
        'TITAN' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_TITAN"),'UF_XML_ID'=>'6c78930f-cddd-11e8-bae8-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'da827376b79dd163e3d2e499d48d98c8.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/47e/47e399f477deec45642ea9ae6873ccbe.png')),
        'LAZUR' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_LAZUR"),'UF_XML_ID'=>'5f610209-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'e146a1243911669273514f63d8e00b61.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/da8/da827376b79dd163e3d2e499d48d98c8.png')),
        'MARENGO' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_MARENGO"),'UF_XML_ID'=>'5f61020a-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'3a3705807fcc7b162ab19c5066cf557f.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/e14/e146a1243911669273514f63d8e00b61.png')),
        'SILVER' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_SILVER"),'UF_XML_ID'=>'6c7892fe-cddd-11e8-bae8-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'4bbaaef0f37c7098bbd81cbd3ec8c2f0.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/3a3/3a3705807fcc7b162ab19c5066cf557f.png')),
        'BLACK' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_BLACK"),'UF_XML_ID'=>'6c7892ff-cddd-11e8-bae8-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'af66111f8897ca50c7e6dc71eedd6399.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/4bb/4bbaaef0f37c7098bbd81cbd3ec8c2f0.png')),
        'GRAY_SPACE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_GRAY_SPACE"),'UF_XML_ID'=>'6c789305-cddd-11e8-bae8-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'4fc8d5c2a1bb0edf30321405c2e294c2.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/af6/af66111f8897ca50c7e6dc71eedd6399.png')),
        'BLUE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_BLUE"),'UF_XML_ID'=>'6c789300-cddd-11e8-bae8-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'51b545f73493f1ec2b8e42a801f877cc.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/4fc/4fc8d5c2a1bb0edf30321405c2e294c2.png')),
        'ORANGE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_ORANGE"),'UF_XML_ID'=>'6c789301-cddd-11e8-bae8-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'f7641e5a9bd0d3878064691a11b6ea91.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/51b/51b545f73493f1ec2b8e42a801f877cc.png')),
        'GOLDEN' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_GOLDEN"),'UF_XML_ID'=>'a240a7e2-cdfa-11e8-bae8-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'39411d4dd625b72df7d7c8abe68ad655.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/f76/f7641e5a9bd0d3878064691a11b6ea91.png')),
        'RED' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_RED"),'UF_XML_ID'=>'6c789302-cddd-11e8-bae8-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'92efa333e5c849e8cfa7a0f564d53b1d.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/394/39411d4dd625b72df7d7c8abe68ad655.png')),
        'YELLOW' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_YELLOW"),'UF_XML_ID'=>'6c789303-cddd-11e8-bae8-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'8d26811519f286da10d70cea9d1bb1ae.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/92e/92efa333e5c849e8cfa7a0f564d53b1d.png')),
        'WHITE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_WHITE"),'UF_XML_ID'=>'6c789304-cddd-11e8-bae8-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'5005ca2dfc7dbc2ae31e3ec4438afe2d.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/8d2/8d26811519f286da10d70cea9d1bb1ae.png')),
        'BLACK_BLUE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_BLACK_BLUE"),'UF_XML_ID'=>'de0a7b6f-e35c-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'79bc09a959827aa75458829af6ff0653.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/500/5005ca2dfc7dbc2ae31e3ec4438afe2d.png')),
        'WHITE_GREEN' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_WHITE_GREEN"),'UF_XML_ID'=>'de0a7b70-e35c-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'2f2a64c9640f11a7c7d2c91cdad94bd8.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/79b/79bc09a959827aa75458829af6ff0653.png')),
        'GRAY_ROSE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_GRAY_ROSE"),'UF_XML_ID'=>'de0a7b72-e35c-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'a7dd5da990badd02bea1c6a0bc78e276.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/2f2/2f2a64c9640f11a7c7d2c91cdad94bd8.png')),
        'GRAY' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_GRAY"),'UF_XML_ID'=>'53ac5fbf-ce00-11e8-bae8-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'4ee80ffb401b1828390cdc97882c7e8a.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/a7d/a7dd5da990badd02bea1c6a0bc78e276.png')),
        'GRAY_TITAN' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_GRAY_TITAN"),'UF_XML_ID'=>'e12fe62a-ceb1-11e8-bae8-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'd12833f6e9b0edbf4efdafaaabed3129.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/4ee/4ee80ffb401b1828390cdc97882c7e8a.png')),
        'ROSE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_ROSE"),'UF_XML_ID'=>'e5ab37ee-cecc-11e8-bae8-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'88a3dffbd2ce5f914cd0fcd08d84e87e.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/d12/d12833f6e9b0edbf4efdafaaabed3129.png')),
        'GREEN' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_GREEN"),'UF_XML_ID'=>'e5ab37f9-cecc-11e8-bae8-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'9af03ff7ee24fdee135e342d53b72b5f.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/88a/88a3dffbd2ce5f914cd0fcd08d84e87e.png')),
        'CAYN' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_CAYN"),'UF_XML_ID'=>'e5ab3801-cecc-11e8-bae8-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'3bdc519fca5660ae6cd41ee4d3a164db.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/9af/9af03ff7ee24fdee135e342d53b72b5f.png')),
        'BROWM_HEART' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_BROWM_HEART"),'UF_XML_ID'=>'e5ab380d-cecc-11e8-bae8-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'fb3131e164a28dcd4f99cd4d7a443b65.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/3bd/3bdc519fca5660ae6cd41ee4d3a164db.png')),
        'BROWN' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_BROWN"),'UF_XML_ID'=>'e5ab380f-cecc-11e8-bae8-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'60a0d85bbe92502575e256fa7edec567.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/fb3/fb3131e164a28dcd4f99cd4d7a443b65.png')),
        'BEIGE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_BEIGE"),'UF_XML_ID'=>'deb86515-ced5-11e8-bae8-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'6958327d8434df1f1b9a0fbc626cc1cb.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/60a/60a0d85bbe92502575e256fa7edec567.png')),
        'PURPULE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_PURPULE"),'UF_XML_ID'=>'deb8652c-ced5-11e8-bae8-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'6a89f87819fe9f22a1144d36cb7db95b.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/695/6958327d8434df1f1b9a0fbc626cc1cb.png')),
        'METALLIC' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_METALLIC"),'UF_XML_ID'=>'6d260e2c-d04d-11e8-bae8-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'04673c0e4df45f2efec0f078006cae31.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/6a8/6a89f87819fe9f22a1144d36cb7db95b.png')),
        'BLUE_ORANGE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_BLUE_ORANGE"),'UF_XML_ID'=>'5f610208-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'721ebc2dc6c1fa403dc14d7af59ec9dd.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/046/04673c0e4df45f2efec0f078006cae31.png')),
        'TRANSPARENT' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_TRANSPARENT"),'UF_XML_ID'=>'1a5e4976-d06d-11e8-bae8-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'dd97e1f6b4493193bd48ab6b6d2c1d6e.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/721/721ebc2dc6c1fa403dc14d7af59ec9dd.png')),
        'GRAY_LIGHT_GREEN' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_GRAY_LIGHT_GREEN"),'UF_XML_ID'=>'de0a7b73-e35c-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'4bbf47302496fe3061be334eb67ad30c.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/dd9/dd97e1f6b4493193bd48ab6b6d2c1d6e.png')),
        'KRAIOLA' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_KRAIOLA"),'UF_XML_ID'=>'5f6101f2-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'22f786a15326811c44ba40f24ce68e5b.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/4bb/4bbf47302496fe3061be334eb67ad30c.png')),
        'DARK_BLUE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_DARK_BLUE"),'UF_XML_ID'=>'5f6101f3-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'd6647d922c1d20d5b4fb59468f8fb0ee.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/22f/22f786a15326811c44ba40f24ce68e5b.png')),
        'CORNFLOWER' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_CORNFLOWER"),'UF_XML_ID'=>'5f6101f4-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'f95a1a803dbafea2d62c15bbc8049688.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/d66/d6647d922c1d20d5b4fb59468f8fb0ee.png')),
        'PURPLE_BLUE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_PURPLE_BLUE"),'UF_XML_ID'=>'5f6101f5-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'899ac8d894456de5bf535205dd4fccef.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/f95/f95a1a803dbafea2d62c15bbc8049688.png')),
        'LIGHT_GRAY' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_LIGHT_GRAY"),'UF_XML_ID'=>'5f6101f6-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'524b7707db408a206497d849c821626c.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/899/899ac8d894456de5bf535205dd4fccef.png')),
        'BLUE_KRAIOLA' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_BLUE_KRAIOLA"),'UF_XML_ID'=>'5f6101f7-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'8f3ca19606df6c408aa1c369a30cee28.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/524/524b7707db408a206497d849c821626c.png')),
        'BLED_PURPULE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_BLED_PURPULE"),'UF_XML_ID'=>'5f6101f8-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'6d557d747450d73feed103d9f042a41b.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/8f3/8f3ca19606df6c408aa1c369a30cee28.png')),
        'LIGHT_PURPULE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_LIGHT_PURPULE"),'UF_XML_ID'=>'5f6101f9-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'3e26591f0979c3e3184a5a473e18995f.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/6d5/6d557d747450d73feed103d9f042a41b.png')),
        'DARK_GREEN' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_DARK_GREEN"),'UF_XML_ID'=>'5f6101fa-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'8412f7f8e3607435c7aae7950309e5fc.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/3e2/3e26591f0979c3e3184a5a473e18995f.png')),
        'GRAPHITIC_BLACK' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_GRAPHITIC_BLACK"),'UF_XML_ID'=>'5f6101fb-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'0a832d6c3422e27f11c4044eb4d3b6a7.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/841/8412f7f8e3607435c7aae7950309e5fc.png')),
        'GRAPHITIC_GRAY' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_GRAPHITIC_GRAY"),'UF_XML_ID'=>'5f6101fc-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'f8702dbee269b732c87b743fb75c9653.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/0a8/0a832d6c3422e27f11c4044eb4d3b6a7.png')),
        'LINEN' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_LINEN"),'UF_XML_ID'=>'5f6101fd-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'22ff2f550a7e95a186e6bcf809d6140f.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/f87/f8702dbee269b732c87b743fb75c9653.png')),
        'WINE_RED' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_WINE_RED"),'UF_XML_ID'=>'5f6101fe-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'e7d19b79b220a35bc1f0f09a96876a6b.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/22f/22ff2f550a7e95a186e6bcf809d6140f.png')),
        'COBALTOVO_BLUE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_COBALTOVO_BLUE"),'UF_XML_ID'=>'5f6101ff-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'd4f5ad22344a7c6b8818187a4fddd710.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/e7d/e7d19b79b220a35bc1f0f09a96876a6b.png')),
        'SAPFIR_BLUE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_SAPFIR_BLUE"),'UF_XML_ID'=>'5f610200-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'f47cd3ec2e543c25e8b9647b8bb7e27f.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/d4f/d4f5ad22344a7c6b8818187a4fddd710.png'	)),
        'SILVER_GRAY' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_SILVER_GRAY"),'UF_XML_ID'=>'5f610201-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'd08a57588ba656bab375e4d1fa774834.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/f47/f47cd3ec2e543c25e8b9647b8bb7e27f.png')),
        'GREENEN_BLUE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_GREENEN_BLUE"),'UF_XML_ID'=>'5f610202-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'e78e62fdea03cbd223cfa363c791ab58.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/d08/d08a57588ba656bab375e4d1fa774834.png')),
        'IVORY' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_IVORY"),'UF_XML_ID'=>'5f610203-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'897a2efe31b00b3d16d00e4e34f917c0.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/e78/e78e62fdea03cbd223cfa363c791ab58.png')),
        'ANTHRACITE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_ANTHRACITE"),'UF_XML_ID'=>'5f610204-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'5dea2dc06ba0908f9fb10c3c96e6b12a.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/897/897a2efe31b00b3d16d00e4e34f917c0.png')),
        'SKY_BLUE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_SKY_BLUE"),'UF_XML_ID'=>'5f610205-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'8e0f1c412078dff4b373b6c9e8619f00.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/5de/5dea2dc06ba0908f9fb10c3c96e6b12a.png')),
        'INKY_BLUE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_INKY_BLUE"),'UF_XML_ID'=>'5f610206-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'5807ec85d98330f9f536f504ea6b5c3d.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/8e0/8e0f1c412078dff4b373b6c9e8619f00.png')),
        'BARVINC' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_BARVINC"),'UF_XML_ID'=>'5f610207-e25f-11e8-bae9-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'5807ec85d98330f9f536f504ea6b5c3d.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/580/5807ec85d98330f9f536f504ea6b5c3d.png')),
        'GREYBLACK' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_GREYBLACK"),'UF_XML_ID'=>'b0ac0347-1af9-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'372363a4fd0618db118698c17681e3ee.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/372/372363a4fd0618db118698c17681e3ee.png')),
        'REDBLACK' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_REDBLACK"),'UF_XML_ID'=>'b0ac0366-1af9-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'ebcd11364e0ff865adfcb8ef5c096a7d.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/ebc/ebcd11364e0ff865adfcb8ef5c096a7d.png')),
        'ANTRACIT' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_ANTRACIT"),'UF_XML_ID'=>'b0ac0368-1af9-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'970131f550c688b0b86f99d60f46bbf6.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/970/970131f550c688b0b86f99d60f46bbf6.png')),
        'OLIVE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_OLIVE"),'UF_XML_ID'=>'19f5d4c6-1bb2-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'ca13c3fe51694d3e7745f4893b6bbf20.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/ca1/ca13c3fe51694d3e7745f4893b6bbf20.png')),
        'GREEMWHITE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_GREEMWHITE"),'UF_XML_ID'=>'71f69fd8-1b12-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'1d71c21ed5ca3790f572363bcde796a2.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/1d7/1d71c21ed5ca3790f572363bcde796a2.png')),
        'BORDOVIY' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_BORDOVIY"),'UF_XML_ID'=>'9fd7f1be-1b1b-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'398e2722f9f2bcaafcc7332d6e41c1dc.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/398/398e2722f9f2bcaafcc7332d6e41c1dc.png')),
        'BLACKGOLD' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_BLACKGOLD"),'UF_XML_ID'=>'9fd7f1c4-1b1b-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'86472c60fd5fee75e012947787ff916c.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/864/86472c60fd5fee75e012947787ff916c.png')),
        'BLACKWHITE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_BLACKWHITE"),'UF_XML_ID'=>'56e6c2e3-1b1f-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'4356b7f0721bee6a867f5ba0a2ae1e68.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/435/4356b7f0721bee6a867f5ba0a2ae1e68.png')),
        'PINKWHITE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_PINKWHITE"),'UF_XML_ID'=>'56e6c2ee-1b1f-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'9058579bdcce19ee760675b84bac649e.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/905/9058579bdcce19ee760675b84bac649e.png')),
        'PINKBLACK' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_PINKBLACK"),'UF_XML_ID'=>'56e6c303-1b1f-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'71765a914e71a7cc7e28c9e8c7f69d8e.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/717/71765a914e71a7cc7e28c9e8c7f69d8e.png')),
        'GREENBLACK' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_GREENBLACK"),'UF_XML_ID'=>'5761ebc0-1a4a-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'9c46bdcc2b77b8c73474fe8f06bb234d.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/9c4/9c46bdcc2b77b8c73474fe8f06bb234d.png')),
        'WHITEBLACK' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_WHITEBLACK"),'UF_XML_ID'=>'5761ebc7-1a4a-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'5bb92db734d1eb9ae82e4e27ecacc66a.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/5bb/5bb92db734d1eb9ae82e4e27ecacc66a.png')),
        'CORALLBLACK' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_CORALLBLACK"),'UF_XML_ID'=>'5761ebce-1a4a-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'419808880c1f60582e5692db24652102.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/419/419808880c1f60582e5692db24652102.png')),
        'BLACKGREY' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_BLACKGREY"),'UF_XML_ID'=>'5761ebd5-1a4a-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'3830447a1f8920b52ce3cb744e0b4245.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/383/3830447a1f8920b52ce3cb744e0b4245.png')),
        'GREYGOLD' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_GREYGOLD"),'UF_XML_ID'=>'56e6c322-1b1f-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'6f3903790f69d30d633e9b2ac44eb92f.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/6f3/6f3903790f69d30d633e9b2ac44eb92f.png')),
        'BLUEBLACK' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_BLUEBLACK"),'UF_XML_ID'=>'5222d943-1b21-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'161d88f5e8d4425186c15f5219fc6786.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/161/161d88f5e8d4425186c15f5219fc6786.png')),
        'PINKMALINA' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_PINKMALINA"),'UF_XML_ID'=>'5761ebe0-1a4a-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'2132b9ba995730123b3327ea411bf279.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/213/2132b9ba995730123b3327ea411bf279.png')),
        'ORANGEHORCHIZA' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_ORANGEHORCHIZA"),'UF_XML_ID'=>'5761ebe7-1a4a-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'de277ea9c9781f74335012a2502f4f2b.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/de2/de277ea9c9781f74335012a2502f4f2b.png')),
        'WHITEORANGE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_WHITEORANGE"),'UF_XML_ID'=>'097c1792-1bb6-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'c5e8d85b01477e27372c4af677b3ca4a.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/c5e/c5e8d85b01477e27372c4af677b3ca4a.png')),
        'WHITEGREY' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_WHITEGREY"),'UF_XML_ID'=>'097c1794-1bb6-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'50c044b2c4a7713d82f50afeb343354a.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/50c/50c044b2c4a7713d82f50afeb343354a.png')),
        'BLACKBRILLIANT' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_BLACKBRILLIANT"),'UF_XML_ID'=>'6c78930d-cddd-11e8-bae8-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'061bf190f4d4f4a7e6f49d2c90a043d2.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/061/061bf190f4d4f4a7e6f49d2c90a043d2.png')),
        'FUKSIYA' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_FUKSIYA"),'UF_XML_ID'=>'8391f1f1-1a56-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'1ce16e93fd9606709c25a6502e95eebe.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/1ce/1ce16e93fd9606709c25a6502e95eebe.png')),
        'BLACKBROWN' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_BLACKBROWN"),'UF_XML_ID'=>'e89b0cd7-1bb8-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'c0478cead275381579578229b7d294ed.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/c04/c0478cead275381579578229b7d294ed.png')),
        'DARKGREY' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_DARKGREY"),'UF_XML_ID'=>'8391f20a-1a56-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'ea6f61db63224009ff1bb7c26a12c955.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/ea6/ea6f61db63224009ff1bb7c26a12c955.png')),
        'REDBLUEWHITE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_REDBLUEWHITE"),'UF_XML_ID'=>'988d8757-1a5a-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'9bd114cedde9654bca6458ab7e7279a8.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/9bd/9bd114cedde9654bca6458ab7e7279a8.png')),
        'GREYWHITE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_GREYWHITE"),'UF_XML_ID'=>'22cfad37-1a62-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'd2a96b518feb3b51ea5825ee916e37a4.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/d2a/d2a96b518feb3b51ea5825ee916e37a4.png')),
        'GREEREDWHITE' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_GREEREDWHITE"),'UF_XML_ID'=>'22cfad3e-1a62-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'd36f8366a5235b8c0e57d8f59c9ea2c6.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/d36/d36f8366a5235b8c0e57d8f59c9ea2c6.png')),
        'PINKGREY' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_PINKGREY"),'UF_XML_ID'=>'22cfad45-1a62-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'73ea84f79f794c0769faaf6f36c4f9f6.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/73e/73ea84f79f794c0769faaf6f36c4f9f6.png')),
        'HAKI' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_HAKI"),'UF_XML_ID'=>'22cfad50-1a62-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'1dcab261e70f444a9a86fcd03f706288.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/1dc/1dcab261e70f444a9a86fcd03f706288.png')),
        'REDBROWN' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_REDBROWN"),'UF_XML_ID'=>'22cfad52-1a62-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'1dcab261e70f444a9a86fcd03f706288.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/fcb/fcb3a327131645ec20c2a5706a32bcd4.png')),
        'SIRENEVY' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_SIRENEVY"),'UF_XML_ID'=>'22cfad54-1a62-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'58ee2679e2592c3b347a955f562ffbea.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/58e/58ee2679e2592c3b347a955f562ffbea.png')),
        'CORAL' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_CORAL"),'UF_XML_ID'=>'423cd939-1ae4-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'953856d6be0adece7546ac2b8e127b3a.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/953/953856d6be0adece7546ac2b8e127b3a.png')),
        'DARKVIOLET' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_DARKVIOLET"),'UF_XML_ID'=>'423cd94f-1ae4-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'cdf3173f5bee4f2bb888375503a3d1f4.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/cdf/cdf3173f5bee4f2bb888375503a3d1f4.png')),
        'BLACKRED' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_BLACKRED"),'UF_XML_ID'=>'9f47d923-1aea-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'2b32afb6b0b703183189c43e1bb307d0.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/2b3/2b32afb6b0b703183189c43e1bb307d0.png')),
        'BLACKGREY2' => array('UF_NAME'=> GetMessage("WZD_REF_COLOR_BLACKGREY2"),'UF_XML_ID'=>'9f47d925-1aea-11e9-baf2-e0d55e7b67e6',	 'UF_FILE'=> array('name'=>	'4bec01a8ec16cf0823bda9260e3c1aa3.png'	,'type'=>'image/png','tmp_name'=>WIZARD_ABSOLUTE_PATH. '/site/services/iblock/references_files/uf/4be/4bec01a8ec16cf0823bda9260e3c1aa3.png')),
    ),
    'RAZMER' => array(
        'L' => array('UF_NAME' => 'L', 'UF_XML_ID' => '471689de-1a41-11e9-baf2-e0d55e7b67e6'),
        '50-52' => array('UF_NAME' => '50-52', 'UF_XML_ID' => '7b47779a-1a66-11e9-baf2-e0d55e7b67e6'),
        '56-58' => array('UF_NAME' => '56-58','UF_XML_ID' => '7b47778b-1a66-11e9-baf2-e0d55e7b67e6'),
        'M' => array('UF_NAME' => 'M','UF_XML_ID' => '471689dd-1a41-11e9-baf2-e0d55e7b67e6'),
        '52-54' => array('UF_NAME' => '52-54','UF_XML_ID' => '7b477789-1a66-11e9-baf2-e0d55e7b67e6	'),
        'S' => array('UF_NAME' => 'S','UF_XML_ID' => '471689dc-1a41-11e9-baf2-e0d55e7b67e6'),
        '43' => array('UF_NAME' => '43','UF_XML_ID' => '02c5ee18-1b0e-11e9-baf2-e0d55e7b67e6'),
        'XS' => array('UF_NAME' => 'XS','UF_XML_ID' => '471689db-1a41-11e9-baf2-e0d55e7b67e6'),
        'XXS' => array('UF_NAME' => 'XXS','UF_XML_ID' => '471689da-1a41-11e9-baf2-e0d55e7b67e6'),
        '42' => array('UF_NAME' => '42','UF_XML_ID' => '02c5ee16-1b0e-11e9-baf2-e0d55e7b67e6'),
        '41' => array('UF_NAME' => '41','UF_XML_ID' => '02c5ee14-1b0e-11e9-baf2-e0d55e7b67e6'),
        '40' => array('UF_NAME' => '40','UF_XML_ID' => '02c5ee12-1b0e-11e9-baf2-e0d55e7b67e6'),
        '48-50' => array('UF_NAME' => '48-50','UF_XML_ID' => '7b477788-1a66-11e9-baf2-e0d55e7b67e6'),
        '54-56' => array('UF_NAME' => '54-56','UF_XML_ID' => '7b47778a-1a66-11e9-baf2-e0d55e7b67e6'),
        '39' => array('UF_NAME' => '39','UF_XML_ID' => '02c5ee10-1b0e-11e9-baf2-e0d55e7b67e6'),
        '4XL' => array('UF_NAME' => '4XL','UF_XML_ID' => '471689e2-1a41-11e9-baf2-e0d55e7b67e6'),
        '5XL' => array('UF_NAME' => '5XL','UF_XML_ID' => '471689e3-1a41-11e9-baf2-e0d55e7b67e6'),
        'XXXL' => array('UF_NAME' => 'XXXL','UF_XML_ID' => '471689e1-1a41-11e9-baf2-e0d55e7b67e6'),
        'XXL' => array('UF_NAME' => 'XXL','UF_XML_ID' => '471689e0-1a41-11e9-baf2-e0d55e7b67e6'),
        'XL' => array('UF_NAME' => 'XL','UF_XML_ID' => '471689df-1a41-11e9-baf2-e0d55e7b67e6'),
        '35' => array('UF_NAME' => '35','UF_XML_ID' => 'cc188664-1bbb-11e9-baf2-e0d55e7b67e6'),
        '36' => array('UF_NAME' => '36','UF_XML_ID' => 'cc188666-1bbb-11e9-baf2-e0d55e7b67e6'),
        '37' => array('UF_NAME' => '37','UF_XML_ID' => 'cc188668-1bbb-11e9-baf2-e0d55e7b67e6'),
        '38' => array('UF_NAME' => '38','UF_XML_ID' => 'cc18866a-1bbb-11e9-baf2-e0d55e7b67e6'),
    ),
    'CHASTOTAPROTSESSORA' => array(
        '2800' => array('UF_NAME' => '2800', 'UF_XML_ID' => '5883305f-cd5c-11e8-82dc-4ccc6a2774c4'),
        '1600' => array('UF_NAME' => '1600', 'UF_XML_ID' => '58833060-cd5-11e8-82dc-4ccc6a2774c4'),
        '1800' => array('UF_NAME' => '1800', 'UF_XML_ID' => '58833061-cd5c-11e8-82dc-4ccc6a2774c4'),
        '2000' => array('UF_NAME' => '2000', 'UF_XML_ID' => '58833062-cd5c-11e8-82dc-4ccc6a2774c4'),
        '3400' => array('UF_NAME' => '3400', 'UF_XML_ID' => '58833063-cd5c-11e8-82dc-4ccc6a2774c4'),
        '4000' => array('UF_NAME' => '4000', 'UF_XML_ID' => '58833064-cd5c-11e8-82dc-4ccc6a2774c4'),
        '900'  => array('UF_NAME' => '900', 'UF_XML_ID' => '58833065-cd5c-11e8-82dc-4ccc6a2774c4'),
        '1000' => array('UF_NAME' => '1000', 'UF_XML_ID' => '58833066-cd5c-11e8-82dc-4ccc6a2774c4'),
        '1200' => array('UF_NAME' => '1200', 'UF_XML_ID' => '58833067-cd5c-11e8-82dc-4ccc6a2774c4'),
        '2900' => array('UF_NAME' => '2900', 'UF_XML_ID' => '53ac5fb2-ce00-11e8-bae8-e0d55e7b67e6'),
        '2200' => array('UF_NAME' => '2200', 'UF_XML_ID' => '53ac5fae-ce00-11e8-bae8-e0d55e7b67e6'),
        '2700' => array('UF_NAME' => '2700', 'UF_XML_ID' => '53ac5fca-ce00-11e8-bae8-e0d55e7b67e6'),
        '2500' => array('UF_NAME' => '2500', 'UF_XML_ID' => '5883305e-cd5c-11e8-82dc-4ccc6a2774c4'),
        '2300' => array('UF_NAME' => '2300', 'UF_XML_ID' => '53ac5fbd-ce00-11e8-bae8-e0d55e7b67e6'),
    ),
    'OBEMOPERATIVNOYPAMYATI' => array(
        '8 Gb' => array('UF_NAME' =>'8 Gb', 'UF_XML_ID' => '58833077-cd5c-11e8-82dc-4ccc6a2774c4'),
        '16 Gb' => array('UF_NAME' =>'16 Gb', 'UF_XML_ID' => '58833078-cd5c-11e8-82dc-4ccc6a2774c4'),
        '6 Gb' => array('UF_NAME' =>'6 Gb', 'UF_XML_ID' => '58833079-cd5c-11e8-82dc-4ccc6a2774c4'),
        '10 Gb' => array('UF_NAME' =>'10 Gb', 'UF_XML_ID' => '5883307a-cd5c-11e8-82dc-4ccc6a2774c4'),
        '12 Gb' => array('UF_NAME' =>'12 Gb', 'UF_XML_ID' => '5883307b-cd5c-11e8-82dc-4ccc6a2774c4'),
        '32 Gb' => array('UF_NAME' =>'32 Gb', 'UF_XML_ID' => '53ac5fb3-ce00-11e8-bae8-e0d55e7b67e6'),
        '2 Gb' => array('UF_NAME' =>'2 Gb', 'UF_XML_ID' => '58833075-cd5c-11e8-82dc-4ccc6a2774c4'),
        '4 Gb' => array('UF_NAME' =>'4 Gb', 'UF_XML_ID' => '58833076-cd5c-11e8-82dc-4ccc6a2774c4'),
    
    ),
    'PROTSESSOR' => array(
        'Core i5' => array('UF_NAME' =>'Core i5', 'UF_XML_ID' => '58833057-cd5c-11e8-82dc-4ccc6a2774c4'),
        'Core i7' => array('UF_NAME' =>'Core i7', 'UF_XML_ID' => '58833058-cd5c-11e8-82dc-4ccc6a2774c4'),
        'SKIP' => array('UF_NAME' =>GetMessage("WZD_REF_SKIP"), 'UF_XML_ID' => '58833059-cd5c-11e8-82dc-4ccc6a2774c4'),
        'Core i3' => array('UF_NAME' =>'Core i3', 'UF_XML_ID' => '5883305a-cd5c-11e8-82dc-4ccc6a2774c4'),
        'Pentium' => array('UF_NAME' =>'Pentium', 'UF_XML_ID' => '5883305b-cd5c-11e8-82dc-4ccc6a2774c4'),
        'Celeron' => array('UF_NAME' =>'Celeron', 'UF_XML_ID' => '5883305c-cd5c-11e8-82dc-4ccc6a2774c4'),
        'Intel Core m3 6Y30' =>	array('UF_NAME' =>'	Intel Core m3 6Y30', 'UF_XML_ID' => 'a240a7e0-cdfa-11e8-bae8-e0d55e7b67e6'),
        'Intel Core i7 7500U' => array('UF_NAME' =>'Intel Core i7 7500U', 'UF_XML_ID' => '53ac5fc9-ce00-11e8-bae8-e0d55e7b67e6'),
        'Intel Core i7 7700HQ' => array('UF_NAME' =>'Intel Core i7 7700HQ', 'UF_XML_ID' => '53ac5fa3-ce00-11e8-bae8-e0d55e7b67e6'),
        'Intel Core i5 7300HQ' => array('UF_NAME' =>'Intel Core i5 7300HQ', 'UF_XML_ID' => '53ac5fa1-ce00-11e8-bae8-e0d55e7b67e6'),
        'Intel Core m3 7Y30' =>	array('UF_NAME' =>'Intel Core m3 7Y30', 'UF_XML_ID' => 'a240a7e4-cdfa-11e8-bae8-e0d55e7b67e6'),
        'Intel Core i9 8950HK' => array('UF_NAME' =>'Intel Core i9 8950HK', 'UF_XML_ID' => '53ac5fb1-ce00-11e8-bae8-e0d55e7b67e6'),
        'Intel Core i7 8750H' => array('UF_NAME' =>'Intel Core i7 8750H', 'UF_XML_ID' => '53ac5fad-ce00-11e8-bae8-e0d55e7b67e6'	),
        'Intel Core i7 8550U' => array('UF_NAME' =>'Intel Core i7 8550U', 'UF_XML_ID' => '53ac5fcc-ce00-11e8-bae8-e0d55e7b67e6'),
        'Intel Core i5 8259U' => array('UF_NAME' =>'Intel Core i5 8259U', 'UF_XML_ID' => '53ac5fbc-ce00-11e8-bae8-e0d55e7b67e6'),
    ),
);


$dbHblock = Bitrix\Highloadblock\HighloadBlockTable::getList(array());
while($arHblock = $dbHblock->Fetch()) {
    if($arrCreateHL[$arHblock['NAME']]){
        $_SESSION["WIZARD_".$arHblock['NAME']."_HIGHBLOCK_ID"] = $arHblock['ID'];
        unset($arrCreateHL[$arHblock['NAME']]);
    }
}


foreach($arrCreateHL as $key => $data) {
    $Iblock = '';
    $result = Bitrix\Highloadblock\HighloadBlockTable::add($data);
    $Iblock = $result->getId();
    // ???????? ???????????????? ????????
    $arFieldsName = array(
        'UF_NAME' => array("Y", "string"),
        'UF_XML_ID' => array("Y", "string"),
        'UF_LINK' => array("N", "string"),
        'UF_DESCRIPTION' => array("N", "string"),
        'UF_FULL_DESCRIPTION' => array("N", "string"),
        'UF_SORT' => array("N", "integer"),
        'UF_FILE' => array("N", "file"),
        'UF_DEF' => array("N", "boolean"),
    );
    $obUserField = new CUserTypeEntity();
    $sort = 100;
    $arUserFields = array();
    
    
    
    foreach($arFieldsName as $fieldName => $fieldValue)
    {
        
        $arUserField = array(
            "ENTITY_ID" => "HLBLOCK_".$Iblock,
            "FIELD_NAME" => $fieldName,
            "USER_TYPE_ID" => $fieldValue[1],
            "XML_ID" => $fieldName,
            "SORT" => $sort,
            "MULTIPLE" => "N",
            "MANDATORY" => $fieldValue[0],
            "SHOW_FILTER" => "N",
            "SHOW_IN_LIST" => "Y",
            "EDIT_IN_LIST" => "Y",
            "IS_SEARCHABLE" => "N",
            "SETTINGS" => array(),
        );
        $obUserField->Add($arUserField);
        $sort += 100;
    }
    $arrCreateHL[$key]['HLIBLOCK'] = $Iblock;
    $_SESSION["WIZARD_".$key."_HIGHBLOCK_ID"] = $Iblock;
    
}

foreach($arrCreateHL as $data) {
    
    global $USER_FIELD_MANAGER;
    // ???????? ???????? ??????????
    if($arrCreateHLitem[$data['NAME']] && $data['HLIBLOCK']){
        $hldata = Bitrix\Highloadblock\HighloadBlockTable::getById($data['HLIBLOCK'])->fetch();
        $hlentity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
        
        $entity_data_class = $hlentity->getDataClass();
        foreach($arrCreateHLitem[$data['NAME']] as $keyItem=>$valueItem){
            
            if(empty($valueItem['UF_SORT'])){
                $valueItem['UF_SORT'] = '100';
            }
            $USER_FIELD_MANAGER->EditFormAddFields('HLBLOCK_'.$data['HLIBLOCK'], $valueItem);
            $USER_FIELD_MANAGER->checkFields('HLBLOCK_'.$data['HLIBLOCK'], null, $valueItem);
            
            $result = $entity_data_class::add($valueItem);
            
        }
    }
}


?>