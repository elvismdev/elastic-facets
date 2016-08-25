<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Plugin;

/**
 * Interface PluginLoader
 *
 * @package ElasticFacets\Plugin
 */
interface PluginLoader {

	/**
	 * @return void
	 */
	public function register_callbacks();
}