<?php
/**
 * @package BuddyBoss Child
 * The parent theme functions are located at /buddyboss-theme/inc/theme/functions.php
 * Add your own functions at the bottom of this file.
 */






/**
 * Sets up theme for translation
 *
 * @since BuddyBoss Child 1.0.0
 */
function buddyboss_theme_child_languages()
{
  /**
   * Makes child theme available for translation.
   * Translations can be added into the /languages/ directory.
   */

  // Translate text from the PARENT theme.
  load_theme_textdomain( 'buddyboss-theme', get_stylesheet_directory() . '/languages' );

  // Translate text from the CHILD theme only.
  // Change 'buddyboss-theme' instances in all child theme files to 'buddyboss-theme-child'.
  // load_theme_textdomain( 'buddyboss-theme-child', get_stylesheet_directory() . '/languages' );

}
add_action( 'after_setup_theme', 'buddyboss_theme_child_languages' );
// Remove customizer CSS output
add_action( 'after_setup_theme', 'remove_buddyboss_customizer_css') ;
function remove_buddyboss_customizer_css() {
  remove_action('wp_head', 'buddyboss_customizer_css');
}

/**
 * Enqueues scripts and styles for child theme front-end.
 *
 * @since Boss Child Theme  1.0.0
 */
function buddyboss_theme_child_scripts_styles()
{
  /**
   * Scripts and Styles loaded by the parent theme can be unloaded if needed
   * using wp_deregister_script or wp_deregister_style.
   *
   * See the WordPress Codex for more information about those functions:
   * http://codex.wordpress.org/Function_Reference/wp_deregister_script
   * http://codex.wordpress.org/Function_Reference/wp_deregister_style
   **/

  // Styles
  wp_enqueue_style( 'buddyboss-arkde-css', get_stylesheet_directory_uri().'/dist/css/arkde.css', '', '1.1.0' );

  // Javascript
  wp_enqueue_script( 'buddyboss-arkde-js', get_stylesheet_directory_uri().'/dist/js/arkde.js', '', '1.1.0' );
  wp_enqueue_script( 'font-awesome-js', "https://kit.fontawesome.com/b5bc26307c.js", '', '1.0.0' );

  //lets remove the WP jquery since is loaded from Buddyboss platform
  //wp_deregister_script('jquery');
  wp_dequeue_script('jquery');

  //Buddyboss PRO JQuery countdown is a STUPID add on so lets remove so we can add the real fucking thing
  wp_dequeue_script( 'jquery-countdown', trailingslashit( bb_platform_pro()->plugin_url ) . 'assets/js/vendor/jquery.countdown.js', array(), '1.0.1', true );
        

}
add_action( 'wp_enqueue_scripts', 'buddyboss_theme_child_scripts_styles', 9999 );

function my_login_stylesheet() {
    wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/style.css' );
}
add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );


/****************************** CUSTOM FUNCTIONS ******************************/
add_action( 'wp_loaded', 'change_WISDMreview_selector' );
 
function change_WISDMreview_selector() {
  global $rrf_ratings_settings;
  $rrf_ratings_settings['selectors'] = apply_filters(
      'rrf_rating_selectors',
      array( 'span.ratings-here' )
  );
}
function show_notification()
{
  global $template;
  global $post;
  $template_name = basename($template);
  if(!strpos($template_name,"sfwd-lessons") && !strpos($template_name,"sfwd-topic") && !is_checkout() && $post->post_name != "premium")
  {
    //not in a lesson or a topic page, find notification
    $notif = get_posts( 
    [
        'post_type' => 'promo_notification', 
        'posts_per_page' => 1
    ]);
    if(!empty($notif) )
    {
      $notif = $notif[0];

      $today = new DateTime('NOW');
      $notif_date = get_field('end_date',$notif->ID);
      $dateF = DateTime::createFromFormat('d/m/Y', $notif_date);

      if ($today <= $dateF) { 
        //show the notification
        include(locate_template('template-parts/promo_notification.php'));
      }

    }
  }

  //include(locate_template('template-parts/course_card_price.php'));
}

add_action("wp_body_open","show_notification",10,1);

// Add your own custom functions here
function show_course_price($course_id) {
  include(locate_template('template-parts/course_card_price.php'));
}
add_action( 'course_card_price', 'show_course_price',10,1 );

function show_career_price($career_id) {
  include(locate_template('template-parts/career_card_price.php'));
}
add_action( 'career_card_price', 'show_career_price',10,1 );


