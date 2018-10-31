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
     * @time  : 10/31/18 15:01
     *
     * @param string $url    Đường dẫn hoặc URL hình ảnh
     * @param int    $width  Thiết lập thông số chiều rộng
     * @param int    $height Thiết lập thông số chiều cao
     * @param string $format Format đầu ra
     *
     * @return \Imagine\Image\ImageInterface
     */
    public static function process($url = '', $width = 100, $height = 100, $format = 'png')
    {
        $imagine = new Imagine();
        if (is_file($url)) {
            $image = $imagine->open($url);
        } else {
            $url   = static::getImageFromUrl($url);
            $image = $imagine->load($url);
        }
        $image->resize(new Box($width, $height), ImageInterface::FILTER_UNDEFINED);

        return $image->show($format);
    }

    /**
     * Function getImageFromUrl
     *
     * Sử dụng cURL để Get Content Image
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/31/18 14:43
     *
     * @param string $url
     *
     * @return mixed|null
     */
    public static function getImageFromUrl($url = '')
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_HTTPHEADER     => [
                "cache-control: no-cache"
            ],
        ]);
        $response = curl_exec($curl);
        $err      = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return NULL;
        } else {
            return $response;
        }
    }
}
