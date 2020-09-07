<? include(__DIR__.'/../.begin.php') ?>
<?

use Bitrix\Main\Loader;
use intec\core\base\Collection;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var Collection $data
 * @var array $languages
 * @var CWizardBase $wizard
 * @var CWizardStep $this
 */

if (!Loader::includeModule('intec.startshop'))
    return;

$import = function ($code, $localization = [], $fields = []) use (&$languages) {
    $code = $code.'_'.WIZARD_SITE_ID;
    $form = CStartShopForm::GetList([], [
        'CODE' => $code
    ])->Fetch();

    if (!empty($form))
        return $form;

    if (!Type::isArray($localization))
        $localization = [];

    if (!Type::isArray($fields))
        $fields = [];

    $site = WIZARD_SITE_ID;
    $form = [
        'CODE' => $code,
        'SORT' => 100,
        'USE_POST' => 'Y',
        'USE_CAPTCHA' => 'N',
        'LANG' => [],
        'SID' => [$site]
    ];

    foreach ($languages as $id => $language) {
        $language = ArrayHelper::getValue($localization, $id);
        $language = Type::isArray($language) ? $language : [];
        $language = ArrayHelper::merge([
            'name' => '',
            'button' => '',
            'sent' => ''
        ], $language);

        foreach ($language as $key => $value)
            if (empty($value))
                $language[$key] = '';

        $form['LANG'][$id] = [
            'NAME' => $language['name'],
            'BUTTON' => $language['button'],
            'SENT' => $language['sent']
        ];
    }

    $form = CStartShopForm::Add($form);
    $form = CStartShopForm::GetList([], [
        'ID' => $form
    ])->Fetch();

    if ($form === false)
        return null;

    foreach($fields as $field) {
        $localization = ArrayHelper::getValue($field, 'localization');

        if (!Type::isArray($localization))
            $localization = [];

        $field = [
            'CODE' => $field['code'],
            'FORM' => $form['ID'],
            'SORT' => 500,
            'ACTIVE' => 'Y',
            'REQUIRED' => $field['required'],
            'READONLY' => $field['readonly'],
            'TYPE' => $field['type'],
            'LANG' => []
        ];

        foreach ($languages as $id => $language) {
            $language = ArrayHelper::getValue($localization, $id);
            $language = Type::isArray($language) ? $language : [];
            $language = ArrayHelper::merge([
                'name' => ''
            ], $language);

            foreach ($language as $key => $value)
                if (empty($value))
                    $language[$key] = '';

            $field['LANG'][$id] = [
                'NAME' => $language['name']
            ];
        }

        CStartShopFormProperty::Add($field);
    }

    return $form;
};