/**
 * Let's show a info message if the user is trying ton buy a semestral/anual subscription
 * @param  [type] $wccm_checkout_text_after [description]
 * @return [type]                           [description]
 */

//add_action( 'woocommerce_checkout_after_order_review', 'show_recurring_payments_info',10,1 );

function matched_cart_items( $search_products ) {
    $count = 0; // Initializing

    if ( ! WC()->cart->is_empty() ) 
    {
        // Loop though cart items
        foreach(WC()->cart->get_cart() as $cart_item ) 
        {
            // Handling also variable products and their products variations
            $cart_item_ids = array($cart_item['product_id'], $cart_item['variation_id']);

            // Handle a simple product Id (int or string) or an array of product Ids 
            if( ( is_array($search_products) && array_intersect($search_products, cart_item_ids) ) || ( !is_array($search_products) && in_array($search_products, $cart_item_ids) ) )
                $count++; // incrementing items count
        }
    }
    return $count; // returning matched items count 
}

add_filter( 'wc_add_to_cart_message_html', function( $string, $product_id = 0 ) {

  $start = strpos( $string, '<a href=' ) ?: 0;
  $end = strpos( $string, '</a>', $start ) ?: 0;

  return substr( $string, $end ) ?: $string;
} );

// Load Composer dependencies.
require_once __DIR__ . '/vendor/autoload.php';
// Initialize Timber.
new Timber\Timber();
Timber::$dirname = array('views');


//Write to error log
if ( ! function_exists('write_log')) {
   function write_log ( $log )  {
      if ( is_array( $log ) || is_object( $log ) ) {
         error_log( print_r( $log, true ) );
      } else {
         error_log( $log );
      }
   }
}




add_theme_support('woocommerce');

//Add image size small
add_theme_support( 'post-thumbnails' );
add_image_size( 'tiny', 100, 100 );
add_image_size( 'medium-big', 450, 400 );

function arkde_remove_checkout_fields( $fields ) {

  unset($fields['billing']['billing_company']);
  unset($fields['billing']['billing_postcode']);
  unset($fields['billing']['billing_address_1']);
  unset($fields['billing']['billing_address_2']);
  unset($fields['order']['order_comments']);
  unset($fields['billing']['billing_state']);
  unset($fields['billing']['billing_phone']);
  //unset($fields['billing']['billing_city']);
  return $fields; 

}
function arkde_show_account_notice()
{
  if(!is_user_logged_in())
  {
   //echo "<p><i class='bb-icons bb-icon-alert-thin'></i> Usaremos tu nombre y correo para crear una cuenta en ARKDE y así podrás acceder a tus cursos</p>";
  }
}
add_filter( 'woocommerce_checkout_fields' , 'arkde_remove_checkout_fields' );
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 ); 
remove_action( 'woocommerce_order_details_after_order_table', 'woocommerce_order_again_button' );
add_action( 'woocommerce_before_checkout_form', 'arkde_show_account_notice', 10 ); 

add_filter( 'bp_is_profile_cover_image_active', '__return_false' );

//create new menu item to display invoices of user

global $bp;
function profile_screen_feedback_show() {

  // call your feedback template
  locate_template( array( 'buddypress/members/single/wc_orders.php' ), true );

}
function arkde_orders_page() {
  add_action( 'bp_template_content', 'profile_screen_feedback_show' );
  bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );

}


add_filter ( 'woocommerce_account_menu_items', 'arkde_remove_my_account_links' );
function arkde_remove_my_account_links( $menu_links ){
 
  unset( $menu_links['edit-account'] ); // Remove Account details tab
  unset( $menu_links['customer-logout'] ); // Remove Logout link
 
  return $menu_links;
 
}

function woocommerce_disable_shop_page() {
    global $post;
    if($_SERVER['SERVER_ADDR'] != '127.0.0.1')
    {
      if (is_shop() || is_product()):
      global $wp_query;
      $wp_query->set_404();
      status_header(404);
      endif;
    }

}
add_action( 'wp', 'woocommerce_disable_shop_page' );


add_filter( 'woocommerce_product_add_to_cart_text', 'change_addtocart_text', 20, 2 ); 
/**
 * Change add_to_cart text if its a bootcamp
 * @param  [type] $button_text [description]
 * @param  [type] $product     [description]
 * @return [type]              [description]
 */
