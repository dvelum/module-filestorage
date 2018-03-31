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

use Dvelum\Config\ConfigInterface;
use Dvelum\Orm\{
    Model, Record, Record\Config
};

/**
 * File storage ORM adapter.
 */
class Orm extends Simple
{
    /**
     * Object name
     * @var string
     */
    protected $object;

    /**
     * Orm object fields
     * @var array
     */
    protected $objectFields = array('id', 'path', 'date', 'ext', 'size', 'user_id');

    public function __construct(ConfigInterface $config)
    {
        parent::__construct($config);

        if (!$this->config->offsetExists('object')) {
            throw new \Exception('Filestorage_Orm undefined Orm object');
        }

        $this->object = $this->config->get('object');

        if (!$this->config->offsetExists('check_orm_structure') || $this->config->get('check_orm_structure')) {
            $this->checkOrmStructure();
        }
    }

    /**
     * Check Db_Object structure
     * @throws \Exception
     */
    public function checkOrmStructure()
    {
        if (!Config::configExists($this->object)) {
            throw new \Exception(get_class($this) . ' undefined Orm object');
        }

        $cfg = Config::factory($this->object);
        $fields = $cfg->getFieldsConfig(true);

        foreach ($this->objectFields as $name) {
            if (!isset($fields[$name])) {
                throw new \Exception(get_class($this) . ' invalid orm structure, field ' . $name . ' not found');
            }
        }
    }

    /**
     * (non-PHPdoc)
     * @see Filestorage_Simple::upload()
     */
    public function upload()
    {
        $data = parent::upload();

        if (empty($data)) {
            return false;
        }

        foreach ($data as $k => &$v) {
            try {
                $o = Record::factory($this->object);
                $o->setValues([
                    'path' => $v['path'],
                    'date' => date('Y-m-d H:i:s'),
                    'ext' => $v['ext'],
                    'size' => number_format(($v['size'] / 1024 / 1024), 3),
                    'user_id' => $this->config->get('user_id'),
                    'name' => $v['old_name']
                ]);

                if (!$o->save()) {
                    throw new \Exception(get_class($this) . ' Cannot save object');
                }

                $v['id'] = $o->getId();

            } catch (\Exception $e) {
                Model::factory($this->object)->logError(get_class($this) . ' ' . $e->getMessage());
            }
        }
        unset($v);
        return $data;
    }

    /**
     * (non-PHPdoc)
     * @see Filestorage_Simple::generateFilePath()
     */
    protected function generateFilePath(): string
    {
        return $this->config->get('filepath') . '/' . date('Y') . '/' . date('m') . '/' . date('d') . '/' . $this->config->get('user_id') . '/';
    }

    /**
     * Remove file from storage
     * @param string $fileId
     * @return bool
     */
    public function remove($fileId): bool
    {
        if (!Record::objectExists($this->object, $fileId)) {
            return true;
        }

        try {
            $o = Record::factory($this->object, $fileId);
        } catch (\Exception $e) {
            return false;
        }

        $path = $o->path;

        if (!$o->delete()) {
            return false;
        }

        return parent::remove($path);
    }

    /**
     * (non-PHPdoc)
     * @see Filestorage_Simple::add()
     */
    public function add($filePath, $useName = false)
    {
        $data = parent::add($filePath, $useName);

        if (empty($data)) {
            return false;
        }
        try {
            $o = Record::factory($this->object);
            $o->setValues([
                'path' => $data['path'],
                'date' => date('Y-m-d H:i:s'),
                'ext' => $data['ext'],
                'size' => number_format(($data['size'] / 1024 / 1024), 3),
                'user_id' => $this->config->get('user_id'),
                'name' => $data['old_name']
            ]);

            if (!$o->save()) {
                throw new \Exception('Cannot save object');
            }

            $data['id'] = $o->getId();

        } catch (\Exception $e) {
            Model::factory($this->object)->logError(get_class($this) . ' ' . $e->getMessage());
        }
        return $data;
    }

    /**
     * Get file Path
     * @param $fileId
     * @return string
     */
    public function getFilePath($fileId): ?string
    {
        $item = Model::factory($this->object)->getItem($fileId);
        if(!empty($item) && file_exists($this->getPath() . $item['path'])){
            return $this->getPath() . $item['path'];
        }
        return null;
    }
}