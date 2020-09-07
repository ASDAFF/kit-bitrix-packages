<?
function get_id_video($url){
    $arrUrl = parse_url($url);
    if(isset($arrUrl['query'])) {
        $arrUrlGet = explode('&', $arrUrl['query']);
        foreach ($arrUrlGet as $value){
            $arrGetParam = explode('=', $value);
            if(!strcmp(array_shift($arrGetParam), 'v'))
                return array_pop($arrGetParam);
        }
        return array_pop(explode('/', $arrUrl['path']));
    }
    else
        return array_pop(explode('/', $url));
}