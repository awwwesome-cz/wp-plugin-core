<?php

namespace Awwwesome\Support\Traits;

trait RequirementCheck {

	/**
	 * Example:
	 *
	 * [
	 *    'elementor/elementor.php' => '1.0'
	 * ];
	 *
	 * If does not need version
	 * [
	 *    'elementor/elementor.php' => null
	 * ];
	 * @var array Associative plugins with min version as value
	 */
	protected array $need_activated = [];

	/**
	 * Hold errors
	 *
	 * @var array
	 */
	private array $requirements_errors = [];

	/**
	 * Boot function
	 *
	 * @return void
	 */
	function check() {
		add_action( 'admin_init', [ $this, 'init' ] );
	}

	public function init() {
		foreach ( $this->need_activated as $plugin => $version ) {
			$this->check_activation( $plugin, $version );
		}

		// check errors
		if ( count( $this->requirements_errors ) > 0 ) {
			// revert activation
			$this->revert_activation();

			// show danger notice
			add_action( 'admin_notices', [ $this, 'plugin_requirements_notice' ] );
		}
	}

	/**
	 * @param string $plugin
	 * @param string|null $version
	 *
	 * @return bool
	 */
	protected function check_activation( string $plugin, string $version = null ): bool {
		$active_plugins = apply_filters( 'active_plugins', [
			...get_option( 'active_plugins', [] ),
			// TODO: load mu_plugins
		] );

		if ( in_array( $plugin, $active_plugins ) ) {
			return true;
		}

		$this->requirements_errors[] = $plugin . " Not activated!";

		return false;
	}


	/**
	 * Build notices error
	 *
	 * Show error of plugins & disable activation
	 *
	 * @access public
	 * @since 1.0
	 */
	public function plugin_requirements_notice(): void {
		$list = "";
		foreach ( $this->requirements_errors as $requirements_error ) {
			$list .= "<li>$requirements_error</li>";
		}
		printf( '<div class="notice notice-error is-dismissible"><ul>%s</ul></div>', $list );
	}

	/**
	 * Unset Activate
	 *
	 * Remove $_GET['activate]
	 * Deactivate plugin
	 *
	 * @access private
	 * @since 1.0
	 */
	private function revert_activation(): void {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$plugin = plugin_basename( $this->plugin_name );
		if ( is_plugin_active( $plugin ) ) {
			deactivate_plugins( $plugin );
		}
	}
}