function change_addtocart_text( $button_text, $product ) {
    // HERE define your specific product IDs in this array
    $delivery_type = get_field("delivery_type",$product->get_id());
    $cat = get_the_terms ( $product->get_id(), 'product_cat' )[0]->slug;
    if($cat == "bootcamp")
    {
      $button_text = "Matricúlate Hoy";
      //$button_text = __('Buy Now', 'woocommerce');
    }
    else if($cat == "mentorship")
      $button_text = "Compralo Ahora";
    else if($cat == "workshop")
      $button_text = "Inscríbete Aquí";

    return $button_text;
}



function course_underdev_action( $arg1, $arg2,$arg3 = false ) {
  include(locate_template('templates/course_underdev.php'));
}
add_action( 'learndash-course-certificate-link-before', 'course_underdev_action', 10, 3 );
add_action( 'learndash-underdev', 'course_underdev_action', 10, 3 );

function add_module_content_lesson_list($lesson_id, $course_id, $user_id)
{
  include(locate_template('template-parts/module-content-in-list.php'));
}

add_action('learndash-lesson-row-topic-list-before','add_module_content_lesson_list',10,3);


function add_after_course_pensum( $course_id, $user_id ) {
  include(locate_template('template-parts/after-pensum-course.php'));
}
function add_before_course_pensum( $course_id, $user_id ) {
  include(locate_template('template-parts/before-pensum-course.php'));
}
function add_before_course_content( $course_id, $user_id ) {
  include(locate_template('template-parts/before-content-course.php'));
}
add_action( 'learndash-course-content-list-after', 'add_after_course_pensum', 10, 3 );
add_action( 'learndash-course-heading-before', 'add_before_course_pensum', 10, 3 );
add_action( 'learndash-course-certificate-link-after', 'add_before_course_content', 10, 3 );







/**
 * =========================================================================
 * Woocommerce filters and hooks and whatnot
 * =========================================================================
 */



//add_filter( 'woocommerce_available_payment_gateways', 'show_gateways_by_country_product' );

function show_gateways_by_country_product($available_gateways)
{
  if ( ! is_checkout() ) return $available_gateways;

  $items =  WC()->cart->get_cart();
  $currency =  get_woocommerce_currency();

  //by currency
  if($currency == "COP" )
  {
    unset( $available_gateways['payulatam']);
    unset( $available_gateways['ppec_paypal']);
  }
  else if($currency == "USD")
  {
    unset( $available_gateways['epayco']);
  }
  else {
    unset( $available_gateways['epayco']);
    //unset( $available_gateways['ppec_paypal']);

  }
  
  return $available_gateways;
    
}

//add_filter('woocommerce_product_get_price', 'check_premium_price', 10, 2);

//add_filter( 'woocommerce_product_add_to_cart_url', 'check_cart_existence', 10, 2 );
/**
 * If there is already the product in the cart then go to cart if its a course or go to checkout if its a bootcamp or a mentorship
 * @return [type]                  [description]
 */
function check_cart_existence( $add_to_cart_url, $product ){
 
  $terms = get_the_terms( $product->get_id(), 'product_cat' );

  //if its an individual product, is in cart and it can be purchased
  if( $product->get_sold_individually() 
    && WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( $product->id ) )
    && $product->is_purchasable()) {

      //already in cart
      if(!empty($terms))
      {
        foreach ($terms as $term) {
          
          if($term->slug == "bootcamp" || $term->slug == "mentorship")
          {
            $add_to_cart_url = wc_get_checkout_url();
            break;
          }

        }

      }
      
  }
  return $add_to_cart_url;
}

/**
 * Redirect the user to checkout when one of two conditions:
 * 1. Is a bootcamp
 * 2. Is mentorship of a course
 * @return [type]      [description]
 */
function redirect_if_conditions( $url ) {
  
  write_log("Redirect HOOK");
  if ( ! isset( $_REQUEST['add-to-cart'] ) || ! is_numeric( $_REQUEST['add-to-cart'] ) ) {
    return $url;
  }
  
  $product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_REQUEST['add-to-cart'] ) );
  
  $terms = get_the_terms( $product_id, 'product_cat' );
  if(!empty($terms))
  {
    foreach ($terms as $term) {

      if($term->slug == "mentorship" || $term->slug == "bootcamp" || $term->slug == "workshop")
      {
        $url = wc_get_checkout_url();
        break;
      }

    }
  }

  return $url;
}
add_filter( 'woocommerce_add_to_cart_redirect', 'redirect_if_conditions' );

//Lets change the Tinymce CSS styles when on frontend
add_filter( 'mce_css', 'plugin_mce_css' );
function plugin_mce_css( $mce_css ) {
    if ( ! is_admin() )
    {
        $mce_css = get_stylesheet_directory_uri().'/dist/css/tinymce.css';
    }
    return $mce_css;
}



