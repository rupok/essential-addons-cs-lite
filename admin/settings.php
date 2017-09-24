<?php 
/**
 * Admin Settings Page
 */

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

class Eacs_Admin_Settings {
	protected $is_pro = FALSE;
	private $eacs_default_settings = array(
	   'logo-carousel'      => true,
	   'logo-carousel-item' => true,
	   'post-carousel'      => true,
	   'product-carousel'   => true,
	   'product-grid'       => true,
	   'team-members'       => true,
	   'team-members-item'  => true,
	   'testimonial-item'   => true,
	   'testimonial-slider' => true,
	);

	private $eacs_settings;
	private $eacs_get_settings;

	/**
	 * Initializing all default hooks and functions
	 * @param 
	 * @return void
	 * @since 1.0.0
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'create_eacs_admin_menu' ) );	
		add_action( 'init', array( $this, 'enqueue_eacs_admin_scripts' ) );
		add_action( 'wp_ajax_save_settings_with_ajax', array( $this, 'eacs_save_settings_with_ajax' ) );
		add_action( 'wp_ajax_nopriv_save_settings_with_ajax', array( $this, 'eacs_save_settings_with_ajax' ) );

	}

	/**
	 * Loading all essential scripts
	 * @param
	 * @return void
	 * @since 1.0.0
	 */
	public function enqueue_eacs_admin_scripts() {

		if( isset( $_GET['page'] ) && $_GET['page'] == 'eacs-settings' ) {
			wp_enqueue_style( 'essential_addons_elementor-admin-css', plugins_url( '/', __FILE__ ).'assets/css/admin.css' );
			wp_enqueue_style( 'font-awesome-css', plugins_url( '/', __FILE__ ).'assets/vendor/font-awesome/css/font-awesome.min.css' );
			wp_enqueue_style( 'essential_addons_elementor-sweetalert2-css', plugins_url( '/', __FILE__ ).'assets/vendor/sweetalert2/css/sweetalert2.min.css' );

			wp_enqueue_script( "jquery-ui-tabs" );
			wp_enqueue_script( 'essential_addons_elementor-admin-js', plugins_url( '/', __FILE__ ).'assets/js/admin.js', array( 'jquery', 'jquery-ui-tabs' ), '1.0', true );
			wp_enqueue_script( 'essential_addons_core-js', plugins_url( '/', __FILE__ ).'assets/vendor/sweetalert2/js/core.js', array( 'jquery' ), '1.0', true );
			wp_enqueue_script( 'essential_addons_sweetalert2-js', plugins_url( '/', __FILE__ ).'assets/vendor/sweetalert2/js/sweetalert2.min.js', array( 'jquery', 'essential_addons_core-js' ), '1.0', true );
		}

	}

	/**
	 * Create an admin menu.
	 * @param 
	 * @return void
	 * @since 1.0.0 
	 */
	public function create_eacs_admin_menu() {

		add_menu_page( 
			'Essential Addon Cornerstone', 
			'Essential Addon Cornerstone', 
			'manage_options', 
			'eacs-settings', 
			array( $this, 'eacs_admin_settings_page' ), 
			'dashicons-admin-generic', 
			199  
		);

	}

	/**
	 * Create settings page.
	 * @param 
	 * @return void
	 * @since 1.0.0
	 */
	public function eacs_admin_settings_page() {

		$js_info = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' )
		);
		wp_localize_script( 'essential_addons_elementor-admin-js', 'settings', $js_info );

