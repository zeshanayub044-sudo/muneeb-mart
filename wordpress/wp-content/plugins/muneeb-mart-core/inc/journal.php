<?php
/** Native WordPress editorial content and portable SEO fields. */
defined('ABSPATH') || exit;
add_action('init', function () {
    foreach (['post','page','product'] as $type) {
        foreach (['_mm_seo_title','_mm_meta_description'] as $key) {
            register_post_meta($type,$key,['type'=>'string','single'=>true,'show_in_rest'=>true,'sanitize_callback'=>'sanitize_text_field','auth_callback'=>function($allowed,$key,$id){return current_user_can('edit_post',$id);}]);
        }
    }
});
add_action('add_meta_boxes',function(){add_meta_box('mm-editorial-seo','MUNEEB MART · Search appearance','mm_seo_box',['post','page','product'],'normal','default');});
function mm_seo_box($post){
    wp_nonce_field('mm_seo_save','mm_seo_nonce');
    echo '<p>Optional search title and description. Search engines may choose different text. These fields also work with the block editor.</p>';
    foreach(['_mm_seo_title'=>'SEO title','_mm_meta_description'=>'Meta description'] as $key=>$label)echo '<p><label for="'.esc_attr($key).'">'.esc_html($label).'</label><br><input class="widefat" id="'.esc_attr($key).'" name="'.esc_attr($key).'" value="'.esc_attr(get_post_meta($post->ID,$key,true)).'" maxlength="'.($key==='_mm_seo_title'?120:320).'"></p>';
}
add_action('save_post',function($id){
    if(defined('DOING_AUTOSAVE')&&DOING_AUTOSAVE || wp_is_post_revision($id) || !current_user_can('edit_post',$id) || !isset($_POST['mm_seo_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['mm_seo_nonce'])),'mm_seo_save'))return;
    foreach(['_mm_seo_title','_mm_meta_description'] as $key)if(isset($_POST[$key]))update_post_meta($id,$key,sanitize_text_field(wp_unslash($_POST[$key])));
});
add_filter('pre_get_document_title',function($title){if(defined('WPSEO_VERSION')||defined('RANK_MATH_VERSION'))return $title;$custom=is_singular()?get_post_meta(get_queried_object_id(),'_mm_seo_title',true):'';return $custom?:$title;});
function mm_seed_journal(){
    $starter=get_page_by_path('hello-world',OBJECT,'post');
    if($starter&&$starter->post_title==='Hello world!'&&str_contains($starter->post_content,'Welcome to WordPress')&&!get_post_meta($starter->ID,'_edit_last',true))wp_trash_post($starter->ID);
    $categories=['watch-buying-guides'=>'Watch Buying Guides','mens-watch-fashion'=>"Men's Watch Fashion",'womens-watch-fashion'=>"Women's Watch Fashion",'watch-styling-tips'=>'Watch Styling Tips','watch-care-maintenance'=>'Watch Care and Maintenance','watch-trends'=>'Watch Trends','luxury-watch-inspiration'=>'Luxury Watch Inspiration','product-comparisons'=>'Product Comparisons','gift-guides'=>'Gift Guides','muneeb-mart-updates'=>'MUNEEB MART Updates'];
    $ids=[];foreach($categories as $slug=>$name){$term=term_exists($slug,'category');if(!$term)$term=wp_insert_term($name,'category',['slug'=>$slug]);if(!is_wp_error($term))$ids[$slug]=(int)$term['term_id'];}
    $blog=get_page_by_path('journal');if(!$blog)$blog=get_post(wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_name'=>'journal','post_title'=>'The MUNEEB MART Journal']));
    if($blog)update_option('page_for_posts',$blog->ID);
    update_option('posts_per_page',6);
    $files=['journal-guides.json','journal-care.json'];$count=0;
    foreach($files as $file){$rows=json_decode(file_get_contents(MM_CORE_DIR.'data/'.$file),true);if(!is_array($rows))continue;
        foreach($rows as $row){
            // Idempotent by slug; never overwrite an owner's posts or editorial changes.
            if(get_page_by_path($row['slug'],OBJECT,'post'))continue;
            $content=str_replace('{{home}}',untrailingslashit(home_url()),$row['content']);
            $id=wp_insert_post(wp_slash(['post_type'=>'post','post_status'=>'publish','post_name'=>$row['slug'],'post_title'=>$row['title'],'post_excerpt'=>$row['excerpt'],'post_content'=>wp_kses_post($content),'post_author'=>get_current_user_id()?:1,'post_category'=>[$ids[$row['category']]??$ids['watch-buying-guides']],'tags_input'=>$row['tags'],'comment_status'=>'closed','ping_status'=>'closed','post_date'=>wp_date('Y-m-d H:i:s',time()-(count($categories)-$count)*DAY_IN_SECONDS)]));
            if(!$id||is_wp_error($id))continue;
            $image=mm_demo_image($row['image'].'.webp',$row['image_alt']);if($image)set_post_thumbnail($id,$image);
            update_post_meta($id,'_mm_featured_alt',sanitize_text_field($row['image_alt']));
            update_post_meta($id,'_mm_seo_title',sanitize_text_field($row['seo_title']));update_post_meta($id,'_mm_meta_description',sanitize_text_field($row['description']));update_post_meta($id,'_mm_recommend_collections',array_map('sanitize_title',$row['collections']));update_post_meta($id,'_mm_demo_article',1);$count++;
        }
    }
    $menu=wp_get_nav_menu_object('MUNEEB MART primary');if($menu&&$blog){$items=wp_get_nav_menu_items($menu->term_id)?:[];if(!array_filter($items,fn($item)=>(int)$item->object_id===$blog->ID))wp_update_nav_menu_item($menu->term_id,0,['menu-item-title'=>'Journal','menu-item-object'=>'page','menu-item-object-id'=>$blog->ID,'menu-item-type'=>'post_type','menu-item-status'=>'publish','menu-item-position'=>4]);}
    return $count;
}
function mm_seed_expansion(){
    $images=['steel'=>mm_demo_image('steel.webp','Unbranded steel watch'),'leather'=>mm_demo_image('leather.webp','Unbranded leather watch')];
    foreach(['executive'=>['Business Executive Collection','Considered watch styles for business and formal wardrobes. Explore the proportions, dial layouts and strap aesthetics that complement your everyday routine.','leather'],'budget'=>['Watches Under Budget','Explore watches by your own price range. Use the minimum and maximum price filters to set a budget; displayed prices are demonstration values.','steel']] as $slug=>$data){$term=term_exists($slug,'product_cat');if(!$term)$term=wp_insert_term($data[0],'product_cat',['slug'=>$slug,'description'=>$data[1]]);if(!is_wp_error($term)){update_term_meta((int)$term['term_id'],'thumbnail_id',$images[$data[2]]);$products=wc_get_products(['limit'=>-1,'status'=>'publish','return'=>'ids','meta_key'=>'_mm_demo','meta_value'=>'yes']);foreach($products as $id){$p=wc_get_product($id);if($slug==='executive'&&preg_match('/Executive|Heritage|Signature/i',$p->get_name())||$slug==='budget'&&(float)$p->get_price()<=9000)wp_set_object_terms($id,[(int)$term['term_id']],'product_cat',true);}}}
    $pages=json_decode(file_get_contents(MM_CORE_DIR.'data/support-pages.json'),true);
    foreach($pages as $slug=>$page)if(!get_page_by_path($slug))wp_insert_post(wp_slash(['post_type'=>'page','post_status'=>'publish','post_name'=>$slug,'post_title'=>$page['title'],'post_content'=>wp_kses_post(str_replace('{{home}}',untrailingslashit(home_url()),$page['content']))]));
    mm_seed_journal();flush_rewrite_rules();
}
add_shortcode('mm_featured_edit',function(){return do_shortcode('[products visibility="featured" limit="8" columns="4"]');});
