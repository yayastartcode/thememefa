<?php
/**
 * Template part for displaying the header content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package beritanih
 */

?>

<header id="masthead" class="w-full">
	<!-- Breaking News Ticker and Search Section -->
	<div class="bg-blue-600 text-white py-2 px-4">
		<div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
			<!-- Breaking News Ticker -->
			<div class="flex items-center flex-1 min-w-0">
				<span class="hidden md:inline-block bg-red-600 text-white px-3 py-1 text-sm font-bold rounded mr-4 whitespace-nowrap">
					<?php esc_html_e( 'Breaking News', 'beritanih' ); ?>
				</span>
				<div class="overflow-hidden flex-1 min-w-0">
					<div id="breaking-news-rotator" class="relative h-6 lg:h-7 w-full" aria-live="polite">
						<?php
						// Get latest 5 posts for breaking news
						$breaking_news = get_posts(array(
							'numberposts' => 5,
							'post_status' => 'publish'
						));
						
						if (!empty($breaking_news)) {
							$i = 0;
							//print_r($breaking_news);
							foreach ($breaking_news as $pust) {
								$post_id = $pust->ID;
								$permalink = get_permalink( $post_id );
								//echo $post_id . ' - '. $permalink;
								//echo $pust->post_title . '<br>';
								$title = get_the_title($pust->ID);
								//$permalink = get_permalink($pust->ID);
								$opacity_class = ($i === 0) ? 'opacity-100' : 'opacity-0';
								echo '<span class="bn-item absolute inset-0 block ' . $opacity_class . '"><a href="' . esc_url($permalink) . '" class="hover:underline focus:underline focus:outline-none truncate whitespace-nowrap">' . esc_html($title) . '</a></span>';
								$i++;
							}
						} else {
							echo '<span class="bn-item absolute inset-0 block opacity-100">' . esc_html__('Latest news will appear here when posts are available', 'beritanih') . '</span>';
						}
						?>
					</div>
				</div>
			</div>
			
			<!-- Search Form -->
			<div class="flex-shrink-0 flex items-center gap-3">
				<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="hidden md:flex">
					<input 
						type="search" 
						name="s" 
						value="<?php echo get_search_query(); ?>"
						placeholder="<?php esc_attr_e('Topik berita apa yang Anda cari?', 'beritanih'); ?>"
						class="px-4 py-2 text-gray-900 bg-white rounded-l-md border-0 focus:ring-2 focus:ring-white focus:outline-none w-64 lg:w-80"
						aria-label="<?php esc_attr_e('Search', 'beritanih'); ?>"
					>
					<button 
						type="submit" 
						class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-r-md transition-colors duration-200"
						aria-label="<?php esc_attr_e('Submit search', 'beritanih'); ?>"
					>
						<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
						</svg>
					</button>
				</form>
				<button
					id="search-toggle"
					type="button"
					class="md:hidden p-2 rounded-lg bg-white/20 hover:bg-white/30 text-white focus:outline-none focus:ring-2 focus:ring-white"
					aria-controls="search-overlay"
					aria-expanded="false"
					aria-label="<?php esc_attr_e('Open search', 'beritanih'); ?>"
				>
					<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
					</svg>
				</button>
			</div>
		</div>
	</div>

	<!-- Search overlay -->
	<div id="search-overlay" class="fixed inset-0 z-50 hidden bg-black/70">
		<div class="absolute inset-0" id="search-overlay-backdrop"></div>
		<div class="relative z-10 flex items-center justify-center h-full p-4">
			<div class="w-full max-w-2xl bg-white dark:bg-gray-900 rounded-xl shadow-lg p-6">
				<div class="flex justify-between items-center mb-4">
					<h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200"><?php esc_html_e('Search', 'beritanih'); ?></h2>
					<button id="search-overlay-close" class="p-2 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none" aria-label="<?php esc_attr_e('Close search', 'beritanih'); ?>">
						<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
						</svg>
					</button>
				</div>
				<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="flex">
					<input
						type="search"
						name="s"
						value="<?php echo get_search_query(); ?>"
						placeholder="<?php esc_attr_e('Cari berita...', 'beritanih'); ?>"
						class="flex-1 px-4 py-3 rounded-l-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:outline-none"
						aria-label="<?php esc_attr_e('Search', 'beritanih'); ?>"
					>
					<button
						type="submit"
						class="px-4 py-3 rounded-r-md bg-red-600 hover:bg-red-700 text-white font-semibold transition-colors duration-200"
						aria-label="<?php esc_attr_e('Submit search', 'beritanih'); ?>"
					>
						<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
						</svg>
					</button>
				</form>
			</div>
		</div>
	</div>
	
	<!-- Main Header Section -->
	<div class="bg-white dark:bg-gray-900 shadow-sm border-b border-gray-200 dark:border-gray-700">
		<div class="max-w-7xl mx-auto px-4 py-4">
			<div class="flex items-center justify-between">
				<!-- Logo -->
				<div class="flex items-center">
					<?php if (is_front_page()) : ?>
						<h1 class="text-2xl lg:text-3xl font-bold">
							<a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors duration-200">
								<svg class="w-8 h-8 mr-2" viewBox="0 0 24 24" fill="currentColor">
									<path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
								</svg>
								<?php bloginfo('name'); ?>
							</a>
						</h1>
					<?php else : ?>
						<div class="text-2xl lg:text-3xl font-bold">
							<a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors duration-200">
								<svg class="w-8 h-8 mr-2" viewBox="0 0 24 24" fill="currentColor">
									<path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
								</svg>
								<?php bloginfo('name'); ?>
							</a>
						</div>
					<?php endif; ?>
				</div>

				<!-- Right Side: Pages Menu + Dark/Light Mode Toggle and Mobile Menu -->
				<div class="flex items-center space-x-4">
					<!-- Pages Menu Horizontal Navigation -->
					<?php if ( has_nav_menu('menu-pages') ) : ?>                                                                                                                                                                          
						<nav class="hidden lg:block">
							<?php
							wp_nav_menu([
								'theme_location' => 'menu-pages',
								'menu_id'        => 'pages-horizontal-menu',
								'menu_class'     => 'flex items-center gap-3 whitespace-nowrap',
								'container'      => false,
								'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
								'link_before'    => '<span class="inline-flex items-center px-3 py-1.5 rounded-md font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 focus:bg-gray-200 dark:focus:bg-gray-700 focus:outline-none transition-colors duration-200">',
								'link_after'     => '</span>',
							]);
							?>
						</nav>
					<?php endif; ?>
					</div>

					<!-- Dark/Light Mode Toggle -->
					<button 
						id="theme-toggle" 
						class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200"
						aria-label="<?php esc_attr_e('Toggle dark mode', 'beritanih'); ?>"
					>
						<svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
							<path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
						</svg>
						<svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
						</svg>
					</button>

					<!-- Mobile Menu Button -->
					<button
						id="mobile-menu-button"
						type="button"
						class="lg:hidden p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200"
						aria-controls="mobile-menu"
						aria-expanded="false"
						aria-label="<?php esc_attr_e('Toggle mobile menu', 'beritanih'); ?>"
					>
						<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
						</svg>
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Navigation Menu -->
	<nav id="site-navigation" class="text-white my-2" aria-label="<?php esc_attr_e('Main Navigation', 'beritanih'); ?>">
		<div class="max-w-7xl mx-auto px-4 bg-blue-600 rounded-xl">
			<!-- Desktop Menu -->
			<div class="hidden lg:block">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'primary-menu',
						'menu_class'     => 'flex flex-nowrap items-center gap-3 py-2 overflow-x-auto no-scrollbar whitespace-nowrap',
						'container'      => false,
						'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
						'link_before'    => '<span class="inline-flex items-center px-3 py-1.5 rounded-md font-medium text-white hover:bg-blue-700 focus:bg-blue-700 focus:outline-none transition-colors duration-200">',
						'link_after'     => '</span>',
					)
				);
				?>
			</div>

			<!-- Mobile Menu -->
			<div id="mobile-menu" class="lg:hidden hidden">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'mobile-primary-menu',
						'menu_class'     => 'flex flex-nowrap items-center gap-2 py-2 overflow-x-auto no-scrollbar whitespace-nowrap',
						'container'      => false,
						'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
						'link_before'    => '<span class="inline-flex items-center px-3 py-1.5 rounded-md bg-blue-500/20 hover:bg-blue-500/30 text-white font-medium transition-colors duration-200">',
						'link_after'     => '</span>',
					)
				);
				?>
			</div>
		</div>
	</nav>

	<!-- Trending Tags Section -->
	<?php
	$random_tags = beritanih_get_random_tags( 15 );
	if ( ! empty( $random_tags ) ) :
	?>
	<div class="trending-tags bg-gray-100 border-t border-gray-200 py-3 no-scrollbar">
		<div class="max-w-7xl mx-auto px-4">
			<div class="flex items-center gap-4">
				<span class="text-sm font-semibold text-gray-700 uppercase tracking-wide whitespace-nowrap">
					<?php esc_html_e( 'Trending', 'beritanih' ); ?>
				</span>
				<div class="flex-1 min-w-0 flex flex-nowrap md:flex-wrap items-center gap-2 overflow-x-auto no-scrollbar">
					<?php foreach ( $random_tags as $tag ) : ?>
						<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" 
						   class="inline-flex items-center px-3 py-1 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-full hover:bg-gray-50 hover:text-gray-800 transition-colors duration-200 whitespace-nowrap">
							#<?php echo esc_html( $tag->name ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>

