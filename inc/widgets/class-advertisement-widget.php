<?php
/**
 * Advertisement Widget
 *
 * @package beritanih
 */

class Beritanih_Advertisement_Widget extends WP_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct(
			'beritanih_advertisement',
			__( 'Advertisement', 'beritanih' ),
			array(
				'description' => __( 'Display advertisement banners with customizable size and content.', 'beritanih' ),
				'classname'   => 'beritanih-advertisement-widget',
			)
		);
	}

	/**
	 * Widget output
	 */
	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$ad_code = ! empty( $instance['ad_code'] ) ? $instance['ad_code'] : '';
		$width = ! empty( $instance['width'] ) ? $instance['width'] : '300';
		$height = ! empty( $instance['height'] ) ? $instance['height'] : '250';
		$ad_type = ! empty( $instance['ad_type'] ) ? $instance['ad_type'] : 'banner';

		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
		}
		?>
		<div class="text-center">
			<?php if ( ! empty( $ad_code ) ) : ?>
				<!-- Custom Ad Code -->
				<div class="advertisement-content">
					<?php echo wp_kses_post( $ad_code ); ?>
				</div>
			<?php else : ?>
				<!-- Placeholder Ad -->
				<p class="text-sm text-gray-500 mb-3"><?php esc_html_e( 'Advertisement', 'beritanih' ); ?></p>
				<?php
				$gradient_class = 'from-gray-100 to-gray-200';
				$border_class = 'border-gray-300';
				$icon_class = 'text-gray-400';
				$text_class = 'text-gray-400';
				
				if ( $ad_type === 'skyscraper' ) {
					$gradient_class = 'from-blue-50 to-indigo-100';
					$border_class = 'border-blue-300';
					$icon_class = 'text-blue-400';
					$text_class = 'text-blue-500';
				}
				?>
				<div class="bg-gradient-to-br <?php echo esc_attr( $gradient_class ); ?> flex items-center justify-center rounded-lg border-2 border-dashed <?php echo esc_attr( $border_class ); ?>" style="height: <?php echo esc_attr( $height ); ?>px;">
					<div class="text-center">
						<?php if ( $ad_type === 'skyscraper' ) : ?>
							<svg class="w-12 h-12 <?php echo esc_attr( $icon_class ); ?> mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
							</svg>
						<?php else : ?>
							<svg class="w-12 h-12 <?php echo esc_attr( $icon_class ); ?> mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
							</svg>
						<?php endif; ?>
						<p class="<?php echo esc_attr( $text_class ); ?> text-sm font-medium"><?php echo esc_html( $width . 'x' . $height ); ?> px</p>
						<p class="<?php echo esc_attr( $text_class ); ?> text-xs">
							<?php echo $ad_type === 'skyscraper' ? esc_html__( 'Skyscraper Banner', 'beritanih' ) : esc_html__( 'Banner Ad', 'beritanih' ); ?>
						</p>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<?php
		echo $args['after_widget'];
	}

	/**
	 * Widget form
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Advertisement', 'beritanih' );
		$ad_code = ! empty( $instance['ad_code'] ) ? $instance['ad_code'] : '';
		$width = ! empty( $instance['width'] ) ? $instance['width'] : '300';
		$height = ! empty( $instance['height'] ) ? $instance['height'] : '250';
		$ad_type = ! empty( $instance['ad_type'] ) ? $instance['ad_type'] : 'banner';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'beritanih' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'ad_type' ) ); ?>"><?php esc_attr_e( 'Ad Type:', 'beritanih' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'ad_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'ad_type' ) ); ?>">
				<option value="banner" <?php selected( $ad_type, 'banner' ); ?>><?php esc_html_e( 'Banner', 'beritanih' ); ?></option>
				<option value="skyscraper" <?php selected( $ad_type, 'skyscraper' ); ?>><?php esc_html_e( 'Skyscraper', 'beritanih' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>"><?php esc_attr_e( 'Width (px):', 'beritanih' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'width' ) ); ?>" type="number" value="<?php echo esc_attr( $width ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"><?php esc_attr_e( 'Height (px):', 'beritanih' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'height' ) ); ?>" type="number" value="<?php echo esc_attr( $height ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'ad_code' ) ); ?>"><?php esc_attr_e( 'Ad Code (HTML/JavaScript):', 'beritanih' ); ?></label>
			<textarea class="widefat" rows="5" id="<?php echo esc_attr( $this->get_field_id( 'ad_code' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'ad_code' ) ); ?>"><?php echo esc_textarea( $ad_code ); ?></textarea>
			<small><?php esc_html_e( 'Leave empty to show placeholder. Paste your ad network code here (Google AdSense, etc.)', 'beritanih' ); ?></small>
		</p>
		<?php
	}

	/**
	 * Update widget
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['ad_code'] = ( ! empty( $new_instance['ad_code'] ) ) ? wp_kses_post( $new_instance['ad_code'] ) : '';
		$instance['width'] = ( ! empty( $new_instance['width'] ) ) ? absint( $new_instance['width'] ) : 300;
		$instance['height'] = ( ! empty( $new_instance['height'] ) ) ? absint( $new_instance['height'] ) : 250;
		$instance['ad_type'] = ( ! empty( $new_instance['ad_type'] ) ) ? sanitize_text_field( $new_instance['ad_type'] ) : 'banner';

		return $instance;
	}
}
