<?php
if(PHP_SAPI!=='cli')exit(1);
require __DIR__.'/../.local/wordpress/wp-load.php';
if(!function_exists('mm_seed_expansion'))throw new RuntimeException('Sync custom code first.');
wp_set_current_user(1);
mm_seed_expansion();
update_option('timezone_string','Asia/Karachi');
// Replace only the original bundled headline, preserving later owner edits.
if(get_theme_mod('mm_hero_title','Time is the ultimate luxury.')==='Time is the ultimate luxury.')set_theme_mod('mm_hero_title','Timeless elegance. Every second.');
echo "Native Journal, additional collections and support pages prepared. Existing orders, product stock, accounts and edited content preserved.\n";
