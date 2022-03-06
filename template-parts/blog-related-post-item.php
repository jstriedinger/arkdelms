<?php 
global $post;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class()?>>
    <a href="<?php the_permalink()?>" class="has-card" title="Blog post <?php echo the_title_attribute( 'echo=0' ); ?>">
        <div class="card blog-item">
           <div class="card-background" style="background-image: url(<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(),'full'))?>)">
           </div>
           <div class="card-content">
               <div class="tags is-marginless-bottom">
                   <?php
                   if(has_tag())
                   {
                        foreach(get_the_tags() as $tag) { ?>
                            <span class="tag is-light"><?php echo $tag->name ?></span>
                            
                <?php     }

                   }
                   ?>
               </div>
                <header class="entry-header">
                    <h2 class="subtitle is-size-4 has-text-weight-bold">
                        <?php the_title()?>
                    </h2>
                </header><!-- .entry-header -->
                <div class="content is-small">
                    <i class="bb-icon-clock"></i> <?php echo get_the_date('j M, Y')?>
                </div>
                
           </div>
        </div>
    </a>
</article>