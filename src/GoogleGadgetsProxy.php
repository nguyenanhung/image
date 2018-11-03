<?php
/**
 * Project image.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 11/3/18
 * Time: 17:36
 */

namespace nguyenanhung\MyImage;

/**
 * Class GoogleGadgetsProxy
 *
 * @package   nguyenanhung\MyImage
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class GoogleGadgetsProxy
{
    /**
     * url: original image URL
     * container: must be "focus" (i dunno lol)
     * refresh: time (in seconds) to cache it on G's servers
     * resize_w: width in pixels
     * resize_h: height in pixels
     */
    const PROXY_URL       = 'https://images1-focus-opensocial.googleusercontent.com/gadgets/proxy';
    const PROXY_CONTAINER = 'focus';
    const PROXY_REFRESH   = 2592000;

    /**
     * Hàm resize ảnh sử dụng Google Gadgets Proxy
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/3/18 17:41
     *
     * @param string   $url    Đường dẫn ảnh cần Resize
     * @param int      $width  Chiều rộng
     * @param null|int $height Chiều cao
     *
     * @return string Đường dẫn ảnh sau khi đã resize
     */
    public static function resize($url = '', $width = 100, $height = NULL)
    {
        $params             = array();
        $params['url']      = $url;
        $params['resize_w'] = $width;
        if (!empty($height)) {
            $params['resize_h'] = $height;
        }
        $params['container'] = self::PROXY_CONTAINER;
        $params['refresh']   = self::PROXY_REFRESH;
        // Result URL
        $url = self::PROXY_URL . '?' . urldecode(http_build_query($params));

        return trim($url);
    }
}
