<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
    die();

use Bitrix\Main\Loader;
Loader::IncludeModule('form');

$arFields1 = array(
    "NAME"              => GetMessage("FORM1_NAME"),
    "SID"               => "LIKEOFFER",
    "C_SORT"            => 100,
    "BUTTON"            => GetMessage("FORM1_BUTTON"),
    "DESCRIPTION"       => GetMessage("FORM1_DESCRIPTION"),
    "DESCRIPTION_TYPE"  => "text",
    "STAT_EVENT1"       => "form",
    "STAT_EVENT2"       => "",
    "arSITE"            => array(WIZARD_SITE_ID),
    "arMENU"            => array("ru" => GetMessage("FORM1_NAME"), "en" => ""),
);

$NEW_ID1 = CForm::Set($arFields1);

if($NEW_ID1) {

    $_SESSION['KIT_ORIGAMI_WIZARD_FORMS'][1] = $NEW_ID1;
    CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"]."/local/templates/kit_origami/theme/footers/1/content.php", Array("FORM_ID" => $NEW_ID1));

    $questions = array(
        array(
            'FORM_ID' => $NEW_ID1,
            'ACTIVE' => 'Y',
            'SID' => 'LIKEOFFER',
            'TITLE' => GetMessage("FORM_TITLE_NAME"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 100,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'N',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "text",
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID1,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM_TITLE_PHONE"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 200,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'Y',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "text",
                    "FIELD_PARAM" => "id=\"tel\""
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID1,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM_TITLE_EMAIL"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 300,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'Y',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "email",
                )
            )
        )
    );
    foreach($questions as $question) {
        $id = CFormField::set($question);
    }

    CFormStatus::Set(array(
        "FORM_ID"		=> $NEW_ID1,
        "C_SORT"		=> 100,
        "ACTIVE"		=> "Y",
        "TITLE"			=> "DEFAULT",
        "DESCRIPTION"		=> "DEFAULT",
        "CSS"			=> "statusgreen",
        "DEFAULT_VALUE"		=> "Y",
        "arPERMISSION_VIEW"	=> array(0),
        "arPERMISSION_MOVE"	=> array(0),
        "arPERMISSION_EDIT"	=> array(0),
        "arPERMISSION_DELETE"	=> array(0),
    ), false, 'N');
}

$arFields2 = array(
    "NAME"              => GetMessage("FORM2_NAME"),
    "SID"               => 'QUESTIONS',
    "C_SORT"            => 200,
    "BUTTON"            => GetMessage("FORM1_BUTTON"),
    "DESCRIPTION"       => "",
    "DESCRIPTION_TYPE"  => "text",
    "STAT_EVENT1"       => "form",
    "STAT_EVENT2"       => "SIMPLE_FORM_2_D9Mh7",
    "arSITE"            => array(WIZARD_SITE_ID),
    "arMENU"            => array("ru" => GetMessage("FORM2_NAME"), "en" => ""),
);

$NEW_ID2 = CForm::Set($arFields2);

if($NEW_ID2) {
    $_SESSION['KIT_ORIGAMI_WIZARD_FORMS'][2] = $NEW_ID2;
    CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"]."/local/templates/.default/components/bitrix/news/kit_origami_promotions/detail.php", Array("FORM2_ID" => $NEW_ID2));
    //CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"]."/local/templates/kit_origami/components/bitrix/news/kit_origami_shops/detail.php", Array("FORM2_IBLOCK_ID" => $NEW_ID2));

    $questions = array(
        array(
            'FORM_ID' => $NEW_ID2,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM1_TITLE_NAME"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 100,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'N',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "text",
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID2,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM1_TITLE_PHONE"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 200,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'Y',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "text",
                    "FIELD_PARAM" => "id=\"tel\""
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID2,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM_TITLE_EMAIL"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 300,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'N',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "email",
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID2,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM_TITLE_QUENST"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 400,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'N',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "textarea",
                )
            )
        )
    );
    foreach($questions as $question) {
        $id = CFormField::set($question);
    }

    CFormStatus::Set(array(
        "FORM_ID"		=> $NEW_ID2,
        "C_SORT"		=> 100,
        "ACTIVE"		=> "Y",
        "TITLE"			=> "DEFAULT",
        "DESCRIPTION"		=> "DEFAULT",
        "CSS"			=> "statusgreen",
        "DEFAULT_VALUE"		=> "Y",
        "arPERMISSION_VIEW"	=> array(0),
        "arPERMISSION_MOVE"	=> array(0),
        "arPERMISSION_EDIT"	=> array(0),
        "arPERMISSION_DELETE"	=> array(0),
    ), false, 'N');
}

