<?php
/**
 * Project image.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 11/1/18
 * Time: 14:24
 */

namespace nguyenanhung\MyImage;

use Curl\Curl;

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
     * @time  : 11/2/18 09:43
     *
     * @param string $url
     *
     * @return array|bool|string
     */
    public static function getImageFromUrl($url = '')
    {
        try {
            $curl = new Curl();
            $curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
            $curl->setOpt(CURLOPT_SSL_VERIFYPEER, TRUE);
            $curl->setOpt(CURLOPT_SSL_VERIFYHOST, TRUE);
            $curl->setOpt(CURLOPT_ENCODING, "");
            $curl->setOpt(CURLOPT_MAXREDIRS, 10);
            $curl->setOpt(CURLOPT_TIMEOUT, 30);
            $curl->setOpt(CURLOPT_CONNECTTIMEOUT, 30);
            $curl->setOpt(CURLOPT_FOLLOWLOCATION, TRUE);
            $curl->get($url);
            if ($curl->error === TRUE) {
                return [
                    'status'          => 'error',
                    'code'            => $curl->http_status_code,
                    'error'           => $curl->error_message,
                    'response_header' => $curl->response_headers,
                    'content'         => NULL
                ];
            } else {
                return [
                    'status'          => 'error',
                    'code'            => $curl->http_status_code,
                    'error'           => $curl->error_message,
                    'response_header' => $curl->response_headers,
                    'content'         => $curl->response
                ];
            }
        }
        catch (\Exception $e) {
            return file_get_contents($url);
        }
    }
}
