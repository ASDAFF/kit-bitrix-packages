<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog.php");

use Bitrix\Main\Loader;
use intec\core\base\InvalidParamException;
use intec\core\helpers\Json;
use intec\core\net\Url;
use intec\core\net\http\Request;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\FileHelper;

if (!Loader::includeModule('intec.core'))
    return;

$sBackUrl = ArrayHelper::getValue($_REQUEST, 'BACK_URL');
$sToken = ArrayHelper::getValue($_REQUEST, 'ACCESS_TOKEN');
$sCachePath = ArrayHelper::getValue($_REQUEST, 'CACHE_FILE');
$iCountItems = ArrayHelper::getValue($_REQUEST, 'COUNT_ITEM', '10');
$sClientId = 'self';

$sPathInstagramAPI = '/v1/users/'.$sClientId.'/media/recent';
$url = new Url();
$url->setScheme('https')
    ->setHost('api.instagram.com')
    ->setPathString($sPathInstagramAPI)
    ->getQuery()->set('access_token', $sToken)
    ->set('count',$iCountItems);

$request = new Request();
$request->setJumps(100);
$response = $request->send($url->build());
$json = $response->getContent();
$data = Json::decode($json);
$data['date'] = date('Y-m-d H:i:s');
$json = Json::encode($data);

$data = null;

try {
    $data = Json::decode($json);
} catch (InvalidParamException $exception) {
    $data = null;
}

if (!empty($data)){
    $res = FileHelper::setFileData($sCachePath, $json);
}

LocalRedirect($sBackUrl);