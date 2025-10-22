/**
 * Sliders JavaScript for BeritaNih Theme
 * Contains Hero Slider, Editor Slider, and Category Tabs functionality
 */

(function() {
	'use strict';

	document.addEventListener('DOMContentLoaded', function() {
		initHeroSlider();
		initEditorSlider();
		initCategoryTabs();
	});

	/**
	 * Hero Slider Functionality
	 */
	function initHeroSlider() {
		const slider = document.querySelector('.hero-slider-wrapper');
		const slides = document.querySelectorAll('.hero-slide');
		const nextBtn = document.querySelector('.hero-slider-nav.next');
		const prevBtn = document.querySelector('.hero-slider-nav.prev');
		const indicators = document.querySelectorAll('.hero-slider-indicator');

		console.log('Hero Slider elements found:', {
			slider: !!slider,
			slides: slides.length,
			nextBtn: !!nextBtn,
			prevBtn: !!prevBtn,
			indicators: indicators.length
		});

		if (!slider || slides.length === 0) {
			console.error('Hero slider elements not found');
			return;
		}

		// Get original slide count from PHP
		const originalSlideCount = window.heroSliderConfig?.originalSlideCount || 4;
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
			console.log('Hero slider initialized at slide:', currentSlide);
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

		// Event listeners
		if (nextBtn) {
			nextBtn.addEventListener('click', (e) => {
				e.preventDefault();
				nextSlide();
			});
			console.log('Hero next button listener added');
		}

		if (prevBtn) {
			prevBtn.addEventListener('click', (e) => {
				e.preventDefault();
				prevSlide();
			});
			console.log('Hero prev button listener added');
		}

		// Indicator clicks
		indicators.forEach((indicator, index) => {
			indicator.addEventListener('click', (e) => {
				e.preventDefault();
				goToSlide(index);
			});
		});
		console.log('Hero indicator listeners added:', indicators.length);

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
	}

	/**
	 * Editor Slider Functionality
	 */
	function initEditorSlider() {
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
	}

	/**
	 * Category Tabs Functionality
	 */
	function initCategoryTabs() {
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
	}

})();