<?php
/*
 * Copyright (c) 2012, Josef Kufner  <jk@frozen-doe.net>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */

namespace Magic;

/**
 * Block storage which magically creates page blocks.
 */
class BlockStorage implements \Cascade\Core\IBlockStorage
{

	/**
	 * Constructor will get options from core.ini.php file.
	 */
	public function __construct($storage_opts, $auth, $context)
	{
		if (!empty($storage_opts['scan_context'])) {
			$this->scanContext($context);
		}
	}


	/**
	 * Automatic scan of (global) context to collect all available 
	 * metadata.
	 */
	protected function scanContext($context)
	{
		// TODO
	}


	/**
	 * Returns true if there is no way that this storage can modify or
	 * create blocks. When creating or modifying block, first storage that
	 * returns true will be used.
	 */
	public function isReadOnly()
	{
		return true;
	}


	/**
	 * Create instance of requested block and give it loaded configuration.
	 * No further initialisation here, that is job for cascade controller.
	 * Returns created instance or false.
	 */
	public function createBlockInstance ($block)
	{
		return false;
	}


	/**
	 * Describe block for documentation generator.
	 *
	 * @todo
	 */
	public function describeBlock ($block)
	{
		return false;
	}


	/**
	 * Load block configuration. Returns false if block is not found.
	 */
	public function loadBlock ($block)
	{
		return false;
	}


	/**
	 * Store block configuration.
	 */
	public function storeBlock ($block, $config)
	{
		return false;
	}


	/**
	 * Delete block configuration.
	 */
	public function deleteBlock ($block)
	{
		return false;
	}


	/**
	 * Get time (unix timestamp) of last modification of the block.
	 */
	public function blockMTime ($block)
	{
		return 0;
	}


	/**
	 * List all available blocks in this storage.
	 */
	public function getKnownBlocks (& $blocks = array())
	{
		return $blocks;
	}

}


