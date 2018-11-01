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
$url = 'http://sctv.tv247.vn/public/uploads/sctv/logo_nen5_18_1535073239.png';

$cache = new \nguyenanhung\MyImage\ImageCache();
$cache->setTmpPath(__DIR__ . '/../storage/tmp/');
$cache->setUrlPath('http://anhung.io/Packages/image/storage/tmp/');

$thumbnail = $cache->thumbnail($url);

imgSrc($thumbnail);
