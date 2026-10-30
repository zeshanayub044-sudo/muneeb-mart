<?php defined('ABSPATH') || exit;get_header(); ?>
<main id="main" class="journal-main container">
<header class="journal-heading"><p class="eyebrow">MUNEEB MART · STYLE, TIME & ELEGANCE</p><h1><?php if(is_home())echo 'The MUNEEB MART Journal';elseif(is_search())echo 'Journal search: '.esc_html(get_search_query());else the_archive_title(); ?></h1><p><?php echo is_home()?'A considered perspective on watches, personal style, and the details that make a difference.':esc_html(wp_strip_all_tags(get_the_archive_description())); ?></p></header>
<?php mm_journal_search();mm_journal_categories(); ?>
<?php if(have_posts()):$first=true; ?><div class="journal-grid journal-archive-grid"><?php while(have_posts()):the_post();if($first&&is_home()&&!is_paged()):$first=false; ?>
<article class="journal-feature"><a class="journal-feature-image" href="<?php the_permalink(); ?>"><?php if(has_post_thumbnail())the_post_thumbnail('large',['alt'=>mm_journal_image_alt()]); ?></a><div><p class="eyebrow">THE EDITORIAL PICK</p><?php mm_post_meta(); ?><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><p><?php echo esc_html(get_the_excerpt()); ?></p><a class="text-link" href="<?php the_permalink(); ?>">Read article <?php echo mm_icon('arrow'); ?></a></div></article>
<?php else:mm_journal_card();endif;endwhile; ?></div><?php the_posts_pagination(['mid_size'=>1,'prev_text'=>'← Previous','next_text'=>'Next →']);else: ?>
<div class="empty-state"><h2>A fresh perspective is on its way.</h2><p>No articles match this selection yet. Explore another category or browse the complete Journal.</p><a class="button" href="<?php echo esc_url(mm_journal_url()); ?>">All articles</a></div><?php endif; ?>
</main><?php get_footer(); ?>