/**
 * =========================================================================
 * Learndash ARKDE assignments
 * =========================================================================
 */

/**
 * Example usage for learndash_lesson_completed action.
 */
add_action(
  'learndash_topic_completed',
  function( $topic_data ) {
      // May add any custom logic using $lesson_data
      $clase = $topic_data["topic"];
      $modulo = $topic_data["lesson"];
      $course = $topic_data["course"];
      write_log("Topic completed hook fired");
      if($clase->ID == $modulo->ID)
      {
        write_log("clase created wihtout modulo first");
        return;
      }
      if(!learndash_is_lesson_complete($topic_data["user"]->ID,$modulo->ID,$course->ID) && !is_admin())
      {
        //lets check if, with this, all topics from lesson are completed. If so, then complete de lesson
        $topics = learndash_get_topic_list( $modulo->ID, $course->ID );
        if(!empty($topics))
        {
          $to_complete = true;
          foreach ( $topics as $topic ) {
            if($topic->ID != $clase->ID)
            {
              if(!learndash_is_topic_complete($topic_data["user"]->ID,$topic->ID,$course->ID))
              {  
                write_log("topic differente and not completed:".$topic->post_title);
                //An uncompleted topic, therefore we can autocomplete the lesson (module)
                $to_complete = false;
                break;
              }
            }
            
          }
          if($to_complete)
          {
            write_log("Time to complete the module!");
            learndash_process_mark_complete( $topic_data["user"]->ID,  $modulo->ID );
          }

        }
      }
  }
);

//Remove the initial action from the LD plugin and lets create our own callbacks
remove_action( 'parse_request', 'learndash_assignment_process_init', 1 );
add_action( 'parse_request', 'arkde_assignment_answer_init', 1 );
add_action("learndash_assignment_uploaded",'arkde_update_assignment_body',1);

/**
 * Action to run aftet the assignment answer is created regardless of uploaded file or not
 * @param  [type] $tarea_id [description]
 * @return [type]           [description]
 */
function arkde_update_assignment_body ($tarea_id)
{
  //lets get the post data of answer
  $answer = $_POST['assignment_answer'];
  update_field("answer", html_entity_decode($answer), $tarea_id);

}



/**
 * Create the assignment answer when there IS an uploaded file
 * @param int $post_id Post ID.
 * @param int $fname   Assignment file name.
 */
function arkde_updatedb_assignment_withfile( $post_id, $fname ) {
  // Initialize an empty array
  global $wp;

  if ( ! function_exists( 'wp_get_current_user' ) ) {
    include ABSPATH . 'wp-includes/pluggable.php';
  }

  $new_assignmnt_meta = array();
  $current_user       = wp_get_current_user();
  $username           = $current_user->user_login;
  $dispname           = $current_user->display_name;
  $userid             = $current_user->ID;
  $url_link_arr       = wp_upload_dir();
  $url_link           = $url_link_arr['baseurl'];
  $dir_link           = $url_link_arr['basedir'];
  $file_path          = $dir_link . '/assignments/';
  $url_path           = $url_link . '/assignments/' . $fname;

  if ( file_exists( $file_path . $fname ) ) {
    $dest = $url_path;
  } else {
    return;
  }

  update_post_meta( $post_id, 'sfwd_lessons-assignment', $new_assignmnt_meta );
  $post      = get_post( $post_id );
  $course_id = learndash_get_course_id( $post->ID );

  $assignment_meta = array(
    'file_name'    => $fname,
    'file_link'    => $dest,
    'user_name'    => $username,
    'disp_name'    => $dispname,
    'file_path'    => rawurlencode( $file_path . $fname ),
    'user_id'      => $current_user->ID,
    'lesson_id'    => $post->ID,
    'course_id'    => $course_id,
    'lesson_title' => $post->post_title,
    'lesson_type'  => $post->post_type,
  );

  $points_enabled = learndash_get_setting( $post, 'lesson_assignment_points_enabled' );

  if ( 'on' === $points_enabled ) {
    $assignment_meta['points'] = 'pending';
  }
  $assignment_name    = $current_user->user_login."_".$post_id; 
  $assignment = array(
    'post_title'   => $assignment_name,
    'post_type'    => learndash_get_post_type_slug( 'assignment' ),
    'post_status'  => 'publish',
    'post_content' => "<a href='" . $dest . "' target='_blank'>" . $fname . '</a>',
    'post_author'  => $current_user->ID,
  );

  $assignment_post_id = wp_insert_post( $assignment );
  $auto_approve       = learndash_get_setting( $post, 'auto_approve_assignment' );


  if ( $assignment_post_id ) {
    foreach ( $assignment_meta as $key => $value ) {
      update_post_meta( $assignment_post_id, $key, $value );
    }

    do_action( 'learndash_assignment_uploaded', $assignment_post_id );

    if ( empty( $auto_approve ) ) {

      update_user_meta(
        get_current_user_id(),
        'ld_assignment_message',
        array(
          array(
            'type'    => 'success',
            'message' => esc_html__( 'Assignment successfully uploaded.', 'learndash' ),
          ),
        )
      );

      learndash_safe_redirect( get_permalink( $post->ID ), 303 );
    }
  }

  if ( ! empty( $auto_approve ) ) {
    learndash_approve_assignment( $current_user->ID, $post_id, $assignment_post_id );

    // assign full points if auto approve & points are enabled
    if ( 'on' === $points_enabled ) {
      $points = learndash_get_setting( $post, 'lesson_assignment_points_amount' );
      update_post_meta( $assignment_post_id, 'points', intval( $points ) );
    }

    learndash_safe_redirect( get_permalink( $post->ID ), 303 );
    //learndash_get_next_lesson_redirect( $post );
  }
}

