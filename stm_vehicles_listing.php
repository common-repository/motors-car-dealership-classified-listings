<?php
/**
 * Plugin Name: Motors – Car Dealer, Classifieds & Listing
 * Plugin URI: https://wordpress.org/plugins/motors-car-dealership-classified-listings/
 * Description: Manage classified listings from the WordPress admin panel, and allow users to post classified listings directly to your website.
 * Author: StylemixThemes
 * Author URI: https://stylemixthemes.com/
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: stm_vehicles_listing
 * Version: 1.4.30
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( in_array( 'stm_vehicles_listing/stm_vehicles_listing.php', (array) get_option( 'active_plugins', array() ), true ) ) {

	if ( get_template_directory() === get_stylesheet_directory() ) {
		if ( ! function_exists( 'deactivate_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		deactivate_plugins( 'stm_vehicles_listing/stm_vehicles_listing.php', true );
	} else {
		if ( is_admin() ) {
			add_action(
				'after_plugin_row_motors-car-dealership-classified-listings/stm_vehicles_listing.php',
				function ( $plugin_file, $plugin_data, $status ) {
					printf(
						'<tr class="plugin-update-tr" id="motors-car-dealership-classified-listings-update" data-slug="stm_vehicles_listing" data-plugin="stm_vehicles_listing/stm_vehicles_listing.php"><td colspan="%s" class="plugin-update colspanchange"><div class="notice inline notice-warning notice-alt"><h4 style="margin: 0; font-size: 14px;"><i class="error-message fa fa-exclamation-circle"></i> %s</h4>%s</div></td></tr>',
						4,
						'Please Deactivate the Motors - Car Dealer, Classifieds & Listing (Deprecated) ' . esc_html( STM_LISTINGS_V ),
						wpautop( 'To make sure everything works well and without any problems, please deactivate the Motors - Car Dealer, Classifieds & Listing (Deprecated) ' . STM_LISTINGS_V . ' plugin' ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					);
				},
				30,
				3
			);
		}

		return;
	}
}

if ( ! defined( 'STM_LISTINGS_PATH' ) ) {
	define( 'STM_LISTINGS_FILE', __FILE__ );
	define( 'STM_LISTINGS_PATH', dirname( STM_LISTINGS_FILE ) );
	define( 'STM_LISTINGS_URL', plugins_url( '', STM_LISTINGS_FILE ) );
	define( 'STM_LISTINGS', 'stm_vehicles_listing' );
	define( 'STM_THEME_V_NEED', '5.6.33' );
	define( 'STM_LISTINGS_V', '1.4.30' );
	define( 'STM_LISTINGS_IMAGES', STM_LISTINGS_URL . '/includes/admin/butterbean/images/' );
}

require_once STM_LISTINGS_PATH . '/vendor/autoload.php';

use MotorsNuxy\MotorsNuxyHelpers;
use MotorsVehiclesListing\Features\Elementor\Nuxy\TemplateManager;
use MotorsVehiclesListing\Features\FriendlyUrl;
use MotorsVehiclesListing\Features\MultiplePlan;
use MotorsVehiclesListing\SellerNoteMetaBoxes;
use MotorsVehiclesListing\MenuPages\MenuBuilder;
use MotorsVehiclesListing\MenuPages\SingleListingTemplateSettings;
use MotorsVehiclesListing\MenuPages\ListingDetailsSettings;
use MotorsVehiclesListing\MenuPages\AddCarFormSettings;
use MotorsVehiclesListing\MenuPages\SearchResultsSettings;
use MotorsVehiclesListing\MenuPages\FilterSettings;
use MotorsVehiclesListing\Plugin\Settings;
use MotorsVehiclesListing\Elementor\Nuxy\AddListingManager;
use MotorsVehiclesListing\Elementor\Nuxy\FeaturesSettings;
use MotorsVehiclesListing\Helper\ListingStats;
use Motors_Elementor_Widgets_Free\MotorsElementorWidgetsFree;

if ( ! is_textdomain_loaded( 'stm_vehicles_listing' ) ) {
	load_plugin_textdomain( 'stm_vehicles_listing', false, 'stm_vehicles_listing/languages' );
}

if ( ! in_array( 'stm-motors-extends/stm-motors-extends.php', (array) get_option( 'active_plugins', array() ), true ) ) {
	add_action(
		'admin_init',
		function () {
			new SellerNoteMetaBoxes();
		}
	);
}

require_once dirname( __FILE__ ) . '/nuxy/NUXY.php';
require_once STM_LISTINGS_PATH . '/includes/functions.php';
require_once STM_LISTINGS_PATH . '/includes/helpers.php';

if ( file_exists( STM_LISTINGS_PATH . '/includes/starter-theme/classes/class-loader.php' ) ) {
	require_once STM_LISTINGS_PATH . '/includes/starter-theme/classes/class-loader.php';
}

require_once STM_LISTINGS_PATH . '/includes/user-extra.php';

/* Features */

if ( apply_filters( 'stm_is_motors_theme', false ) || apply_filters( 'is_mvl_pro', false ) ) {
	if ( file_exists( STM_LISTINGS_PATH . '/includes/class/Plugin/hooks.php' ) ) {
		require_once STM_LISTINGS_PATH . '/includes/class/Plugin/hooks.php';
	}

	$active_plugins = (array) get_option( 'active_plugins', array() );

	add_action(
		'init',
		function () use ( $active_plugins ) {
			if ( class_exists( 'WooCommerce' ) && ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'dealer_pay_per_listing' ) ||
				apply_filters( 'motors_vl_get_nuxy_mod', false, 'dealer_payments_for_featured_listing' ) ||
				apply_filters( 'motors_vl_get_nuxy_mod', false, 'enable_woo_online' ) ) ) {

				require_once STM_LISTINGS_PATH . '/includes/class/Features/STMListingDataStoreCPT.php';
			}
			if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'enable_plans' ) ) {
				if ( in_array( 'woocommerce/woocommerce.php', $active_plugins, true ) && in_array( 'subscriptio/subscriptio.php', $active_plugins, true ) ) {
					new MultiplePlan();
				}
			}
		},
		0
	);

	if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'friendly_url' ) ) {
		FriendlyUrl::init();
	}
}

