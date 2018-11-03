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
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use nguyenanhung\MyImage\Interfaces\ProjectInterface;

ini_set('display_errors', 0);

/**
 * Class Utils
 *
 * @package nguyenanhung\MyImage
 */
class Utils implements ProjectInterface
{
    /**
     * Function getVersion
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/2/18 15:26
     *
     * @return mixed|string
     */
    public function getVersion()
    {
        return self::VERSION;
    }

    /**
     * Function getImageFromUrl
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/2/18 16:48
     *
     * @param string $url
     *
     * @return array|bool|null|string
     * @throws \Exception
     */
    public static function getImageFromUrl($url = '')
    {
        try {
            $curl = new Curl();
            $curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
            $curl->setOpt(CURLOPT_ENCODING, "");
            $curl->setOpt(CURLOPT_MAXREDIRS, 10);
            $curl->setOpt(CURLOPT_TIMEOUT, 30);
            $curl->setOpt(CURLOPT_CONNECTTIMEOUT, 30);
            $curl->setOpt(CURLOPT_FOLLOWLOCATION, TRUE);
            $curl->get($url);
            if ($curl->error === TRUE) {
                self::debug('Error Exception: ' . $curl->http_status_code);

                return [
                    'status'          => 'error',
                    'code'            => $curl->http_status_code,
                    'error'           => $curl->error_message,
                    'response_header' => $curl->response_headers,
                    'content'         => NULL
                ];
            } else {
                return [
                    'status'          => 'success',
                    'code'            => $curl->http_status_code,
                    'error'           => $curl->error_message,
                    'response_header' => $curl->response_headers,
                    'content'         => $curl->response
                ];
            }
        }
        catch (\Exception $e) {
            self::debug('Error Exception: ' . $e->getMessage());

            return file_get_contents($url);
        }
    }

    /**
     * Function debug
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/3/18 17:43
     *
     * @param string $msg
     */
    public static function debug($msg = 'test')
    {
        try {
            if (self::USE_DEBUG === TRUE) {
                $logger = new Logger('imageCache');
                $logger->pushHandler(new StreamHandler(__DIR__ . '/../storage/logs/Log-' . date('Y-m-d') . '.log', Logger::DEBUG));
                $logger->debug($msg);
            }
        }
        catch (\Exception $e) {
            return;
        }
    }
}
