<?
namespace Kit\Schemaorg;

use Bitrix\Main\Localization\Loc;
//use Kit\Schemaorg\SchemaPageMetaTable;

Loc::loadMessages(__FILE__);

/**
 * class for page meta settings
 *
 */
class SchemaCategoryTable extends \DataManagerEx_SchemaOrg {
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName() {
        return 'b_kit_schema_category';
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
            'PARENT_ID' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('KIT_SCHEMA_PAGE_META_ENTITY_PARENT_ID_FIELD'),
            ),
            'NAME' => array(
                'data_type' => 'string',
                'required' => true,
                'title' => Loc::getMessage('KIT_SCHEMA_PAGE_META_ENTITY_NAME_FIELD'),
            ),
            'TIMESTAMP_X' => array(
                'data_type' => 'datetime',
                'default_value' => function(){ return new \Bitrix\Main\Type\DateTime(); },
                'title' => Loc::getMessage('KIT_SCHEMA_ENTITY_TIMESTAMP_X_FIELD'),
            ),
            'DATE_CREATE' => array(
                'data_type' => 'datetime',
                'default_value' => function(){ return new \Bitrix\Main\Type\DateTime(); },
                'title' => Loc::getMessage('KIT_SCHEMA_ENTITY_TIMESTAMP_X_FIELD'),
            ),
            'SITE_ID' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('KIT_SCHEMA_PAGE_META_ENTITY_SITE_ID_FIELD'),
            ),
            'SORT' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('KIT_SCHEMA_PAGE_META_ENTITY_SORT_FIELD'),
            )
        );
    }

    public static function deleteEntities($ID)
    {
        $entitiesID = self::getList(
            array(
                'select' => array("ID"),
                'filter' => array("PARENT_ID" => $ID),
            )
        )->fetchAll();
        if(isset($entitiesID) && is_array($entitiesID) && !empty($entitiesID))
            foreach ($entitiesID as $item) {
                self::delete($item);
                SchemaPageMetaTable::deleteEntities($item);
            }
        self::delete($ID);
        SchemaPageMetaTable::deleteEntities($ID);
    }
}
