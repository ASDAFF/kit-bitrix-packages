<?
namespace Kit\Schemaorg;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * class for page meta settings
 *
 */
class SchemaPageMetaTable extends \DataManagerEx_SchemaOrg {

    private $errors = array();

    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName() {
        return 'b_kit_schema_page_meta';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap() {
        return array(
            'ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('KIT_SCHEMA_PAGE_META_ENTITY_ID_FIELD'),
            ),
            'NAME' => array(
                'data_type' => 'string',
                'required' => true,
                'title' => Loc::getMessage('KIT_SCHEMA_PAGE_META_ENTITY_NAME_FIELD'),
            ),
            'ACTIVE' => array(
                'data_type' => 'boolean',
                'values' => array(
                    'N',
                    'Y'
                ),
                'title' => Loc::getMessage('KIT_SCHEMA_PAGE_META_ENTITY_ACTIVE_FIELD'),
            ),
            'URL' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('KIT_SCHEMA_PAGE_META_ENTITY_URL_FIELD'),
            ),
            'ENTITIES' => array(
                'data_type' => 'text',
                'required' => true,
                'title' => Loc::getMessage('KIT_SCHEMA_PAGE_META_ENTITIES_FIELD'),
            ),
            'CATEGORY_ID' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('KIT_SCHEMA_CATEGORY_ID_FIELD'),
            ),
            'TIMESTAMP_X' =>  array(
                'data_type' => 'datetime',
                'default_value' => function(){ return new \Bitrix\Main\Type\DateTime(); },
                'title' => Loc::getMessage('KIT_SCHEMA_ENTITY_TIMESTAMP_X_FIELD'),
            ),
            'DATE_CREATE' =>  array(
                'data_type' => 'datetime',
                'default_value' => function(){ return new \Bitrix\Main\Type\DateTime(); },
                'title' => Loc::getMessage('KIT_SCHEMA_ENTITY_TIMESTAMP_X_FIELD'),
            ),
            'SITE_ID' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('KIT_SCHEMA_SITE_ID'),
            ),
            'SORT' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('KIT_SCHEMA_PAGE_META_ENTITY_SORT_FIELD'),
            ),
            'ENTITY_TYPE' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('KIT_SCHEMA_ENTITY_TYPE'),
            )
        );
    }

    public static function deleteEntities($ID)
    {
        $entitiesID = self::getList(
            array(
                'select' => array('ID'),
                'filter' => array("CATEGORY_ID" => $ID)
            )
        )->fetchAll();

        if(isset($entitiesID) && is_array($entitiesID) && !empty($entitiesID)){
            foreach ($entitiesID as $item){
                self::delete($item);
            }
        }
    }

    private function fillErrors(array $arFields) {
        foreach($arFields as $key => $val) {
            $this->errors[$key] = Loc::getMessage('KIT_SCHEMA_PAGE_META_ENTITY_REQUIRED_FIELD_ERROR')." [".Loc::getMessage('KIT_SCHEMA_'.$key)."]";
        }
    }

    public function getErrors() {
        return $this->errors;
    }
}

?>