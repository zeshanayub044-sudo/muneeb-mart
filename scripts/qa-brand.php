<?php
if(PHP_SAPI!=='cli')exit(1);
require __DIR__.'/../.local/wordpress/wp-load.php';
$checks=[];$check=function($test,$pass)use(&$checks){$checks[]=['test'=>$test,'pass'=>(bool)$pass];echo ($pass?'PASS':'FAIL').': '.$test."\n";};
$products=wc_get_products(['status'=>'publish','limit'=>-1]);
$check('WordPress site title is MUNEEB MART',get_bloginfo('name')==='MUNEEB MART');
$check('MUNEEB MART theme is active',get_stylesheet()==='muneeb-mart'&&wp_get_theme()->get('Name')==='MUNEEB MART');
$check('Renamed companion plugin is active',in_array('muneeb-mart-core/muneeb-mart-core.php',get_option('active_plugins',[]),true));
$check('All 20 product names use MUNEEB',count($products)===20&&count(array_filter($products,fn($p)=>str_starts_with($p->get_name(),'MUNEEB ')))===20);
$check('All product URLs use the corrected prefix',count(array_filter($products,fn($p)=>str_contains($p->get_permalink(),'/product/muneeb-')))===20);
$check('WooCommerce email sender uses MUNEEB MART',get_option('woocommerce_email_from_name')==='MUNEEB MART');
$check('Local database name corrected',DB_NAME==='muneeb_mart');
$admin=get_user_by('login','muneeb_admin');$check('Owner administrator ID and role preserved',$admin&&$admin->ID===1&&in_array('administrator',$admin->roles,true));
$orders=wc_get_orders(['limit'=>-1]);$testOrders=array_filter($orders,fn($order)=>$order->get_meta('_mm_test_order')==='yes');$original=wc_get_order(59);$check('Original test order preserved',$original&&$original->get_meta('_mm_test_order')==='yes'&&(float)$original->get_total()===15000.0);
$check('Test-order item branding is correct',!array_filter($testOrders,function($order){foreach($order->get_items() as $item)if(!str_starts_with($item->get_name(),'MUNEEB '))return true;return false;}));
file_put_contents(__DIR__.'/../.local/qa-brand.json',json_encode($checks,JSON_PRETTY_PRINT));
file_put_contents(__DIR__.'/../docs/local-status.json',json_encode(['brand'=>'MUNEEB MART','website_status'=>'Local development installation; public hosting not yet verified','local_url'=>home_url('/'),'admin_url'=>admin_url(),'repository'=>'https://github.com/zeshanayub044-sudo/muneeb-mart','repository_visibility'=>'private','branch'=>'main','wordpress_version'=>get_bloginfo('version'),'woocommerce_version'=>WC_VERSION,'theme'=>get_stylesheet(),'theme_version'=>wp_get_theme()->get('Version'),'products'=>count($products),'collections'=>count(get_terms(['taxonomy'=>'product_cat','hide_empty'=>false,'exclude'=>[get_option('default_product_cat')]])),'test_orders_preserved'=>count($testOrders),'payment'=>'Development test gateway; no payment collected','checked_at'=>wp_date('c')],JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
exit(count(array_filter($checks,fn($c)=>!$c['pass']))?1:0);
