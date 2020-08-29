<?
namespace Kit\Origami;

use Kit\Origami\Video\Vimeo;
use Kit\Origami\Video\Youtube;
use Kit\Origami\Video\HTML;

class Video{
    /**
     * @var string
     */
    protected $url = '';

    /**
     * @var Youtube|Vimeo|HTML
     */
    protected $service;

    /**
     * Video constructor.
     *
     * @param string $url
     */
    public function __construct($url = '') {
        if(!$url){
            return false;
        }
        if(
            preg_match(
                '/[http|https]+:\/\/(?:www\.|)youtube\.com\/watch\?(?:.*)?v=([a-zA-Z0-9_\-]+)/i',
                $url,
                $matches ) ||
            preg_match(
                '/[http|https]+:\/\/(?:www\.|)youtube\.com\/embed\/([a-zA-Z0-9_\-]+)/i',
                $url,
                $matches ) ||
            preg_match(
                    '/[http|https]+:\/\/(?:www\.|)youtu\.be\/([a-zA-Z0-9_\-]+)/i',
                $url,
                    $matches )
        )
        {
            $this->service = new Youtube();
            $this->url = $this->service->prepareUrl($matches[1]);
        }
        elseif(strpos( $url, "vimeo.com" )){
            $this->service = new Vimeo();
            $this->url = $this->service->prepareUrl($url);
        }
        else{
            $this->service = new HTML();
        }
    }
    public function getContent(){
        return $this->service->getContent($this->url);
    }
}