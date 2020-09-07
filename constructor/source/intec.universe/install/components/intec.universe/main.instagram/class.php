<?

use intec\core\base\InvalidParamException;
use intec\core\helpers\Json;
use intec\core\net\Url;
use intec\core\net\http\Request;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Encoding;
use intec\core\helpers\FileHelper;
use intec\core\helpers\StringHelper;
use intec\core\io\Path;
use Bitrix\Main\Localization\Loc;

class IntecInstagramComponent extends CBitrixComponent {

    public function executeComponent(){

        global $APPLICATION;

        $arParams = $this->arParams;
        $sToken = ArrayHelper::getValue($arParams, 'ACCESS_TOKEN');
        $iCountItems = ArrayHelper::getValue($arParams, 'COUNT_ITEMS', '10');
        $sPath = ArrayHelper::getValue(
            $arParams,
            'CACHE_PATH',
            'upload/intec.universe/instagram/cache/#SITE_DIR#'
        );

        $bUpdate = false;
        $sPath = StringHelper::replaceMacros($sPath, ['SITE_DIR' => SITE_DIR]);
        $sPath = Path::from('@root/'.$sPath);
        $sPath = $sPath->add('instagram.'.$sToken.'.json')->value;

        if (FileHelper::isFile($sPath)) {

            $bUpdate = false;
            $json = FileHelper::getFileData($sPath);
            $data = Json::decode($json);

            $date = new DateTime($data['date']);
            $date -> add(new DateInterval('PT'.$arParams['CACHE_TIME'].'S'));
            $dateCreate = $date->format('Y-m-d H:i:s');
            $currentDate = date('Y-m-d H:i:s');

            if ($currentDate > $dateCreate)
                $bUpdate = true;

        }

        if ($bUpdate) {

            $this->clearResultCache();

            $sClientId = 'self';
            $url = new Url();
            $url->setScheme('https')
                ->setHost('api.instagram.com')
                ->setPathString('/v1/users/'.$sClientId.'/media/recent')
                ->getQuery()->set('access_token', $sToken)
                ->set('count', $iCountItems);

            $request = new Request();
            $request->setJumps(100);
            $response = $request->send($url->build());
            $json = $response->getContent();
            $data = Json::decode($json);
            $data['date'] = date('Y-m-d H:i:s');
            $json = Json::encode($data);

            FileHelper::setFileData($sPath, $json);

        }

        if ($this->startResultCache()) {

            $this->arResult = [
                'HEADER_BLOCK' => [],
                'DESCRIPTION_BLOCK' => [],
                'FOOTER_BLOCK' => [],
                'VISUAL' => [],
                'ITEMS' => []
            ];

            $sHeaderText = ArrayHelper::getValue($arParams, 'HEADER_TEXT');
            $sHeaderText = trim($sHeaderText);
            $bHeaderShow = ArrayHelper::getValue($arParams, 'HEADER_SHOW');
            $bHeaderShow = $bHeaderShow == 'Y' && !empty($sHeaderText);

            $this->arResult['HEADER_BLOCK'] = [
                'SHOW' => $bHeaderShow,
                'POSITION' => ArrayHelper::getValue($arParams, 'HEADER_POSITION'),
                'TEXT' => Html::encode($sHeaderText)
            ];

            $sDescription = ArrayHelper::getValue($arParams, 'DESCRIPTION_TEXT');
            $sDescription = trim($sDescription);
            $bDescriptionShow = ArrayHelper::getValue($arParams, 'HEADER_SHOW');
            $bDescriptionShow = $bDescriptionShow == 'Y' && !empty($sDescription);

            $this->arResult['DESCRIPTION_BLOCK'] = [
                'SHOW' => $bDescriptionShow,
                'POSITION' => ArrayHelper::getValue($arParams, 'DESCRIPTION_POSITION'),
                'TEXT' => Html::encode($sDescription)
            ];

            $data = null;

            try {
                $data = Json::decode($json);
            } catch (InvalidParamException $exception) {
                $data = null;
            }

            if ($data !== null) {
                $iCounter = 1;
                foreach($data['data'] as $arItem){
                    if ($iCounter > $iCountItems) break;
                    $this->arResult['ITEMS'][] = [
                        'ID' => $arItem['id'],
                        'IMAGES' => $arItem['images'],
                        'DESCRIPTION' => $arItem['caption']['text'],
                        'COUNT_LIKES' => $arItem['likes']['count'],
                        'LINK' => $arItem['link']
                    ];
                    $iCounter++;
                }

                $this->arResult['ITEMS'] = ArrayHelper::convertEncoding(
                    $this->arResult['ITEMS'],
                    Encoding::getDefault(),
                    Encoding::UTF8
                );
            }

            $this->includeComponentTemplate();
        }

        $sBackUrl = $APPLICATION->GetCurPage();
        $sUrlUpdate = new Url();
        $sUrlUpdate->setPathString($this->getPath()."/update.php")
            ->getQuery()->set('BACK_URL', $sBackUrl)
            ->set('ACCESS_TOKEN', $sToken)
            ->set('COUNT_ITEM', $iCountItems)
            ->set('CACHE_FILE', $sPath)
            ->set('refresh', 'Y');

        $this->AddIncludeAreaIcons(
            array(
                array(
                    'URL' => $sUrlUpdate->build(),
                    'SRC' => '',
                    'TITLE' => Loc::GetMessage('REFRESH_BUTTON')
                )
            )
        );

        return null;
    }
}