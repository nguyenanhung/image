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
    use Version;

    /**
     * Function getImageFromUrl
     *
     * @param string $url
     *
     * @return array|false|string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/4/19 44:48
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
                self::debug('Error Exception: ' . $curl->httpErrorMessage);

                return array(
                    'status'          => 'error',
                    'code'            => $curl->httpStatusCode,
                    'error'           => $curl->errorMessage,
                    'response_header' => $curl->responseHeaders,
                    'content'         => NULL
                );
            } else {
                return array(
                    'status'          => 'success',
                    'code'            => $curl->httpStatusCode,
                    'error'           => $curl->errorMessage,
                    'response_header' => $curl->responseHeaders,
                    'content'         => $curl->response
                );
            }
        }
        catch (Exception $e) {
            if (function_exists('log_message')) {
                $message = 'Error Code: ' . $e->getCode() . ' - File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Message: ' . $e->getMessage();
                log_message('error', $message);
            }

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
     * @time     : 10/4/19 44:37
     */
    public static function debug($msg = 'test')
    {
        try {
            if (function_exists('log_message')) {
                log_message('debug', $msg);
            } else {
                if (self::USE_DEBUG === TRUE) {
                    $logger = new Logger('imageCache');
                    $logger->pushHandler(new StreamHandler(__DIR__ . '/../storage/logs/Log-' . date('Y-m-d') . '.log', Logger::DEBUG));
                    $logger->debug($msg);
                }
            }
        }
        catch (Exception $e) {
            if (function_exists('log_message')) {
                $message = 'Error Code: ' . $e->getCode() . ' - File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Message: ' . $e->getMessage();
                log_message('error', $message);
            }

            return;
        }
    }
}
