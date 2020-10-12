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
$url = 'https://znews-photo.zadn.vn/w960/Uploaded/jugtzb/2020_10_12/DJI_0254.jpg';

$cache = new nguyenanhung\MyImage\ImageCache();
$cache->setTmpPath(__DIR__ . '/../storage/tmp/');
$cache->setUrlPath('http://anhung.io/Packages/image/storage/tmp/');
$cache->setDefaultImage();

$thumbnail  = $cache->thumbnail($url, 200, 300);
$thumbnail2 = $cache->thumbnail('https://znews-photo.zadn.vn/w960/Uploaded/jugtzb/2020_10_12/DJI_0254.jpg', 200, 300);
$saveImage  = $cache->saveImage($url);

imgSrc($thumbnail);
imgSrc($thumbnail2);
imgSrc($saveImage);

