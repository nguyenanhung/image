<?php
/**
 * Project image.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 11/1/18
 * Time: 14:46
 */

namespace nguyenanhung\MyImage;

/**
 * Interface ImageCacheInterface
 *
 * @package   nguyenanhung\MyImage
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
interface ImageCacheInterface
{
    /**
     * Cấu hình thư mục lưu trữ file Cache Image
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/1/18 14:21
     *
     * @param string $tmpPath Thư mục cần lưu trữ
     */
    public function setTmpPath($tmpPath = '');

    /**
     * Cấu hình đường dẫn link hình ảnh trên server
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/1/18 14:34
     *
     * @param string $urlPath
     */
    public function setUrlPath($urlPath = '');

    /**
     * Cấu hình đường dẫn link ảnh mặc định
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/1/18 15:40
     *
     * @param string $defaultImage Đường dẫn link ảnh mặc định
     */
    public function setDefaultImage($defaultImage = '');

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
    public function thumbnail($url = '', $width = 100, $height = 100, $format = 'png');

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
    public function saveImage($url = '', $format = 'png');
}
