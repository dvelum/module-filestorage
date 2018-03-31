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

namespace Dvelum\FileStorage\Adapter;

use Dvelum\FileStorage\AbstractAdapter;
use Dvelum\File;
use Dvelum\Request;

/**
 * File storage simple filesystem adapter.
 * @author  Kirill A Egorov 2014
 *
 */
class Simple extends AbstractAdapter
{
    /**
     * Upload files from $_POST and $_FILES
     * @return  boolean | array
     */
    public function upload()
    {
        $path = $this->generateFilePath();

        if (!is_dir($path) && !@mkdir($path, $this->config->get('mkdir_mode'), true)) {
            $this->logError('Cannot write FS ' . $path . ' ' . self::ERROR_CANT_WRITE_FS);
            return false;
        }

        $fileList = Request::factory()->files();
        $files = [];

        foreach ($fileList as $item => $cfg) {
            if (is_array($cfg) && !isset($cfg['name'])) {
                foreach ($cfg as $item) {
                    $item['old_name'] = $item['name'];
                    if ($this->config->get('rename')) {
                        $item['name'] = time() . uniqid('-') . File::getExt($item['name']);
                    }
                    $files[] = $item;
                }

            } else {
                $cfg['old_name'] = $cfg['name'];
                if ($this->config->get('rename')) {
                    $cfg['name'] = time() . uniqid('-') . File::getExt($cfg['name']);
                }
                $files[] = $cfg;
            }
        }

        if (empty($files)) {
            return false;
        }

        $uploadAdapter = $this->config->get('uploader');
        $uploaderConfig = $this->config->get('uploader_config');

        /**
         * @var \Upload_AbstractAdapter $uploader
         */
        $uploader = new $uploadAdapter($uploaderConfig);
        $uploaded = $uploader->start($files, $path);

        if (empty($uploaded)) {
            $errors = $uploader->getErrors();
            if (!empty($errors)) {
                $this->logError(implode(', ', $errors));
                return false;
            }

            return [];
        }

        foreach ($uploaded as $k => &$v) {
            $v['path'] = str_replace($this->config->get('filepath'), '', $v['path']);
            $v['id'] = $v['path'];
        }
        unset($v);

        return $uploaded;
    }

    /**
     * Generate file path
     * @return string
     */
    protected function generateFilePath(): string
    {
        return $this->config->get('filepath') . '/' . date('Y') . '/' . date('m') . '/' . date('d');
    }

    /**
     * (non-PHPdoc)
     * @see Filestorage_Abstract::remove()
     */
    public function remove($fileId) : bool
    {
        $fullPath = $this->config->get('filepath') . $fileId;

        if (!file_exists($fullPath)) {
            return true;
        }

        return unlink($fullPath);
    }

    /**
     * (non-PHPdoc)
     * @see Filestorage_Abstract::add()
     */
    public function add($filePath, $useName = false)
    {
        if (!file_exists($filePath)) {
            return false;
        }

        $path = $this->generateFilePath();

        if (!is_dir($path) && !@mkdir($path, $this->config->get('mkdir_mode'), true)) {
            $this->logError('Cannot write FS ' . $path . ' ' . self::ERROR_CANT_WRITE_FS);
            return false;
        }

        $uploadAdapter = $this->config->get('uploader');
        $uploaderConfig = $this->config->get('uploader_config');
        $uploader = new $uploadAdapter($uploaderConfig);

        $fileName = basename($filePath);
        $oldName = basename($filePath);

        if ($useName !== false) {
            $oldName = $useName;
        }

        if ($this->config->get('rename')) {
            $fileName = time() . uniqid('-') . File::getExt($fileName);
        }

        $files = array(
            'file' => array(
                'name' => $fileName,
                'old_name' => $oldName,
                'type' => '',
                'tmp_name' => $filePath,
                'error' => 0,
                'size' => filesize($filePath)
            )
        );

        $uploaded = $uploader->start($files, $path, false);

        if (empty($uploaded)) {
            return false;
        }

        $uploaded = $uploaded[0];
        $uploaded['path'] = str_replace($this->config->get('filepath'), '', $uploaded['path']);
        $uploaded['id'] = $uploaded['path'];

        return $uploaded;
    }

    /**
     * Get file Path
     * @param $fileId
     * @return null|string
     */
    public function getFilePath($fileId): ?string
    {
        if(file_exists($this->getPath() . $fileId)){
            return $this->getPath() . $fileId;
        }
        return null;
    }
}