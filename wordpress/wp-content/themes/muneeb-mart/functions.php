<?php
/** Theme presentation; commerce and content belong to WooCommerce and MUNEEB MART Core. */
defined('ABSPATH') || exit;
define('MM_THEME_VERSION', '1.1.0');
function mm_setup() {
    load_theme_textdomain('muneeb-mart', get_template_directory() . '/languages');
    add_theme_support('title-tag'); add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', ['height'=>70,'width'=>320,'flex-width'=>true,'flex-height'=>true]);
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
    add_theme_support('align-wide'); add_theme_support('responsive-embeds');
    add_theme_support('editor-styles'); add_editor_style('assets/css/store.css');
    add_theme_support('woocommerce', ['thumbnail_image_width'=>600,'single_image_width'=>1000,'product_grid'=>['default_columns'=>4,'default_rows'=>3]]);
    add_theme_support('wc-product-gallery-zoom'); add_theme_support('wc-product-gallery-lightbox'); add_theme_support('wc-product-gallery-slider');
    register_nav_menus(['primary'=>__('Primary navigation','muneeb-mart'),'footer'=>__('Footer navigation','muneeb-mart')]);
}
add_action('after_setup_theme','mm_setup');
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('mm-store', get_template_directory_uri().'/assets/css/store.css', [], MM_THEME_VERSION);
    wp_enqueue_style('mm-checkout',get_template_directory_uri().'/assets/css/checkout.css',['mm-store'],MM_THEME_VERSION);
    wp_enqueue_script('mm-store', get_template_directory_uri().'/assets/js/store.js', [], MM_THEME_VERSION, true);
    wp_localize_script('mm-store','mmStore',['catalog'=>function_exists('wc_get_page_permalink')?wc_get_page_permalink('shop'):home_url('/catalog/'),'productEndpoint'=>rest_url('wc/store/v1/products/'),'wishlist'=>home_url('/wishlist/')]);
    $accent = sanitize_hex_color(get_theme_mod('mm_accent','#b79b62'));
    wp_add_inline_style('mm-store', ':root{--gold:'.($accent ?: '#b79b62').';}');
});
function mm_icon($name, $class='') {
    $paths = ['search'=>'<circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.5 4.5"/>','bag'=>'<path d="M5 7h14l1 14H4L5 7Z"/><path d="M9 8V5a3 3 0 0 1 6 0v3"/>','heart'=>'<path d="M20.8 4.8a5.5 5.5 0 0 0-7.8 0L12 6l-1-1.2a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.4a5.5 5.5 0 0 0 0-7.8Z"/>','user'=>'<circle cx="12" cy="7.5" r="3.5"/><path d="M4.5 21v-2a7.5 7.5 0 0 1 15 0v2"/>','arrow'=>'<path d="M4 12h16m-6-6 6 6-6 6"/>','menu'=>'<path d="M4 6h16M4 12h16M4 18h16"/>','close'=>'<path d="m5 5 14 14M5 19 19 5"/>','box'=>'<path d="m3 7 9-4 9 4-9 4-9-4Zm0 0v10l9 4 9-4V7M12 11v10M7 5l10 5"/>','shield'=>'<path d="M12 2 3 6v6c0 6 9 10 9 10s9-4 9-10V6l-9-4Z"/><path d="m8 12 3 3 5-6"/>','chat'=>'<path d="M21 11a9 9 0 0 1-9 9H3l2-5a9 9 0 1 1 16-4Z"/>','watch'=>'<rect x="6" y="6" width="12" height="12" rx="6"/><path d="M9 6V2h6v4M9 18v4h6v-4M12 9v4h3"/>'];
    return '<svg class="icon '.esc_attr($class).'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.($paths[$name] ?? $paths['arrow']).'</svg>';
}
function mm_image($file) { return get_template_directory_uri().'/assets/images/'.$file; }
function mm_mod($key,$default='') { return get_theme_mod('mm_'.$key,$default); }
function mm_collection_url($slug) { $term=get_term_by('slug',$slug,'product_cat'); return $term && !is_wp_error($term) ? get_term_link($term) : home_url('/collections/'); }
function mm_primary_fallback() { foreach([''=>'Home','catalog/'=>'Catalog','collections/'=>'Collections','about-us/'=>'About Us','contact-us/'=>'Contact'] as $url=>$label) echo '<a href="'.esc_url(home_url('/'.$url)).'"'.((($url===''&&is_front_page())||($url&&is_page(trim($url,'/')))||($url==='catalog/'&&function_exists('is_shop')&&is_shop()))?' aria-current="page"':'').'>'.esc_html($label).'</a>'; }
function mm_products($args=[]) {
    if(!function_exists('wc_get_products')) return;
    $items=wc_get_products(array_merge(['status'=>'publish','limit'=>4,'orderby'=>'date','order'=>'DESC','visibility'=>'catalog'],$args));
    woocommerce_product_loop_start(); foreach($items as $item) { $post=get_post($item->get_id()); setup_postdata($GLOBALS['post']=$post); wc_get_template_part('content','product'); } wp_reset_postdata(); woocommerce_product_loop_end();
}
add_filter('woocommerce_enqueue_styles','__return_empty_array');
remove_action('woocommerce_before_main_content','woocommerce_output_content_wrapper',10);
remove_action('woocommerce_after_main_content','woocommerce_output_content_wrapper_end',10);
remove_action('woocommerce_sidebar','woocommerce_get_sidebar',10);
add_action('woocommerce_before_main_content',function(){echo '<main id="main" class="container commerce-main">';},10);
add_action('woocommerce_after_main_content',function(){echo '</main>';},10);
add_filter('loop_shop_columns',fn()=>4);
add_filter('loop_shop_per_page',fn()=>12);
add_action('woocommerce_before_shop_loop_item',function(){global $product; echo '<button type="button" class="wish-toggle" data-wish="'.esc_attr($product->get_id()).'" aria-label="'.esc_attr(sprintf(__('Save %s to wishlist','muneeb-mart'),$product->get_name())).'" aria-pressed="false">'.mm_icon('heart').'</button>';},5);
add_action('woocommerce_after_shop_loop_item_title',function(){global $product; echo '<span class="card-attribute">'.esc_html($product->get_attribute('Strap material')).'</span>';},6);
add_filter('woocommerce_add_to_cart_fragments',function($fragments){$fragments['.bag-count']='<span class="bag-count">'.WC()->cart->get_cart_contents_count().'</span>';return $fragments;});
add_action('woocommerce_before_cart',function(){echo '<p><a class="text-link" href="'.esc_url(wc_get_page_permalink('shop')).'">← Continue shopping</a></p>';});
add_action('woocommerce_single_product_summary',function(){global $product;echo '<button type="button" class="text-link product-wish" data-wish="'.esc_attr($product->get_id()).'" aria-pressed="false">'.mm_icon('heart').' Save this watch</button>';},35);
add_filter('woocommerce_structured_data_product',function($data){return get_option('mm_demo_mode')?[]:$data;});
add_filter('woocommerce_product_tabs',function($tabs){
    if(isset($tabs['reviews']) && get_comments_number()===0) unset($tabs['reviews']);
    $tabs['care']=['title'=>__('Care & guidance','muneeb-mart'),'priority'=>40,'callback'=>function(){echo '<h2>Care & guidance</h2><p>Keep your watch away from extreme temperatures, chemicals, and moisture unless its verified specifications permit exposure. Clean gently with a dry soft cloth. Have servicing carried out by a qualified professional.</p><p>Demo specifications and imagery are illustrative. Confirm final product details before purchasing.</p>'; }];
    $tabs['shipping']=['title'=>__('Shipping & returns','muneeb-mart'),'priority'=>30,'callback'=>function(){echo '<h2>Shipping & returns</h2><p>Available shipping methods and costs are shown at checkout when configured.</p><p><a href="'.esc_url(home_url('/shipping-policy/')).'">Shipping policy</a> · <a href="'.esc_url(home_url('/return-refund-policy/')).'">Returns policy</a></p>';}]; return $tabs;
});
add_filter('woocommerce_catalog_orderby',function($options){return ['date'=>'Newest arrivals','price'=>'Price: low to high','price-desc'=>'Price: high to low','title'=>'Name: A–Z'];});
add_filter('woocommerce_default_catalog_orderby',fn($value)=>$value==='menu_order'?'date':$value);
add_filter('woocommerce_redirect_single_search_result','__return_false');
add_filter('woocommerce_get_catalog_ordering_args',function($args){if(isset($_GET['orderby'])&&$_GET['orderby']==='title') {$args['orderby']='title';$args['order']='ASC';}return $args;});
add_action('customize_register',function($c){
    $c->add_section('mm_editorial',['title'=>'MUNEEB MART · Store design','priority'=>30]);
    $fields=['announcement'=>['Announcement','TIMELESS STYLE. MODERN ELEGANCE.'],'eyebrow'=>['Hero eyebrow','THE ART OF KEEPING TIME'],'hero_title'=>['Hero headline','Time is the ultimate luxury.'],'hero_text'=>['Hero description','Exceptional watches. Considered design. Find a timepiece that feels unmistakably you.'],'hero_cta'=>['Hero button','Explore the collection'],'curated_title'=>['Collection headline','A watch for every version of you.'],'selection_title'=>['Featured product heading','Considered. Curated. Yours.'],'story_title'=>['Story headline','More than a watch. A statement.'],'story_text'=>['Story paragraph','A watch is personal. It speaks to who you are, how you move, and what matters to you. At MUNEEB MART, we bring together distinctive designs for a style that is entirely your own.'],'campaign_title'=>['Campaign headline','Find your signature.'],'footer_text'=>['Footer description','Thoughtfully curated watches for the moments that make you.'],'support_email'=>['Published support email',''],'phone'=>['Published phone / WhatsApp',''],'hours'=>['Published business hours',''],'instagram'=>['Instagram URL',''],'facebook'=>['Facebook URL','']];
    $fields=array_merge($fields,['arrivals_title'=>['New arrivals heading','New to the collection.'],'newsletter_title'=>['Newsletter heading','The art of timeless style.'],'newsletter_text'=>['Newsletter description','New collections, thoughtful edits, and a little inspiration.'],'men_title'=>['Men’s collection card title','For him.'],'men_text'=>['Men’s collection card description','Distinctive by nature.'],'women_title'=>['Women’s collection card title','For her.'],'women_text'=>['Women’s collection card description','Elegance in every detail.'],'classic_title'=>['Classic collection card title','The classics.'],'classic_text'=>['Classic collection card description','Always in good taste.']]);
    foreach($fields as $id=>$field){$c->add_setting('mm_'.$id,['default'=>$field[1],'sanitize_callback'=>in_array($id,['instagram','facebook'],true)?'esc_url_raw':($id==='support_email'?'sanitize_email':'sanitize_text_field')]);$c->add_control('mm_'.$id,['section'=>'mm_editorial','label'=>$field[0],'type'=>in_array($id,['hero_text','story_text','footer_text'],true)?'textarea':'text']);}
    $c->add_setting('mm_accent',['default'=>'#b79b62','sanitize_callback'=>'sanitize_hex_color']);$c->add_control(new WP_Customize_Color_Control($c,'mm_accent',['label'=>'Accent color','section'=>'mm_editorial']));
    foreach(['hero_image'=>'Hero photograph','story_image'=>'Story photograph'] as $id=>$label){$c->add_setting('mm_'.$id,['sanitize_callback'=>'esc_url_raw']);$c->add_control(new WP_Customize_Image_Control($c,'mm_'.$id,['section'=>'mm_editorial','label'=>$label]));}
});
add_action('wp_head',function(){
    if(!has_site_icon())echo '<link rel="icon" type="image/svg+xml" href="'.esc_url(mm_image('favicon.svg')).'">';
    if(get_option('mm_demo_mode')) echo '<meta name="robots" content="noindex,nofollow">';
    if(!defined('WPSEO_VERSION')&&!defined('RANK_MATH_VERSION')) { $description=is_front_page()?'Discover thoughtfully curated watches at MUNEEB MART. Classic silhouettes, contemporary details, and a style of your own.':wp_strip_all_tags(get_the_excerpt());if(is_tax())$description=wp_strip_all_tags(term_description());if(function_exists('is_shop')&&is_shop())$description='Explore the MUNEEB MART watch collection. Filter by style, strap, dial, finish, and price to find your signature timepiece.';if($description) echo '<meta name="description" content="'.esc_attr(wp_trim_words($description,30,'')).'">'; }
    if(!defined('WPSEO_VERSION')&&!defined('RANK_MATH_VERSION')) {
        $url=is_singular()?get_permalink():home_url('/');
        if(function_exists('is_shop')&&is_shop())$url=wc_get_page_permalink('shop');
        elseif(function_exists('is_product_taxonomy')&&is_product_taxonomy()){$term_url=get_term_link(get_queried_object());if(!is_wp_error($term_url))$url=$term_url;}
        if(!is_singular()&&!is_search()&&!is_404())echo '<link rel="canonical" href="'.esc_url($url).'">';
        echo '<meta property="og:title" content="'.esc_attr(wp_get_document_title()).'"><meta property="og:type" content="website"><meta property="og:url" content="'.esc_url($url).'"><meta property="og:site_name" content="MUNEEB MART"><meta property="og:image" content="'.esc_url(is_singular()&&has_post_thumbnail()?get_the_post_thumbnail_url(null,'large'):mm_image('hero.webp')).'">';
    }
});