/* Features */

if ( class_exists( 'Elementor\Plugin' ) && class_exists( '\MotorsVehiclesListing\Features\Elementor\Nuxy\TemplateManager' ) ) {
	new TemplateManager();
}

new ListingStats();

if ( ! defined( 'WPB_VC_VERSION' ) ) {
	new FeaturesSettings();
	new AddListingManager();
}

require_once STM_LISTINGS_PATH . '/includes/multiple_currencies.php';
require_once STM_LISTINGS_PATH . '/includes/query.php';
require_once STM_LISTINGS_PATH . '/includes/options.php';
require_once STM_LISTINGS_PATH . '/includes/actions.php';
require_once STM_LISTINGS_PATH . '/includes/fix-image-orientation.php';
require_once STM_LISTINGS_PATH . '/includes/shortcodes.php';

if ( file_exists( STM_LISTINGS_PATH . '/includes/stm_single_dealer.php' ) ) {
	require_once STM_LISTINGS_PATH . '/includes/stm_single_dealer.php';
}

if ( is_admin() ) {

	if ( file_exists( STM_LISTINGS_PATH . '/includes/starter-theme/classes/class-loader.php' ) ) {
		require_once STM_LISTINGS_PATH . '/includes/starter-theme/classes/class-loader.php';
	}

	require_once STM_LISTINGS_PATH . '/includes/admin/categories.php';
	require_once STM_LISTINGS_PATH . '/includes/admin/enqueue.php';
	require_once STM_LISTINGS_PATH . '/includes/admin/butterbean_hooks.php';
	require_once STM_LISTINGS_PATH . '/includes/admin/butterbean_metaboxes.php';
	require_once STM_LISTINGS_PATH . '/includes/admin/category-image.php';
	require_once STM_LISTINGS_PATH . '/includes/admin/admin_sort.php';
	require_once STM_LISTINGS_PATH . '/includes/admin/page_generator/main.php';

	/*For plugin only*/
	require_once STM_LISTINGS_PATH . '/includes/admin/announcement/main.php';
	if ( file_exists( STM_LISTINGS_PATH . '/includes/admin/mailchimp-integration.php' ) ) {
		require_once STM_LISTINGS_PATH . '/includes/admin/mailchimp-integration.php';
	}

	if ( file_exists( STM_LISTINGS_PATH . '/includes/lib/admin-notices/admin-notices.php' ) ) {
		require_once STM_LISTINGS_PATH . '/includes/lib/admin-notices/admin-notices.php';
	}

	if ( class_exists( '\MotorsNuxy\MotorsNuxyHelpers' ) ) {
		new MotorsNuxyHelpers();
	}

	require_once STM_LISTINGS_PATH . '/includes/admin/setup-wizard/main.php';

	new Settings();

	if ( class_exists( 'Elementor\Plugin' ) && class_exists( '\MotorsVehiclesListing\MenuPages\SingleListingTemplateSettings' ) ) {
		new SingleListingTemplateSettings();
	}

	if ( apply_filters( 'mvl_add_listing_form_enable', true ) && class_exists( '\MotorsVehiclesListing\MenuPages\AddCarFormSettings' ) ) {
		new AddCarFormSettings();
	}

	if ( class_exists( '\MotorsVehiclesListing\MenuPages\SearchResultsSettings' ) ) {
		new SearchResultsSettings();
	}

	if ( class_exists( '\MotorsVehiclesListing\MenuPages\FilterSettings' ) ) {
		new FilterSettings();
	}

	if ( class_exists( '\MotorsVehiclesListing\MenuPages\ListingDetailsSettings' ) ) {
		new ListingDetailsSettings();
	}

	if ( class_exists( '\MotorsVehiclesListing\MenuPages\MenuBuilder' ) && ! is_network_admin() ) {
		new MenuBuilder();
	}
}

if ( file_exists( STM_LISTINGS_PATH . '/elementor/MotorsElementorWidgetsFree.php' ) ) {
	require_once STM_LISTINGS_PATH . '/elementor/MotorsElementorWidgetsFree.php';
}

if ( ! in_array( 'motors-elementor-widgets/motors-elementor-widgets.php', (array) get_option( 'active_plugins', array() ), true ) && class_exists( 'Elementor\Plugin' ) ) {
	new MotorsElementorWidgetsFree();
}

if ( file_exists( STM_LISTINGS_PATH . '/includes/notices.php' ) ) {
	require_once STM_LISTINGS_PATH . '/includes/notices.php';
}

add_action(
	'plugins_loaded',
	function () {
		if ( apply_filters( 'is_mvl_pro', false ) && file_exists( STM_LISTINGS_PATH . '/includes/class/Features/email_template_manager/email_template_manager.php' ) ) {
			require_once STM_LISTINGS_PATH . '/includes/class/Features/email_template_manager/email_template_manager.php';
		} else {
			require_once STM_LISTINGS_PATH . '/includes/email_templates/email_templates.php';
		}
	}
);
