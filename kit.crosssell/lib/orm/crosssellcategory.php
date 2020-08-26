<?
namespace Kit\Crosssell\Orm;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * class for page meta settings
 *
 */
class CrosssellCategoryTable extends \DataManagerCrosssell {
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName() {
        return 'kit_crosssell_section';
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
            ),
            'PARENT_ID' => array(
                'data_type' => 'integer',
            ),
            'NAME' => array(
                'data_type' => 'string',
                'required' => true,
            ),
            'PARENT' => array(
                'data_type' => 'Kit\Crosssell\Orm\CrosssellCategory',
                'reference' => array('=this.PARENT_ID' => 'ref.ID'),
            ),
            'TIMESTAMP_X' => array(
                'data_type' => 'datetime',
            ),
            'DATE_CREATE' => array(
                'data_type' => 'datetime',
            ),
            'SITE_ID' => array(
                'data_type' => 'string',
            ),
            'SORT' => array(
                'data_type' => 'string',
            ),
            /*'EXTRA_SETTINGS' => array(
                'data_type' => 'string'
            ),*/
            'SYMBOL_CODE' => array(
                'data_type' => 'string',
            ),
        );
    }
}

?>