	   /**
	    * This section will handle the "eacs_save_settings" array. If any new settings options is added
	    * then it will matches with the older array and then if it founds anything new then it will update the entire array.
	    */
	   $this->eacs_get_settings = get_option( 'eacs_save_settings', $this->eacs_default_settings );
	   $eacs_new_settings = array_diff_key( $this->eacs_default_settings, $this->eacs_get_settings );
	   if( ! empty( $eacs_new_settings ) ) {
	   	$eacs_updated_settings = array_merge( $this->eacs_get_settings, $eacs_new_settings );
	   	update_option( 'eacs_save_settings', $eacs_updated_settings );
	   }
	   $this->eacs_get_settings = get_option( 'eacs_save_settings', $this->eacs_default_settings );
		?>
		<div class="wrap">
		  	<h2><?php _e( 'Essential Cornerstone Addon Settings', 'essential-addons-cs' ); ?></h2> <hr>
		  	<div class="response-wrap"></div>
		  	<form action="" method="POST" id="eacs-settings" name="eacs-settings">
			  	<div class="eacs-settings-tabs">
			    	<ul>
				      <li><a href="#general"><i class="fa fa-cogs"></i> General</a></li>
				      <li><a href="#elements"><i class="fa fa-cubes"></i> Elements</a></li>
				      <li><a href="#go-pro"><i class="fa fa-magic"></i> Go Pro</a></li>
				      <li><a href="#support"><i class="fa fa-ticket"></i> Support</a></li>
			    	</ul>
			    	<div id="general" class="eacs-settings-tab">
			      	<p>General Settings</p>
			    	</div>
			    	<div id="elements" class="eacs-settings-tab">
			      	<div class="row">
			      		<div class="col-full">
			      			<table class="form-table">
									<tr>
										<td>
											<div class="eacs-checkbox">
												<p class="title"><?php _e( 'Logo Carousel', 'essential-addons-cs' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Logo Carousel', 'essential-addons-cs' ); ?></p>
				                        <input type="checkbox" id="logo-carousel" name="logo-carousel" <?php checked( 1, $this->eacs_get_settings['logo-carousel'], true ); ?> >
				                        <label for="logo-carousel"></label>
				                    	</div>
										</td>
										<td>
											<div class="eacs-checkbox">
												<p class="title"><?php _e( 'Logo Carousel Item', 'essential-addons-cs' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Logo Carousel Item', 'essential-addons-cs' ); ?></p>
				                        <input type="checkbox" id="logo-carousel-item" name="logo-carousel-item" <?php checked( 1, $this->eacs_get_settings['logo-carousel-item'], true ); ?> >
				                        <label for="logo-carousel-item"></label>
				                    	</div>
										</td>
										<td>
											<div class="eacs-checkbox">
												<p class="title"><?php _e( 'Post Carousel', 'essential-addons-cs' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Post Carousel', 'essential-addons-cs' ); ?></p>
				                        <input type="checkbox" id="post-carousel" name="post-carousel" <?php checked( 1, $this->eacs_get_settings['post-carousel'], true ); ?> >
				                        <label for="post-carousel"></label>
				                    	</div>
										</td>
										<td>
											<div class="eacs-checkbox">
												<p class="title"><?php _e( 'Product Carousel', 'essential-addons-cs' ) ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Product Carousel', 'essential-addons-cs' ); ?></p>
				                        <input type="checkbox" id="product-carousel" name="product-carousel" <?php checked( 1, $this->eacs_get_settings['product-carousel'], true ); ?> >
				                        <label for="product-carousel"></label>
				                    	</div>
										</td>
										<td>
											<div class="eacs-checkbox">
												<p class="title"><?php _e( 'Product Grid', 'essential-addons-cs' ) ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Product Grid', 'essential-addons-cs' ); ?></p>
				                        <input type="checkbox" id="product-grid" name="product-grid" <?php checked( 1, $this->eacs_get_settings['product-grid'], true ); ?> >
				                        <label for="product-grid"></label>
				                    	</div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="eacs-checkbox">
												<p class="title"><?php _e( 'Team Members', 'essential-addons-cs' ) ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Team Members', 'essential-addons-cs' ); ?></p>
				                        <input type="checkbox" id="team-members" name="team-members" <?php checked( 1, $this->eacs_get_settings['team-members'], true ); ?> >
				                        <label for="team-members"></label>
				                    	</div>
										</td>
										<td>
											<div class="eacs-checkbox">
												<p class="title"><?php _e( 'Team Members Item', 'essential-addons-cs' ) ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Team Members Item', 'essential-addons-cs' ); ?></p>
				                        <input type="checkbox" id="team-members-item" name="team-members-item" <?php checked( 1, $this->eacs_get_settings['team-members-item'], true ); ?> >
				                        <label for="team-members-item"></label>
				                    	</div>
										</td>
										<td>
											<div class="eacs-checkbox">
												<p class="title"><?php _e( 'Testimonial Slider', 'essential-addons-cs' ) ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Testimonial Slider', 'essential-addons-cs' ); ?></p>
				                        <input type="checkbox" id="testimonial-slider" name="testimonial-slider" <?php checked( 1, $this->eacs_get_settings['testimonial-slider'], true ); ?> >
				                        <label for="testimonial-slider"></label>
				                    	</div>
										</td>
										<td>
											<div class="eacs-checkbox">
												<p class="title"><?php _e( 'Testimonial Item', 'essential-addons-cs' ) ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Testimonial Item', 'essential-addons-cs' ); ?></p>
				                        <input type="checkbox" id="testimonial-item" name="testimonial-item" <?php checked( 1, $this->eacs_get_settings['testimonial-item'], true ); ?> >
				                        <label for="testimonial-item"></label>
				                    	</div>
										</td>
									</tr>
					      	</table>
			      		</div>
			      		<div class="col-full">
			      			<h2 class="section-title">Pro Version Components!</h2>
			      			<table class="form-table">
									<tr>
										<td>
											<div class="eacs-checkbox">
												<p class="title"><?php _e( 'Count Down', 'essential-addons-cs' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Count Down', 'essential-addons-cs' ); ?></p>
				                        <input type="checkbox" id="count-down" name="count-down" disabled >
				                        <label for="count-down" class="<?php if( (bool) $is_pro === false ) : echo 'eacs-get-pro'; endif; ?>"></label>
				                    	</div>
										</td>
										<td>
											<div class="eacs-checkbox">
												<p class="title"><?php _e( 'Creative Button', 'essential-addons-cs' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Creative Button', 'essential-addons-cs' ); ?></p>
				                        <input type="checkbox" id="creative-btn" name="creative-btn" disabled >
				                        <label for="creative-btn" class="<?php if( (bool) $is_pro === false ) : echo 'eacs-get-pro'; endif; ?>"></label>
				                    	</div>
										</td>
										<td>
											<div class="eacs-checkbox">
												<p class="title"><?php _e( 'Image Comparison', 'essential-addons-cs' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Image Comparison', 'essential-addons-cs' ); ?></p>
				                        <input type="checkbox" id="img-comparison" name="img-comparison" disabled >
				                        <label for="img-comparison" class="<?php if( (bool) $is_pro === false ) : echo 'eacs-get-pro'; endif; ?>"></label>
				                    	</div>
										</td>
										<td>
											<div class="eacs-checkbox">
												<p class="title"><?php _e( 'Instagram Feed', 'essential-addons-cs' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Instagram Feed', 'essential-addons-cs' ); ?></p>
				                        <input type="checkbox" id="instagram-feed" name="instagram-feed" disabled >
				                        <label for="instagram-feed" class="<?php if( (bool) $is_pro === false ) : echo 'eacs-get-pro'; endif; ?>"></label>
				                    	</div>
										</td>
										<td>
											<div class="eacs-checkbox">
												<p class="title"><?php _e( 'Interactive Promo', 'essential-addons-cs' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Interactive Promo', 'essential-addons-cs' ); ?></p>
				                        <input type="checkbox" id="interactive-promo" name="interactive-promo" disabled >
				                        <label for="interactive-promo" class="<?php if( (bool) $is_pro === false ) : echo 'eacs-get-pro'; endif; ?>"></label>
				                    	</div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="eacs-checkbox">
												<p class="title"><?php _e( 'Lightbox', 'essential-addons-cs' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Lightbox', 'essential-addons-cs' ); ?></p>
				                        <input type="checkbox" id="lightbox" name="lightbox" disabled >
				                        <label for="lightbox" class="<?php if( (bool) $is_pro === false ) : echo 'eacs-get-pro'; endif; ?>"></label>
				                    	</div>
										</td>
										<td>
											<div class="eacs-checkbox">
												<p class="title"><?php _e( 'Post Block', 'essential-addons-cs' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Post Block', 'essential-addons-cs' ); ?></p>
				                        <input type="checkbox" id="post-block" name="post-block" disabled >
				                        <label for="post-block" class="<?php if( (bool) $is_pro === false ) : echo 'eacs-get-pro'; endif; ?>"></label>
				                    	</div>
										</td>
										<td>
											<div class="eacs-checkbox">
												<p class="title"><?php _e( 'Post Grid', 'essential-addons-cs' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Post Grid', 'essential-addons-cs' ); ?></p>
				                        <input type="checkbox" id="post-grid" name="post-grid" disabled >
				                        <label for="post-grid" class="<?php if( (bool) $is_pro === false ) : echo 'eacs-get-pro'; endif; ?>"></label>
				                    	</div>
										</td>
										<td>
											<div class="eacs-checkbox">
												<p class="title"><?php _e( 'Post Timeline', 'essential-addons-cs' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Post Timeline', 'essential-addons-cs' ); ?></p>
				                        <input type="checkbox" id="post-timeline" name="post-timeline" disabled >
				                        <label for="post-timeline" class="<?php if( (bool) $is_pro === false ) : echo 'eacs-get-pro'; endif; ?>"></label>
				                    	</div>
										</td>
										<td>
											<div class="eacs-checkbox">
												<p class="title"><?php _e( 'Social Icons', 'essential-addons-cs' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Social Icons', 'essential-addons-cs' ); ?></p>
				                        <input type="checkbox" id="social-icons" name="social-icons" disabled >
				                        <label for="social-icons" class="<?php if( (bool) $is_pro === false ) : echo 'eacs-get-pro'; endif; ?>"></label>
				                    	</div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="eacs-checkbox">
												<p class="title"><?php _e( 'Social Icons Item', 'essential-addons-cs' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Social Icons Item', 'essential-addons-cs' ); ?></p>
				                        <input type="checkbox" id="social-icons-item" name="social-icons-item" disabled >
				                        <label for="social-icons-item" class="<?php if( (bool) $is_pro === false ) : echo 'eacs-get-pro'; endif; ?>"></label>
				                    	</div>
										</td>
										<td>
											<div class="eacs-checkbox">
												<p class="title"><?php _e( 'Product Grid', 'essential-addons-cs' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Product Grid', 'essential-addons-cs' ); ?></p>
				                        <input type="checkbox" id="product-grid" name="product-grid" disabled >
				                        <label for="product-grid" class="<?php if( (bool) $is_pro === false ) : echo 'eacs-get-pro'; endif; ?>"></label>
				                    	</div>
										</td>
									</tr>
					      	</table>
			      		</div>
			      	</div>
			    	</div>
			    	<div id="go-pro" class="eacs-settings-tab">
			      	<p>Go Pro!</p>
			    	</div>
			    	<div id="support" class="eacs-settings-tab">
			      	<div class="row">
			      		<div class="col-half">
				      		<h4>Need any help? Open a ticket!</h4>
				      		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi maxime quis deleniti iure placeat ducimus voluptate perspiciatis nam eveniet eos accusantium maiores nulla temporibus fuga sunt tenetur error, delectus veniam.</p>
				      		<a href="#" class="button button-primary">Get Help</a>
				      	</div>
				      	<div class="col-half">
				      		<h4>Need any help? Open a ticket!</h4>
				      		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi maxime quis deleniti iure placeat ducimus voluptate perspiciatis nam eveniet eos accusantium maiores nulla temporibus fuga sunt tenetur error, delectus veniam.</p>
				      		<a href="#" class="button button-primary">Get Help</a>
				      	</div>
			      	</div>
			    	</div>
			  	</div>
			  	<div class="eacs-settings-footer">
			  		<input type="submit" value="Save settings" class="button button-primary"/>
			  	</div>
		  	</form>
		</div>
		<?php
		
	}

	/**
	 * Saving data with ajax request
	 * @param 
	 * @return  array in json
	 * @since 1.0.0 
	 */
	public function eacs_save_settings_with_ajax() {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$this->eacs_settings = array(
				'logo-carousel' 		=> intval( $_POST['logoCarousel'] ? 1 : 0 ),
				'logo-carousel-item' => intval( $_POST['logoCarouselItem'] ? 1 : 0 ),
				'post-carousel' 		=> intval( $_POST['postCarousel'] ? 1 : 0 ),
				'product-carousel' 	=> intval( $_POST['productCarousel'] ? 1 : 0 ),
				'product-grid' 		=> intval( $_POST['productGrid'] ? 1 : 0 ),
				'team-members' 		=> intval( $_POST['teamMembers'] ? 1 : 0 ),
				'team-members-item' 	=> intval( $_POST['teamMembersItem'] ? 1 : 0 ),
				'testimonial-item' 	=> intval( $_POST['testimonialItem'] ? 1 : 0 ),
				'testimonial-slider' => intval( $_POST['testimonialSlider'] ? 1 : 0 ),
			);
			update_option( 'eacs_save_settings', $this->eacs_settings );
			return true;
			die();
		}

	}

}

new Eacs_Admin_Settings();

	


