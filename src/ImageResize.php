<?php
/**
 * Project image.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 10/31/18
 * Time: 11:09
 */

namespace nguyenanhung\MyImage;

use Exception;
use SplFileInfo;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

/**
 * Class ImageResize
 *
 * @package   nguyenanhung\MyImage
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class ImageResize extends BaseImage
{
    /**
     * Function process
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/31/18 15:01
     *
     * @param string $url    Đường dẫn hoặc URL hình ảnh
     * @param int    $width  Thiết lập thông số chiều rộng
     * @param int    $height Thiết lập thông số chiều cao
     * @param string $format Format đầu ra
     *
     * @return \Imagine\Image\ImageInterface|string
     */
    public static function process(string $url = '', int $width = 100, int $height = 100, string $format = 'png')
    {
        try {
            $info = new SplFileInfo($url);
            $fileExtension = $info->getExtension();
            $outputFormat = !empty($fileExtension) ? $fileExtension : $format;
            $imagine = new Imagine();
            if (is_file($url)) {
                $image = $imagine->open($url);
            } else {
                $url = Utils::getImageFromUrl($url);
                $image = $imagine->load($url);
            }
            $image->resize(new Box($width, $height), ImageInterface::FILTER_UNDEFINED);

            return $image->show($outputFormat);
        } catch (Exception $e) {
            return $url;
        }
    }
}
