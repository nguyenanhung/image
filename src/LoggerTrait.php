<?php
/**
 * Project image
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 10/06/2020
 * Time: 11:46
 */

namespace nguyenanhung\MyImage;

/**
 * Trait LoggerTrait
 *
 * @package   nguyenanhung\MyImage
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
trait LoggerTrait
{
    /**
     * Function setLoggerStatus
     *
     * @param bool $loggerStatus
     *
     * @return $this
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/06/2020 45:23
     */
    public function setLoggerStatus(bool $loggerStatus = false)
    {
        $this->loggerStatus = $loggerStatus;

        return $this;
    }

    /**
     * Function setLoggerLevel
     *
     * @param null $loggerLevel
     *
     * @return $this
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/06/2020 45:50
     */
    public function setLoggerLevel($loggerLevel = null)
    {
        $this->loggerLevel = $loggerLevel;

        return $this;
    }

    /**
     * Function setLoggerPath
     *
     * @param $loggerPath
     *
     * @return $this
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/06/2020 46:01
     */
    public function setLoggerPath($loggerPath)
    {
        $this->loggerPath = $loggerPath;

        return $this;
    }
}
