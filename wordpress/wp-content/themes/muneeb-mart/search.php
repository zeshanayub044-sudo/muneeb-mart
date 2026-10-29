<?php
defined('ABSPATH') || exit;
if(function_exists('WC')&&get_query_var('post_type')==='product')require get_template_directory().'/woocommerce/archive-product.php';
else require get_template_directory().'/index.php';