$arFields3 = array(
    "NAME"              => GetMessage("FORM3_NAME"),
    "SID"               => 'FEEDBACK',
    "C_SORT"            => 200,
    "BUTTON"            => GetMessage("FORM1_BUTTON"),
    "DESCRIPTION"       => "",
    "DESCRIPTION_TYPE"  => "text",
    "STAT_EVENT1"       => "form",
    "STAT_EVENT2"       => "SIMPLE_FORM_2_w3yiU",
    "arSITE"            => array(WIZARD_SITE_ID),
    "arMENU"            => array("ru" => GetMessage("FORM3_NAME"), "en" => ""),
);

$NEW_ID3 = CForm::Set($arFields3);

if($NEW_ID3) {

    $_SESSION['KIT_ORIGAMI_WIZARD_FORMS'][3] = $NEW_ID3;
    CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"]."/local/templates/kit_origami/theme/contacts/1/content.php", array("FORM3_ID" => $NEW_ID3));
    CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"]."/local/templates/kit_origami/theme/contacts/2/content.php", array("FORM3_ID" => $NEW_ID3));
    CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"]."/local/templates/kit_origami/theme/contacts/3/content.php", array("FORM3_ID" => $NEW_ID3));
    CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"]."/local/templates/kit_origami/theme/contacts/4/content.php", array("FORM3_ID" => $NEW_ID3));

    $questions = array(
        array(
            'FORM_ID' => $NEW_ID3,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM1_TITLE_NAME"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 100,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'N',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "text",
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID3,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM1_TITLE_PHONE"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 200,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'Y',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "text",
                    "FIELD_PARAM" => "id=\"tel\""
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID3,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM_TITLE_EMAIL"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 300,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'N',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "email",
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID3,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM_TITLE_COMMENT"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 400,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'N',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "textarea",
                )
            )
        )
    );
    foreach($questions as $question) {
        $id = CFormField::set($question);
    }

    CFormStatus::Set(array(
        "FORM_ID"		=> $NEW_ID3,
        "C_SORT"		=> 100,
        "ACTIVE"		=> "Y",
        "TITLE"			=> "DEFAULT",
        "DESCRIPTION"		=> "DEFAULT",
        "CSS"			=> "statusgreen",
        "DEFAULT_VALUE"		=> "Y",
        "arPERMISSION_VIEW"	=> array(0),
        "arPERMISSION_MOVE"	=> array(0),
        "arPERMISSION_EDIT"	=> array(0),
        "arPERMISSION_DELETE"	=> array(0),
    ), false, 'N');
}

$arFields4 = array(
    "NAME"              => GetMessage("FORM4_NAME"),
    "SID"               => 'CALLBACK',
    "C_SORT"            => 200,
    "BUTTON"            => GetMessage("FORM4_BUTTON"),
    "DESCRIPTION"       => "",
    "DESCRIPTION_TYPE"  => "text",
    "STAT_EVENT1"       => "form",
    "STAT_EVENT2"       => "",
    "arSITE"            => array(WIZARD_SITE_ID),
    "arMENU"            => array("ru" => GetMessage("FORM4_NAME"), "en" => ""),
);

$NEW_ID4 = CForm::Set($arFields4);

