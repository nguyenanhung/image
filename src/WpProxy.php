<?php
/**
 * Project image
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 02/10/2021
 * Time: 00:01
 */

namespace nguyenanhung\MyImage;

/**
 * Class WpProxy
 *
 * @package   nguyenanhung\MyImage
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class WpProxy extends BaseImage
{
    /**
     * Function generate
     *
     * @param string $imageUrl
     * @param string $server
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 02/10/2021 06:14
     */
    public function generate(string $imageUrl = '', string $server = 'i3'): string
    {
        $imageUrl = str_replace(array('https://', 'http://'), '', $imageUrl);
        $url = 'https://' . trim($server) . '.wp.com/' . $imageUrl;

        return trim($url);
    }

    /**
     * Function cache
     *
     * @param string $imageUrl
     * @param string $server
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 09/24/2021 54:12
     */
    public static function cache(string $imageUrl = '', string $server = 'i3'): string
    {
        return (new self)->generate($imageUrl, $server);
    }
}
