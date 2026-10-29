<?php defined('ABSPATH') || exit;
function mm_filter_form(){
    $value=static fn($key)=>isset($_GET[$key])?sanitize_text_field(wp_unslash($_GET[$key])):'';
    echo '<form method="get" action="'.esc_url(get_permalink(wc_get_page_id('shop'))).'">';
    echo '<div class="filter-field"><label for="filter-search">Search the collection</label><input id="filter-search" type="search" name="s" value="'.esc_attr($value('s')).'" placeholder="Name or style"><input type="hidden" name="post_type" value="product"></div>';
    $terms=get_terms(['taxonomy'=>'product_cat','hide_empty'=>true]);
    $category=$value('product_cat');if(!$category&&is_product_category())$category=get_queried_object()->slug;
    echo '<div class="filter-field"><label for="collection-filter">Collection</label><select id="collection-filter" name="product_cat"><option value="">All collections</option>';if(!is_wp_error($terms))foreach($terms as $term)if($term->slug!=='uncategorized')echo '<option value="'.esc_attr($term->slug).'" '.selected($category,$term->slug,false).'>'.esc_html($term->name).'</option>';echo '</select></div>';
    foreach(['pa_watch-type'=>'Watch style','pa_strap-material'=>'Strap material','pa_dial-color'=>'Dial color','pa_case-color'=>'Case color'] as $taxonomy=>$label){$key='filter_'.str_replace('pa_','',$taxonomy);$options=get_terms(['taxonomy'=>$taxonomy,'hide_empty'=>true]);if(is_wp_error($options)||!$options)continue;echo '<div class="filter-field"><label for="'.esc_attr($key).'">'.esc_html($label).'</label><select id="'.esc_attr($key).'" name="'.esc_attr($key).'"><option value="">All '.esc_html(strtolower($label)).'s</option>';foreach($options as $term)echo '<option value="'.esc_attr($term->slug).'" '.selected($value($key),$term->slug,false).'>'.esc_html($term->name).'</option>';echo '</select></div>';}
    foreach(['min_price'=>'Minimum price','max_price'=>'Maximum price'] as $key=>$label)echo '<div class="filter-field"><label for="'.esc_attr($key).'">'.esc_html($label).' ('.esc_html(get_woocommerce_currency()).')</label><input id="'.esc_attr($key).'" type="number" name="'.esc_attr($key).'" min="0" step="100" value="'.esc_attr($value($key)).'" placeholder="'.($key==='min_price'?'0':'Any').'"></div>';
    echo '<div class="filter-field"><label><input type="checkbox" name="in_stock" value="1" '.checked($value('in_stock'),'1',false).'> In stock only</label></div>';
    if($value('orderby'))echo '<input type="hidden" name="orderby" value="'.esc_attr($value('orderby')).'">';
    echo '<button class="button" type="submit">Apply filters</button><a class="filter-clear" href="'.esc_url(wc_get_page_permalink('shop')).'">Clear all filters</a></form>';
}
add_action('woocommerce_product_query',function($query){
    if(is_admin())return;
    $tax=(array)$query->get('tax_query');
    foreach(['watch-type','strap-material','dial-color','case-color'] as $attribute){$key='filter_'.$attribute;if(!empty($_GET[$key]))$tax[]=['taxonomy'=>'pa_'.$attribute,'field'=>'slug','terms'=>array_filter(array_map('sanitize_title',explode(',',wp_unslash($_GET[$key]))))];}
    $query->set('tax_query',$tax);
    if(is_product_category('new-arrivals')&&!isset($_GET['orderby'])){$query->set('orderby','date');$query->set('order','DESC');}
    if(isset($_GET['in_stock'])&&$_GET['in_stock']==='1'){$meta=(array)$query->get('meta_query');$meta[]=['key'=>'_stock_status','value'=>'instock'];$query->set('meta_query',$meta);}
});
add_action('woocommerce_new_product',function($id){$term=get_term_by('slug','new-arrivals','product_cat');if($term)wp_set_object_terms($id,[(int)$term->term_id],'product_cat',true);});
add_shortcode('mm_collections',function(){
    if(!function_exists('wc_get_products'))return '<p>Activate WooCommerce to browse collections.</p>';
    $terms=get_terms(['taxonomy'=>'product_cat','hide_empty'=>false,'exclude'=>[get_option('default_product_cat')]]);if(is_wp_error($terms))return '';
    ob_start();echo '<div class="collection-landing-grid">';foreach($terms as $term){$image=wp_get_attachment_image(get_term_meta($term->term_id,'thumbnail_id',true),'medium_large',false,['loading'=>'lazy']);echo '<article><a href="'.esc_url(get_term_link($term)).'">'.$image.'<h2>'.esc_html($term->name).'</h2></a><p>'.esc_html(wp_trim_words($term->description,24)).'</p><a class="text-link" href="'.esc_url(get_term_link($term)).'">Explore the collection →</a></article>';}echo '</div>';return ob_get_clean();
});
add_shortcode('mm_wishlist',fn()=>'<p>Save your favourites with the heart icon. Your wishlist is stored in this browser.</p><div id="wishlist-products" class="wishlist-grid" aria-live="polite"></div><p><a class="text-link" href="'.esc_url(wc_get_page_permalink('shop')).'">Explore all watches →</a></p>');
add_filter('body_class',function($classes){if(is_page('collections'))$classes[]='collections-page';return $classes;});
