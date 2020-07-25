<?php
/**
 * Project image.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 11/1/18
 * Time: 14:15
 */

namespace nguyenanhung\MyImage;

use Exception;
use SplFileInfo;
use Imagine\Exception\RuntimeException;
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
    use Version;

    /** @var string Đường dẫn thư mục lưu trữ hình ảnh */
    protected $tmpPath;
    /** @var string Đường dẫn hình ảnh trên server - tương đương với tmpPath */
    protected $urlPath;
    /** @var string Cấu hình tới link ảnh mặc định, sẽ sử dụng trong trường hợp ảnh bị lỗi */
    protected $defaultImage;

    /**
     * ImageCache constructor.
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     */
    public function __construct()
    {
    }

    /**
     * Cấu hình thư mục lưu trữ file Cache Image
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/1/18 14:21
     *
     * @param string $tmpPath Thư mục cần lưu trữ
     *
     * @return  $this
     */
    public function setTmpPath($tmpPath = '')
    {
        if (empty($tmpPath)) {
            $tmpPath = __DIR__ . '/../storage/tmp/';
        }
        $this->tmpPath = $tmpPath;

        return $this;
    }

    /**
     * Cấu hình đường dẫn link hình ảnh trên server
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/1/18 14:34
     *
     * @param string $urlPath
     *
     * @return  $this
     */
    public function setUrlPath($urlPath = '')
    {
        $this->urlPath = $urlPath;

        return $this;
    }

    /**
     * Cấu hình đường dẫn link ảnh mặc định
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/1/18 15:40
     *
     * @param string $defaultImage Đường dẫn link ảnh mặc định
     *
     * @return  $this
     */
    public function setDefaultImage($defaultImage = '')
    {
        if (empty($defaultImage)) {
            $image        = DataRepository::getData('config_image');
            $defaultImage = $image['default_image'];
        }
        $this->defaultImage = $defaultImage;

        return $this;
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
        $image        = DataRepository::getData('config_image');
        $defaultImage = $image['default_image'];
        try {
            Utils::debug('URL: ' . $url);
            Utils::debug('Width: ' . $width);
            Utils::debug('Height: ' . $height);
            Utils::debug('Format: ' . $format);
            try {
                // Xác định extention của file ảnh
                $info          = new SplFileInfo($url);
                $fileExtension = $info->getExtension();
                $outputFormat  = !empty($fileExtension) ? $fileExtension : $format;
                Utils::debug('Output Format: ' . $outputFormat);
                // Quy định tên file ảnh sẽ lưu
                $fileName  = md5($url . $width . $height) . '-' . $width . 'x' . $height . '.' . $outputFormat;
                $imageFile = $this->tmpPath . $fileName;
                $imageUrl  = $this->urlPath . $fileName;
                if (!file_exists($imageFile)) {
                    Utils::debug('Khong ton tai file: ' . $imageFile);
                    // Nếu như không tồn tại file ảnh -> sẽ tiến hành phân tích và cache file
                    // Xác định size ảnh
                    $size    = new Box($width, $height);
                    $imagine = new Imagine();
                    if (is_file($url)) {
                        Utils::debug('URL is File => ' . $url);
                        $image = $imagine->open($url);
                        $image->resize($size)->save($imageFile);
                    } else {
                        Utils::debug('URL is URL => ' . $url);
                        $getContent = Utils::getImageFromUrl($url);
                        Utils::debug('Data Content: ' . json_encode($getContent));
                        if (isset($getContent['content'])) {
                            $image = $imagine->load($getContent['content']);
                            Utils::debug('Load Content with CURL');
                        } else {
                            $image = $imagine->load($getContent);
                            Utils::debug('Load Content with file_get_content');
                        }
                        $result = $image->resize($size)->save($imageFile);
                        Utils::debug('Thumbnail Result: ' . json_encode($result));
                    }
                }
                $resultImage = trim($imageUrl);

                return $resultImage;
            }
            catch (RuntimeException $runtimeException) {
                if (function_exists('log_message')) {
                    log_message('error', 'Runtime Error Message: ' . $runtimeException->getMessage());
                    log_message('error', 'Runtime Error TraceAsString: ' . $runtimeException->getTraceAsString());
                }

                return NULL;
            }
        }
        catch (Exception $e) {
            if (function_exists('log_message')) {
                log_message('error', 'Error Message: ' . $e->getMessage());
                log_message('error', 'Error TraceAsString: ' . $e->getTraceAsString());
            }

            return $defaultImage;
        }
    }

    /**
     * Hàm cache Image và save về server
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/7/18 09:10
     *
     * @param string $url    Đường dẫn hoặc URL hình ảnh
     * @param string $format Format đầu ra
     *
     * @return null|string Đường dẫn link tới hình ảnh được cache
     */
    public function saveImage($url = '', $format = 'png')
    {
        $image        = DataRepository::getData('config_image');
        $defaultImage = $image['default_image'];
        try {
            Utils::debug('URL: ' . $url);
            Utils::debug('Format: ' . $format);
            try {
                // Xác định extention của file ảnh
                $info          = new SplFileInfo($url);
                $fileExtension = $info->getExtension();
                $outputFormat  = !empty($fileExtension) ? $fileExtension : $format;
                Utils::debug('Output Format: ' . $outputFormat);
                // Quy định tên file ảnh sẽ lưu
                $fileName  = md5($url) . '.' . $outputFormat;
                $imageFile = $this->tmpPath . $fileName;
                $imageUrl  = $this->urlPath . $fileName;
                if (!file_exists($imageFile)) {
                    Utils::debug('Khong ton tai file: ' . $imageFile);
                    // Nếu như không tồn tại file ảnh -> sẽ tiến hành phân tích và cache file
                    // Xác định size ảnh
                    $imagine = new Imagine();
                    if (is_file($url)) {
                        Utils::debug('URL is File => ' . $url);
                        $image = $imagine->open($url);
                        $image->save($imageFile);
                    } else {
                        Utils::debug('URL is URL => ' . $url);
                        $getContent = Utils::getImageFromUrl($url);
                        Utils::debug('Data Content: ' . json_encode($getContent));
                        if (isset($getContent['content'])) {
                            $image = $imagine->load($getContent['content']);
                            Utils::debug('Load Content with CURL');
                        } else {
                            $image = $imagine->load($getContent);
                            Utils::debug('Load Content with file_get_content');
                        }
                        $result = $image->save($imageFile);
                        Utils::debug('Save Image Result: ' . json_encode($result));
                    }
                }
                $resultImage = trim($imageUrl);

                return $resultImage;
            }
            catch (RuntimeException $runtimeException) {
                if (function_exists('log_message')) {
                    log_message('error', 'Runtime Error Message: ' . $runtimeException->getMessage());
                    log_message('error', 'Runtime Error TraceAsString: ' . $runtimeException->getTraceAsString());
                }

                return NULL;
            }
        }
        catch (Exception $e) {
            if (function_exists('log_message')) {
                log_message('error', 'Error Message: ' . $e->getMessage());
                log_message('error', 'Error TraceAsString: ' . $e->getTraceAsString());
            }

            return $defaultImage;
        }
    }
}
