<?php
/**
 * Project image
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 17/02/2023
 * Time: 09:32
 */

namespace nguyenanhung\MyImage;

class BaseImage implements ProjectInterface
{
    /** @var bool Logger Status: TRUE or FALSE */
    protected $loggerStatus;
    /** @var bool Logger Level */
    protected $loggerLevel;
    /** @var bool Logger Path */
    protected $loggerPath;

    public function getVersion()
    {
        return self::VERSION;
    }

    public function setLoggerStatus($loggerStatus = false)
    {
        $this->loggerStatus = $loggerStatus;

        return $this;
    }

    public function setLoggerLevel($loggerLevel = null)
    {
        $this->loggerLevel = $loggerLevel;

        return $this;
    }

    public function setLoggerPath($loggerPath)
    {
        $this->loggerPath = $loggerPath;

        return $this;
    }
}
