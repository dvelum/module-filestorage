<?php
/**
 * DVelum project http://code.google.com/p/dvelum/ , https://github.com/k-samuel/dvelum , http://dvelum.net
 * Copyright (C) 2011-2017  Kirill Yegorov
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
declare(strict_types=1);

namespace Dvelum\FileStorage;

use Dvelum\Config\ConfigInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Abstract adapter for file storage
 */
abstract class AbstractAdapter
{
    const ERROR_CANT_WRITE_FS = 1;

    /**
     * Configuration object
     * @var ConfigInterface
     */
    protected $config;

    /**
     * Logs adapter
     * @var LoggerInterface
     */
    protected $log = false;

    public function __construct(ConfigInterface $config)
    {
    	$this->config = $config;
    }
    /**
     * Upload files from $_POST and $_FILES
     * @return  boolean | array
     */
    abstract public function upload();
    /**
     * Remove file from storage
     * @param string $fileId
     * @return bool
     */
    abstract public function remove($fileId) : bool ;

    /**
     * Add file (copy to storage)
     * @param string $filePath
     * @param string $useName, optional set specific file name
     * @throws \Exception
     * @return array | boolean false - file info
     */
    abstract public function add($filePath , $useName);

    /**
     * Set logs adapter
     * @param LoggerInterface $log
     */
    public function setLog(LoggerInterface $log)  : void
    {
    	$this->log = $log;
    }
    /**
     * Log error
     * @param string $message
     */
    public function logError(string $message) : void
    {
    	if($this->log)
    	    $this->log->log(LogLevel::ERROR,'Filestorage ' . $message);
    }

    /**
     * @return ConfigInterface
     */
    public function getConfig() : ConfigInterface
    {
        return $this->config;
    }

    /**
     * Get storage files path
     * @return string
     */
    public function getPath() : string
    {
        return $this->config->get('filepath');
    }

    /**
     * Get file Path
     * @param $fileId
     * @return null|string
     */
    abstract public function getFilePath($fileId): ?string;

}