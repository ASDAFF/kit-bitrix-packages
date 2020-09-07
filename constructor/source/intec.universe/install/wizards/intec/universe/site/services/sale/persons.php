<? include(__DIR__.'/../.begin.php') ?>
<?

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\base\Collection;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var Collection $data
 * @var CWizardBase $wizard
 * @var CWizardStep $this
 */

if ($mode === WIZARD_MODE_UPDATE)
    return;

Loc::loadMessages(__FILE__);

if (Loader::includeModule('sale')) {
    $site = WIZARD_SITE_ID;
    $types = $wizard->GetVar('personType');
    $types = [
        'individual' => ArrayHelper::getValue($types, 'fiz') == 'Y',
        'legacy' => ArrayHelper::getValue($types, 'ur') == 'Y'
    ];

    $persons = [];
    $result = CSalePersonType::GetList();

    while ($person = $result->GetNext())
        $persons[] = $person;

    $number = 0;

    /**
     * Импорт типов плательщиков.
     * @var string $type
     * @var bool $active
     */
    foreach ($types as $type => $active) {
        $number++;

        /** Если плательщик не выбран, идем к следующему */
        if (!$active)
            continue;

        /**
         * Находим наименование плательщика из локализации.
         * @var string $name
         */
        $name = Loc::getMessage('wizard.services.sale.persons.persons.'.$type);
        $person = null;

        /**
         * Поиск плательщика по наименованию.
         * @var array $person
         */
        foreach ($persons as $person) {
            if ($person['NAME'] === $name)
                break;

            $person = null;
        }

        /** Если такого типа плательщика нет */
        if ($person === null) {
            $person = (new CSalePersonType())->Add([
                'LID' => $site,
                'ACTIVE' => 'Y',
                'NAME' => $name,
                'SORT' => (100 * $number)
            ]);

            $person = CSalePersonType::GetByID($person);
        } else { /** Иначе обновляем текущего плательщика, делая его активным и добавляя его в текущий сайт */
            if (!ArrayHelper::isIn($site, $person['LIDS']))
                $person['LIDS'][] = $site;

            (new CSalePersonType())->Update($person['ID'], [
                'ACTIVE' => 'Y',
                'LID' => $person['LIDS']
            ]);
        }

        $person['GROUPS'] = [];
        $types[$type] = $person;
    }

    /** Добавление групп для Физического лица */
    if ($types['individual'] !== false) {
        $types['individual']['GROUPS']['data'] = 'data';
        $types['individual']['GROUPS']['delivery'] = 'delivery';
    }

    /** Добавление групп для Юридического лица */
    if ($types['legacy'] !== false) {
        $types['legacy']['GROUPS']['data'] = 'data';
        $types['legacy']['GROUPS']['contacts'] = 'contacts';
    }

    $groups = [];
    $result = CSaleOrderPropsGroup::GetList();

    while ($group = $result->GetNext())
        $groups[] = $group;

    /**
     * Импорт групп свойств заказа.
     * @var string $type
     * @var array $person
     */
    foreach ($types as $type => $person) {
        if ($person === false)
            continue;

        $number = 0;

        foreach ($person['GROUPS'] as $code) {
            $number++;
            $name = Loc::getMessage('wizard.services.sale.persons.groups.'.$type.'.'.$code);
            $group = null;

            /**
             * Поиск гурппы свойств заказа по наименованию.
             * @var array $group
             */
            foreach ($groups as $group) {
                if ($group['NAME'] === $name && $group['PERSON_TYPE_ID'] == $person['ID'])
                    break;

                $group = null;
            }

            /** Если такой группы нет */
            if ($group === null) {
                $group = (new CSaleOrderPropsGroup())->Add([
                    'PERSON_TYPE_ID' => $person['ID'],
                    'NAME' => $name,
                    'SORT' => (100 * $number)
                ]);

                $group = CSaleOrderPropsGroup::GetByID($group);
            }

            $types[$type]['GROUPS'][$code] = $group;
        }
    }

    $properties = [];

    /** Импорт свойств заказа для Физического лица */
    if ($types['individual'] !== false) {
        $person = $types['individual'];

        if (!empty($person['GROUPS']['data'])) {
            $group = $person['GROUPS']['data'];

            /** Инициалы */
            $properties[] = [
                'PERSON_TYPE_ID' => $person['ID'],
                'NAME' => Loc::getMessage('wizard.services.sale.persons.fields.initials'),
                'CODE' => 'FIO',
                'TYPE' => 'TEXT',
                'REQUIRED' => 'Y',
                'DEFAULT_VALUE' => '',
                'SORT' => 100,
                'USER_PROPS' => 'Y',
                'IS_LOCATION' => 'N',
                'PROPS_GROUP_ID' => $group['ID'],
                'SIZE1' => 40,
                'SIZE2' => 0,
                'DESCRIPTION' => '',
                'IS_EMAIL' => 'N',
                'IS_PROFILE_NAME' => 'Y',
                'IS_PAYER' => 'Y',
                'IS_LOCATION4TAX' => 'N',
                'IS_FILTERED' => 'Y'
            ];

            /** Эл. почта */
            $properties[] = [
                'PERSON_TYPE_ID' => $person['ID'],
                'NAME' => 'E-Mail',
                'CODE' => 'EMAIL',
                'TYPE' => 'TEXT',
                'REQUIRED' => 'Y',
                'DEFAULT_VALUE' => '',
                'SORT' => 110,
                'USER_PROPS' => 'Y',
                'IS_LOCATION' => 'N',
                'PROPS_GROUP_ID' => $group['ID'],
                'SIZE1' => 40,
                'SIZE2' => 0,
                'DESCRIPTION' => '',
                'IS_EMAIL' => 'Y',
                'IS_PROFILE_NAME' => 'N',
                'IS_PAYER' => 'N',
                'IS_LOCATION4TAX' => 'N',
                'IS_FILTERED' => 'Y'
            ];

            /** Телефон */
            $properties[] = [
                'PERSON_TYPE_ID' => $person['ID'],
                'NAME' => Loc::getMessage('wizard.services.sale.persons.fields.phone'),
                'CODE' => 'PHONE',
                'TYPE' => 'TEXT',
                'REQUIRED' => 'Y',
                'DEFAULT_VALUE' => '',
                'SORT' => 120,
                'USER_PROPS' => 'Y',
                'IS_LOCATION' => 'N',
                'PROPS_GROUP_ID' => $group['ID'],
                'SIZE1' => 0,
                'SIZE2' => 0,
                'DESCRIPTION' => '',
                'IS_EMAIL' => 'N',
                'IS_PROFILE_NAME' => 'N',
                'IS_PAYER' => 'N',
                'IS_LOCATION4TAX' => 'N',
                'IS_PHONE' => 'Y',
                'IS_FILTERED' => 'N'
            ];

        }

        if (!empty($person['GROUPS']['delivery'])) {
            $group = $person['GROUPS']['delivery'];

            /** Индекс */
            $properties[] = [
                'PERSON_TYPE_ID' => $person['ID'],
                'NAME' => Loc::getMessage('wizard.services.sale.persons.fields.zip'),
                'CODE' => 'ZIP',
                'TYPE' => 'TEXT',
                'REQUIRED' => 'N',
                'DEFAULT_VALUE' => '101000',
                'SORT' => 130,
                'USER_PROPS' => 'Y',
                'IS_LOCATION' => 'N',
                'PROPS_GROUP_ID' => $group['ID'],
                'SIZE1' => 8,
                'SIZE2' => 0,
                'DESCRIPTION' => '',
                'IS_EMAIL' => 'N',
                'IS_PROFILE_NAME' => 'N',
                'IS_PAYER' => 'N',
                'IS_LOCATION4TAX' => 'N',
                'IS_FILTERED' => 'N',
                'IS_ZIP' => 'Y'
            ];

            /** Город */
            $properties[] = [
                'PERSON_TYPE_ID' => $person['ID'],
                'NAME' => Loc::getMessage('wizard.services.sale.persons.fields.city'),
                'CODE' => 'CITY',
                'TYPE' => 'TEXT',
                'REQUIRED' => 'N',
                'DEFAULT_VALUE' => $wizard->GetVar('shopLocation'),
                'SORT' => 145,
                'USER_PROPS' => 'Y',
                'IS_LOCATION' => 'N',
                'PROPS_GROUP_ID' => $group['ID'],
                'SIZE1' => 40,
                'SIZE2' => 0,
                'DESCRIPTION' => '',
                'IS_EMAIL' => 'N',
                'IS_PROFILE_NAME' => 'N',
                'IS_PAYER' => 'N',
                'IS_LOCATION4TAX' => 'N',
                'IS_FILTERED' => 'Y'
            ];

            /** Местоположение */
            $properties[] = [
                'PERSON_TYPE_ID' => $person['ID'],
                'NAME' => Loc::getMessage('wizard.services.sale.persons.fields.location'),
                'CODE' => 'LOCATION',
                'TYPE' => 'LOCATION',
                'REQUIRED' => 'Y',
                'DEFAULT_VALUE' => '',
                'SORT' => 140,
                'USER_PROPS' => 'Y',
                'IS_LOCATION' => 'Y',
                'PROPS_GROUP_ID' => $group['ID'],
                'SIZE1' => 40,
                'SIZE2' => 0,
                'DESCRIPTION' => '',
                'IS_EMAIL' => 'N',
                'IS_PROFILE_NAME' => 'N',
                'IS_PAYER' => 'N',
                'IS_LOCATION4TAX' => 'N',
                'IS_FILTERED' => 'N',
                'INPUT_FIELD_LOCATION' => ''
            ];

            /** Адрес */
            $properties[] = [
                'PERSON_TYPE_ID' => $person['ID'],
                'NAME' => Loc::getMessage('wizard.services.sale.persons.fields.address'),
                'CODE' => 'ADDRESS',
                'TYPE' => 'TEXTAREA',
                'REQUIRED' => 'Y',
                'DEFAULT_VALUE' => '',
                'SORT' => 150,
                'USER_PROPS' => 'Y',
                'IS_LOCATION' => 'N',
                'PROPS_GROUP_ID' => $group['ID'],
                'SIZE1' => 30,
                'SIZE2' => 3,
                'DESCRIPTION' => '',
                'IS_EMAIL' => 'N',
                'IS_PROFILE_NAME' => 'N',
                'IS_PAYER' => 'N',
                'IS_LOCATION4TAX' => 'N',
                'IS_FILTERED' => 'N'
            ];
        }
    }

    /** Импорт свойств заказа для Юридического лица */
    if ($types['legacy'] !== false) {
        $person = $types['legacy'];

        if (!empty($person['GROUPS']['data'])) {
            $group = $person['GROUPS']['data'];

            /** Наименование компании */
            $properties[] = [
                'PERSON_TYPE_ID' => $person['ID'],
                'NAME' => Loc::getMessage('wizard.services.sale.persons.fields.company'),
                'CODE' => 'COMPANY',
                'TYPE' => 'TEXT',
                'REQUIRED' => 'Y',
                'DEFAULT_VALUE' => '',
                'SORT' => 200,
                'USER_PROPS' => 'Y',
                'IS_LOCATION' => 'N',
                'PROPS_GROUP_ID' => $group['ID'],
                'SIZE1' => 40,
                'SIZE2' => 0,
                'DESCRIPTION' => '',
                'IS_EMAIL' => 'N',
                'IS_PROFILE_NAME' => 'Y',
                'IS_PAYER' => 'N',
                'IS_LOCATION4TAX' => 'N',
                'IS_FILTERED' => 'Y'
            ];
            
            /** Юридический адрес */
            $properties[] = [
                'PERSON_TYPE_ID' => $person['ID'],
                'NAME' => Loc::getMessage('wizard.services.sale.persons.fields.companyAddress'),
                'CODE' => 'COMPANY_ADDRESS',
                'TYPE' => 'TEXTAREA',
                'REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',
                'SORT' => 210,
                'USER_PROPS' => 'Y',
                'IS_LOCATION' => 'N',
                'PROPS_GROUP_ID' => $group['ID'],
                'SIZE1' => 40,
                'SIZE2' => 0,
                'DESCRIPTION' => '',
                'IS_EMAIL' => 'N',
                'IS_PROFILE_NAME' => 'N',
                'IS_PAYER' => 'N',
                'IS_LOCATION4TAX' => 'N',
                'IS_FILTERED' => 'N'
            ];
            
            /** ИНН */
            $properties[] = [
                'PERSON_TYPE_ID' => $person['ID'],
                'NAME' => Loc::getMessage('wizard.services.sale.persons.fields.inn'),
                'CODE' => 'INN',
                'TYPE' => 'TEXT',
                'REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',
                'SORT' => 220,
                'USER_PROPS' => 'Y',
                'IS_LOCATION' => 'N',
                'PROPS_GROUP_ID' => $group['ID'],
                'SIZE1' => 0,
                'SIZE2' => 0,
                'DESCRIPTION' => '',
                'IS_EMAIL' => 'N',
                'IS_PROFILE_NAME' => 'N',
                'IS_PAYER' => 'N',
                'IS_LOCATION4TAX' => 'N',
                'IS_FILTERED' => 'N'
            ];
            
            /** КПП */
            $properties[] = [
                'PERSON_TYPE_ID' => $person['ID'],
                'NAME' => Loc::getMessage('wizard.services.sale.persons.fields.kpp'),
                'CODE' => 'KPP',
                'TYPE' => 'TEXT',
                'REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',
                'SORT' => 230,
                'USER_PROPS' => 'Y',
                'IS_LOCATION' => 'N',
                'PROPS_GROUP_ID' => $group['ID'],
                'SIZE1' => 0,
                'SIZE2' => 0,
                'DESCRIPTION' => '',
                'IS_EMAIL' => 'N',
                'IS_PROFILE_NAME' => 'N',
                'IS_PAYER' => 'N',
                'IS_LOCATION4TAX' => 'N',
                'IS_FILTERED' => 'N'
            ];
        }

        if (!empty($person['GROUPS']['contacts'])) {
            $group = $person['GROUPS']['contacts'];

            /** Контактное лицо */
            $properties[] = [
                'PERSON_TYPE_ID' => $person['ID'],
                'NAME' => Loc::getMessage('wizard.services.sale.persons.fields.contactPerson'),
                'CODE' => 'CONTACT_PERSON',
                'TYPE' => 'TEXT',
                'REQUIRED' => 'Y',
                'DEFAULT_VALUE' => '',
                'SORT' => 240,
                'USER_PROPS' => 'Y',
                'IS_LOCATION' => 'N',
                'PROPS_GROUP_ID' => $group['ID'],
                'SIZE1' => 0,
                'SIZE2' => 0,
                'DESCRIPTION' => '',
                'IS_EMAIL' => 'N',
                'IS_PROFILE_NAME' => 'N',
                'IS_PAYER' => 'Y',
                'IS_LOCATION4TAX' => 'N',
                'IS_FILTERED' => 'N'
            ];
            
            /** Эл. почта */
            $properties[] = [
                'PERSON_TYPE_ID' => $person['ID'],
                'NAME' => 'E-Mail',
                'CODE' => 'EMAIL',
                'TYPE' => 'TEXT',
                'REQUIRED' => 'Y',
                'DEFAULT_VALUE' => '',
                'SORT' => 250,
                'USER_PROPS' => 'Y',
                'IS_LOCATION' => 'N',
                'PROPS_GROUP_ID' => $group['ID'],
                'SIZE1' => 40,
                'SIZE2' => 0,
                'DESCRIPTION' => '',
                'IS_EMAIL' => 'Y',
                'IS_PROFILE_NAME' => 'N',
                'IS_PAYER' => 'N',
                'IS_LOCATION4TAX' => 'N',
                'IS_FILTERED' => 'N'
            ];

            /** Телефон */
            $properties[] = [
                'PERSON_TYPE_ID' => $person['ID'],
                'NAME' => Loc::getMessage('wizard.services.sale.persons.fields.phone'),
                'CODE' => 'PHONE',
                'TYPE' => 'TEXT',
                'REQUIED' => 'N',
                'DEFAULT_VALUE' => '',
                'SORT' => 260,
                'USER_PROPS' => 'Y',
                'IS_LOCATION' => 'N',
                'PROPS_GROUP_ID' => $group['ID'],
                'SIZE1' => 0,
                'SIZE2' => 0,
                'DESCRIPTION' => '',
                'IS_EMAIL' => 'N',
                'IS_PROFILE_NAME' => 'N',
                'IS_PAYER' => 'N',
                'IS_LOCATION4TAX' => 'N',
                'IS_PHONE' => 'Y',
                'IS_FILTERED' => 'N'
            ];

            /** Факс */
            $properties[] = [
                'PERSON_TYPE_ID' => $person['ID'],
                'NAME' => Loc::getMessage('wizard.services.sale.persons.fields.fax'),
                'CODE' => 'FAX',
                'TYPE' => 'TEXT',
                'REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',
                'SORT' => 270,
                'USER_PROPS' => 'Y',
                'IS_LOCATION' => 'N',
                'PROPS_GROUP_ID' => $group['ID'],
                'SIZE1' => 0,
                'SIZE2' => 0,
                'DESCRIPTION' => '',
                'IS_EMAIL' => 'N',
                'IS_PROFILE_NAME' => 'N',
                'IS_PAYER' => 'N',
                'IS_LOCATION4TAX' => 'N',
                'IS_FILTERED' => 'N'
            ];

            /** Индекс */
            $properties[] = [
                'PERSON_TYPE_ID' => $person['ID'],
                'NAME' => Loc::getMessage('wizard.services.sale.persons.fields.zip'),
                'CODE' => 'ZIP',
                'TYPE' => 'TEXT',
                'REQUIRED' => 'N',
                'DEFAULT_VALUE' => '101000',
                'SORT' => 280,
                'USER_PROPS' => 'Y',
                'IS_LOCATION' => 'N',
                'PROPS_GROUP_ID' => $group['ID'],
                'SIZE1' => 8,
                'SIZE2' => 0,
                'DESCRIPTION' => '',
                'IS_EMAIL' => 'N',
                'IS_PROFILE_NAME' => 'N',
                'IS_PAYER' => 'N',
                'IS_LOCATION4TAX' => 'N',
                'IS_FILTERED' => 'N',
                'IS_ZIP' => 'Y'
            ];

            /** Город */
            $properties[] = [
                'PERSON_TYPE_ID' => $person['ID'],
                'NAME' => Loc::getMessage('wizard.services.sale.persons.fields.city'),
                'TYPE' => 'TEXT',
                'REQUIRED' => 'N',
                'DEFAULT_VALUE' => $wizard->GetVar('shopLocation'),
                'SORT' => 285,
                'USER_PROPS' => 'Y',
                'IS_LOCATION' => 'N',
                'PROPS_GROUP_ID' => $group['ID'],
                'SIZE1' => 40,
                'SIZE2' => 0,
                'DESCRIPTION' => '',
                'IS_EMAIL' => 'N',
                'IS_PROFILE_NAME' => 'N',
                'IS_PAYER' => 'N',
                'IS_LOCATION4TAX' => 'N',
                'CODE' => 'CITY',
                'IS_FILTERED' => 'Y',
            ];

            /** Местоположение */
            $properties[] = [
                'PERSON_TYPE_ID' => $person['ID'],
                'NAME' => Loc::getMessage('wizard.services.sale.persons.fields.location'),
                'CODE' => 'LOCATION',
                'TYPE' => 'LOCATION',
                'REQUIRED' => 'Y',
                'DEFAULT_VALUE' => '',
                'SORT' => 290,
                'USER_PROPS' => 'Y',
                'IS_LOCATION' => 'Y',
                'PROPS_GROUP_ID' => $group['ID'],
                'SIZE1' => 40,
                'SIZE2' => 0,
                'DESCRIPTION' => '',
                'IS_EMAIL' => 'N',
                'IS_PROFILE_NAME' => 'N',
                'IS_PAYER' => 'N',
                'IS_LOCATION4TAX' => 'Y',
                'IS_FILTERED' => 'N'
            ];

            /** Адрес */
            $properties[] = [
                'PERSON_TYPE_ID' => $person['ID'],
                'NAME' => Loc::getMessage('wizard.services.sale.persons.fields.address'),
                'CODE' => 'ADDRESS',
                'TYPE' => 'TEXTAREA',
                'REQUIED' => 'Y',
                'DEFAULT_VALUE' => '',
                'SORT' => 300,
                'USER_PROPS' => 'Y',
                'IS_LOCATION' => 'N',
                'PROPS_GROUP_ID' => $group['ID'],
                'SIZE1' => 30,
                'SIZE2' => 10,
                'DESCRIPTION' => '',
                'IS_EMAIL' => 'N',
                'IS_PROFILE_NAME' => 'N',
                'IS_PAYER' => 'N',
                'IS_LOCATION4TAX' => 'N',
                'IS_FILTERED' => 'N'
            ];
        }
    }

    $dbProperties = [];
    $result = CSaleOrderProps::GetList();

    while ($dbProperty = $result->GetNext())
        $dbProperties[] = $dbProperty;

    /**
     * Импортируем свойства.
     * @var array $property
     */
    foreach ($properties as $property) {
        $dbProperty = null;

        foreach ($dbProperties as $dbProperty) {
            if ($dbProperty['CODE'] == $property['CODE'] && $dbProperty['PERSON_TYPE_ID'] == $property['PERSON_TYPE_ID'])
                break;

            $dbProperty = null;
        }

        /** Если свойство существует, идем к следующему */
        if ($dbProperty !== null)
            continue;

        (new CSaleOrderProps())->Add($property);
    }
}

?>
<? include(__DIR__.'/../.end.php') ?>