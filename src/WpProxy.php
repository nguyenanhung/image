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
class WpProxy implements ProjectInterface
{
    use Version;

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
    public function generate($imageUrl = '', $server = 'i3')
    {
        $imageUrl = str_replace('https://', '', $imageUrl);
        $imageUrl = str_replace('http://', '', $imageUrl);
        $url      = 'https://' . trim($server) . '.wp.com/' . $imageUrl;

        return trim($url);
    }
}