if($NEW_ID4) {

    $_SESSION['KIT_ORIGAMI_WIZARD_FORMS'][4] = $NEW_ID4;
    $questions = array(
        array(
            'FORM_ID' => $NEW_ID4,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM_TITLE_NAME"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 100,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'N',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "text",
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID4,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM1_TITLE_PHONE"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 200,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'Y',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "text",
                    "FIELD_PARAM" => "id=\"tel\""
                )
            )
        )
    );
    foreach($questions as $question) {
        $id = CFormField::set($question);
    }

    CFormStatus::Set(array(
        "FORM_ID"		=> $NEW_ID4,
        "C_SORT"		=> 100,
        "ACTIVE"		=> "Y",
        "TITLE"			=> "DEFAULT",
        "DESCRIPTION"		=> "DEFAULT",
        "CSS"			=> "statusgreen",
        "DEFAULT_VALUE"		=> "Y",
        "arPERMISSION_VIEW"	=> array(0),
        "arPERMISSION_MOVE"	=> array(0),
        "arPERMISSION_EDIT"	=> array(0),
        "arPERMISSION_DELETE"	=> array(0),
    ), false, 'N');

    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH ."include/ajax/callbackphone.php", Array("FORM4_ID" => $NEW_ID4));
    // CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"] ."/include/ajax/callbackphone.php", Array("FORM4_IBLOCK_ID" => $NEW_ID4));
}

$arFields5 = array(
    "NAME"              => GetMessage("FORM5_NAME"),
    "SID"               => 'FOUNDCHEAPER',
    "C_SORT"            => 200,
    "BUTTON"            => GetMessage("FORM1_BUTTON"),
    "DESCRIPTION"       => "",
    "DESCRIPTION_TYPE"  => "text",
    "STAT_EVENT1"       => "form",
    "STAT_EVENT2"       => "",
    "arSITE"            => array(WIZARD_SITE_ID),
    "arMENU"            => array("ru" => GetMessage("FORM5_NAME"), "en" => ""),
);

$NEW_ID5 = CForm::Set($arFields5);

if($NEW_ID5) {

    $_SESSION['KIT_ORIGAMI_WIZARD_FORMS'][5] = $NEW_ID5;
    $questions = array(
        array(
            'FORM_ID' => $NEW_ID5,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM_TITLE_NAME"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 100,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'Y',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "text",
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID5,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM5_TITLE_PHONE"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 200,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'Y',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "text",
                    "FIELD_PARAM" => "id=\"tel\""
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID5,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM5_TITLE_EMAIL"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 300,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'N',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "email",
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID5,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM5_TITLE_PRODUCT_NAME"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 200,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'Y',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "text",
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID5,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM5_TITLE_PRODUCT_LINK"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 200,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'Y',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "text",
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID5,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM5_TITLE_MESSAGE"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 200,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'N',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "textarea",
                )
            )
        )
    );
    foreach($questions as $question) {
        $id = CFormField::set($question);
    }

    CFormStatus::Set(array(
        "FORM_ID"		=> $NEW_ID5,
        "C_SORT"		=> 100,
        "ACTIVE"		=> "Y",
        "TITLE"			=> "DEFAULT",
        "DESCRIPTION"	=> "DEFAULT",
        "CSS"			=> "statusgreen",
        "DEFAULT_VALUE"		=> "Y",
        "arPERMISSION_VIEW"	=> array(0),
        "arPERMISSION_MOVE"	=> array(0),
        "arPERMISSION_EDIT"	=> array(0),
        "arPERMISSION_DELETE" => array(0),
    ), false, 'N');

    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH ."include/ajax/foundcheaper.php", Array("FORM5_ID" => $NEW_ID5));
    //CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"] ."/include/ajax/foundcheaper.php", Array("WEB_FORM_ID" => $NEW_ID5));
}



$arFields6 = array(
    "NAME"              => GetMessage("FORM6_NAME"),
    "SID"               => 'WANTGIFT',
    "C_SORT"            => 200,
    "BUTTON"            => GetMessage("FORM1_BUTTON"),
    "DESCRIPTION"       => "",
    "DESCRIPTION_TYPE"  => "text",
    "STAT_EVENT1"       => "form",
    "STAT_EVENT2"       => "",
    "arSITE"            => array(WIZARD_SITE_ID),
    "arMENU"            => array("ru" => GetMessage("FORM6_NAME"), "en" => ""),
);

$NEW_ID6 = CForm::Set($arFields6);