/**
 * Upload the file from the assignment Answer by the student. and upload metadata to the DB
 * @param array $uploadfiles An array of uploaded files data.
 * @param int   $post_id    The assignment ID.
 *
 * @return array Returns file data after upload such as file name and file URL.
 */
function arkde_fileupload_process( $uploadfiles, $post_id ) {

  if ( is_array( $uploadfiles ) ) {

    foreach ( $uploadfiles['name'] as $key => $value ) {
      // look only for uploded files
      if ( 0 == $uploadfiles['error'][ $key ] ) {

        $filetmp = $uploadfiles['tmp_name'][ $key ];

        // clean filename
        $filename = learndash_clean_filename( $uploadfiles['name'][ $key ] );

        // extract extension
        if ( ! function_exists( 'wp_get_current_user' ) ) {
          include ABSPATH . 'wp-includes/pluggable.php';
        }

        // Before this function we have already validated the file extention/type via the function learndash_check_upload
        // @2.5.4
        $file_title = pathinfo( basename( $filename ), PATHINFO_FILENAME );
        $file_ext   = pathinfo( basename( $filename ), PATHINFO_EXTENSION );

        $upload_dir      = wp_upload_dir();
        $upload_dir_base = str_replace( '\\', '/', $upload_dir['basedir'] );
        $upload_url_base = $upload_dir['baseurl'];
        $upload_dir_path = $upload_dir_base . '/assignments';
        $upload_url_path = $upload_url_base . '/assignments/';

        if ( ! file_exists( $upload_dir_path ) ) {
          if ( is_writable( dirname( $upload_dir_path ) ) ) {
            wp_mkdir_p( $upload_dir_path );
          } else {
            die( esc_html__( 'Unable to write to UPLOADS directory. Is this directory writable by the server?', 'learndash' ) );
          }
        }

        // Add an index.php file to prevent directory browesing
        $_index = trailingslashit( $upload_dir_path ) . 'index.php';
        if ( ! file_exists( $_index ) ) {
          file_put_contents( $_index, '//LearnDash is THE Best LMS' );
        }

        $file_time = microtime( true ) * 100;
        $filename  = sprintf( 'assignment_%d_%d_%s.%s', $post_id, $file_time, $file_title, $file_ext );

        /**
         * Filters the assignment upload filename.
         *
         * @since 3.2.0
         *
         * @param string $filename   File name.
         * @param int    $post_id    Post ID.
         * @param string $file_time  Unix timestamp.
         * @param string $file_title Title of the file.
         * @param string $file_ext   File extention.
         */
        $filename = apply_filters( 'learndash_assignment_upload_filename', $filename, $post_id, $file_time, $file_title, $file_ext );

        /**
         * Check if the filename already exist in the directory and rename the
         * file if necessary
         */
        $i = 0;

        $file_title = pathinfo( basename( $filename ), PATHINFO_FILENAME );
        $file_ext   = pathinfo( basename( $filename ), PATHINFO_EXTENSION );

        while ( file_exists( $upload_dir_path . '/' . $filename ) ) {
          $i++;
          $filename = $file_title . '_' . $i . '.' . $file_ext;
        }

        $filedest    = $upload_dir_path . '/' . $filename;
        $destination = $upload_url_path . $filename;

        /**
         * Check write permissions
         */
        if ( ! is_writeable( $upload_dir_path ) ) {
          die( esc_html__( 'Unable to write to directory. Is this directory writable by the server?', 'learndash' ) );
        }

        /**
         * Save temporary file to uploads dir
         */
        if ( ! @move_uploaded_file( $filetmp, $filedest ) ) {
          echo sprintf(
            // translators: placeholder: temporary file, file destination.
            esc_html__( 'Error, the file %1$s could not be moved to: %2$s', 'learndash' ),
            esc_html( $filetmp ),
            esc_html( $filedest )
          );
          continue;
        }

        /**
         * Add upload meta to database
         */
        arkde_updatedb_assignment_withfile( $post_id, $filename );
        $file_desc             = array();
        $file_desc['filename'] = $filename;
        $file_desc['filelink'] = $destination;
        return $file_desc;
      }
    }
  }
}

