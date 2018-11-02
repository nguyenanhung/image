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
use nguyenanhung\MyImage\Repository\DataRepository;

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
    /** @var string Cấu hình tới link ảnh mặc định, sẽ sử dụng trong trường hợp ảnh bị lỗi */
    protected $defaultImage;

    protected $logger;

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
     * Cấu hình đường dẫn link ảnh mặc định
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/1/18 15:40
     *
     * @param string $defaultImage Đường dẫn link ảnh mặc định
     */
    public function setDefaultImage($defaultImage = '')
    {
        if (empty($defaultImage)) {
            $image        = DataRepository::getData('config_image');
            $defaultImage = $image['default_image'];
        }
        $this->defaultImage = $defaultImage;
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
     * @throws \Exception
     */
    public function thumbnail($url = '', $width = 100, $height = 100, $format = 'png')
    {
        Utils::debug('URL: ' . $url);
        Utils::debug('Width: ' . $width);
        Utils::debug('Height: ' . $height);
        Utils::debug('Format: ' . $format);
        try {
            // Xác định extention của file ảnh
            $info          = new \SplFileInfo($url);
            $fileExtension = $info->getExtension();
            $outputFormat  = !empty($fileExtension) ? $fileExtension : $format;
            Utils::debug('Output Format: ' . $outputFormat);
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
                    $image->resize($size)->save($imageFile);
                } else {
                    $getContent = Utils::getImageFromUrl($url);
                    if (isset($getContent['content'])) {
                        $image = $imagine->load($getContent['content']);
                    } else {
                        $image = $imagine->load($getContent);
                    }
                    $image->resize($size)->save($imageFile);
                }
            }
            $resultImage = trim($imageUrl);

            return $resultImage;
        }
        catch (\Exception $e) {
            return NULL;
        }
    }
}
