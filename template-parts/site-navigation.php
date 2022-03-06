<nav id="site-navigation" class="main-navigation" data-menu-space="120">
    <div id="primary-navbar">
    	<?php
        
        $post_type = get_post_type( get_the_ID());
        if(!is_cart() && !is_checkout() && $post->post_name != "premium" && ($post_type != "sfwd-topic" && $post_type != "sfwd-lessons" && $post_type != "sfwd-quiz" ))
        {
        	wp_nav_menu( array(
        		'theme_location' => 'header-menu',
        		'menu_id'		 => 'primary-menu',
        		'container'		 => false,
        		'fallback_cb'	 => '',
        		'menu_class'	 => 'primary-menu bb-primary-overflow', )
        	);
        }?>
        
        <div id="navbar-collapse">
            <a class="more-button" href="#"><i class="bb-icon-menu-dots-h"></i></a>
            <ul id="navbar-extend" class="sub-menu"></ul>
        </div>
    </div>
</nav>