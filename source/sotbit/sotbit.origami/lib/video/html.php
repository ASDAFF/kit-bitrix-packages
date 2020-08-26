<?
namespace Sotbit\Origami\Video;

class HTML{
    public function getContent($url){
        return '<video src="' . $url . '"  controls  />';
    }
}