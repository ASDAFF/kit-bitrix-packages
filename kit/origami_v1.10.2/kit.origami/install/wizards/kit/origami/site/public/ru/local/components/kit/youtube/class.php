<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\SystemException;

class Youtube extends CBitrixComponent
{
    public function getVideos($chanelId = '', $apiKey = '', $maxResults = 4)
    {
        try {
            if (!$chanelId) {
                throw new SystemException("Empty chanel id");
            }
            if (!$apiKey) {
                throw new SystemException("Empty api key");
            }

            $channel
                = "https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId="
                .$chanelId."&maxResults=".$maxResults."&key=".$apiKey;

            $videosList = json_decode(file_get_contents($channel));

            $items = [];

            if($videosList && is_array($items)) {
                foreach ($videosList->items as $key => $item) {
                    if (isset($item->id->videoId)) {
                        $items[$key]["ID"] = $item->id->videoId;
                        $items[$key]["TITLE"]
                            = mb_convert_encoding($item->snippet->title,
                            LANG_CHARSET);
                        $items[$key]["DATE"] = FormatDate("j F Y",
                            strtotime($item->snippet->publishedAt));
                    }
                }
            }

            if (!is_array($items)) {
                throw new SystemException("Error get videos");
            }

            return $items;
        } catch (SystemException $e) {
            // echo $e->getTraceAsString();
        }
    }

    public function executeComponent()
    {
        if ($this->startResultCache()) {
            $this->arResult["VIDEOS"] = $this->getVideos(
                $this->arParams["CHANEL_ID"],
                $this->arParams["API_KEY"],
                $this->arParams["VIDEO_COUNT"]
            );
            $this->includeComponentTemplate();
        }

        return $this->arResult["VIDEOS"];
    }
}

?>