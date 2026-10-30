<?php
/**
 * Plugin Name: MUNEEB MART Core
 * Description: Demo setup, catalog filters, consent-based enquiries and subscriptions, and a development-only WooCommerce gateway.
 * Version: 1.2.0
 * Requires at least: 6.7
 * Requires PHP: 8.2
 * Requires Plugins: woocommerce
 * License: GPL-2.0-or-later
 * Text Domain: muneeb-mart
 */
defined('ABSPATH') || exit;
define('MM_CORE_DIR',plugin_dir_path(__FILE__));
require_once MM_CORE_DIR.'inc/catalog.php';
require_once MM_CORE_DIR.'inc/forms.php';
require_once MM_CORE_DIR.'inc/setup.php';
require_once MM_CORE_DIR.'inc/journal.php';
add_action('before_woocommerce_init',function(){if(class_exists('Automattic\WooCommerce\Utilities\FeaturesUtil')) \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables',__FILE__,true);});
add_action('admin_menu',function(){add_submenu_page('woocommerce','MUNEEB MART setup','MUNEEB MART setup','manage_options','muneeb-mart','mm_setup_screen');});
function mm_setup_screen(){
    if(!current_user_can('manage_options'))return;
    if(isset($_POST['mm_expand'])){check_admin_referer('mm_expand');mm_seed_expansion();echo '<div class="notice notice-success"><p>Journal and support pages prepared. Existing content was preserved.</p></div>';}
    echo '<div class="wrap"><h2>The Watch Journal</h2><p>Add eight original demonstration articles, ten native blog categories, two collections and missing support pages. Existing content is preserved.</p><form method="post">';wp_nonce_field('mm_expand');submit_button('Prepare journal and support pages','secondary','mm_expand');echo '</form></div>';
    if(isset($_POST['mm_seed'])){check_admin_referer('mm_seed');mm_seed_store();echo '<div class="notice notice-success"><p>Demonstration store prepared. Existing products were preserved.</p></div>';}
    if(isset($_POST['mm_save_settings'])){check_admin_referer('mm_store_settings');update_option('mm_demo_mode',isset($_POST['demo_mode'])?1:0);update_option('mm_notification_email',sanitize_email(wp_unslash($_POST['notification_email']??'')));echo '<div class="notice notice-success"><p>Settings saved.</p></div>';}
    echo '<div class="wrap"><h1>MUNEEB MART</h1><p>Your products, collections, orders, stock, shipping and payments are managed by WooCommerce. Design settings are in Appearance → Customize.</p><h2>Development content</h2><p>This creates missing pages, 20 sample watches, eleven collections and eight Journal articles, attributes, and a navigation menu. It never replaces existing product edits. Original generated images and prices are illustrative. Demo mode disables indexing and labels the storefront. Only use this importer in a development store.</p><form method="post">';wp_nonce_field('mm_seed');submit_button('Prepare demonstration store','secondary','mm_seed');echo '</form><hr><h2>Store settings</h2><form method="post">';wp_nonce_field('mm_store_settings');echo '<p><label><input type="checkbox" name="demo_mode" value="1" '.checked(1,get_option('mm_demo_mode'),false).'> Development storefront label and noindex</label></p><p><label>Enquiry notification email (leave blank to store enquiries only)<br><input type="email" class="regular-text" name="notification_email" value="'.esc_attr(get_option('mm_notification_email','')).'"></label></p><p>Enquiries are saved under Store enquiries, subscriptions under Subscribers. Configure and verify SMTP separately. The test gateway is available only when demo mode is on and WordPress environment is development or local.</p>';submit_button('Save settings','primary','mm_save_settings');echo '</form><p>Before launch: remove demo products, approve policies, configure shipping/taxes and a payment method, verify email, and turn off development mode.</p></div>';
}
add_action('plugins_loaded',function(){
    if(!class_exists('WC_Payment_Gateway'))return;
    class MM_Test_Gateway extends WC_Payment_Gateway {
        public function __construct(){ $this->id='mm_test';$this->method_title='MUNEEB MART development test';$this->method_description='Creates an on-hold TEST order. No payment is collected. Available only in local/development WordPress environments with demo mode enabled.';$this->title='Development test order — no payment';$this->description='This is a development store. Submit a TEST order to verify checkout. No payment will be collected and no item will be dispatched.';$this->enabled='yes';$this->has_fields=false;$this->supports=['products']; }
        public function is_available(){return (bool)get_option('mm_demo_mode')&&in_array(wp_get_environment_type(),['local','development'],true)&&parent::is_available();}
        public function process_payment($id){if(!$this->is_available())return ['result'=>'failure'];$order=wc_get_order($id);if(!$order)return ['result'=>'failure'];$order->update_meta_data('_mm_test_order','yes');$order->update_status('on-hold','TEST ORDER — no payment collected; do not fulfill.');$order->save();wc_reduce_stock_levels($id);WC()->cart->empty_cart();return ['result'=>'success','redirect'=>$this->get_return_url($order)];}
    }
    add_filter('woocommerce_payment_gateways',function($gateways){$gateways[]='MM_Test_Gateway';return $gateways;});
});
add_action('woocommerce_thankyou',function($id){$order=wc_get_order($id);if($order&&$order->get_meta('_mm_test_order')==='yes')echo '<div class="woocommerce-info"><strong>Development test order.</strong> No payment was collected. This order will not be fulfilled.</div>';},1);
add_filter('woocommerce_email_enabled_customer_on_hold_order',function($enabled,$order){return $order&&$order->get_meta('_mm_test_order')==='yes'?false:$enabled;},10,2);
add_filter('woocommerce_email_enabled_new_order',function($enabled,$order){return $order&&($order->get_meta('_mm_test_order')==='yes'||$order->get_payment_method()==='mm_test')?false:$enabled;},10,2);