/**
 * Create the assignment answer when there is no uploaded file
 * @param  [type] $post_id [description]
 * @return [type]          [description]
 */
function arkde_updatedb_assignment_withoutfile( $post_id ) {
  // Initialize an empty array
  global $wp;

  if ( ! function_exists( 'wp_get_current_user' ) ) {
    include ABSPATH . 'wp-includes/pluggable.php';
  }


  $new_assignmnt_meta = array();
  $current_user       = wp_get_current_user();
  $username           = $current_user->user_login;
  $dispname           = $current_user->display_name;
  $userid             = $current_user->ID;
  $url_link_arr       = wp_upload_dir();
  $url_link           = $url_link_arr['baseurl'];
  $dir_link           = $url_link_arr['basedir'];
  $file_path          = $dir_link . '/assignments/';
  $assignment_name    = $current_user->user_login."_".$post_id; 
 

  update_post_meta( $post_id, 'sfwd_lessons-assignment', $new_assignmnt_meta );
  $post      = get_post( $post_id );
  $course_id = learndash_get_course_id( $post->ID );

  $assignment_meta = array(
    'file_name'    => "No file",
    'user_name'    => $username,
    'disp_name'    => $dispname,
    'user_id'      => $current_user->ID,
    'lesson_id'    => $post->ID,
    'course_id'    => $course_id,
    'lesson_title' => $post->post_title,
    'lesson_type'  => $post->post_type,
  );

  $points_enabled = learndash_get_setting( $post, 'lesson_assignment_points_enabled' );

  if ( 'on' === $points_enabled ) {
    $assignment_meta['points'] = 'pending';
  }

  $assignment = array(
    'post_title'   => $assignment_name,
    'post_type'    => learndash_get_post_type_slug( 'assignment' ),
    'post_status'  => 'publish',
    'post_content' => "",
    'post_author'  => $current_user->ID,
  );

  $assignment_post_id = wp_insert_post( $assignment );
  $auto_approve       = learndash_get_setting( $post, 'auto_approve_assignment' );


  if ( $assignment_post_id ) {
    foreach ( $assignment_meta as $key => $value ) {
      update_post_meta( $assignment_post_id, $key, $value );
    }

    /**
     * Fires after the assignment is uploaded.
     *
     * @since 2.2.0
     *
     * @param int   $assignment_post_id The assignment post id created after the assignment upload.
     * @param array $assignment_meta    Assignment meta data.
     */
    do_action( 'learndash_assignment_uploaded', $assignment_post_id );

    if ( empty( $auto_approve ) ) {

      update_user_meta(
        get_current_user_id(),
        'ld_assignment_message',
        array(
          array(
            'type'    => 'success',
            'message' => esc_html__( 'Assignment successfully uploaded.', 'learndash' ),
          ),
        )
      );

      learndash_safe_redirect( get_permalink( $post->ID ), 303 );
    }
  }

  if ( ! empty( $auto_approve ) ) {
    learndash_approve_assignment( $current_user->ID, $post_id, $assignment_post_id );

    // assign full points if auto approve & points are enabled
    if ( 'on' === $points_enabled ) {
      $points = learndash_get_setting( $post, 'lesson_assignment_points_amount' );
      update_post_meta( $assignment_post_id, 'points', intval( $points ) );
    }

    //learndash_safe_redirect( get_permalink( $post->ID ), 303 );
    learndash_get_next_lesson_redirect( $post );
  }
}