if($NEW_ID6) {

    $_SESSION['KIT_ORIGAMI_WIZARD_FORMS'][6] = $NEW_ID6;
    $questions = array(
        array(
            'FORM_ID' => $NEW_ID6,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM_TITLE_NAME"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 100,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'Y',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "text",
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID6,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM6_TITLE_FRIEND_NAME"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 200,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'Y',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "text",
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID6,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM6_TITLE_FRIEND_EMAIL"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 300,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'Y',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "email",
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID6,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM6_TITLE_PRODUCT_LINK"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 200,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'N',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "text",
                )
            )
        )
    );
    foreach($questions as $question) {
        $id = CFormField::set($question);
    }

    CFormStatus::Set(array(
        "FORM_ID"		=> $NEW_ID6,
        "C_SORT"		=> 100,
        "ACTIVE"		=> "Y",
        "TITLE"			=> "DEFAULT",
        "DESCRIPTION"	=> "DEFAULT",
        "CSS"			=> "statusgreen",
        "DEFAULT_VALUE"		=> "Y",
        "arPERMISSION_VIEW"	=> array(0),
        "arPERMISSION_MOVE"	=> array(0),
        "arPERMISSION_EDIT"	=> array(0),
        "arPERMISSION_DELETE" => array(0),
    ), false, 'N');

    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH ."include/ajax/wantgift.php", Array("FORM6_ID" => $NEW_ID6));
    //CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"] ."/include/ajax/wantgift.php", Array("WEB_FORM_ID" => $NEW_ID6));
}

$arFields7 = array(
    "NAME"              => GetMessage("FORM7_NAME"),
    "SID"               => 'CHECKSTOCK',
    "C_SORT"            => 200,
    "BUTTON"            => GetMessage("FORM1_BUTTON"),
    "DESCRIPTION"       => "",
    "DESCRIPTION_TYPE"  => "text",
    "STAT_EVENT1"       => "form",
    "STAT_EVENT2"       => "",
    "arSITE"            => array(WIZARD_SITE_ID),
    "arMENU"            => array("ru" => GetMessage("FORM7_NAME"), "en" => ""),
);

$NEW_ID7 = CForm::Set($arFields7);

if($NEW_ID7) {

    $_SESSION['KIT_ORIGAMI_WIZARD_FORMS'][7] = $NEW_ID7;
    $questions = array(
        array(
            'FORM_ID' => $NEW_ID7,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM_TITLE_NAME2"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 100,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'Y',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "text",
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID7,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM7_PHONE"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 200,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'Y',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "text",
                    "FIELD_PARAM" => "id=\"tel\""
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID7,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 300),
            'TITLE' => GetMessage("FORM7_PRODUCT"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 300,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'Y',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "text",
                )
            )
        )
    );
    foreach($questions as $question) {
        $id = CFormField::set($question);
    }

    CFormStatus::Set(array(
        "FORM_ID"		=> $NEW_ID7,
        "C_SORT"		=> 100,
        "ACTIVE"		=> "Y",
        "TITLE"			=> "DEFAULT",
        "DESCRIPTION"	=> "DEFAULT",
        "CSS"			=> "statusgreen",
        "DEFAULT_VALUE"		=> "Y",
        "arPERMISSION_VIEW"	=> array(0),
        "arPERMISSION_MOVE"	=> array(0),
        "arPERMISSION_EDIT"	=> array(0),
        "arPERMISSION_DELETE" => array(0),
    ), false, 'N');

    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH ."include/ajax/checkstock.php", Array("FORM7_ID" => $NEW_ID7));
    //CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"] ."/include/ajax/wantgift.php", Array("WEB_FORM_ID" => $NEW_ID6));
}

$arFields8 = array(
    "NAME"              => GetMessage("FORM8_NAME"),
    "SID"               => "MEETING",
    "C_SORT"            => 800,
    "BUTTON"            => GetMessage("FORM8_BUTTON"),
    "DESCRIPTION"       => "",
    "DESCRIPTION_TYPE"  => "text",
    "STAT_EVENT1"       => "form",
    "STAT_EVENT2"       => "",
    "arSITE"            => array(WIZARD_SITE_ID),
    "arMENU"            => array("ru" => GetMessage("FORM8_NAME"), "en" => ""),
);

$NEW_ID8 = CForm::Set($arFields8);

