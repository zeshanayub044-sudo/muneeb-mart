<?php
if(PHP_SAPI!=='cli')exit;
require __DIR__.'/../.local/wordpress/wp-load.php';
$result=mm_seed_store();if(is_wp_error($result)){echo $result->get_error_message();exit(1);}echo "Local demo setup refreshed without replacing products or edited pages.\n";
