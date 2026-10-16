$(document).ready(function(){

	// Mobile Button Nav Show
	$('.btn-menu').click(function () {
		$('.mobile-menu').toggleClass('open');
		$('.btn-menu').toggleClass('open');
	});
	$('.mobile-menu a').click(function (e) {
		$('.mobile-menu').removeClass('open');
		$('.btn-menu').removeClass('open');
	});

	// Desktop Navigation Dropdowns
	$('.hasDropdown').click(function () {

		$(this).closest('li').toggleClass('active')
		.siblings('li').removeClass('active'); // if other dropdowns are open, hide them.

		// If this is a dropdown button in the top row nav
		if($(this).parents('.nav-top-row-inner').length == 1) {
			$('#navigation').find('li.active').removeClass('active');
		}    

		// If this is a dropdown button in the primary navigation
		if($(this).parents('#navigation').length == 1) {
			$('.nav-top-row-inner').find('li.active').removeClass('active');
		}

		if (  $('.page-header-container').hasClass("megamenu-open") ) {
			// alert('megamenu is open');
			$('.page-header-container').removeClass('megamenu-open');
		}
	});


	// Desktop Navigation Megamenu
	$('.hasMegamenu').click(function () {
		$(this).closest('li').toggleClass('active')
		.siblings('li').removeClass('active'); // if other dropdowns are open, hide them.

		// If a dropdown is open in the top row nav, make sure to close it as well
		$('.nav-top-row-inner').find('li.active').removeClass('active');

		// if page has a white nav and the mega menu is open, make sure to make the nav white again after closing the mega menu, or nav links will stay dark.
		if ($(this).closest('li').hasClass('active')) {
			// 'li' is .active - so show nav in color over the megamenu expanded
			$('.page-header-container').addClass('megamenu-open');	
		} else {
			// 'li' is not active anymore - so make nav white because the megamenu is hidden.
			$('.page-header-container').removeClass('megamenu-open');
		}
	});


	// If you click outside of the dropdown or megamenu, close the dropdown.
	$(document).mouseup(function(e){
		var dropDownMenu = $('.dropDownNav, .megamenu, .hasMegamenu, .hasDropdown');
		if (!dropDownMenu.is(e.target) // The target of the click isn't the dropdown.
		&& dropDownMenu.has(e.target).length === 0) // Nor a child element of the dropdown
		{
			$('.hasDropdown, .hasMegamenu').closest('li').removeClass('active');
			$('.page-header-container').removeClass('megamenu-open');

		}
		
	});
	
	
	// Mobile Nav and button
	var mainHeader = $('.page-header-container')

	mainHeader.on('click', '.nav-trigger', function(event){
		// Open Navigation on MOBILE
		event.preventDefault();
		mainHeader.toggleClass('nav-open');
	});


	// Lozad - Lazy Loading
	const observer = lozad('.lozad', {
		threshold: 0,
		loaded: function(el) {
			el.classList.add('fade');
	  	}
	  });
	  observer.observe();

	// FadeIn sections on scroll
	const faders = document.querySelectorAll(".animateOnScroll");
	const appearOptions = {
		threshold: 0.1
	};
	const appearOnScroll = new IntersectionObserver(function(
	entries,
	appearOnScroll
	) {
	entries.forEach(entry => {
		if (!entry.isIntersecting) {
			return;
		} else {
			entry.target.classList.add("visible");
			appearOnScroll.unobserve(entry.target);
		}
	});
	},
	appearOptions);
	faders.forEach(fader => {
	appearOnScroll.observe(fader);
	});
	// End Fade on scroll


	// Services Index page slider
	$(".slider-component").each(function (index) {
		const swiper = new Swiper($(this).find(".swiper")[0], {
			sliderPerView: 1,
			spaceBetween: 10,
			speed: 600,
			// loop: true,
			// centeredSlides: true,
			freeMode: true,
			grabCursor: true,
			keyboardControl: true,
			keyboard: true,

			breakpoints: {
				700: {
				  slidesPerView: 2.1,
				  spaceBetween: 25
				},
				1150: {
				  slidesPerView: 3.1,
				  spaceBetween: 30
				}
			},
			// Pagination dots
			pagination: {
			  el: '.swiper-pagination',
			  clickable: true,
			},
			// navigation arrows
			navigation: {
				nextEl: '.swiper-arrow-next',
				prevEl: '.swiper-arrow-prev',
			}
		});
	});


	// Resources Block slider if more than 4 entries selected
	$(".slider-resources-component").each(function (index) {
		const swiper = new Swiper($(this).find(".swiper")[0], {
			slideClass: 'grid-item',
			slidesPerView: 1.1,
			speed: 600, 
			autoHeight: true,
			draggable: true,
			keyboardControl: true,
			keyboard: true,
			grabCursor: true,
			snapOnRelease: true,
			// Pagination dots
			pagination: {
			  el: '.swiper-pagination',
			  clickable: true,
			},
			// navigation arrows
			navigation: {
				nextEl: '.swiper-arrow-next',
				prevEl: '.swiper-arrow-prev',
			},
			
			breakpoints: {
				500: {
				  slidesPerView: 2.1,
				},
				1000: {
				  slidesPerView: 3,
				},
				1200: {
				  slidesPerView: 4,
				}
			},
		});
	});

	// Carousel block slider on about us page and careers page
	$(".slider-carousel-component").each(function (index) {
		const swiper = new Swiper($(this).find(".swiper")[0], {
			sliderPerView: 1.1,
			spaceBetween: 10,
			speed: 600,
			// loop: true,
			// centeredSlides: true,
			freeMode: true,
			grabCursor: true,
			keyboardControl: true,
			keyboard: true,

			breakpoints: {
				700: {
				  slidesPerView: 1.8,
				  spaceBetween: 25
				},
				1150: {
				  slidesPerView: 2.5,
				  spaceBetween: 30
				}
			},
			// Pagination dots
			pagination: {
			  el: '.swiper-pagination',
			  clickable: true,
			},
			// navigation arrows
			navigation: {
				nextEl: '.swiper-arrow-next',
				prevEl: '.swiper-arrow-prev',
			}
		});
	});



	// Services page MULTI-FILTERING
	// To keep our code clean and modular, all custom functionality will be contained inside a single object literal called "dropdownFilter".
	var dropdownFilter = {
		// Declare any variables we will need as properties of the object
		$filters: null,
		$reset: null,
		groups: [],
		outputArray: [],
		outputString: '',
	
		// The "init" method will run on document ready and cache any jQuery objects we will need.
		init: function(){
		var self = this; // As a best practice, in each method we will asign "this" to the variable "self" so that it remains scope-agnostic. We will use it to refer to the parent "dropdownFilter" object so that we can share methods and properties between all parts of the object.
		
		self.$filters = $('#serviceFilters');
		self.$reset = $('#eset');
		self.$container = $('#services_mix_container');
		
		self.$filters.find('fieldset').each(function(){
			self.groups.push({
				$dropdown: $(this).find('select'),
				active: ''
			});
		});
		
		self.bindHandlers();
		},
		// The "bindHandlers" method will listen for whenever a select is changed. 
		bindHandlers: function(){
		var self = this;
		
		// Handle select change
		self.$filters.on('change', 'select', function(e){
			e.preventDefault();
			
			self.parseFilters();
		});
		
		},
		
		// The parseFilters method pulls the value of each active select option
		parseFilters: function(){
		var self = this;
		// loop through each filter group and grap the value from each one.
		for(var i = 0, group; group = self.groups[i]; i++){
			group.active = group.$dropdown.val();
		}
		self.concatenate();
		},

		// The "concatenate" method will crawl through each group, concatenating filters as desired:
		concatenate: function(){
		var self = this;
		self.outputString = ''; // Reset output string
		for(var i = 0, group; group = self.groups[i]; i++){
			self.outputString += group.active;
		}
		
		// If the output string is empty, show all rather than none:
		!self.outputString.length && (self.outputString = 'all'); 
		
		//console.log(self.outputString); 
		// ^ we can check the console here to take a look at the filter string that is produced
		// Send the output string to MixItUp via the 'filter' method:
			if(self.$container.mixItUp('isLoaded')){
			self.$container.mixItUp('filter', self.outputString);
			}
		}
	};
	// On document ready, initialise our code.
	$(function(){
		// Initialize dropdownFilter code
		dropdownFilter.init();
		// Instantiate MixItUp
		$('#services_mix_container').mixItUp({
			controls: {
				enable: false // we won't be needing these
			},
		});
	});


	// Auto resizing the SELECT element according to selected OPTION's width
	$(function() {
		let arrowWidth = 5;
	
		$.fn.resizeselect = function(settings) {
		  return this.each(function() { 
	
			$(this).change(function(){
			  let $this = $(this);
	
			  // get font-weight, font-size, and font-family
			  let style = window.getComputedStyle(this)
			  let { fontWeight, fontSize, fontFamily } = style
	
			  // create test element
			  let text = $this.find("option:selected").text();
			  let $test = $("<span>").html(text).css({
				"font-size": fontSize, 
				"font-weight": fontWeight, 
				"font-family": fontFamily,
				"visibility": "hidden" // prevents FOUC
			  });
	
			  // add to body, get width, and get out
			  $test.appendTo($this.parent());
			  let width = $test.width();
			  $test.remove();
	
			  // set select width
			  $this.width(width + arrowWidth);
	
			  // run on start
			}).change();
	
		  });
		};
	
		// Run by default 100ms after page load
		setTimeout(function() { 
			$("select.filter-dropdown").resizeselect();         
		}, 100);   
	})


	//SMOOTH SCROLL DOWN TO ANCHOR
	// Select all links with hashes
	$('a[href*="#"]')
	// Remove links that don't actually link to anything
	.not('[href="#"]')
	.not('[href="#0"]')
	.click(function(event) {
	// On-page links
	if (
		location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
		&& 
		location.hostname == this.hostname
	) {
		// Figure out element to scroll to
		var target = $(this.hash);
		target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
		// Does a scroll target exist?
		if (target.length) {
		// Only prevent default if animation is actually gonna happen
		event.preventDefault();
		$('html, body').animate({
			scrollTop: target.offset().top
		}, 600, function() {
			// Callback after animation
			// Must change focus!
			var $target = $(target);
			$target.focus();
			if ($target.is(":focus")) { // Checking if the target was focused
			return false;
			} else {
			$target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
			$target.focus(); // Set focus again
			};
		});
		}
	}
	});


});
// End of document ready
