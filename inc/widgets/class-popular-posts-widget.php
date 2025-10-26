<?php
/**
 * Popular Posts Widget
 *
 * @package beritanih
 */

class Beritanih_Popular_Posts_Widget extends WP_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct(
			'beritanih_popular_posts',
			__( 'Popular Posts', 'beritanih' ),
			array(
				'description' => __( 'Display popular posts based on comment count.', 'beritanih' ),
				'classname'   => 'beritanih-popular-posts-widget',
			)
		);
	}

	/**
	 * Widget output
	 */
	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Popular Posts', 'beritanih' );
		$number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;

		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
		}

		// Query popular posts
		$popular_posts = new WP_Query( array(
			'post_type'      => 'post',
			'posts_per_page' => $number,
			'orderby'        => 'comment_count',
			'order'          => 'DESC',
			'post_status'    => 'publish',
		) );

		if ( $popular_posts->have_posts() ) :
			$counter = 1;
			while ( $popular_posts->have_posts() ) :
				$popular_posts->the_post();
				$categories = get_the_category();
				$first_category = ! empty( $categories ) ? $categories[0] : null;
				?>
				<div class="flex gap-4 mb-4 pb-4 border-b border-gray-200 group last:border-b-0">
					<div class="flex-shrink-0 w-12 flex items-start">
						<span class="text-3xl font-bold text-blue-600"><?php echo $counter; ?></span>
					</div>
					<div class="flex-1">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="h-16 rounded-lg mb-2 overflow-hidden">
								<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'w-full h-full object-cover' ) ); ?>
							</div>
						<?php else : ?>
							<div class="h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg mb-2 flex items-center justify-center">
								<svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
									<path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
								</svg>
							</div>
						<?php endif; ?>
						<h4 class="text-sm font-bold text-gray-900 mb-1 group-hover:text-blue-600 transition-colors">
							<a href="<?php the_permalink(); ?>"><?php echo wp_trim_words( get_the_title(), 8, '...' ); ?></a>
						</h4>
						<?php if ( $first_category ) : ?>
							<span class="text-xs p-1 text-white bg-red-600 font-semibold"><?php echo esc_html( $first_category->name ); ?></span>
						<?php endif; ?>
					</div>
				</div>
				<?php
				$counter++;
			endwhile;
		else :
			?>
			<div class="text-center py-4">
				<p class="text-gray-500 text-sm"><?php esc_html_e( 'No popular posts found.', 'beritanih' ); ?></p>
			</div>
			<?php
		endif;
		wp_reset_postdata();

		echo $args['after_widget'];
	}

	/**
	 * Widget form
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Popular Posts', 'beritanih' );
		$number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'beritanih' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_attr_e( 'Number of posts to show:', 'beritanih' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3">
		</p>
		<?php
	}

	/**
	 * Update widget
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? absint( $new_instance['number'] ) : 5;

		return $instance;
	}
}
