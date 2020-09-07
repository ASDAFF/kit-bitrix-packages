<?
namespace Sotbit\Origami\Video;

class Youtube{
    public function prepareUrl($url = ''){
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ||
            $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

        return $protocol . 'www.youtube.com/embed/'. $url;
    }
    public function getContent($url){
        return '<iframe src="' . $url . '" frameborder="0" allowfullscreen></iframe>';
    }
}