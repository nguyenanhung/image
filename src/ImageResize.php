<?php
/**
 * Project image.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 10/31/18
 * Time: 11:09
 */

namespace nguyenanhung\MyImage;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

/**
 * Class ImageResize
 *
 * @package nguyenanhung\MyImage
 */
class ImageResize
{
    /**
     * Function process
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/31/18 11:12
     *
     * @param string $url Url Image or Path to Image
     * @param int    $width
     * @param int    $height
     *
     * @return \Imagine\Image\ImageInterface
     */
    public static function process($url = '', $width = 100, $height = 100)
    {
        $imagine = new Imagine();
        $image   = $imagine->open($url);
        $image->resize(new Box($width, $height), ImageInterface::FILTER_UNDEFINED);

        return $image->show('jpg');
    }
}
