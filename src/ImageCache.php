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
use Imagine\Gd\Imagine;
use Imagine\Image\Box;

/**
 * Class ImageCache
 *
 * @package   nguyenanhung\MyImage
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class ImageCache extends BaseImage
{
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
     * Function setTmpPath - Cấu hình thư mục lưu trữ file Cache Image
     *
     * @param string $tmpPath Thư mục cần lưu trữ
     *
     * @return $this
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/06/2020 31:20
     */
    public function setTmpPath(string $tmpPath = ''): ImageCache
    {
        if (empty($tmpPath)) {
            $tmpPath = __DIR__ . '/../storage/tmp/';
        }
        $this->tmpPath = $tmpPath;

        return $this;
    }

    /**
     * Function setUrlPath - Cấu hình đường dẫn link hình ảnh trên server
     *
     * @param string $urlPath Đường dẫn link hình ảnh trên server
     *
     * @return $this
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/06/2020 31:41
     */
    public function setUrlPath(string $urlPath = ''): ImageCache
    {
        $this->urlPath = $urlPath;

        return $this;
    }

    /**
     * Function setDefaultImage - Cấu hình đường dẫn link ảnh mặc định
     *
     * @param string $defaultImage Đường dẫn link ảnh mặc định
     *
     * @return $this
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/06/2020 32:05
     */
    public function setDefaultImage(string $defaultImage = ''): ImageCache
    {
        if (empty($defaultImage)) {
            $image = DataRepository::getData('config_image');
            $defaultImage = $image['default_image'];
        }
        $this->defaultImage = $defaultImage;

        return $this;
    }

    /**
     * Function thumbnail - Hàm hỗ trợ tạo thumbnail cho ảnh
     *
     * @param string $url    Đường dẫn hoặc URL hình ảnh
     * @param int    $width  Thiết lập thông số chiều rộng
     * @param int    $height Thiết lập thông số chiều cao
     * @param string $format Format đầu ra
     *
     * @return string|null Đường dẫn link tới hình ảnh được tạo thumbnail
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/06/2020 32:28
     */
    public function thumbnail(string $url = '', int $width = 100, int $height = 100, string $format = 'png')
    {
        $image = DataRepository::getData('config_image');
        $defaultImage = $image['default_image'];
        try {
            Utils::debug('URL: ' . $url);
            Utils::debug('Width: ' . $width);
            Utils::debug('Height: ' . $height);
            Utils::debug('Format: ' . $format);
            try {
                // Xác định extention của file ảnh
                $info = new SplFileInfo($url);
                $fileExtension = $info->getExtension();
                $outputFormat = !empty($fileExtension) ? $fileExtension : $format;
                Utils::debug('Output Format: ' . $outputFormat);
                // Quy định tên file ảnh sẽ lưu
                $fileName = md5($url . $width . $height) . '-' . $width . 'x' . $height . '.' . $outputFormat;
                $imageFile = $this->tmpPath . $fileName;
                $imageUrl = $this->urlPath . $fileName;
                if (!file_exists($imageFile)) {
                    Utils::debug('Khong ton tai file: ' . $imageFile);
                    // Nếu như không tồn tại file ảnh -> sẽ tiến hành phân tích và cache file
                    // Xác định size ảnh
                    $size = new Box($width, $height);
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

                return trim($imageUrl);
            } catch (RuntimeException $runtimeException) {
//                if (function_exists('log_message')) {
//                    log_message('error', 'Runtime Error Message: ' . $runtimeException->getMessage());
//                }

                return null;
            }
        } catch (Exception $e) {
//            if (function_exists('log_message')) {
//                log_message('error', 'Error Message: ' . $e->getMessage());
//            }

            return $defaultImage;
        }
    }

    /**
     * Function saveImage - Hàm cache Image và save về server
     *
     * @param string $url    Đường dẫn hoặc URL hình ảnh
     * @param string $format Format đầu ra
     *
     * @return string|null Đường dẫn link tới hình ảnh được cache
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/06/2020 32:52
     */
    public function saveImage(string $url = '', string $format = 'png')
    {
        $image = DataRepository::getData('config_image');
        $defaultImage = $image['default_image'];
        try {
            Utils::debug('URL: ' . $url);
            Utils::debug('Format: ' . $format);
            try {
                // Xác định extention của file ảnh
                $info = new SplFileInfo($url);
                $fileExtension = $info->getExtension();
                $outputFormat = !empty($fileExtension) ? $fileExtension : $format;
                Utils::debug('Output Format: ' . $outputFormat);
                // Quy định tên file ảnh sẽ lưu
                $fileName = md5($url) . '.' . $outputFormat;
                $imageFile = $this->tmpPath . $fileName;
                $imageUrl = $this->urlPath . $fileName;
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

                return trim($imageUrl);
            } catch (RuntimeException $runtimeException) {
//                if (function_exists('log_message')) {
//                    log_message('error', 'Runtime Error Message: ' . $runtimeException->getMessage());
//                }

                return null;
            }
        } catch (Exception $e) {
//            if (function_exists('log_message')) {
//                log_message('error', 'Error Message: ' . $e->getMessage());
//            }

            return $defaultImage;
        }
    }
}