/**
 * Main intro action for creating assignments in Arkde lMS
 * Fires on `parse_request` hook.
 */
function arkde_assignment_answer_init() {


  if ( ( isset( $_POST['uploadfile'] ) ) && ( ! empty( $_POST['uploadfile'] ) ) && ( isset( $_POST['post'] ) ) && ( ! empty( $_POST['post'] ) ) && ( isset( $_POST['course_id'] ) && ( ! empty( $_POST['course_id'] ) ) ) ) {


    $course_id = intval( $_POST['course_id'] );
    $post_id   = intval( $_POST['post'] );


    // 1. Verify nonce
    if ( ! wp_verify_nonce( $_POST['uploadfile'], 'uploadfile_' . get_current_user_id() . '_' . $post_id ) ) {
      return;
    }

    // 2. Verify lesson/topic is set to accept assignment uploads. The 'lesson_assignment_upload'
    // should return 'on' if assignment uploads are enabled
    if ( 'on' !== learndash_get_setting( $post_id, 'lesson_assignment_upload' ) ) {
      return;
    }

    // 3. Verify the lesson/topic is from the correct course.
    $courses = learndash_get_courses_for_step( $post_id, true );
    if ( ( empty( $courses ) ) || ( ! isset( $courses[ $course_id ] ) ) ) {
      return;
    }

    // 4. Verify the user is logged in or allow external filtering
    if ( ! is_user_logged_in() ) {
      
      if ( ! apply_filters( 'learndash_assignment_upload_user_check', false, $course_id, $post_id ) ) {
        return;
      }
    }

    $file = $_FILES['uploadfiles'];

    //check if there is a file and it meets the requirments
    if ( ( ! empty( $file['name'][0] ) ) && ( learndash_check_upload( $file, $post_id ) ) ) {
      $file_desc = arkde_fileupload_process( $file, $post_id );
      $file_name = $file_desc['filename'];
      $file_link = $file_desc['filelink'];
      $params    = array(
        'filelink' => $file_link,
        'filename' => $file_name,
      );
    }
    else
    {
      //There is no file, we should also create the assignment but just without any file on it
      arkde_updatedb_assignment_withoutfile($post_id);
      
    }

  }

  if ( ! empty( $_GET['learndash_delete_attachment'] ) ) {
    $assignment_post = get_post( intval( $_GET['learndash_delete_attachment'] ) );
    if ( ( isset( $assignment_post ) ) && ( $assignment_post instanceof WP_Post ) && ( learndash_get_post_type_slug( 'assignment' ) === $assignment_post->post_type ) ) {
      $current_user_id = get_current_user_id();

      if ( ( $assignment_post->post_author == $current_user_id ) || ( learndash_is_admin_user( $current_user_id ) ) || ( learndash_is_group_leader_of_user( $current_user_id, $assignment_post->post_author ) ) ) {

        $course_id = get_post_meta( $assignment_post->ID, 'course_id', true );
        if ( empty( $course_id ) ) {
          $course_id = learndash_get_course_id( $assignment_post->ID );
        }
        $course_step_id = get_post_meta( $assignment_post->ID, 'lesson_id', true );

        learndash_process_mark_incomplete( $current_user_id, $course_id, $course_step_id );

        /**
         * Filters whether to force delete the assignment or not.
         *
         * @param boolean $force_delete    Whether to force delete assignment or not.
         * @param int     $assignment_id   Assignment ID.
         * @param WP_POST $assignment_post Assignment post object.
         */
        wp_delete_post( $assignment_post->ID, apply_filters( 'learndash_assignment_force_delete', true, $assignment_post->ID, $assignment_post ) );

        update_user_meta(
          get_current_user_id(),
          'ld_assignment_message',
          array(
            array(
              'type'    => 'success',
              'message' => esc_html__( 'Assignment successfully deleted.', 'learndash' ),
            ),
          )
        );

        $return_url = remove_query_arg( 'learndash_delete_attachment' );
        learndash_safe_redirect( $return_url );
      }
    }
  }

  if ( ! empty( $_POST['attachment_mark_complete'] ) && ! empty( $_POST['userid'] ) ) {
    $lesson_id       = $_POST['attachment_mark_complete'];
    $current_user_id = get_current_user_id();
    $user_id         = $_POST['userid'];

    if ( ( learndash_is_admin_user( $current_user_id ) ) || ( learndash_is_group_leader_of_user( $current_user_id, $user_id ) ) ) {
      learndash_approve_assignment( $user_id, $lesson_id );
    }
  }
}

