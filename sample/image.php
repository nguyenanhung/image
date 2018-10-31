<?php
/**
 * Project image.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 10/31/18
 * Time: 14:44
 */
require_once __DIR__ . '/../vendor/autoload.php';
$url = 'http://sctv.tv247.vn/public/uploads/sctv/logo_nen5_18_1535073239.png';

\nguyenanhung\MyImage\ImageResize::process($url);
