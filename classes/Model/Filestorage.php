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

use Dvelum\Orm;
use Dvelum\Config;
use Dvelum\FileStorage;
use Dvelum\App\Session\User;

class Model_Filestorage extends Orm\Model
{
	/**
	 * Get file storage
	 * @return FileStorage\AbstractAdapter
	 */
	public function getStorage()
	{
		$configMain = Config::storage()->get('main.php');

		$storageConfig = Config::storage()->get('filestorage.php');
		$storageCfg = Config::factory(Config\Factory::Simple,'_filestorage');

		if($configMain->get('development')){
			$storageCfg->setData($storageConfig->get('development'));
		}else{
			$storageCfg->setData($storageConfig->get('production'));
		}

		$storageCfg->set('user_id', User::factory()->getId());

		$fileStorage = FileStorage\Factory::adapter($storageCfg->get('adapter'), $storageCfg);

		$log = $this->getLogsAdapter();

		if($log){
            $fileStorage->setLog($log);
        }
		return $fileStorage;
	}
}