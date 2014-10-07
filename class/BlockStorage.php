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
class BlockStorage extends \Cascade\Core\JsonBlockStorage implements \Cascade\Core\IBlockStorage
{
	protected $alias;		///< Name of this block storage instance (debug)
	protected $options;		///< Options given to constructor
	protected $context;		///< Global context

	protected $block_patterns;	///< Regexps to match block names

	protected $entity_resources;	///< List of known entities and their storages (map: entity name => resource).

	/**
	 * Constructor will get options from core.ini.php file.
	 */
	public function __construct($storage_opts, $context, $alias, $is_write_allowed)
	{
		parent::__construct($storage_opts, $context, $alias, $is_write_allowed);

		$this->alias = $alias;
		$this->context = $context;
		$this->options = $storage_opts;

		if (!empty($storage_opts['scan_context'])) {
			$this->scanContext($context);
		}

		if (!empty($storage_opts['block_patterns'])) {
			$this->block_patterns = $storage_opts['block_patterns'];
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
		$this->entity_resources = array();

		foreach ($context as $r => $resource) {
			if ($resource instanceof \Smalldb\StateMachine\AbstractBackend) {
				// Remember resource for this entity
				foreach ($resource->getKnownTypes() as $type) {
					$this->entity_resources[$type] = $resource;
				}
			}
		}
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
	//public function createBlockInstance ($block)
	//{
	//}


	/**
	 * Describe block for documentation generator.
	 *
	 * @todo Some minimal description.
	 */
	//public function describeBlock ($block)
	//{
	//	return false;
	//}


	/**
	 * Load block configuration. Returns false if block is not found.
	 */
	public function loadBlock ($block)
	{
		foreach ($this->block_patterns as $pattern => $block_name_fmt) {
			if (preg_match($pattern, $block, $m)) {
				return $this->loadEntityBlock($m, $block_name_fmt);
			}
		}
		return false;
	}


	/**
	 * Load block representing a page for given `$action` on the `$entity`.
	 */
	protected function loadEntityBlock($args, $block_name_fmt)
	{
		$entity = $args['entity'];

		if (isset($this->entity_resources[$entity])) {
			$resource = $this->entity_resources[$entity];
		} else {
			return false;
		}

		// Check Smalldb Backend for actions
		if (isset($args['action']) && $resource instanceof \Smalldb\StateMachine\AbstractBackend) {
			$action = $args['action'];

			// Lookup state machine
			$machine = $resource->getMachine($entity);

			// Lookup action
			if ($action != 'show' && $action != 'listing') {
				$action_desc = $machine->describeMachineAction($action);
				if ($action_desc == null) {
					return false;
				}
			}
		}

		// Load block
		return $this->createBlockFromTemplate($block_name_fmt, $args);
	}

	/**
	 * Load block as usual, but replace symbols in the block and its name
	 * using filename_format().
	 */
	protected function createBlockFromTemplate($template_name_fmt, $args)
	{
		// Load template
		$block_config = parent::loadBlock(filename_format($template_name_fmt, $args));
		if (!$block_config) {
			return false;
		}

		// Replace symbols
		array_walk_recursive($block_config, function(& $val, $key) use ($args) {
			if (is_string($val)) {
				$val = filename_format($val, $args);
			}
		});

		return $block_config;
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


