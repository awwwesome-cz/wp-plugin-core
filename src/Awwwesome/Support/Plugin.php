<?php

namespace Awwwesome\Support;


use Awwwesome\Support\Traits\i18n;
use Awwwesome\Support\Traits\RequirementCheck;

/**
 * Default plugin class for wrapping
 */
abstract class Plugin {
	use i18n;
	use RequirementCheck;

	protected ?string $plugin_name = null;

	public function __construct() {
	}

	/**
	 * Initialize plugin
	 *
	 * @return void
	 */
	function boot() {
		// boot i18n init
		$this->i18n();
		$this->check();
	}

}