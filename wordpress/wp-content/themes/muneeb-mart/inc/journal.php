<?php defined('ABSPATH') || exit;
function mm_journal_url(){return get_permalink((int)get_option('page_for_posts'))?:home_url('/journal/');}
function mm_journal_image_alt(){
    $image=get_post_thumbnail_id();$alt=get_post_meta(get_the_ID(),'_mm_featured_alt',true);
    if($alt&&get_post_meta($image,'_mm_demo_asset',true))return $alt;
    return get_post_meta($image,'_wp_attachment_image_alt',true)?:get_the_title();
}
function mm_post_meta(){
    $cats=get_the_category();if($cats)echo '<a class="eyebrow" href="'.esc_url(get_category_link($cats[0])).'">'.esc_html($cats[0]->name).'</a>';
    echo '<div class="journal-meta"><span>'.esc_html(get_the_author()).'</span><span aria-hidden="true">·</span><time datetime="'.esc_attr(get_the_date('c')).'">'.esc_html(get_the_date('j F Y')).'</time><span aria-hidden="true">·</span><span>'.esc_html(max(1,(int)ceil(str_word_count(wp_strip_all_tags(get_the_content()))/220))).' min read</span></div>';
}
function mm_journal_card(){ ?>
    <article <?php post_class('journal-card'); ?>>
    <a class="journal-card-image" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php if(has_post_thumbnail())the_post_thumbnail('medium_large',['loading'=>'lazy','alt'=>mm_journal_image_alt()]);else echo '<img src="'.esc_url(mm_image('editorial.webp')).'" alt="" width="900" height="600" loading="lazy">'; ?></a>
    <div class="journal-card-copy"><?php mm_post_meta(); ?><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><p><?php echo esc_html(wp_trim_words(get_the_excerpt(),27)); ?></p><a class="text-link" href="<?php the_permalink(); ?>">Read article <?php echo mm_icon('arrow'); ?></a></div></article>
<?php }
function mm_latest_journal(){
    $posts=new WP_Query(['post_type'=>'post','posts_per_page'=>3,'post_status'=>'publish','ignore_sticky_posts'=>true,'no_found_rows'=>true]);if(!$posts->have_posts())return;
    echo '<section class="section journal-preview container"><div class="section-heading"><div><p class="eyebrow">STYLE, TIME & ELEGANCE</p><h2>The Watch Journal</h2></div><a class="text-link" href="'.esc_url(mm_journal_url()).'">Explore the journal '.mm_icon('arrow').'</a></div><div class="journal-grid">';while($posts->have_posts()){$posts->the_post();mm_journal_card();}echo '</div></section>';wp_reset_postdata();
}
function mm_journal_search(){ ?>
    <form role="search" class="journal-search" action="<?php echo esc_url(home_url('/')); ?>" method="get"><label for="journal-search">Search the Journal</label><div><input id="journal-search" type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="Care, style, gifts…" required><input type="hidden" name="post_type" value="post"><button class="button" type="submit">Search</button></div></form>
<?php }
function mm_journal_categories(){
    echo '<nav class="journal-categories" aria-label="Journal categories"><a href="'.esc_url(mm_journal_url()).'"'.(is_home()?' aria-current="page"':'').'>All articles</a>';
    $terms=get_terms(['taxonomy'=>'category','hide_empty'=>false,'exclude'=>[(int)get_option('default_category')]]);if(!is_wp_error($terms))foreach($terms as $term)echo '<a href="'.esc_url(get_term_link($term)).'"'.(is_category($term->term_id)?' aria-current="page"':'').'>'.esc_html($term->name).'</a>';echo '</nav>';
}
/** Unique heading anchors keep contents links stable, including repeated headings. */
function mm_article_sections($content){
    $sections=[];$seen=[];$content=preg_replace_callback('/<h([23])([^>]*)>(.*?)<\/h\1>/is',function($m)use(&$sections,&$seen){$label=wp_strip_all_tags($m[3]);$base=sanitize_title($label)?:'section';$seen[$base]=($seen[$base]??0)+1;$id=$base.($seen[$base]>1?'-'.$seen[$base]:'');$attrs=preg_replace('/\s+id\s*=\s*("[^"]*"|\x27[^\x27]*\x27)/i','',$m[2]);$sections[]=['id'=>$id,'label'=>$label,'level'=>$m[1]];return '<h'.$m[1].$attrs.' id="'.esc_attr($id).'">'.$m[3].'</h'.$m[1].'>';},$content);return [$content,$sections];
}
add_action('wp_enqueue_scripts',function(){wp_enqueue_style('mm-journal',get_theme_file_uri('/assets/css/journal.css'),['mm-store'],MM_THEME_VERSION);});
