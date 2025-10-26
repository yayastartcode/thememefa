<?php
/**
 * Categories Widget
 *
 * @package beritanih
 */

class Beritanih_Categories_Widget extends WP_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct(
			'beritanih_categories',
			__( 'Categories List', 'beritanih' ),
			array(
				'description' => __( 'Display categories with post count and colored indicators.', 'beritanih' ),
				'classname'   => 'beritanih-categories-widget',
			)
		);
	}

	/**
	 * Widget output
	 */
	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Categories', 'beritanih' );
		$number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 8;
		$orderby = ! empty( $instance['orderby'] ) ? $instance['orderby'] : 'count';
		$order = ! empty( $instance['order'] ) ? $instance['order'] : 'DESC';

		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
		}

		// Get categories
		$categories = get_categories( array(
			'orderby'    => $orderby,
			'order'      => $order,
			'number'     => $number,
			'hide_empty' => true,
		) );

		if ( ! empty( $categories ) ) :
			$category_colors = array(
				'teknologi' => 'bg-blue-600',
				'ekonomi'   => 'bg-green-600',
				'politik'   => 'bg-red-600',
				'olahraga'  => 'bg-orange-600',
				'kesehatan' => 'bg-purple-600',
				'berita'    => 'bg-indigo-600',
				'lifestyle'  => 'bg-pink-600',
				'default'   => 'bg-gray-600',
			);
			?>
			<div class="space-y-3">
				<?php foreach ( $categories as $category ) :
					$cat_slug = strtolower( $category->slug );
					$color_class = isset( $category_colors[ $cat_slug ] ) ? $category_colors[ $cat_slug ] : $category_colors['default'];
					?>
					<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors group">
						<div class="flex items-center gap-3">
							<div class="w-3 h-3 <?php echo esc_attr( $color_class ); ?> rounded-full"></div>
							<span class="text-gray-700 group-hover:text-blue-600 font-medium"><?php echo esc_html( $category->name ); ?></span>
						</div>
						<span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-full"><?php echo $category->count; ?></span>
					</a>
				<?php endforeach; ?>
			</div>
			<?php
		else :
			?>
			<div class="text-center py-4">
				<p class="text-gray-500 text-sm"><?php esc_html_e( 'No categories found.', 'beritanih' ); ?></p>
			</div>
			<?php
		endif;

		echo $args['after_widget'];
	}

	/**
	 * Widget form
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Categories', 'beritanih' );
		$number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 8;
		$orderby = ! empty( $instance['orderby'] ) ? $instance['orderby'] : 'count';
		$order = ! empty( $instance['order'] ) ? $instance['order'] : 'DESC';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'beritanih' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_attr_e( 'Number of categories to show:', 'beritanih' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_attr_e( 'Order by:', 'beritanih' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>">
				<option value="count" <?php selected( $orderby, 'count' ); ?>><?php esc_html_e( 'Post Count', 'beritanih' ); ?></option>
				<option value="name" <?php selected( $orderby, 'name' ); ?>><?php esc_html_e( 'Name', 'beritanih' ); ?></option>
				<option value="slug" <?php selected( $orderby, 'slug' ); ?>><?php esc_html_e( 'Slug', 'beritanih' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_attr_e( 'Order:', 'beritanih' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">
				<option value="DESC" <?php selected( $order, 'DESC' ); ?>><?php esc_html_e( 'Descending', 'beritanih' ); ?></option>
				<option value="ASC" <?php selected( $order, 'ASC' ); ?>><?php esc_html_e( 'Ascending', 'beritanih' ); ?></option>
			</select>
		</p>
		<?php
	}

	/**
	 * Update widget
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? absint( $new_instance['number'] ) : 8;
		$instance['orderby'] = ( ! empty( $new_instance['orderby'] ) ) ? sanitize_text_field( $new_instance['orderby'] ) : 'count';
		$instance['order'] = ( ! empty( $new_instance['order'] ) ) ? sanitize_text_field( $new_instance['order'] ) : 'DESC';

		return $instance;
	}
}
