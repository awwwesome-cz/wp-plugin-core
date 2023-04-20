<?php


namespace Awwwesome\Support\Traits;


/**
 * Trait i18n
 *
 * Adding ability to loading language taxdomains in any Plugin.php class
 */
trait i18n {

	/**
	 * Textdomain name in plugin
	 *
	 * @var string
	 */
	static string $domain = 'plugin';

	/**
	 * Language Root Folder
	 *
	 * @var string
	 */
	static string $languagesFolder = '/languages/';

	/**
	 * Init i18n
	 * @return void
	 */
	protected function i18n() {
		add_action( 'plugins_loaded', [ $this, 'load_plugin_textdomain' ] );
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 1.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			self::$domain,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . self::$languagesFolder
		);
	}
}