<?php
/**
 * Bizzabo Community Tab Widget
 * @version 0.1.3
 * @package Bizzabo
 */

class Bizzabo_Community_Tab_Widget extends WP_Widget {

	/**
	 * Unique identifier for this widget.
	 *
	 * Will also serve as the widget class.
	 *
	 * @var string
	 * @since  0.1.0
	 */
	protected $widget_slug = 'bizzabo-widget-community-tab';


	/**
	 * Widget name displayed in Widgets dashboard.
	 * Set in __construct since __() shouldn't take a variable.
	 *
	 * @var string
	 * @since  0.1.0
	 */
	protected $widget_name = 'Bizzabo Community Tab';


	/**
	 * Default widget title displayed in Widgets dashboard.
	 * Set in __construct since __() shouldn't take a variable.
	 *
	 * @var string
	 * @since  0.1.0
	 */
	protected $default_widget_title = '';


	/**
	 * Shortcode name for this widget
	 *
	 * @var string
	 * @since  0.1.0
	 */
	protected static $shortcode = 'bizzabo-community-tab';


	/**
	 * Construct widget class.
	 *
	 * @since 0.1.0
	 * @return  null
	 */
	public function __construct() {

		$this->widget_name          = esc_html__( 'Bizzabo Community Tab Widget', 'bizzabo-community-tab' );
		$this->default_widget_title = esc_html__( 'Bizzabo Community Tab Widget', 'bizzabo-community-tab' );

		parent::__construct(
			$this->widget_slug,
			$this->widget_name,
			array(
				'classname'   => $this->widget_slug,
				'description' => esc_html__( 'Display the Bizzabo Community Tab widget.', 'bizzabo-community-tab' ),
			)
		);

		add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
		add_shortcode( self::$shortcode, array( __CLASS__, 'get_widget' ) );
	}


	/**
	 * Delete this widget's cache.
	 *
	 * Note: Could also delete any transients
	 * delete_transient( 'some-transient-generated-by-this-widget' );
	 *
	 * @since  0.1.0
	 * @return  null
	 */
	public function flush_widget_cache() {
		wp_cache_delete( $this->widget_slug, 'widget' );
	}


	/**
	 * Front-end display of widget.
	 *
	 * @since  0.1.0
	 * @param  array  $args      The widget arguments set up when a sidebar is registered.
	 * @param  array  $instance  The widget settings as set by user.
	 * @return  null
	 */
	public function widget( $args, $instance ) {

		echo self::get_widget( array(
			'before_widget' => $args['before_widget'],
			'after_widget'  => $args['after_widget'],
			'before_title'  => $args['before_title'],
			'after_title'   => $args['after_title'],
			'title'         => $instance['title'],
			'unique_name'   => $instance['unique_name'],
		) );

	}


	/**
	 * Return the widget/shortcode output
	 *
	 * @since  0.1.0
	 * @param  array  $atts Array of widget/shortcode attributes/args
	 * @return string       Widget output
	 */
	public static function get_widget( $atts ) {
		$widget = '';

		// Set up default values for attributes
		$atts = shortcode_atts(
			array(
				// Ensure variables
				'before_widget' => '',
				'after_widget'  => '',
				'before_title'  => '',
				'after_title'   => '',
				'title'         => '',
				'unique_name'   => '',
			),
			(array) $atts,
			self::$shortcode
		);

		// Before widget hook
		$widget .= $atts['before_widget'];

		// Title
		$widget .= ( $atts['title'] ) ? $atts['before_title'] . esc_html( $atts['title'] ) . $atts['after_title'] : '';

		$widget .= bizzabo()->output->template_unique_name( 'community_tab', $atts['unique_name'] );

		// After widget hook
		$widget .= $atts['after_widget'];

		return $widget;
	}


	/**
	 * Update form values as they are saved.
	 *
	 * @since  0.1.0
	 * @param  array  $new_instance  New settings for this instance as input by the user.
	 * @param  array  $old_instance  Old settings for this instance.
	 * @return array  Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {

		// Previously saved values
		$instance = $old_instance;

		// Sanitize title before saving to database
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		// Sanitize text before saving to database
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['unique_name'] = force_balance_tags( $new_instance['unique_name'] );
		} else {
			$instance['unique_name'] = stripslashes( wp_filter_post_kses( addslashes( $new_instance['unique_name'] ) ) );
		}

		// Flush cache
		$this->flush_widget_cache();

		return $instance;
	}


	/**
	 * Back-end widget form with defaults.
	 *
	 * @since  0.1.0
	 * @param  array  $instance  Current settings.
	 * @return  null
	 */
	public function form( $instance ) {

		// If there are no settings, set up defaults
		$instance = wp_parse_args( (array) $instance,
			array(
				'title' => $this->default_widget_title,
				'unique_name'  => '',
			)
		);

		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'bizzabo-community-tab' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_html( $instance['title'] ); ?>" placeholder="optional" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'unique_name' ); ?>"><?php _e( 'Unique Name:', 'bizzabo-community-tab' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'unique_name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'unique_name' ) ); ?>" type="text" value="<?php echo esc_html( $instance['unique_name'] ); ?>" required="required" />
		</p>
		<p class="description"><?php esc_html_e( 'Please enter your Unique Name.', 'bizzabo-community-tab' ); ?></p>

		<?php
	}
}


/**
 * Register this widget with WordPress. Can also move this function to the parent plugin.
 *
 * @since  0.1.0
 * @return  null
 */
function register_bizzabo_community_tab_widget() {
	register_widget( 'Bizzabo_Community_Tab_Widget' );
}
add_action( 'widgets_init', 'register_bizzabo_community_tab_widget' );
