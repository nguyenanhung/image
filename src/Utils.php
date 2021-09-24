<?php
/**
 * Project image.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 11/1/18
 * Time: 14:24
 */

namespace nguyenanhung\MyImage;

use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

ini_set('display_errors', 0);

/**
 * Class Utils
 *
 * @package   nguyenanhung\MyImage
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Utils implements ProjectInterface
{
    use Version;

    /**
     * Function getImageFromUrl
     *
     * @param string $url
     *
     * @return array|false|string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/06/2020 26:41
     */
    public static function getImageFromUrl($url = '')
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL            => trim($url),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 5,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "GET",
            ]);
            $error        = curl_errno($curl);
            $errorMessage = curl_error($curl);
            $response     = curl_exec($curl);
            curl_close($curl);
            if ($error > 0) {
                return [
                    'status'  => 'error',
                    'error'   => $errorMessage,
                    'content' => null
                ];
            }

            return [
                'status'  => 'success',
                'error'   => $errorMessage,
                'content' => $response
            ];
        } catch (Exception $e) {
            return file_get_contents($url);
        }
    }

    /**
     * Function debug
     *
     * @param string $msg
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/06/2020 26:49
     */
    public static function debug($msg = 'test')
    {
        try {
            if (self::USE_DEBUG === true) {
                $logger = new Logger('imageCache');
                $logger->pushHandler(new StreamHandler(__DIR__ . '/../storage/logs/Log-' . date('Y-m-d') . '.log', Logger::DEBUG));
                $logger->debug($msg);
            }
        } catch (Exception $e) {
            return;
        }
    }
}
