<?
namespace Sotbit\Opengraph;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * class for page meta settings
 *
 * @author Evgenij Sidorenko < e.sidorenko@sotbit.ru >
 *
 */
class OpengraphCategoryTable extends DataManager {
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName() {
        return 'b_sotbit_opengraph_section';
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
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_PAGE_META_ENTITY_ID_FIELD'),
            ),
            'PARENT_ID' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_PAGE_META_ENTITY_PARENT_ID_FIELD'),
            ),
            'NAME' => array(
                'data_type' => 'string',
                'required' => true,
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_PAGE_META_ENTITY_NAME_FIELD'),
            ),
            'PARENT' => array(
                'data_type' => 'Sotbit\Opengraph\OpengraphCategory',
                'reference' => array('=this.PARENT_ID' => 'ref.ID'),
            ),
            'TIMESTAMP_X' => array(
                'data_type' => 'datetime',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_ENTITY_TIMESTAMP_X_FIELD'),
            ),
            'DATE_CREATE' => array(
                'data_type' => 'datetime',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_ENTITY_DATE_CREATE_FIELD'),
            ),
            'SITE_ID' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_PAGE_META_ENTITY_SITE_ID_FIELD'),
            ),
            'SORT' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_PAGE_META_ENTITY_SORT_FIELD'),
            ),
        );
    }
}

?>