/**
 * =========================================================================================
 * ACF Filters
 * =========================================================================================
 */

/**
 * In backend and admin or teacher should NOT be able to edit the answer of the student
 * @param  [type] $field [description]
 * @return [type]        [description]
 */
function acf_read_only( $field ) { 
 
  //var_dump($field); 
  if(is_admin())
  {
     $field['readonly'] = 1; 
    $field['disabled'] = true;
  }
  return $field; 
}
add_filter('acf/load_field/name=answer', 'acf_read_only',10,3);

/**
 * Comment Walker for comment from mentor feedback. Some minor changes
 */
if ( ! function_exists( 'arkde_comment' ) ) {

  function arkde_comment( $comment, $args, $depth ) {
    if ( 'div' == $args['style'] ) {
      $tag       = 'div';
      $add_below = 'comment';
    } else {
      $tag       = 'li';
      $add_below = 'div-comment';
    }
    ?>

    <<?php echo $tag; ?> <?php comment_class( $args['has_children'] ? 'parent' : '', $comment ); ?> id="comment-<?php comment_ID(); ?>">

  <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">

      <?php
      if ( 0 != $args['avatar_size'] ) {
        $user_link = function_exists( 'bp_core_get_user_domain' ) ? bp_core_get_user_domain( $comment->user_id ) : get_comment_author_url( $comment );
        ?>
        <div class="comment-author vcard">
            <?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
        </div>
      <?php } ?>

    <div class="comment-content-wrap">
      <div class="comment-meta comment-metadata">
        <?php printf( __( '%s', 'buddyboss-theme' ), sprintf( '<cite class="fn comment-author">%s</cite>', get_comment_author_link( $comment ) ) ); ?>
        <span class="comment-date"><?php printf( __( '%1$s', 'buddyboss-theme' ), get_comment_date( '', $comment ), get_comment_time() ); ?></span>
      </div>

      <?php if ( '0' == $comment->comment_approved ) { ?>
        <p>
          <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'buddyboss-theme' ); ?></em>
        </p>
      <?php } ?>

      <div class="comment-text">
        <?php
        comment_text(
          $comment,
          array_merge(
            $args,
            array(
              'add_below' => $add_below,
              'depth'     => $depth,
              'max_depth' => $args['max_depth'],
            )
          )
        );
        ?>
      </div>

      <footer class="comment-footer">

        <?php edit_comment_link( __( 'Edit', 'buddyboss-theme' ), '', '' ); ?>
      </footer>
    </div>    </article>
    <?php
  }
}
//For example, you can paste this into your theme functions.php file
function meks_which_template_is_loaded() {
    global $template;
    print_r( $template );
}
 
//add_action( 'wp_footer', 'meks_which_template_is_loaded' );


// Register our custom footer menus.  
register_nav_menus( array(  
  'footer-coding' => 'Footer Cursos coding',  
  'footer-design' => 'Footer Cursos design'  
) );

//Custom shortcodes
function check_ld_certificate( $atts ) {
  global $post;
  $id = $post->id;
  ob_start();
  get_template_part('template-parts/arkde_certificate',null,array(
    'course_id' => learndash_get_course_id($id),
    'user_id' => get_current_user_id()
  ));
  return ob_get_clean();
  //return include(locate_template('template-parts/arkde_certificate.php'));
}
add_shortcode( 'arkde_certificate', 'check_ld_certificate' );

/**
 * Auto complete a topic when it has the autocomplete LD tag
 */
function ld_auto_mark_complete() {
  global $template;
  global $post;
  global $wp;
  $user_id = get_current_user_id();
  write_log("LD tipoc before hook fired!");

  if(!is_object($post) || get_post_type()!= 'sfwd-topic' || $post->post_status == "trash" || $post->post_status == "draft"
  || str_contains($wp->request,"admin")) 
    return;
  
  $topic_id = $post->ID;
  $tags = get_the_terms( $topic_id, 'ld_topic_tag' );
  if(!empty($tags) && !is_admin())
  {
    $tag = $tags[0];
    if($tag->slug == "auto-complete")
    {
      //has the autocomplete
      $course_id  = learndash_get_course_id($topic_id);
      if(!empty($course_id) && !learndash_is_topic_complete($user_id,$topic_id,$course_id))
      {
        write_log("autocomplete");
        learndash_process_mark_complete($user_id, $topic_id);
      }
    }
  }
  return;
}
add_action('wp', 'ld_auto_mark_complete');

?>