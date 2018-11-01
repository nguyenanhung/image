<?php
/**
 * Project image.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 11/1/18
 * Time: 14:15
 */

namespace nguyenanhung\MyImage;

use nguyenanhung\MyImage\Interfaces\ImageCacheInterface;
use nguyenanhung\MyImage\Interfaces\ProjectInterface;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;

/**
 * Class ImageCache
 *
 * @package   nguyenanhung\MyImage
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class ImageCache implements ProjectInterface, ImageCacheInterface
{
    /** @var string Đường dẫn thư mục lưu trữ hình ảnh */
    protected $tmpPath;
    /** @var string Đường dẫn hình ảnh trên server - tương đương với tmpPath */
    protected $urlPath;

    /**
     * ImageCache constructor.
     */
    public function __construct()
    {
    }

    /**
     * Function getVersion
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/1/18 14:16
     *
     * @return mixed|string
     */
    public function getVersion()
    {
        return self::VERSION;
    }

    /**
     * Cấu hình thư mục lưu trữ file Cache Image
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/1/18 14:21
     *
     * @param string $tmpPath Thư mục cần lưu trữ
     */
    public function setTmpPath($tmpPath = '')
    {
        if (empty($tmpPath)) {
            $tmpPath = __DIR__ . '/../storage/tmp/';
        }
        $this->tmpPath = $tmpPath;
    }

    /**
     * Cấu hình đường dẫn link hình ảnh trên server
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/1/18 14:34
     *
     * @param string $urlPath
     */
    public function setUrlPath($urlPath = '')
    {
        $this->urlPath = $urlPath;
    }

    /**
     * Hàm hỗ trợ tạo thumbnail cho ảnh
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/1/18 14:44
     *
     * @param string $url    Đường dẫn hoặc URL hình ảnh
     * @param int    $width  Thiết lập thông số chiều rộng
     * @param int    $height Thiết lập thông số chiều cao
     * @param string $format Format đầu ra
     *
     * @return string Đường dẫn link tới hình ảnh được tạo thumbnail
     */
    public function thumbnail($url = '', $width = 100, $height = 100, $format = 'png')
    {
        // Xác định extention của file ảnh
        $info          = new \SplFileInfo($url);
        $fileExtension = $info->getExtension();
        $outputFormat  = !empty($fileExtension) ? $fileExtension : $format;
        // Quy định tên file ảnh sẽ lưu
        $fileName  = md5($url . $width . $height) . '-' . $width . 'x' . $height . '.' . $outputFormat;
        $imageFile = $this->tmpPath . $fileName;
        $imageUrl  = $this->urlPath . $fileName;
        if (!file_exists($imageFile)) {
            // Nếu như không tồn tại file ảnh -> sẽ tiến hành phân tích và cache file
            // Xác định size ảnh
            $size    = new Box($width, $height);
            $imagine = new Imagine();
            if (is_file($url)) {
                $image = $imagine->open($url);
            } else {
                $url   = Utils::getImageFromUrl($url);
                $image = $imagine->load($url);
            }
            $image->thumbnail($size)->save($imageFile);
        }

        return trim($imageUrl);
    }

    /**
     * Hàm hỗ trợ cache ảnh về hệ thống
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/1/18 14:49
     *
     * @param string $url    Đường dẫn hoặc URL hình ảnh
     * @param string $format Format đầu ra
     *
     * @return string Đường dẫn tới hình ảnh được cache
     */
    public function cache($url = '', $format = 'png')
    {
        // Xác định extention của file ảnh
        $info          = new \SplFileInfo($url);
        $fileExtension = $info->getExtension();
        $outputFormat  = !empty($fileExtension) ? $fileExtension : $format;
        // Quy định tên file ảnh sẽ lưu
        $fileName  = md5($url) . $outputFormat;
        $imageFile = $this->tmpPath . $fileName;
        $imageUrl  = $this->urlPath . $fileName;
        if (!file_exists($imageFile)) {
            // Nếu như không tồn tại file ảnh -> sẽ tiến hành phân tích và cache file
            $imagine = new Imagine();
            if (is_file($url)) {
                $image = $imagine->open($url);
            } else {
                $url   = Utils::getImageFromUrl($url);
                $image = $imagine->load($url);
            }
            $image->save($imageFile);
        }

        return trim($imageUrl);
    }
}