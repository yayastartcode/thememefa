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
	
	<!-- Hero Slider JavaScript - Diperbaiki -->
	<script>
	(function() {
		'use strict';

		document.addEventListener('DOMContentLoaded', function() {
			const slider = document.querySelector('.hero-slider-wrapper');
			const slides = document.querySelectorAll('.hero-slide');
			const nextBtn = document.querySelector('.hero-slider-nav.next');
			const prevBtn = document.querySelector('.hero-slider-nav.prev');
			const indicators = document.querySelectorAll('.hero-slider-indicator');

			console.log('Slider elements found:', {
				slider: !!slider,
				slides: slides.length,
				nextBtn: !!nextBtn,
				prevBtn: !!prevBtn,
				indicators: indicators.length
			});

			if (!slider || slides.length === 0) {
				console.error('Slider elements not found');
				return;
			}

			const originalSlideCount = <?php echo count($posts_array); ?>;
			const totalSlides = slides.length;
			let currentSlide = originalSlideCount; // Start at middle set
			let isTransitioning = false;

			// Measurements
			const viewport = document.querySelector('.hero-slider-viewport');
			const getSlideWidth = () => slides[0]?.getBoundingClientRect().width || 0;
			const getViewportWidth = () => viewport?.getBoundingClientRect().width || 0;
			let slideWidth = getSlideWidth();
			let viewportWidth = getViewportWidth();
			function getBaseOffset() {
				return Math.max((viewportWidth - slideWidth) / 2, 0);
			}
			function updateMeasurements() {
				slideWidth = getSlideWidth();
				viewportWidth = getViewportWidth();
			}
			function updateTransform() {
				const offset = currentSlide * slideWidth - getBaseOffset();
				slider.style.transform = `translateX(-${offset}px)`;
			}
			window.addEventListener('resize', () => {
				updateMeasurements();
				updateTransform();
			});

			// Initialize slider
			function initSlider() {
				updateMeasurements();
				updateTransform();
				updateActiveSlide();
				console.log('Slider initialized at slide:', currentSlide);
			}

			function updateActiveSlide() {
				// Remove active class from all slides
				slides.forEach(slide => slide.classList.remove('active'));

				// Add active class to current slide
				if (slides[currentSlide]) {
					slides[currentSlide].classList.add('active');
				}

				// Update indicators with Tailwind classes
				indicators.forEach(indicator => {
					indicator.classList.remove('active');
					indicator.classList.remove('bg-white');
					indicator.classList.add('bg-gray-100');
				});
				const originalIndex = parseInt(slides[currentSlide]?.getAttribute('data-original')) || 0;
				if (indicators[originalIndex]) {
					indicators[originalIndex].classList.add('active');
					indicators[originalIndex].classList.add('bg-white');
					indicators[originalIndex].classList.remove('bg-gray-100');
				}
			}

			function moveToSlide(slideIndex, withTransition = true) {
				if (isTransitioning) return;

				if (withTransition) {
					isTransitioning = true;
					slider.style.transition = 'transform 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
				} else {
					slider.style.transition = 'none';
				}

				currentSlide = slideIndex;
				updateTransform();
				updateActiveSlide();

				if (withTransition) {
					setTimeout(() => {
						isTransitioning = false;
					}, 600);
				}
			}

			function nextSlide() {
				if (isTransitioning) return;

				console.log('Next slide clicked, current:', currentSlide);
				const nextIndex = currentSlide + 1;
				moveToSlide(nextIndex);

				// Reset position for infinite loop
				setTimeout(() => {
					if (currentSlide >= totalSlides - originalSlideCount) {
						moveToSlide(originalSlideCount, false);
					}
				}, 600);
			}

			function prevSlide() {
				if (isTransitioning) return;

				console.log('Prev slide clicked, current:', currentSlide);
				const prevIndex = currentSlide - 1;
				moveToSlide(prevIndex);

				// Reset position for infinite loop
				setTimeout(() => {
					if (currentSlide < originalSlideCount) {
						moveToSlide(totalSlides - originalSlideCount - 1, false);
					}
				}, 600);
			}

			function goToSlide(originalIndex) {
				if (isTransitioning) return;

				console.log('Indicator clicked:', originalIndex);
				const targetSlide = originalSlideCount + originalIndex;
				moveToSlide(targetSlide);
			}

			// Event listeners dengan error handling
			if (nextBtn) {
				nextBtn.addEventListener('click', (e) => {
					e.preventDefault();
					nextSlide();
				});
				console.log('Next button listener added');
			}

			if (prevBtn) {
				prevBtn.addEventListener('click', (e) => {
					e.preventDefault();
					prevSlide();
				});
				console.log('Prev button listener added');
			}

			// Indicator clicks
			indicators.forEach((indicator, index) => {
				indicator.addEventListener('click', (e) => {
					e.preventDefault();
					goToSlide(index);
				});
			});
			console.log('Indicator listeners added:', indicators.length);

			// Auto-play slider
			let autoPlayInterval = setInterval(nextSlide, 4000);

			// Pause auto-play on hover
			const sliderSection = document.querySelector('.hero-slider-section');
			if (sliderSection) {
				sliderSection.addEventListener('mouseenter', () => {
					clearInterval(autoPlayInterval);
				});

				sliderSection.addEventListener('mouseleave', () => {
					autoPlayInterval = setInterval(nextSlide, 4000);
				});
			}

			// Touch/swipe support
			let startX = 0;
			let endX = 0;

			if (slider) {
				slider.addEventListener('touchstart', (e) => {
					startX = e.touches[0].clientX;
				});

				slider.addEventListener('touchend', (e) => {
					endX = e.changedTouches[0].clientX;
					handleSwipe();
				});
			}

			function handleSwipe() {
				const swipeThreshold = 50;
				const diff = startX - endX;

				if (Math.abs(diff) > swipeThreshold) {
					if (diff > 0) {
						nextSlide();
					} else {
						prevSlide();
					}
				}
			}

			// Initialize slider
			initSlider();
		});
	})();
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
					<div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
						<svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
							<path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
							<path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
						</svg>
					</div>
					<h2 class="text-2xl md:text-3xl font-bold text-white section-title-border">PILIHAN EDITOR</h2>
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

	<!-- Pilihan Editor Slider JavaScript -->
	<script>
	(function() {
		'use strict';

		document.addEventListener('DOMContentLoaded', function() {
			const editorSlider = document.getElementById('editorSlider');
			const editorSlides = document.querySelectorAll('.editor-slide');
			const editorNextBtn = document.getElementById('editorNext');
			const editorPrevBtn = document.getElementById('editorPrev');

			if (!editorSlider || editorSlides.length === 0) return;

			const originalSlideCount = editorSlides.length;
			let currentIndex = 0;
			let isTransitioning = false;

			// Clone slides for infinite effect
			function setupInfiniteSlider() {
				// Clone all slides and append/prepend for seamless infinite scroll
				const slidesHTML = editorSlider.innerHTML;
				editorSlider.innerHTML = slidesHTML + slidesHTML + slidesHTML;

				// Start at middle set
				currentIndex = originalSlideCount;
				updateSliderPosition(false);
			}

			// Get visible slides count based on screen width
			function getVisibleSlides() {
				if (window.innerWidth >= 1024) return 4; // lg: 4 slides
				if (window.innerWidth >= 768) return 2;  // md: 2 slides
				return 1; // mobile: 1 slide
			}

			function updateSliderPosition(withTransition = true) {
				const allSlides = document.querySelectorAll('.editor-slide');
				const slideWidth = allSlides[0]?.offsetWidth || 0;
				const gap = window.innerWidth >= 768 ? 24 : 16;
				const offset = currentIndex * (slideWidth + gap);

				if (withTransition) {
					editorSlider.style.transition = 'transform 0.5s ease-in-out';
				} else {
					editorSlider.style.transition = 'none';
				}

				editorSlider.style.transform = `translateX(-${offset}px)`;
			}

			function nextSlide() {
				if (isTransitioning) return;

				isTransitioning = true;
				currentIndex++;
				updateSliderPosition(true);

				// Reset to middle set when reaching end
				setTimeout(() => {
					const allSlides = document.querySelectorAll('.editor-slide');
					if (currentIndex >= originalSlideCount * 2) {
						currentIndex = originalSlideCount;
						updateSliderPosition(false);
					}
					isTransitioning = false;
				}, 500);
			}

			function prevSlide() {
				if (isTransitioning) return;

				isTransitioning = true;
				currentIndex--;
				updateSliderPosition(true);

				// Reset to middle set when reaching start
				setTimeout(() => {
					if (currentIndex < originalSlideCount) {
						currentIndex = originalSlideCount * 2 - 1;
						updateSliderPosition(false);
					}
					isTransitioning = false;
				}, 500);
			}

			// Event listeners
			if (editorNextBtn) {
				editorNextBtn.addEventListener('click', nextSlide);
			}

			if (editorPrevBtn) {
				editorPrevBtn.addEventListener('click', prevSlide);
			}

			// Resize handler
			let resizeTimer;
			window.addEventListener('resize', function() {
				clearTimeout(resizeTimer);
				resizeTimer = setTimeout(function() {
					updateSliderPosition(false);
				}, 250);
			});

			// Touch/swipe support
			let touchStartX = 0;
			let touchEndX = 0;

			editorSlider.addEventListener('touchstart', (e) => {
				touchStartX = e.touches[0].clientX;
			});

			editorSlider.addEventListener('touchend', (e) => {
				touchEndX = e.changedTouches[0].clientX;
				handleSwipe();
			});

			function handleSwipe() {
				const swipeThreshold = 50;
				const diff = touchStartX - touchEndX;

				if (Math.abs(diff) > swipeThreshold) {
					if (diff > 0) {
						nextSlide();
					} else {
						prevSlide();
					}
				}
			}

			// Initialize
			setupInfiniteSlider();
		});
	})();
	</script>

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
				<h2 class="text-lg md:text-lg font-bold text-gray-900 section-title-border">TOPIK TERHANGAT</h2>

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

	<!-- Topik Terhangat JavaScript -->
	<script>
	(function() {
		'use strict';

		document.addEventListener('DOMContentLoaded', function() {
			const categoryTabs = document.querySelectorAll('.category-tab');
			const categoryPostsContainers = document.querySelectorAll('.category-posts');

			if (categoryTabs.length === 0 || categoryPostsContainers.length === 0) return;

			categoryTabs.forEach(tab => {
				tab.addEventListener('click', function(e) {
					e.preventDefault();
					const categoryId = this.getAttribute('data-category');

					// Update tab styles
					categoryTabs.forEach(t => {
						t.classList.remove('bg-red-600', 'text-white');
						t.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
					});
					this.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
					this.classList.add('bg-red-600', 'text-white');

					// Show/hide posts
					categoryPostsContainers.forEach(container => {
						if (container.getAttribute('data-category') === categoryId) {
							container.classList.remove('hidden');
						} else {
							container.classList.add('hidden');
						}
					});
				});
			});
		});
	})();
	</script>

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
					$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
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
					<div class="flex justify-center mt-8">
						<?php
						echo paginate_links(array(
							'total' => $latest_posts->max_num_pages,
							'current' => $paged,
							'format' => '?paged=%#%',
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
						<div class="text-center py-12">
							<h3 class="text-xl font-semibold text-gray-600 mb-4">Belum ada artikel</h3>
							<p class="text-gray-500">Silakan kembali lagi nanti untuk membaca artikel terbaru.</p>
						</div>
					<?php endif; 
					wp_reset_postdata(); ?>
				</div>

				<!-- Sidebar Kanan -->
				<div class="sidebar-right">
					<!-- Widget Terpopuler -->
					<div class="bg-white rounded-xl shadow-lg p-6 mb-6">
						<div class="flex items-center gap-2 mb-6 pb-3 border-b-2 border-blue-600">
							<svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
								<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
							</svg>
							<h3 class="text-xl font-bold text-gray-900 section-title-border">TERPOPULER</h3>
						</div>

						<?php
						// Query untuk post populer berdasarkan komentar
						$popular_posts = new WP_Query(array(
							'post_type' => 'post',
							'posts_per_page' => 5,
							'orderby' => 'comment_count',
							'order' => 'DESC',
							'post_status' => 'publish'
						));

						if ($popular_posts->have_posts()) :
							$counter = 1;
							while ($popular_posts->have_posts()) : $popular_posts->the_post();
								$categories = get_the_category();
								$first_category = !empty($categories) ? $categories[0] : null;
								$category_colors = array(
									'teknologi' => 'text-blue-600',
									'ekonomi' => 'text-green-600', 
									'politik' => 'text-red-600',
									'olahraga' => 'text-orange-600',
									'kesehatan' => 'text-purple-600',
									'default' => 'text-gray-600'
								);
								$category_color = 'text-gray-600';
								if ($first_category) {
									$cat_slug = strtolower($first_category->slug);
									$category_color = 'bg-red-600';
								}
						?>
						<!-- Item Populer <?php echo $counter; ?> -->
						<div class="flex gap-4 mb-4 pb-4 border-b border-gray-200 group">
							<div class="flex-shrink-0 w-12 flex items-start">
								<span class="text-3xl font-bold text-blue-600"><?php echo $counter; ?></span>
							</div>
							<div class="flex-1">
								<?php if (has_post_thumbnail()) : ?>
									<div class="h-16 rounded-lg mb-2 overflow-hidden">
										<?php the_post_thumbnail('thumbnail', array('class' => 'w-full h-full object-cover')); ?>
									</div>
								<?php else : ?>
									<div class="h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg mb-2 flex items-center justify-center">
										<svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
											<path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
										</svg>
									</div>
								<?php endif; ?>
								<h4 class="text-sm font-bold text-gray-900 mb-1 group-hover:text-blue-600 transition-colors">
									<a href="<?php the_permalink(); ?>"><?php echo wp_trim_words(get_the_title(), 8, '...'); ?></a>
								</h4>
								<?php if ($first_category) : ?>
									<span class="text-xs p-1 text-white <?php echo $category_color; ?> font-semibold"><?php echo esc_html($first_category->name); ?></span>
								<?php endif; ?>
							</div>
						</div>
						<?php 
							$counter++;
							endwhile; 
						else : ?>
							<div class="text-center py-4">
								<p class="text-gray-500 text-sm">Belum ada artikel populer</p>
							</div>
						<?php endif; 
						wp_reset_postdata(); ?>

						<!-- Item Populer 5 -->
						<div class="flex gap-4 group">
							<div class="flex-shrink-0 w-12 flex items-start">
								<span class="text-3xl font-bold text-blue-600">5</span>
							</div>
							<div class="flex-1">
								<div class="h-16 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-lg mb-2 flex items-center justify-center">
									<svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
										<path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
									</svg>
								</div>
								<h4 class="text-sm font-bold text-gray-900 mb-1 group-hover:text-blue-600 transition-colors">
									<a href="#">Teknologi Terbaru yang Mengubah Dunia</a>
								</h4>
								<span class="text-xs text-indigo-600 font-semibold">Teknologi</span>
							</div>
						</div>
					</div>

					<!-- Widget Iklan 1 -->
					<div class="bg-white rounded-xl shadow-lg p-6 mb-6">
						<div class="text-center">
							<p class="text-sm text-gray-500 mb-3">Advertisement</p>
							<div class="bg-gradient-to-br from-gray-100 to-gray-200 h-64 flex items-center justify-center rounded-lg border-2 border-dashed border-gray-300">
								<div class="text-center">
									<svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
									</svg>
									<p class="text-gray-400 text-sm font-medium">300x250 px</p>
									<p class="text-gray-400 text-xs">Banner Iklan</p>
								</div>
							</div>
						</div>
					</div>

					<!-- Widget Kategori -->
					<div class="bg-white rounded-xl shadow-lg p-6 mb-6">
						<div class="flex items-center gap-2 mb-6 pb-3 border-b-2 border-blue-600">
							<svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
								<path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
							</svg>
							<h3 class="text-xl font-bold text-gray-900 section-title-border">KATEGORI</h3>
						</div>

						<div class="space-y-3">
							<?php
							// Ambil kategori WordPress
							$categories = get_categories(array(
								'orderby' => 'count',
								'order' => 'DESC',
								'number' => 8,
								'hide_empty' => true
							));

							if (!empty($categories)) :
								$category_colors = array(
									'teknologi' => 'bg-blue-600',
									'ekonomi' => 'bg-green-600', 
									'politik' => 'bg-red-600',
									'olahraga' => 'bg-orange-600',
									'kesehatan' => 'bg-purple-600',
									'berita' => 'bg-indigo-600',
									'lifestyle' => 'bg-pink-600',
									'default' => 'bg-gray-600'
								);
								
								foreach ($categories as $category) :
									$cat_slug = strtolower($category->slug);
									$color_class = isset($category_colors[$cat_slug]) ? $category_colors[$cat_slug] : $category_colors['default'];
							?>
							<a href="<?php echo get_category_link($category->term_id); ?>" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors group">
								<div class="flex items-center gap-3">
									<div class="w-3 h-3 <?php echo $color_class; ?> rounded-full"></div>
									<span class="text-gray-700 group-hover:text-blue-600 font-medium"><?php echo esc_html($category->name); ?></span>
								</div>
								<span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-full"><?php echo $category->count; ?></span>
							</a>
							<?php endforeach; ?>
							<?php else : ?>
								<div class="text-center py-4">
									<p class="text-gray-500 text-sm">Belum ada kategori</p>
								</div>
							<?php endif; ?>
						</div>
					</div>

					<!-- Widget Iklan 2 -->
					<div class="bg-white rounded-xl shadow-lg p-6">
						<div class="text-center">
							<p class="text-sm text-gray-500 mb-3">Advertisement</p>
							<div class="bg-gradient-to-br from-blue-50 to-indigo-100 h-96 flex items-center justify-center rounded-lg border-2 border-dashed border-blue-300">
								<div class="text-center">
									<svg class="w-12 h-12 text-blue-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
									</svg>
									<p class="text-blue-500 text-sm font-medium">300x400 px</p>
									<p class="text-blue-400 text-xs">Skyscraper Banner</p>
								</div>
							</div>
						</div>
					</div>
				</div>
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
