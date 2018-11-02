<?php
/**
 * Project image.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 10/31/18
 * Time: 14:44
 */
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../functions.php';
$url = 'http://sctv.tv247.vn/public/uploads/sctv/logo_nen5_18_1535073239s.png';

$cache = new \nguyenanhung\MyImage\ImageCache();
$cache->setTmpPath(__DIR__ . '/../storage/tmp/');
$cache->setUrlPath('http://anhung.io/Packages/image/storage/tmp/');
$cache->setDefaultImage();

$thumbnail = $cache->thumbnail($url, 200, 300);
$thumbnail2 = $cache->thumbnail('http://sctv.tv247.vn/public/uploads/sctv/cdn8.net1491134259_1491139784.JPG', 200, 300);

imgSrc($thumbnail);
imgSrc($thumbnail2);
