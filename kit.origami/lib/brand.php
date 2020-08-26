<?
namespace Kit\Origami;
use Kit\Origami\Config\Option;
use \Bitrix\Main\Data\Cache;
/**
 * Class Brand
 *
 * @package Kit\Origami
 * 
 */
class Brand
{
    protected $brandProps = [];
    protected $resize = [];
    protected $template = null;
    public function __construct($template = []){
        $this->template = $template;
    }
    public function findBrandsForElement($props = []){
        $return = [];
        $idBrands = [];
        foreach ($this->brandProps as $prop) {
            if ($props[$prop]['VALUE'] && is_array($props[$prop]['VALUE'])) {
                foreach ($props[$prop]['VALUE'] as $v) {
                    $idBrands[] = $v;
                }
            } else {
                $idBrands[] = $props[$prop]['VALUE'];
            }
        }
        $cache = Cache::createInstance();
        
        $idBrands = array_filter($idBrands);
        
        if ($cache->initCache(36000000, 'detail_brands_'.md5(serialize($idBrands)), '/kit.origami')) {
            $return = $cache->getVars();
        }
        elseif($cache->startDataCache())
        {
            if (!empty($idBrands)) {
                $rs = \CIBlockElement::GetList(
                    [],
                    [
                        'ID'        => $idBrands,
                        'IBLOCK_ID' => Option::get('IBLOCK_ID_BRANDS')
                    ],
                    false,
                    false,
                    [
                        'PREVIEW_PICTURE',
                        'DETAIL_PAGE_URL',
                        'NAME'
                    ]
                );
                while ($el = $rs->GetNext()) {
                    if ($el['PREVIEW_PICTURE']) {
                        if ($this->resize) {
                            $el['PREVIEW_PICTURE'] = \CFile::ResizeImageGet(
                                $el['PREVIEW_PICTURE'],
                                [
                                    'width'  => $this->resize['width'],
                                    'height' => $this->resize['height'],
                                ],
                                $this->resize['type']
                            );
                            $el['PREVIEW_PICTURE']['SRC']
                                = $el['PREVIEW_PICTURE']['src'];
                            unset($el['PREVIEW_PICTURE']['src']);
                        } else {
                            $el['PREVIEW_PICTURE']
                                = ['SRC' => \CFile::GetPath($el['PREVIEW_PICTURE'])];
                        }
                    }
                    if ($el['PREVIEW_PICTURE']['SRC']) {
                        $return[$el['ID']] = [
                            'SRC'  => $el['PREVIEW_PICTURE']['SRC'],
                            'URL'  => $el['DETAIL_PAGE_URL'],
                            'NAME' => $el['NAME']
                        ];
                    }
                }
            }
            $cache->endDataCache($return);
        }
        return $return;
    }

    /**
     * @param array $brandProps
     */
    public function setBrandProps($brandProps)
    {
        foreach($brandProps as $prop){
            if($prop){
                $this->brandProps[] = $prop;
            }
        }
    }

    /**
     * @param array $resize
     */
    public function setResize($resize)
    {
        $this->resize = $resize;
    }
}