</header><!-- #masthead -->

<!-- Header JavaScript for Mobile Menu, Search Toggle, and News Ticker -->
<script>
(function() {
	'use strict';

	document.addEventListener('DOMContentLoaded', function() {
		// Mobile Menu Toggle
		const mobileMenuButton = document.getElementById('mobile-menu-button');
		const mobileMenu = document.getElementById('mobile-menu');

		if (mobileMenuButton && mobileMenu) {
			mobileMenuButton.addEventListener('click', function(e) {
				e.preventDefault();
				const isExpanded = this.getAttribute('aria-expanded') === 'true';
				this.setAttribute('aria-expanded', !isExpanded);
				mobileMenu.classList.toggle('hidden');
			});
		}

		// Search Toggle
		const searchToggle = document.getElementById('search-toggle');
		const searchOverlay = document.getElementById('search-overlay');
		const searchOverlayClose = document.getElementById('search-overlay-close');
		const searchOverlayBackdrop = document.getElementById('search-overlay-backdrop');

		if (searchToggle && searchOverlay) {
			// Open search overlay
			searchToggle.addEventListener('click', function(e) {
				e.preventDefault();
				searchOverlay.classList.remove('hidden');
				const searchInput = searchOverlay.querySelector('input[type="search"]');
				if (searchInput) {
					setTimeout(() => searchInput.focus(), 100);
				}
			});

			// Close search overlay
			const closeSearch = function(e) {
				e.preventDefault();
				searchOverlay.classList.add('hidden');
			};

			if (searchOverlayClose) {
				searchOverlayClose.addEventListener('click', closeSearch);
			}

			if (searchOverlayBackdrop) {
				searchOverlayBackdrop.addEventListener('click', closeSearch);
			}

			// Close on Escape key
			document.addEventListener('keydown', function(e) {
				if (e.key === 'Escape' && !searchOverlay.classList.contains('hidden')) {
					searchOverlay.classList.add('hidden');
				}
			});
		}

		// Breaking News Ticker/Rotator
		const breakingNewsRotator = document.getElementById('breaking-news-rotator');
		const newsItems = breakingNewsRotator ? breakingNewsRotator.querySelectorAll('.bn-item') : [];

		if (newsItems.length > 1) {
			let currentNewsIndex = 0;

			function rotateNews() {
				// Hide current item
				newsItems[currentNewsIndex].classList.remove('opacity-100');
				newsItems[currentNewsIndex].classList.add('opacity-0');

				// Move to next item
				currentNewsIndex = (currentNewsIndex + 1) % newsItems.length;

				// Show next item
				newsItems[currentNewsIndex].classList.remove('opacity-0');
				newsItems[currentNewsIndex].classList.add('opacity-100');
			}

			// Add transition class to all items
			newsItems.forEach(item => {
				item.style.transition = 'opacity 0.5s ease-in-out';
			});

			// Rotate every 4 seconds
			setInterval(rotateNews, 4000);
		}

		// Theme Toggle (Dark Mode)
		const themeToggle = document.getElementById('theme-toggle');
		const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
		const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

		if (themeToggle && themeToggleDarkIcon && themeToggleLightIcon) {
			// Check for saved theme preference or default to light mode
			const currentTheme = localStorage.getItem('theme') || 'light';

			if (currentTheme === 'dark') {
				document.documentElement.classList.add('dark');
				themeToggleLightIcon.classList.remove('hidden');
			} else {
				document.documentElement.classList.remove('dark');
				themeToggleDarkIcon.classList.remove('hidden');
			}

			themeToggle.addEventListener('click', function() {
				// Toggle icons
				themeToggleDarkIcon.classList.toggle('hidden');
				themeToggleLightIcon.classList.toggle('hidden');

				// Toggle dark mode
				if (document.documentElement.classList.contains('dark')) {
					document.documentElement.classList.remove('dark');
					localStorage.setItem('theme', 'light');
				} else {
					document.documentElement.classList.add('dark');
					localStorage.setItem('theme', 'dark');
				}
			});
		}
	});
})();
</script>
