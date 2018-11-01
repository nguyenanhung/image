<?php
/**
 * Project image.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 11/1/18
 * Time: 14:24
 */

namespace nguyenanhung\MyImage;

/**
 * Class Utils
 *
 * @package nguyenanhung\MyImage
 */
class Utils
{
    /**
     * Function getImageFromUrl
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/1/18 14:25
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
