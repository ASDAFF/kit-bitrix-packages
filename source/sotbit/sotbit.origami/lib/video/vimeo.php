<?
namespace Sotbit\Origami\Video;

class Vimeo{
    public function prepareUrl($url = ''){
        $path = explode( '/', $url );
        return 'https://player.vimeo.com/video/' . $path[count( $path ) - 1];
    }
    public function getContent($url = ''){
        return '<iframe src="'.$url.'"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
    }
}