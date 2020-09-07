<? include(__DIR__.'/../.begin.php') ?>
<?

use Bitrix\Main\Loader;
use intec\core\base\Collection;
use intec\core\collections\Arrays;
use intec\regionality\models\Region;
use intec\regionality\models\region\PriceType as RegionPriceType;
use intec\regionality\models\region\Site as RegionSite;
use intec\regionality\models\region\Store as RegionStore;

/**
 * @var Collection $data
 * @var string $mode
 * @var CWizardBase $wizard
 * @var CWizardStep $this
 */

if (!Loader::includeModule('intec.regionality'))
    return;

if ($wizard->GetVar('systemConfigureRegions') === 'Y') {
    $regions = Region::find()->with(['sites', 'pricesTypes', 'stores'])->all();

    /** @var Region $region */
    foreach ($regions as $region) {
        $regionSites = $region->getSites(true);
        $regionSites->indexBy('siteId');

        if (!$regionSites->exists(WIZARD_SITE_ID)) {
            $regionSite = new RegionSite();
            $regionSite->regionId = $region->id;
            $regionSite->siteId = WIZARD_SITE_ID;
            $regionSite->save();
        }
    }

    if (Loader::includeModule('catalog')) {
        $pricesTypes = Arrays::fromDBResult(CCatalogGroup::GetList([
            'SORT' => 'ASC'
        ], [
            'ACTIVE' => 'Y',
            'BASE' => 'Y'
        ]));

        $stores = Arrays::fromDBResult(CCatalogStore::GetList([
            'SORT' => 'ASC'
        ], [
            'ACTIVE' => 'Y',
            'ISSUING_CENTER' => 'Y'
        ]));

        /** @var Region $region */
        foreach ($regions as $region) {
            $regionPricesTypes = $region->getPricesTypes(true);
            $regionStores = $region->getStores(true);

            if ($regionPricesTypes->isEmpty())
                foreach ($pricesTypes as $priceType) {
                    $regionPriceType = new RegionPriceType();
                    $regionPriceType->regionId = $region->id;
                    $regionPriceType->priceTypeId = $priceType['ID'];
                    $regionPriceType->save();
                }

            if ($regionStores->isEmpty())
                foreach ($stores as $store) {
                    $regionStore = new RegionStore();
                    $regionStore->regionId = $region->id;
                    $regionStore->storeId = $store['ID'];
                    $regionStore->save();
                }
        }
    } else if (Loader::includeModule('intec.startshop')) {
        $pricesTypes = Arrays::fromDBResult(CStartShopPrice::GetList([
            'SORT' => 'ASC'
        ], [
            'ACTIVE' => 'Y'
        ]));

        /** @var Region $region */
        foreach ($regions as $region) {
            $regionPricesTypes = $region->getPricesTypes(true);

            if ($regionPricesTypes->isEmpty())
                foreach ($pricesTypes as $priceType) {
                    $regionPriceType = new RegionPriceType();
                    $regionPriceType->regionId = $region->id;
                    $regionPriceType->priceTypeId = $priceType['ID'];
                    $regionPriceType->save();
                }
        }
    }
}

?>
<? include(__DIR__.'/../.end.php') ?>