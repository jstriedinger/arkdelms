<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	
    <div class="field has-addons">
        <div class="control">
            <label>
                <span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'buddyboss-theme' ); ?></span>
                <input type="search" class="search-field-top" placeholder="<?php echo esc_attr( apply_filters( 'search_placeholder', __( 'Search', 'buddyboss-theme' ) ) ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                <input type="hidden" name="post_type" value="post" />
            </label>
        </div>
        <div class="control">
            <input class="button is-success" type="submit" value="Buscar">
            
            </input>
        </div>
    </div>
</form>
