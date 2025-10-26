<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no `home.php` file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package beritanih
 */

get_header();
?>

	<!-- Hero Slider Section -->
	<?php
	// Query untuk mengambil 4 post terbaru untuk slider
	$slider_posts = new WP_Query(array(
		'posts_per_page' => 4,
		'post_status' => 'publish',
	));

	if ($slider_posts->have_posts()) :
	?>
	<section class="hero-slider-section py-2 relative overflow-hidden">
		<div class="hero-slider-container relative !h-[350px]">
			<!-- Slider dengan efek separuh post di samping -->
			<div class="hero-slider-track relative h-full flex items-center justify-center">
				<div class="hero-slider-viewport w-full max-w-7xl mx-auto relative overflow-hidden rounded-xl h-full">
					<div class="hero-slider-wrapper flex flex-nowrap transition-transform duration-500 ease-in-out h-full" id="heroSlider">
						<?php 
						$slide_index = 0;
						$posts_array = array();
						
						// Simpan posts dalam array untuk manipulasi
						while ($slider_posts->have_posts()) : 
							$slider_posts->the_post();
							$thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
							
							if (!$thumbnail_url) {
								
								$thumbnail_url = get_template_directory_uri() . '/screenshot.png'; // fallback image
							}
							$posts_array[] = array(
								'id' => get_the_ID(),
								'title' => get_the_title(),
								'permalink' => get_the_permalink(),
								'thumbnail' => $thumbnail_url,
								'category' => get_the_category(),
								'date' => get_the_date('l, d M Y'),
								'author' => get_the_author(),
								'comments' => get_comments_number()
							);
						endwhile;
						
						// Untuk infinite scroll, duplikasi slides
						$all_slides = array_merge($posts_array, $posts_array, $posts_array); // Triple untuk smooth infinite
						
						// Tampilkan slides dengan efek separuh
						foreach ($all_slides as $index => $post) :
							$slide_index++;
							$original_index = $index % count($posts_array);
						?>
						<div class="hero-slide flex-shrink-0 relative w-full md:w-1/2 !h-[350px]" data-slide="<?php echo $slide_index; ?>" data-original="<?php echo $original_index; ?>">
							<div class="slide-content relative mx-2 rounded-xl overflow-hidden h-full">
								<!-- Background Image -->
								<?php if ($post['thumbnail']) : ?>
								<div class="absolute inset-0 bg-cover bg-center bg-no-repeat" 
									 style="background-image: url('<?php echo esc_url($post['thumbnail']); ?>');">
									<div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
								</div>
								<?php else : ?>
								<div class="absolute inset-0 bg-gray-600">
									<div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
								</div>
								<?php endif; ?>
								
								<!-- Content -->
								<div class="relative z-10 h-full flex flex-col items-center justify-end text-center p-4 md:p-6 text-white">
									<?php if (!empty($post['category'])) : ?>
									<span class="hidden md:inline-block bg-red-600 text-white text-xs font-semibold px-3 py-1 rounded-full mb-3">
										<?php echo esc_html($post['category'][0]->name); ?>
									</span>
									<?php endif; ?>
									
									<h2 class="text-base md:text-lg lg:text-xl font-semibold mb-2 leading-tight">
										<a href="<?php echo $post['permalink']; ?>" class="hover:text-gray-200 transition-colors">
											<?php echo $post['title']; ?>
										</a>
									</h2>
									
									<div class="flex items-center gap-2 md:gap-3 text-xs text-gray-300">
										<span><?php echo $post['date']; ?></span>
										<span class="hidden md:inline">•</span>
										<span class="hidden md:inline"><?php echo $post['author']; ?></span>
									</div>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
				</div>
				<!-- Navigation Arrows moved inside viewport -->
				<button class="hero-slider-nav prev absolute top-1/2 -translate-y-1/2 left-4 z-20 w-8 h-8 flex items-center justify-center bg-white text-gray-900 rounded-full shadow focus:outline-none focus:ring-2 focus:ring-white" id="heroPrev">
					<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</button>
				<button class="hero-slider-nav next absolute top-1/2 -translate-y-1/2 right-4 z-20 w-8 h-8 flex items-center justify-center bg-white text-gray-900 rounded-full shadow focus:outline-none focus:ring-2 focus:ring-white" id="heroNext">
					<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</button>
				</div>
				
				<!-- Navigation moved inside viewport -->
				
				<!-- Indicators removed -->
			</div>
		</div>
	</section>
	
	<!-- Hero Slider Configuration -->
	<script>
		// Pass PHP data to JavaScript
		window.heroSliderConfig = {
			originalSlideCount: <?php echo count($posts_array); ?>
		};
	</script>
	
	<?php
	endif;
	wp_reset_postdata();
	?>

	<!-- Pilihan Editor Slider Section -->
	<?php
	// Query untuk mengambil 4 post pilihan editor
	$editor_posts = new WP_Query(array(
		'posts_per_page' => 4,
		'post_status' => 'publish',
		'meta_key' => 'editor_choice',
		'meta_value' => '1'
	));

	// Fallback: jika tidak ada post dengan meta editor_choice, ambil 4 post terbaru
	if (!$editor_posts->have_posts()) {
		$editor_posts = new WP_Query(array(
			'posts_per_page' => 4,
			'post_status' => 'publish',
			'orderby' => 'date',
			'order' => 'DESC'
		));
	}

	if ($editor_posts->have_posts()) :
	?>
	<section class="pilihan-editor-section py-8 md:py-12 relative overflow-hidden">
		<div class="relative z-10 max-w-7xl mx-auto px-4 py-2 rounded-2xl bg-cover bg-center bg-no-repeat"
				 style="background-image: linear-gradient(to right, rgb(30 58 138 / 0.95), rgb(88 28 135 / 0.9), rgb(131 24 67 / 0.95)), url('<?php echo get_template_directory_uri(); ?>/img/gdg.webp');">
			<!-- Background Image -->
			<div class="absolute inset-0 left-1/2 -translate-x-1/2 w-screen bg-cover bg-center bg-no-repeat -z-10">
			</div>
			<!-- Section Header -->
			<div class="flex items-center justify-between mb-8">
				<div class="flex items-center gap-3">
		
					<h2 class="text-lg md:text-xl font-semibold text-white section-title-border mt-2">PILIHAN EDITOR</h2>
				</div>

				<!-- Navigation Arrows -->
				<div class="flex gap-2">
					<button class="editor-slider-nav prev w-10 h-10 flex items-center justify-center bg-white/20 hover:bg-white/30 text-white rounded-full transition-all duration-300" id="editorPrev">
						<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
							<path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>
					<button class="editor-slider-nav next w-10 h-10 flex items-center justify-center bg-white/20 hover:bg-white/30 text-white rounded-full transition-all duration-300" id="editorNext">
						<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
							<path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>
				</div>
			</div>

			<!-- Slider Container -->
			<div class="editor-slider-wrapper overflow-hidden">
				<div class="editor-slider-track flex transition-transform duration-500 ease-in-out gap-4 md:gap-6" id="editorSlider">
					<?php
					while ($editor_posts->have_posts()) :
						$editor_posts->the_post();
						$thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
						if (!$thumbnail_url) {
							$thumbnail_url = get_template_directory_uri() . '/screenshot.png';
						}
						$categories = get_the_category();
						$has_video = has_post_format('video');
					?>
					<div class="editor-slide flex-shrink-0 w-full md:w-[calc(50%-12px)] lg:w-[calc(25%-18px)]">
						<div class="editor-card bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 h-full">
							<!-- Card Image -->
							<div class="relative h-48 md:h-56 overflow-hidden group">
								<img src="<?php echo esc_url($thumbnail_url); ?>"
									 alt="<?php echo esc_attr(get_the_title()); ?>"
									 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
								<div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

								<?php if ($has_video) : ?>
								<!-- Video Play Button -->
								<div class="absolute inset-0 flex items-center justify-center">
									<div class="w-16 h-16 bg-white/90 rounded-full flex items-center justify-center">
										<svg class="w-8 h-8 text-red-600 ml-1" fill="currentColor" viewBox="0 0 20 20">
											<path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
										</svg>
									</div>
								</div>
								<!-- Video Count -->
								<div class="absolute top-3 left-3 bg-black/70 text-white px-3 py-1 rounded-full flex items-center gap-2 text-sm">
									<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
										<path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
									</svg>
									<span>5</span>
								</div>
								<?php endif; ?>

								<!-- Duration Badge (if video) -->
								<?php if ($has_video) : ?>
								<div class="absolute bottom-3 right-3 bg-black/80 text-white px-2 py-1 rounded text-xs font-semibold">
									2:10
								</div>
								<?php endif; ?>
							</div>

							<!-- Card Content -->
							<div class="p-4">
								<!-- Categories -->
								<?php if (!empty($categories)) : ?>
								<div class="flex flex-wrap gap-2 mb-3">
									<?php foreach (array_slice($categories, 0, 2) as $category) : ?>
									<span class="text-xs font-semibold text-red-600">
										<?php echo esc_html($category->name); ?>
									</span>
									<?php endforeach; ?>
								</div>
								<?php endif; ?>

								<!-- Title -->
								<h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 line-clamp-2 hover:text-blue-600 transition-colors">
									<a href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								</h3>

								<!-- Meta Info -->
								<div class="flex items-center gap-2 text-xs text-gray-500">
									<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
									</svg>
									<span><?php echo get_the_author(); ?>, <?php echo get_the_date('d M Y'); ?></span>
								</div>
							</div>
						</div>
					</div>
					<?php endwhile; ?>
			</div>
		</div>
	</section>



	<?php
	endif;
	wp_reset_postdata();
	?>

	
	<!-- Topik Terhangat Section -->
	<?php
	// Get all categories
	$categories = get_categories(array(
		'orderby' => 'count',
		'order' => 'DESC',
		'number' => 5,
		'hide_empty' => true
	));

	if (!empty($categories)) :
	?>
	<section class="topik-terhangat-section py-8 md:py-12">
		<div class="max-w-7xl mx-auto px-4">
			<!-- Section Header with Category Tabs -->
			<div class="flex items-center justify-between mb-8 border-b border-gray-200 pb-4">
				<h2 class="text-md md:text-lg font-bold text-gray-900 section-title-border">TOPIK TERHANGAT</h2>

				<!-- Category Tabs -->
				<nav class="px-2 flex gap-2 overflow-x-auto no-scrollbar">
					<?php foreach ($categories as $index => $category) : ?>
					<button
						class="category-tab px-4 py-2 text-sm font-semibold rounded-lg whitespace-nowrap transition-all duration-300 <?php echo $index === 0 ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>"
						data-category="<?php echo esc_attr($category->term_id); ?>"
						data-index="<?php echo $index; ?>">
						<?php echo esc_html($category->name); ?>
					</button>
					<?php endforeach; ?>
					<a href="<?php echo get_permalink(get_option('page_for_posts')); ?>"
					   class="px-4 py-2 text-sm font-semibold text-red-600 hover:text-red-700 whitespace-nowrap flex items-center gap-1">
						Lihat Semua
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
						</svg>
					</a>
				</nav>
			</div>

			<!-- Posts Container -->
			<div class="posts-container">
				<?php foreach ($categories as $cat_index => $category) :
					// Query 3 posts from this category
					$cat_posts = new WP_Query(array(
						'cat' => $category->term_id,
						'posts_per_page' => 3,
						'post_status' => 'publish'
					));

					if ($cat_posts->have_posts()) :
				?>
				<div class="category-posts <?php echo $cat_index === 0 ? '' : 'hidden'; ?>" data-category="<?php echo esc_attr($category->term_id); ?>">
					<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
						<?php
						$post_index = 0;
						while ($cat_posts->have_posts()) :
							$cat_posts->the_post();
							$thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
							if (!$thumbnail_url) {
								$thumbnail_url = get_template_directory_uri() . '/screenshot.png';
							}
							$post_categories = get_the_category();
							$has_video = has_post_format('video');
							$has_gallery = has_post_format('gallery');
						?>

						<?php if ($post_index === 0) : // Large card on the left ?>
						<div class="lg:row-span-2">
							<article class="group relative h-full bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
								<!-- Image -->
								<div class="relative h-64 lg:h-full overflow-hidden">
									<img src="<?php echo esc_url($thumbnail_url); ?>"
										 alt="<?php echo esc_attr(get_the_title()); ?>"
										 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
									<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

									<?php if ($has_video) : ?>
									<!-- Video Icon -->
									<div class="absolute top-4 left-4 bg-black/70 text-white px-3 py-1 rounded-full flex items-center gap-2 text-sm">
										<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
											<path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
										</svg>
										<span>5</span>
									</div>
									<?php endif; ?>

									<?php if ($has_gallery) : ?>
									<!-- Gallery Icon -->
									<div class="absolute top-4 left-4 bg-black/70 text-white px-3 py-1 rounded-full flex items-center gap-2 text-sm">
										<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
											<path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
										</svg>
										<span>5</span>
									</div>
									<?php endif; ?>

									<!-- Content Overlay -->
									<div class="absolute bottom-0 left-0 right-0 p-6 text-white">
										<!-- Category -->
										<?php if (!empty($post_categories)) : ?>
										<span class="inline-block bg-red-600 text-white text-xs font-semibold px-3 py-1 rounded-full mb-3">
											<?php echo esc_html($post_categories[0]->name); ?>
										</span>
										<?php endif; ?>

										<!-- Title -->
										<h3 class="text-xl lg:text-2xl font-semibold mb-3 leading-tight">
											<a href="<?php the_permalink(); ?>" class="hover:text-gray-200 transition-colors">
												<?php the_title(); ?>
											</a>
										</h3>

										<!-- Excerpt -->
										<p class="text-gray-300 text-sm mb-4 line-clamp-2">
											<?php echo wp_trim_words(get_the_excerpt(), 20); ?>
										</p>

										<!-- Meta -->
										<div class="flex items-center gap-2 text-xs text-gray-300">
											<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
											</svg>
											<span><?php echo get_the_author(); ?>, <?php echo get_the_date('d M Y'); ?></span>
										</div>
									</div>
								</div>
							</article>
						</div>

						<?php else : // Smaller cards on the right ?>
						<article class="group bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
							<div class="flex gap-4 h-full">
								<!-- Image -->
								<div class="relative w-40 md:w-48 flex-shrink-0 overflow-hidden">
									<img src="<?php echo esc_url($thumbnail_url); ?>"
										 alt="<?php echo esc_attr(get_the_title()); ?>"
										 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">

									<?php if ($has_video) : ?>
									<!-- Video Badge -->
									<div class="absolute top-2 right-2 bg-black/80 text-white px-2 py-1 rounded text-xs font-semibold">
										2:10
									</div>
									<?php endif; ?>
								</div>

								<!-- Content -->
								<div class="flex-1 p-4 flex flex-col justify-between">
									<div>
										<!-- Category & Title -->
										<?php if (!empty($post_categories)) : ?>
										<span class="text-xs font-semibold text-red-600 mb-2 inline-block">
											<?php echo esc_html($post_categories[0]->name); ?>
										</span>
										<?php endif; ?>

										<h3 class="text-base md:text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
											<a href="<?php the_permalink(); ?>">
												<?php the_title(); ?>
											</a>
										</h3>

										<p class="text-gray-600 text-sm line-clamp-2 mb-3">
											<?php echo wp_trim_words(get_the_excerpt(), 15); ?>
										</p>
									</div>

									<!-- Meta -->
									<div class="flex items-center gap-2 text-xs text-gray-500">
										<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
										</svg>
										<span><?php echo get_the_author(); ?>, <?php echo get_the_date('d M Y'); ?></span>
									</div>
								</div>
							</div>
						</article>
						<?php endif; ?>

						<?php
						$post_index++;
						endwhile;
						?>
					</div>
				</div>
				<?php
				endif;
				wp_reset_postdata();
				endforeach;
				?>
			</div>
		</div>
	</section>



	<?php endif; ?>

	<!-- Layout Dua Kolom: Konten Kiri & Sidebar Kanan -->
	<section class="py-8 md:py-12 bg-gray-50">
		<div class="two-column-layout">
			<!-- Konten Kiri -->
			<div class="content-left">
					<!-- Header Konten -->
					<div class="mb-8">
						<h2 class="text-3xl font-bold text-gray-900 mb-2 section-title-border">Berita Terbaru</h2>
						<p class="text-gray-600">Artikel dan berita terkini</p>
					</div>

					<?php
					// Query untuk post terbaru
					$paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
					$latest_posts = new WP_Query(array(
						'post_type' => 'post',
						'posts_per_page' => 6,
						'paged' => $paged,
						'post_status' => 'publish'
					));

					if ($latest_posts->have_posts()) :
						while ($latest_posts->have_posts()) : $latest_posts->the_post();
							$categories = get_the_category();
							$first_category = !empty($categories) ? $categories[0] : null;
																										$category_color = 'bg-red-600';
					?>
					<article class="bg-white rounded-xl shadow-lg overflow-hidden mb-6 hover:shadow-xl transition-all duration-300">
						<div class="md:flex">
							<div class="md:w-1/3">
								<?php if (has_post_thumbnail()) : ?>
									<div class="h-48 md:h-full overflow-hidden">
										<?php the_post_thumbnail('medium', array('class' => 'w-full h-full object-cover')); ?>
									</div>
								<?php else : ?>
									<div class="h-48 md:h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
										<div class="text-center text-white">
											<svg class="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
												<path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
											</svg>
											<p class="text-sm">No Image</p>
										</div>
									</div>
								<?php endif; ?>
							</div>
							<div class="md:w-2/3 p-6">
								<?php if ($first_category) : ?>
									<div class="mb-3">
										<span class="inline-block <?php echo $category_color; ?> text-white px-3 py-1 text-sm font-semibold rounded">
											<?php echo esc_html($first_category->name); ?>
										</span>
									</div>
								<?php endif; ?>
								<h3 class="text-xl font-bold text-gray-900 mb-3 hover:text-blue-600 transition-colors">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h3>
								<p class="text-gray-600 mb-4">
									<?php echo wp_trim_words(get_the_excerpt(), 25, '...'); ?>
								</p>
								<div class="flex items-center justify-between text-sm text-gray-500">
									<span><?php echo get_the_date('j F Y'); ?></span>
									<span><?php comments_number('0 komentar', '1 komentar', '% komentar'); ?></span>
								</div>
							</div>
						</div>
					</article>
					<?php endwhile; ?>

					<!-- Pagination WordPress -->
					<div class="flex justify-center mt-8 mb-2">
						<?php
						// Get the base URL for pagination
						$big = 999999999; // need an unlikely integer
						
						echo beritanih_paginate_links(array(
							'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
							'format' => '/page/%#%/',
							'total' => $latest_posts->max_num_pages,
							'current' => max(1, $paged),
							'show_all' => false,
							'type' => 'list',
							'end_size' => 2,
							'mid_size' => 1,
							'prev_next' => true,
							'prev_text' => '« Sebelumnya',
							'next_text' => 'Selanjutnya »',
							'add_args' => false,
							'add_fragment' => '',
							'before_page_number' => '',
							'after_page_number' => ''
						));
						?>
					</div>

					<?php else : ?>
						<div class="text-center py-12 px-2">
							<h3 class="text-xl font-semibold text-gray-600 mb-4">Belum ada artikel</h3>
							<p class="text-gray-500">Silakan kembali lagi nanti untuk membaca artikel terbaru.</p>
						</div>
					<?php endif; 
					wp_reset_postdata(); ?>
					<!-- Category Slider Section -->
	<?php
	// Check if category slider is enabled
	$category_slider_enabled = get_theme_mod('beritanih_category_slider_enable', true);
	$selected_category_id = get_theme_mod('beritanih_category_slider_category', '');
	$section_title = get_theme_mod('beritanih_category_slider_title', 'SPORT');

	if ($category_slider_enabled && !empty($selected_category_id)) :
		// Query 5 posts from selected category
		$category_posts = new WP_Query(array(
			'cat' => $selected_category_id,
			'posts_per_page' => 5,
			'post_status' => 'publish'
		));

		if ($category_posts->have_posts()) :
	?>
	<section class="category-slider-section py-8 md:py-12 bg-gray-50">
		<div class="max-w-7xl mx-auto px-4">
			<!-- Section Header -->
			<div class="flex items-center justify-between mb-8">
				<div class="flex items-center gap-3">
					<div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
						<svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
						</svg>
					</div>
					<h2 class="text-2xl md:text-3xl font-bold text-gray-900 section-title-border"><?php echo esc_html($section_title); ?></h2>
				</div>

				<!-- Navigation Arrows -->
				<div class="flex gap-2">
					<button class="category-slider-nav prev w-10 h-10 flex items-center justify-center bg-white hover:bg-gray-100 text-gray-700 rounded-full shadow transition-all duration-300" id="categoryPrev">
						<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
							<path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>
					<button class="category-slider-nav next w-10 h-10 flex items-center justify-center bg-white hover:bg-gray-100 text-gray-700 rounded-full shadow transition-all duration-300" id="categoryNext">
						<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
							<path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>
				</div>
			</div>

			<!-- Slider Container -->
			<div class="category-slider-wrapper overflow-hidden">
				<div class="category-slider-track flex transition-transform duration-500 ease-in-out gap-4" id="categorySlider">
					<?php
					while ($category_posts->have_posts()) :
						$category_posts->the_post();
						$thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
						if (!$thumbnail_url) {
							$thumbnail_url = get_template_directory_uri() . '/screenshot.png';
						}
						$categories = get_the_category();
						$has_video = has_post_format('video');
						$video_duration = get_post_meta(get_the_ID(), 'video_duration', true) ?: '12:39';
					?>
					<div class="category-slide flex-shrink-0">
						<div class="category-card bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 h-full">
							<!-- Card Image -->
							<div class="relative h-48 md:h-56 overflow-hidden group">
								<img src="<?php echo esc_url($thumbnail_url); ?>"
									 alt="<?php echo esc_attr(get_the_title()); ?>"
									 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
								<div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

								<?php if ($has_video) : ?>
								<!-- Video Play Button -->
								<div class="absolute inset-0 flex items-center justify-center">
									<div class="w-16 h-16 bg-white/90 rounded-full flex items-center justify-center">
										<svg class="w-8 h-8 text-blue-600 ml-1" fill="currentColor" viewBox="0 0 20 20">
											<path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
										</svg>
									</div>
								</div>
								<?php endif; ?>

								<!-- Duration Badge -->
								<div class="absolute bottom-3 right-3 bg-black/80 text-white px-2 py-1 rounded text-xs font-semibold">
									<?php echo esc_html($video_duration); ?>
								</div>

								<!-- Photo Count (if gallery) -->
								<?php if (has_post_format('gallery')) : ?>
								<div class="absolute top-3 right-3 bg-black/70 text-white px-2 py-1 rounded flex items-center gap-1 text-xs">
									<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
										<path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
									</svg>
									<span>5</span>
								</div>
								<?php endif; ?>
							</div>

							<!-- Card Content -->
							<div class="p-4">
								<!-- Title -->
								<h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 line-clamp-2 hover:text-blue-600 transition-colors">
									<a href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								</h3>

								<!-- Meta Info -->
								<div class="flex items-center gap-2 text-xs text-gray-500">
									<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
									</svg>
									<span><?php echo get_the_date('l, d M Y'); ?></span>
								</div>
							</div>
						</div>
					</div>
					<?php endwhile; ?>
				</div>
			</div>

			<!-- View All Link -->
			<div class="text-center mt-8">
				<a href="<?php echo get_category_link($selected_category_id); ?>" 
				   class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-semibold">
					» Lihat Semua: <?php echo esc_html($section_title); ?>
					<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
					</svg>
				</a>
			</div>
		</div>
	</section>

	<?php
		endif;
		wp_reset_postdata();
	endif;
	?>

	<!-- Second Category Slider Section -->
	<?php
	// Check if second category slider is enabled
	$category_slider_2_enabled = get_theme_mod('beritanih_category_slider_2_enable', true);
	$selected_category_2_id = get_theme_mod('beritanih_category_slider_2_category', '');
	$section_2_title = get_theme_mod('beritanih_category_slider_2_title', 'TECHNOLOGY');

	if ($category_slider_2_enabled && !empty($selected_category_2_id)) :
		// Query 5 posts from selected category
		$category_2_posts = new WP_Query(array(
			'cat' => $selected_category_2_id,
			'posts_per_page' => 5,
			'post_status' => 'publish'
		));

		if ($category_2_posts->have_posts()) :
	?>
	<section class="category-slider-section py-8 md:py-12 bg-white">
		<div class="max-w-md lg:max-w-7xl mx-auto px-4">
			<!-- Section Header -->
			<div class="flex items-center justify-between mb-8">
				<div class="flex items-center gap-3">
					<div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center">
						<svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
							<path d="M9 12a1 1 0 01-.117-1.993L9 10h6a1 1 0 01.117 1.993L15 12H9zm-4.5 0a1 1 0 01-.117-1.993L4.5 10h2a1 1 0 01.117 1.993L6.5 12h-2zm13-4a1 1 0 01-.117-1.993L17.5 6h2a1 1 0 01.117 1.993L19.5 8h-2zm-4.5 0a1 1 0 01-.117-1.993L13 6h2a1 1 0 01.117 1.993L15 8h-2zm-4.5 0a1 1 0 01-.117-1.993L9 6h2a1 1 0 01.117 1.993L11 8H9zm-4.5 0a1 1 0 01-.117-1.993L4.5 6h2a1 1 0 01.117 1.993L6.5 8h-2z"/>
						</svg>
					</div>
					<h2 class="text-2xl md:text-3xl font-bold text-gray-900 section-title-border"><?php echo esc_html($section_2_title); ?></h2>
				</div>

				<!-- Navigation Arrows -->
				<div class="flex gap-2">
					<button class="category-slider-nav prev w-10 h-10 flex items-center justify-center bg-white hover:bg-gray-100 text-gray-700 rounded-full shadow transition-all duration-300" id="category2Prev">
						<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
							<path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>
					<button class="category-slider-nav next w-10 h-10 flex items-center justify-center bg-white hover:bg-gray-100 text-gray-700 rounded-full shadow transition-all duration-300" id="category2Next">
						<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
							<path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>
				</div>
			</div>

			<!-- Slider Container -->
			<div class="category-slider-wrapper overflow-hidden">
				<div class="category-slider-track flex transition-transform duration-500 ease-in-out gap-4" id="category2Slider">
					<?php
					while ($category_2_posts->have_posts()) :
						$category_2_posts->the_post();
						$thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
						if (!$thumbnail_url) {
							$thumbnail_url = get_template_directory_uri() . '/screenshot.png';
						}
						$categories = get_the_category();
						$has_video = has_post_format('video');
						$video_duration = get_post_meta(get_the_ID(), 'video_duration', true) ?: '12:39';
					?>
					<div class="category-slide flex-shrink-0">
						<div class="category-card bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 h-full">
							<!-- Card Image -->
							<div class="relative h-48 md:h-56 overflow-hidden group">
								<img src="<?php echo esc_url($thumbnail_url); ?>"
									 alt="<?php echo esc_attr(get_the_title()); ?>"
									 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
								<div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

								<?php if ($has_video) : ?>
								<!-- Video Play Button -->
								<div class="absolute inset-0 flex items-center justify-center">
									<div class="w-16 h-16 bg-white/90 rounded-full flex items-center justify-center">
										<svg class="w-8 h-8 text-green-600 ml-1" fill="currentColor" viewBox="0 0 20 20">
											<path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
										</svg>
									</div>
								</div>
								<?php endif; ?>

								<!-- Duration Badge -->
								<div class="absolute bottom-3 right-3 bg-black/80 text-white px-2 py-1 rounded text-xs font-semibold">
									<?php echo esc_html($video_duration); ?>
								</div>

								<!-- Photo Count (if gallery) -->
								<?php if (has_post_format('gallery')) : ?>
								<div class="absolute top-3 right-3 bg-black/70 text-white px-2 py-1 rounded flex items-center gap-1 text-xs">
									<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
										<path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
									</svg>
									<span>5</span>
								</div>
								<?php endif; ?>
							</div>

							<!-- Card Content -->
							<div class="p-4">
								<!-- Title -->
								<h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 line-clamp-2 hover:text-green-600 transition-colors">
									<a href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								</h3>

								<!-- Meta Info -->
								<div class="flex items-center gap-2 text-xs text-gray-500">
									<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
									</svg>
									<span><?php echo get_the_date('l, d M Y'); ?></span>
								</div>
							</div>
						</div>
					</div>
					<?php endwhile; ?>
				</div>
			</div>

			<!-- View All Link -->
			<div class="text-center mt-8">
				<a href="<?php echo get_category_link($selected_category_2_id); ?>" 
				   class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 font-semibold">
					» Lihat Semua: <?php echo esc_html($section_2_title); ?>
					<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
					</svg>
				</a>
			</div>
		</div>
	</section>

	<?php
		endif;
		wp_reset_postdata();
	endif;
	?>

	<!-- Third Category Slider Section -->
	<?php
	// Check if third category slider is enabled
	$category_slider_3_enabled = get_theme_mod('beritanih_category_slider_3_enable', true);
	$selected_category_3_id = get_theme_mod('beritanih_category_slider_3_category', '');
	$section_3_title = get_theme_mod('beritanih_category_slider_3_title', 'LIFESTYLE');

	if ($category_slider_3_enabled && !empty($selected_category_3_id)) :
		// Query 5 posts from selected category
		$category_3_posts = new WP_Query(array(
			'cat' => $selected_category_3_id,
			'posts_per_page' => 5,
			'post_status' => 'publish'
		));

		if ($category_3_posts->have_posts()) :
	?>
	<section class="category-slider-section py-8 md:py-12 bg-gray-50">
		<div class="max-w-7xl mx-auto px-4">
			<!-- Section Header -->
			<div class="flex items-center justify-between mb-8">
				<div class="flex items-center gap-3">
					<div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center">
						<svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
						</svg>
					</div>
					<h2 class="text-2xl md:text-3xl font-bold text-gray-900 section-title-border"><?php echo esc_html($section_3_title); ?></h2>
				</div>

				<!-- Navigation Arrows -->
				<div class="flex gap-2">
					<button class="category-slider-nav prev w-10 h-10 flex items-center justify-center bg-white hover:bg-gray-100 text-gray-700 rounded-full shadow transition-all duration-300" id="category3Prev">
						<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
							<path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>
					<button class="category-slider-nav next w-10 h-10 flex items-center justify-center bg-white hover:bg-gray-100 text-gray-700 rounded-full shadow transition-all duration-300" id="category3Next">
						<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
							<path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>
				</div>
			</div>

			<!-- Slider Container -->
			<div class="category-slider-wrapper overflow-hidden">
				<div class="category-slider-track flex transition-transform duration-500 ease-in-out gap-4" id="category3Slider">
					<?php
					while ($category_3_posts->have_posts()) :
						$category_3_posts->the_post();
						$thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
						if (!$thumbnail_url) {
							$thumbnail_url = get_template_directory_uri() . '/screenshot.png';
						}
						$categories = get_the_category();
						$has_video = has_post_format('video');
		$video_duration = get_post_meta(get_the_ID(), 'video_duration', true) ?: '12:39';
					?>
					<div class="category-slide flex-shrink-0">
						<div class="category-card bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 h-full">
							<!-- Card Image -->
							<div class="relative h-48 md:h-56 overflow-hidden group">
								<img src="<?php echo esc_url($thumbnail_url); ?>"
									 alt="<?php echo esc_attr(get_the_title()); ?>"
									 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
								<div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

								<?php if ($has_video) : ?>
								<!-- Video Play Button -->
								<div class="absolute inset-0 flex items-center justify-center">
									<div class="w-16 h-16 bg-white/90 rounded-full flex items-center justify-center">
										<svg class="w-8 h-8 text-purple-600 ml-1" fill="currentColor" viewBox="0 0 20 20">
											<path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
										</svg>
									</div>
								</div>
								<?php endif; ?>

								<!-- Duration Badge -->
								<div class="absolute bottom-3 right-3 bg-black/80 text-white px-2 py-1 rounded text-xs font-semibold">
									<?php echo esc_html($video_duration); ?>
								</div>

								<!-- Photo Count (if gallery) -->
								<?php if (has_post_format('gallery')) : ?>
								<div class="absolute top-3 right-3 bg-black/70 text-white px-2 py-1 rounded flex items-center gap-1 text-xs">
									<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
										<path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
									</svg>
									<span>5</span>
								</div>
								<?php endif; ?>
							</div>

							<!-- Card Content -->
							<div class="p-4">
								<!-- Title -->
								<h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 line-clamp-2 hover:text-purple-600 transition-colors">
									<a href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								</h3>

								<!-- Meta Info -->
								<div class="flex items-center gap-2 text-xs text-gray-500">
									<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
									</svg>
									<span><?php echo get_the_date('l, d M Y'); ?></span>
								</div>
							</div>
						</div>
					</div>
					<?php endwhile; ?>
				</div>
			</div>

			<!-- View All Link -->
			<div class="text-center mt-8">
				<a href="<?php echo get_category_link($selected_category_3_id); ?>" 
				   class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-700 font-semibold">
					» Lihat Semua: <?php echo esc_html($section_3_title); ?>
					<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
					</svg>
				</a>
			</div>
		</div>
	</section>

	<?php
		endif;
		wp_reset_postdata();
	endif;
	?>


		</div>

				<?php get_sidebar(); ?>
			</div>
		</div>
	</section>

	<section id="primary" class="hidden">
		<main id="main">

		<?php
		if ( have_posts() ) {

			if ( is_home() && ! is_front_page() ) :
				?>
				<header class="entry-header">
					<h1 class="entry-title"><?php single_post_title(); ?></h1>
				</header><!-- .entry-header -->
				<?php
			endif;

			// Load posts loop.
			while ( have_posts() ) {
				the_post();
				get_template_part( 'template-parts/content/content' );
			}

			// Previous/next page navigation.
			beritanih_the_posts_navigation();

		} else {

			// If no content, include the "No posts found" template.
			get_template_part( 'template-parts/content/content', 'none' );

		}
		?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
