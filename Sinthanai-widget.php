<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Adds Sinthanai_Widget widget.
 */
class Sinthanai_Widget extends WP_Widget {
	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'Sinthanai_Widget', // Base ID
			'Sinthanai', // Name
			array( 'description' => __( 'Display a random Sinthani of National Leader of Tamileelam', 'text_domain'))
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		$sinthanai_font_size = ( ! empty( $instance['sinthanai_font_size'] ) ) ? absint( $instance['sinthanai_font_size'] ) : 16;
		if ( ! $sinthanai_font_size ) {
			$sinthanai_font_size = 16;
		}
		$sinthanai_font_size = $kural_font_size . "px";

		$sinthanai_italics = empty( $instance['sinthanai_italics'] ) ? 'normal' : 'italic';

		$sinthanai_bold = empty( $instance['sinthanai_bold'] ) ? 'normal' : 'bold';

		global $wpdb;
		$sinthanai_table = $wpdb->prefix . "sinthanai";
		

		$sinthanai_id = rand(1, 94);
		$sqlSinthanai = "SELECT * FROM `".$sinthanai_table."` WHERE `id` = ".$sinthanai_id;
		$sinthanai = $wpdb->get_row( $sqlSinthanai );
		//$kural_array = explode(',', $kural->kural);

		

		echo $before_widget;
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		?>

		<?php if ( $show_chapter_section ) { ?>
		<div class="row">
			<div class="col-md-6 text-right">
				<p <?php echo "style=\"font-size: ".$sinthanai_font_size ?>";\">
					<?php echo $sinthanai->section_name ?>
				</p>
			</div>
		</div>
		<?php } ?>
		<div class="row kural-wrapper" <?php echo " style=\"font-size: ".$sinthanai_font_size."; font-weight: ". $sinthanai_bold ."; font-style:".$sinthanai_italics.";\">" ?>
			<div class="col-md-12 kural"><?php echo $sinthanai->sinthanai ?></div>
			<div class="text-right kural-author">
				<small>தமிழீழ தேசிய தலைவர்</small>
			</div>
		</div>


		<?php 
	echo $after_widget;
}
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['sinthanai_font_size'] = (int) $new_instance['sinthanai_font_size'];
		$instance['sinthanai_italics'] = !empty( $new_instance['sinthanai_italics'] ) ? 1 : 0;
		$instance['sinthanai_bold'] = !empty( $new_instance['sinthanai_bold'] ) ? 1 : 0;
		$instance['show_chapter_section'] = !empty( $new_instance['show_chapter_section'] ) ? 1 : 0;
		return $instance;
	}

	/**
	 * Outputs the settings form for the Tamil Sinthanai widget.
	 *
	 * @see WP_Widget::form()	 *
	 * @param array $instance Saved values from database.	 *
	 */
	public function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance);
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : __( 'தலைவரின் சிந்தனை', 'text_domain');
		$sinthanai_font_size    = isset( $instance['sinthanai_font_size'] ) ? absint( $instance['sinthanai_font_size'] ) : 16;
		$sinthanai_italics = isset($instance['sinthanai_italics']) ? (bool) $instance['sinthanai_italics'] :false;
		$sinthanai_bold = isset($instance['sinthanai_bold']) ? (bool) $instance['sinthanai_bold'] :false;
		$show_chapter_section = isset($instance['show_chapter_section']) ? (bool) $instance['show_chapter_section'] :false;
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">
				<?php _e( 'Widget Title:' ); ?>
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p style="background:#f2f2f2">
			<b>Customize Sinthanai</b><br/>
			<label for="<?php echo $this->get_field_id( 'sinthanai_font_size' ); ?>">
				<?php _e( 'Font Size(px):' ); ?>
			</label>
			<input id="<?php echo $this->get_field_id( 'sinthanai_font_size' ); ?>" name="<?php echo $this->get_field_name( 'sinthanai_font_size' ); ?>" type="number" min="-30" max="100" step="1" value="<?php echo $sinthanai_font_size; ?>" />

			<label for="<?php echo $this->get_field_id( 'sinthanai_italics' ); ?>">
				<?php _e( 'Italics:' ); ?>
			</label>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('sinthanai_italics'); ?>" name="<?php echo $this->get_field_name('sinthanai_italics'); ?>"<?php checked( $sinthanai_italics ); ?> />

			<label for="<?php echo $this->get_field_id( 'sinthanai_bold' ); ?>">
				<?php _e( 'Bold:' ); ?>
			</label>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('sinthanai_bold'); ?>" name="<?php echo $this->get_field_name('sinthanai_bold'); ?>"<?php checked( $sinthanai_bold ); ?> />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'show_chapter_section' ); ?>"><?php _e( 'Show Chapter & Section:' ); ?></label>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_chapter_section'); ?>" name="<?php echo $this->get_field_name('show_chapter_section'); ?>"<?php checked( $show_chapter_section ); ?> />
		</p>
		<?php
	}
}

// Register Sinthanai_Widget widget

add_action( 'widgets_init', function() { register_widget( 'Sinthanai_Widget' ); } );
