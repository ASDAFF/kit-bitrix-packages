<?

namespace Sotbit\Origami;

use Sotbit\Origami\Config\Option;

/**
 * Class Resize
 *
 * @package Sotbit\Origami
 * @author  Sergey Danilkin <s.danilkin@kit.ru>
 */
class Resize
{
    /**
     * @var int
     */
    protected $smallWidth = 0;
    /**
     * @var int
     */
    protected $smallHeight = 0;
    /**
     * @var int
     */
    protected $mediumWidth = 0;
    /**
     * @var int
     */
    protected $mediumHeight = 0;
    /**
     * @var int
     */
    protected $bigWidth = 0;
    /**
     * @var int
     */
    protected $bigHeight = 0;
    /**
     * @var int
     */
    protected $type = 1;

    /**
     * Resize constructor.
     *
     * @param $template null|string
     */
    public function __construct($template = null)
    {
        if (!is_null($template)) {
            $this->smallWidth = Option::get('RESIZE_WIDTH_SMALL_'.$template);
            $this->smallHeight = Option::get('RESIZE_HEIGHT_SMALL_'.$template);
            $this->mediumWidth = Option::get('RESIZE_WIDTH_MEDIUM_'.$template);
            $this->mediumHeight = Option::get('RESIZE_HEIGHT_MEDIUM_'.$template);
            $this->bigWidth = Option::get('RESIZE_WIDTH_BIG_'.$template);
            $this->bigHeight = Option::get('RESIZE_HEIGHT_BIG_'.$template);
            $this->type = Option::get('RESIZE_TYPE_'.$template);
        }
    }

    /**
     * @param array $img
     *
     * @return array
     */
    public function resizeImage($img = [])
    {
        $return = ['ORIGINAL' => $img];
        if ( $this->smallWidth > 0 && $this->smallHeight > 0) {
            $return['SMALL'] = \CFile::ResizeImageGet(
                $img['ID'],
                [
                    'width'  => $this->smallWidth,
                    'height' => $this->smallHeight,
                ],
                $this->type
            );
            $return['SMALL']['SRC'] = $return['SMALL']['src'];
            $return['SMALL']['WIDTH'] = $return['SMALL']['width'];
            $return['SMALL']['HEIGHT'] = $return['SMALL']['height'];
            unset($return['SMALL']['src']);
            unset($return['SMALL']['width']);
            unset($return['SMALL']['height']);
        }
        if ( $this->mediumWidth > 0 && $this->mediumHeight > 0) {
            $return['MEDIUM'] = \CFile::ResizeImageGet(
                $img['ID'],
                [
                    'width'  => $this->mediumWidth,
                    'height' => $this->mediumHeight,
                ],
                $this->type
            );
            $return['MEDIUM']['SRC'] = $return['MEDIUM']['src'];
            $return['MEDIUM']['WIDTH'] = $return['MEDIUM']['width'];
            $return['MEDIUM']['HEIGHT'] = $return['MEDIUM']['height'];
            unset($return['MEDIUM']['src']);
            unset($return['MEDIUM']['width']);
            unset($return['MEDIUM']['height']);
        }
        if ( $this->bigWidth > 0 && $this->bigHeight > 0) {
            $return['BIG'] = \CFile::ResizeImageGet(
                $img['ID'],
                [
                    'width'  => $this->bigWidth,
                    'height' => $this->bigHeight,
                ],
                $this->type
            );
            $return['BIG']['SRC'] = $return['BIG']['src'];
            $return['BIG']['WIDTH'] = $return['BIG']['width'];
            $return['BIG']['HEIGHT'] = $return['BIG']['height'];
            unset($return['BIG']['src']);
            unset($return['BIG']['width']);
            unset($return['BIG']['height']);
        }
        return $return;
    }
}