<?php
/**
 * Lil Notices Plugin Class
 *
 * @since 0.1.2
 */

//avoid direct calls to this file, because now WP core and framework has been used
if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! class_exists( 'Lil_Notices' ) ) {
	class Lil_Notices {

		/**
		 * Initialize the plugin
		 *
		 * @since   0.1
		 * @return  void
		 */
		public static function init() {
			if ( ( is_multisite() ) && ( is_network_admin () ) ) {
				return;
			}
			add_action( 'admin_bar_menu', array( get_called_class(), 'admin_bar_menu' ), 999 );
			add_action( 'admin_enqueue_scripts', array( get_called_class(), 'admin_enqueue_scripts' ), 999 );
		} // end public function init


		public static function admin_enqueue_scripts() {
			wp_enqueue_script( 'ln-scripts', plugin_dir_url( __FILE__ ) . 'assets/dist/main.js', array(), LIL_NOTICES__VERSION, true );
			wp_enqueue_style( 'ln-styles', plugin_dir_url( __FILE__ ) . 'assets/dist/style.css', array(), LIL_NOTICES__VERSION );
		}

		public static function admin_bar_menu() {

			global $wp_admin_bar;

			$menu_id = 'ln_menu';
			$wp_admin_bar->add_node( array(
				'href' => '#',
				'id' => $menu_id,
				'parent' => 'top-secondary',
				'title' => __( 'Notices' ),
			) );
			$wp_admin_bar->add_node( array(
				'id'     => 'ln_all_notices',
				'meta'   => array(
					'html' => static::notices_content(),
				),
				'parent' => $menu_id,

			) );

		}

		/**
		 * Returns the HTML content for the Admin Bar Menu notices dropdown
		 *
		 * @since   0.1
		 * @return  string
		 */
		public static function notices_content() {
			global $wp_filter;
			// count the number of items attached to 'admin_notices'
			// might actually just pull each item from this bit as it will make more sense
			$html = '';
			if ( empty( $wp_filter['admin_notices'] ) ) {
				$html .= apply_filters( 'ln_no_notices', __( 'Nothing to see here!', 'ln_domain' ) );
			} else {
				$html .= '<ul class="ln-notice-list">';

				ob_start();
				static::do_action( 'admin_notices' );
				$notices = ob_get_clean();

				// preg match & replace the classes that WP is using to move admin notices in under the page's H tag
				$notices = preg_replace(
					'/(class=[\'\"])([A-Za-z0-9\-\_\ ]*)(updated)([A-Za-z0-9\-\_\ ]*)([\"\'])/',
					'$1$2 ln-updated $4$5', $notices );

				$html .= $notices;

				$html .= '</ul>';
			}

			// since we're grabbing them above, remove the core do_action via remove_all_actions
			remove_all_actions( 'admin_notices' );

			return $html;
		}

		/**
		 * Custom do_action function based on WP core's implementation
		 *
		 * @param        $tag
		 * @param string $arg
		 *
		 * @since 0.1
		 */
		static function do_action( $tag, $arg = '' ) {
			global $wp_filter, $wp_actions, $merged_filters, $wp_current_filter;

			if ( ! isset( $wp_actions[ $tag ] ) ) {
				$wp_actions[ $tag ] = 1;
			} else {
				++ $wp_actions[ $tag ];
			}

			// Do 'all' actions first
			if ( isset( $wp_filter['all'] ) ) {
				$wp_current_filter[] = $tag;
				$all_args            = func_get_args();
				_wp_call_all_hook( $all_args );
			}

			if ( ! isset( $wp_filter[ $tag ] ) ) {
				if ( isset( $wp_filter['all'] ) ) {
					array_pop( $wp_current_filter );
				}

				return;
			}

			if ( ! isset( $wp_filter['all'] ) ) {
				$wp_current_filter[] = $tag;
			}

			$args = array();
			if ( is_array( $arg ) && 1 == count( $arg ) && isset( $arg[0] ) && is_object( $arg[0] ) ) // array(&$this)
			{
				$args[] =& $arg[0];
			} else {
				$args[] = $arg;
			}
			for ( $a = 2, $num = func_num_args(); $a < $num; $a ++ ) {
				$args[] = func_get_arg( $a );
			}

			// Sort
			if ( ! isset( $merged_filters[ $tag ] ) ) {
				ksort( $wp_filter[ $tag ] );
				$merged_filters[ $tag ] = true;
			}

			reset( $wp_filter[ $tag ] );

			do {
				foreach ( (array) current( $wp_filter[ $tag ] ) as $the_ ) {
					if ( ! is_null( $the_['function'] ) ) {
						/**
						 * Use ob_start/ob_get_clean so we have something
						 * "returned" that we can then check for false/empty values.
						 */
						ob_start();
						call_user_func_array( $the_['function'], array_slice( $args, 0, (int) $the_['accepted_args'] ) );
						$the_item = ob_get_clean();

						if ( false != $the_item ) {
							echo '<li class="ln-notice" data-notice-id="' . sanitize_key( $the_['function'] ) . '">';
							echo $the_item;
							echo '</li>';
						}

					}
				}

			} while ( next( $wp_filter[ $tag ] ) !== false );

			array_pop( $wp_current_filter );
		}

		/**
		 * Activate the plugin
		 *
		 * @since   0.1
		 * @return  void
		 */
		public static function activate() {
		} // END public static function activate

		/**
		 * Deactivate the plugin
		 *
		 * @since   0.1
		 * @return  void
		 */
		public static function deactivate() {
		} // END public static function deactivate

	} // END class Lil_Notices
} // END if ( ! class_exists( 'Lil_Notices' ) )