<?php
if(PHP_SAPI!=='cli')exit(1);
require __DIR__.'/../.local/wordpress/wp-load.php';require_once ABSPATH.'wp-admin/includes/plugin.php';
switch_theme('muneeb-mart');activate_plugin('muneeb-mart-core/muneeb-mart-core.php');
// Plugin activation (or the normal WordPress bootstrap) loads the setup module.
$result=mm_seed_store();if(is_wp_error($result))throw new Exception($result->get_error_message());
// Configure a clearly labelled, development-only free shipping method for Pakistan.
$zones=WC_Shipping_Zones::get_zones();$found=false;foreach($zones as $zone)if($zone['zone_name']==='Development — Pakistan')$found=true;
if(!$found){$zone=new WC_Shipping_Zone();$zone->set_zone_name('Development — Pakistan');$zone->add_location('PK','country');$zone->save();$instance=$zone->add_shipping_method('flat_rate');update_option('woocommerce_flat_rate_'.$instance.'_settings',['title'=>'Development delivery — no dispatch','tax_status'=>'none','cost'=>'0']);}
update_option('woocommerce_coming_soon','no');update_option('woocommerce_store_pages_only','no');
echo "Theme activated; 20 demo products, collections, pages and local test shipping prepared.\n";
