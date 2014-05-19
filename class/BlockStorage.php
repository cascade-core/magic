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
	protected $alias;
	protected $options;
	protected $context;

	/**
	 * Constructor will get options from core.ini.php file.
	 */
	public function __construct($storage_opts, $context, $alias)
	{
		$this->alias = $alias;
		$this->context = $context;
		$this->options = $storage_opts;

		// TODO: Do this later.
		if (!empty($storage_opts['scan_context'])) {
			$this->scanContext($context);
		}
	}


	/**
	 * Automatic scan of (global) context to collect all available 
	 * metadata.
	 *
	 * TODO: Register only factories after context scan, detect block existence on demand.
	 *       Simple map: Object class -> Factory
	 *
	 * TODO: Scan for smalldb machines, create pages from template 'page/$machine_type/$action'.
	 *       (only for block enumeration)
	 *
	 * TODO: Introduce extendable scanning mechanism.
	 *
	 * TODO: Select correct block by requested Content-Type -- AJAX API.
	 */
	protected function scanContext($context)
	{
		//foreach ($context as $r => $resource) {
		//	debug_dump($resource, $r);
		//}
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
	 * Create instance of requested block. Block is created automagically.
	 */
	public function createBlockInstance ($block)
	{
		// Parse block name
		$block_parts = explode('/', $block);
		if (count($block_parts) != 3) {
			return false;
		}
		list($prefix, $entity, $action) = $block_parts;

		// Scan all resources
		foreach ($this->context as $r => $resource) {

			// Check Smalldb Backend
			if ($resource instanceof \Smalldb\StateMachine\AbstractBackend) {

				// Lookup state machine
				$machine = $resource->getMachine($entity);
				if ($machine == null) {
					debug_dump('no mach');
					return false;
				}

				// Lookup action
				if ($action == 'show') {
					// Virtual action to display entity
					return new \B_core__dummy;
				} else {
					// Transition invocation
					$action_desc = $machine->describeMachineAction($action);
					if ($action_desc == null) {
						return false;
					}
					return new \B_core__dummy;
				}
			}
		}

		return false;
	}


	/**
	 * Describe block for documentation generator.
	 *
	 * @todo Some minimal description.
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
	 *
	 * TODO: Scan all factories for known blocks.
	 */
	public function getKnownBlocks (& $blocks = array())
	{
		return $blocks;
	}

}