if($NEW_ID8)
{
    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH ."include/ajax/contacts_callback_manager.php", Array("FORM8_ID" => $NEW_ID8));
    $questions = array(
        array(
            'FORM_ID' => $NEW_ID8,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 900),
            'TITLE' => GetMessage("FORM8_TITLE_NAME"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 100,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'Y',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "text",
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID8,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 900),
            'TITLE' => GetMessage("FORM8_TITLE_PHONE"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 200,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'Y',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "text",
                    "FIELD_PARAM" => "id=\"tel\""
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID8,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 900),
            'TITLE' => GetMessage("FORM8_TITLE_DATE"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 300,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'Y',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "date",
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID8,
            'ACTIVE' => 'Y',
            'SID' => 'KIT_FORM_QUESTION_'.rand(50, 900),
            'TITLE' => GetMessage("FORM8_TITLE_COMMENT"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 300,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'Y',
            'arANSWER' => array(
                array(
                    "MESSAGE"     => " ",
                    "C_SORT"      => 100,
                    "ACTIVE"      => "Y",
                    "FIELD_TYPE"  => "textarea",
                )
            )
        )
    );

    foreach($questions as $question) {
        $id = CFormField::set($question);
    }
    CFormStatus::Set(array(
        "FORM_ID"		=> $NEW_ID8,
        "C_SORT"		=> 100,
        "ACTIVE"		=> "Y",
        "TITLE"			=> "DEFAULT",
        "DESCRIPTION"	=> "DEFAULT",
        "CSS"			=> "statusgreen",
        "DEFAULT_VALUE"	=> "Y",
        "arPERMISSION_VIEW"	=> array(0),
        "arPERMISSION_MOVE"	=> array(0),
        "arPERMISSION_EDIT"	=> array(0),
        "arPERMISSION_DELETE"	=> array(0),
    ), false, 'N');
}

$arFields9 = array(
    "NAME"              => GetMessage("FORM9_NAME"),
    "SID"               => "FOUND_ERROR",
    "C_SORT"            => 800,
    "BUTTON"            => GetMessage("FORM9_BUTTON"),
    "DESCRIPTION"       => "",
    "DESCRIPTION_TYPE"  => "text",
    "STAT_EVENT1"       => "form",
    "STAT_EVENT2"       => "",
    "arSITE"            => array(WIZARD_SITE_ID),
    "arMENU"            => array("ru" => GetMessage("FORM9_NAME"), "en" => ""),
);

$NEW_ID9 = CForm::Set($arFields9);

if ($NEW_ID9) {
    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "include/ajax/found_error.php", Array("FORM9_ID" => $NEW_ID9));

    $questions = array(
        array(
            'FORM_ID' => $NEW_ID9,
            'ACTIVE' => 'Y',
            'SID' => 'SIMPLE_QUESTION_' . rand(30, 800),
            'TITLE' => GetMessage("FORM9_TITLE_TEXTAREA"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 100,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'N',
            'arANSWER' => array(
                array(
                    "MESSAGE" => " ",
                    "C_SORT" => 100,
                    "ACTIVE" => "Y",
                    "FIELD_TYPE" => "textarea",
                )
            )
        ),
        array(
            'FORM_ID' => $NEW_ID9,
            'ACTIVE' => 'Y',
            'SID' => 'SIMPLE_QUESTION_' . rand(30, 800),
            'TITLE' => GetMessage("FORM9_TITLE_FILE"),
            'TITLE_TYPE' => 'text',
            'C_SORT' => 200,
            'ADDITIONAL' => 'N',
            'REQUIRED' => 'N',
            'arANSWER' => array(
                array(
                    "MESSAGE" => " ",
                    "C_SORT" => 100,
                    "ACTIVE" => "Y",
                    "FIELD_TYPE" => "file",
                )
            )
        ),
    );
    foreach($questions as $question) {
        $id = CFormField::set($question);
    }
    CFormStatus::Set(array(
        "FORM_ID"		=> $NEW_ID9,
        "C_SORT"		=> 100,
        "ACTIVE"		=> "Y",
        "TITLE"			=> "DEFAULT",
        "DESCRIPTION"	=> "DEFAULT",
        "CSS"			=> "statusgreen",
        "DEFAULT_VALUE"	=> "Y",
        "arPERMISSION_VIEW"	=> array(0),
        "arPERMISSION_MOVE"	=> array(0),
        "arPERMISSION_EDIT"	=> array(0),
        "arPERMISSION_DELETE"	=> array(0),
    ), false, 'N');

}
?>
