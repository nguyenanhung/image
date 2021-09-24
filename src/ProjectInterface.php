<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 10/4/18
 * Time: 14:55
 */

namespace nguyenanhung\MyImage;

/**
 * Interface ProjectInterface
 *
 * @package   nguyenanhung\MyImage
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
interface ProjectInterface
{
    const VERSION        = '2.1.0';
    const LAST_MODIFIED  = '2021-09-24';
    const PROJECT_NAME   = 'My Image Processing';
    const AUTHOR_NAME    = 'Hung Nguyen';
    const AUTHOR_EMAIL   = 'dev@nguyenanhung.com';
    const AUTHOR_WEBSITE = 'https://nguyenanhung.com/';
    const USE_DEBUG      = false;

    /**
     * Function getVersion
     *
     * @return mixed
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/06/2020 29:17
     */
    public function getVersion();
}
