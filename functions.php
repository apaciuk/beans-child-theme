<?php
// Include Beans. Do not remove the line below.
require_once( get_template_directory() . '/lib/init.php' );
/*
 * Remove this action and callback function if you do not whish to use LESS to style your site or overwrite UIkit variables.
 * If you are using LESS, make sure to enable development mode via the Admin->Appearance->Settings option. LESS will then be processed on the fly.
 */
add_action( 'beans_uikit_enqueue_scripts', 'beans_child_enqueue_uikit_assets' );
function beans_child_enqueue_uikit_assets() {
beans_compiler_add_fragment( 'uikit', get_stylesheet_directory_uri() . '/style.less', 'less' );
}
function beans_child_enqueue_assets() {
wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css' );
}
// Remove the secondary sidebar.
add_action( 'widgets_init', 'remove_widget_area' );
function remove_widget_area() {
beans_deregister_widget_area( 'sidebar_secondary' );
}
/* register footer nav */
function slift_register_nav_menu(){
        register_nav_menus( array(
            'footer_menu'  => __( 'Footer Menu', 'sectionlift' ),
        ) );
}
add_action( 'after_setup_theme', 'slift_register_nav_menu', 0 );
/* remove beans image edit */
add_filter( 'beans_post_image_edit', '__return_false' );
/* Remove page titles 
beans_add_smart_action('beans_before_posts_loop', 'remove_page_title_on_pages_only' );
function remove_page_title_on_pages_only() {
  if ( is_singular() && is_page() ) {
   remove_all_actions( 'beans_post_header' );
  }
}*/
// Removes featured image from all pages (posts and pages) except the blog page.
add_action( 'wp', 'beans_child_setup_document' );
function beans_child_setup_document() {
 if ( is_single() ) { 
 beans_remove_action( 'beans_post_image' );
 }
}
/*
* Creating a function to create our CPT
*/
function custom_post_type() {
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Portfolio', 'Post Type General Name', 'sectionlift' ),
        'singular_name'       => _x( 'portfolio', 'Post Type Singular Name', 'sectionlift' ),
        'menu_name'           => __( 'Portfolios', 'sectionlift' ),
        'parent_item_colon'   => __( 'Parent portfolio', 'sectionlift' ),
        'all_items'           => __( 'All Portfolios', 'sectionlift' ),
        'view_item'           => __( 'View portfolio', 'sectionlift' ),
        'add_new_item'        => __( 'Add New portfolio', 'sectionlift' ),
        'add_new'             => __( 'Add New', 'sectionlift' ),
        'edit_item'           => __( 'Edit portfolio', 'sectionlift' ),
        'update_item'         => __( 'Update portfolio', 'sectionlift' ),
        'search_items'        => __( 'Search portfolio', 'sectionlift' ),
        'not_found'           => __( 'Not Found', 'sectionlift' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'sectionlift' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'Portfolio', 'sectionlift' ),
        'description'         => __( 'portfolio news and reviews', 'sectionlift' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => true,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
 
    );
     
    // Registering your Custom Post Type
    register_post_type( 'Portfolio', $args );
 
}
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
add_action( 'init', 'custom_post_type', 0 );
// Search component - Load the js file into the functions.php file.
beans_compiler_add_fragment( 'uikit', get_stylesheet_directory_uri() . '/assets/js/global.js', 'js' );
/* Adds search to primary menu. 
beans_add_smart_action( 'beans_menu[_navbar][_primary]_append_markup', 'slift_primary_menu_search' );
function slift_primary_menu_search() {
?>
<li class="uk-search uk-medium-visible uk-margin-large-left">
<?php get_search_form(); ?>
</li>
<?php
}*/
/*------- Footer & Social icons -------*/
add_action( 'widgets_init', 'beans_child_logo_footer_loop' );
function beans_child_logo_footer_loop() {    
     beans_register_widget_area( array(        
                'name' => 'Logo Footer',
                'id' => 'logo-footer',     
                 'description' => 'Widgets for logo will be shown above the footer.'     
     ) );
}
add_action( 'widgets_init', 'beans_child_social_footer_loop' );
function beans_child_social_footer_loop() {    
     beans_register_widget_area( array(        
                'name' => 'Social Footer',
                'id' => 'social-footer',     
               'description' => 'Widgets for social icons will be shown above the footer.'     
     ) );
}
add_action( 'widgets_init', 'beans_child_widgets_footer_loop' );
function beans_child_widgets_footer_loop() {    
     beans_register_widget_area( array(
		       'name' => 'Footer',        
               'id' => 'footer',        
               'description' => 'Widgets in this area will be shown in the footer section as a grid.', 
               'beans_type' => 'grid'    
     ) );
}
// Display the footer & social widget area in the front end.
add_action( 'beans_footer_before_markup', 'footer_widget_area' );
function footer_widget_area() {
      // Stop here if no widget
 if( !beans_is_active_widget_area( 'footer' ) )
 return;
        ?> 
            <div class="widget-footer uk-block" id="logo-footer"> 
			 <div class="uk-container uk-container-center"> 
			   <?php echo beans_widget_area( 'logo-footer' ); ?>
				</div>
             </div>
           <div class="widget-footer uk-block" id="social-footer"> 
          <div class="uk-container uk-container-center"> 
		     <?php echo beans_widget_area( 'social-footer' ); ?> 
          </div> 
</div>
      <div class="widget-footer uk-block"> 
            <div class="uk-container uk-container-center"> 
                  <?php echo beans_widget_area( 'footer' ); ?> 
          </div> 
    </div> 
        <div class="widget-footer uk-block uk-flex-center" id="menu-footer"> 
        <div class="uk-container uk-container-center"> 
				 <?php echo wp_nav_menu( array(
                 'menu' => 'Footer Menu',
			    )); ?>
        </div> 
</div>
      
    <?php
}
/* read More link */
beans_add_attribute( 'beans_post_more_link', 'class', 'uk-button uk-button-large uk-button-primary' );
/*----- COPYRIGHT INFO BOTTOM ----*/
// Overwrite the footer content.
beans_modify_action_callback( 'beans_footer_content', 'beans_child_footer_content' );

// COPYRIGHT area
function beans_child_footer_content() {
?>
<div class="tm-sub-footer uk-text-center uk-text-muted">
    <p>© <?php echo date('Y'); ?>
		<a href="<?php echo site_url();?>" target="_blank" title="SectionLift">Section<span>Lift</span></a> - an <a href="http://www.applifting.com/" title="Applifting" target="_blank">Applifting</a> Group Company </p>
    </div>
    <?php
}


