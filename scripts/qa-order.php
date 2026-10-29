<?php
if(PHP_SAPI!=='cli')exit(1);
require __DIR__.'/../.local/wordpress/wp-load.php';
$checks=[];$check=function($test,$pass)use(&$checks){$checks[]=['test'=>$test,'pass'=>(bool)$pass];echo ($pass?'PASS':'FAIL').': '.$test."\n";};
$orders=wc_get_orders(['limit'=>10,'orderby'=>'date','order'=>'DESC']);$found=null;foreach($orders as $order)if($order->get_meta('_mm_test_order')==='yes'){$found=$order;break;}
$check('Checkout order exists in WooCommerce administration',(bool)$found);
if($found){$check('Test order is on-hold, unpaid and labelled',$found->get_status()==='on-hold'&&!$found->is_paid()&&$found->get_payment_method()==='mm_test');$check('Correct total and two ordered items',(float)$found->get_total()===15000.0&&$found->get_item_count()===2);$fixtures=json_decode(file_get_contents(__DIR__.'/../.local/qa-fixtures.json'),true);$stock=wc_get_product(wc_get_product_id_by_sku('MM-DEMO-001'))->get_stock_quantity();$check('Inventory reduced by the two ordered watches',$stock===$fixtures['stock_before']-2);echo 'Development order #'.$found->get_id().' (no payment collected). Stock: '.$fixtures['stock_before'].' → '.$stock."\n";}
$enquiries=get_posts(['post_type'=>'mm_enquiry','post_status'=>'private','meta_key'=>'_mm_email','meta_value'=>'qa@example.invalid']);$subs=get_posts(['post_type'=>'mm_subscriber','post_status'=>'private','meta_key'=>'_mm_email','meta_value'=>'newsletter-qa@example.invalid']);
$check('Enquiry persisted privately with consent',(bool)$enquiries&&get_post_meta($enquiries[0]->ID,'_mm_consent_at',true));$check('Newsletter consent persisted privately',(bool)$subs&&get_post_meta($subs[0]->ID,'_mm_consent_at',true));
file_put_contents(__DIR__.'/../.local/qa-order-verified.json',json_encode($checks,JSON_PRETTY_PRINT));exit(count(array_filter($checks,fn($c)=>!$c['pass']))?1:0);
