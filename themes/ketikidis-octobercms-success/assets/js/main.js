/* 
   Slider Carousel
   ========================================================================== */
var owl = $("#owlcarousel-area");
owl.owlCarousel({
	animateOut: 'fadeOut',
	loop: true,
	nav: true,
	dots: true,
	autoplay: true,
	autoplayTimeout: 5000,
	autoplayHoverPause: false,
	smartSpeed: 1000,
	items: 1,
	video:true,
	lazyLoad:true,
	navText : ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
	//margin:90,
	//stagePadding:90,
	responsive: {
		0: {
			items: 1
		},
		480: {
			items: 1
		},
		768: {
			items: 1
		},
		992: {
			items: 1
		},
		1200: {
			items: 1
		}
	}
});


/* 
   Clients Carousel
   ========================================================================== */
var owl = $("#clients-scroller");
owl.owlCarousel({
	loop: true,
	nav: false,
	dots: true,
	autoplay: false,
	autoplayTimeout: 5000,
	autoplayHoverPause: false,
	smartSpeed: 450,
	items: 4,
	//margin:90,
	//stagePadding:90,
	responsive: {
		0: {
			items: 1
		},
		480: {
			items: 2
		},
		768: {
			items: 3
		},
		992: {
			items: 3
		},
		1200: {
			items: 4
		}
	}
});


/* 
   Color Clients Carousel
   ========================================================================== */
var owl = $("#color-client-scroller");
owl.owlCarousel({
	loop: true,
	nav: false,
	dots: true,
	autoplay: false,
	autoplayTimeout: 5000,
	autoplayHoverPause: false,
	smartSpeed: 450,
	items: 4,
	//margin:90,
	//stagePadding:90,
	responsive: {
		0: {
			items: 1
		},
		480: {
			items: 2
		},
		768: {
			items: 3
		},
		992: {
			items: 3
		},
		1200: {
			items: 4
		}
	}
});


/* 
   Testimonial Carousel
   ========================================================================== */
var owl = $("#testimonial-item");
owl.owlCarousel({
	loop: true,
	nav: false,
	dots: true,
	autoplay: true,
	autoplayTimeout: 5000,
	autoplayHoverPause: false,
	smartSpeed: 450,
	items: 3,
	//margin:90,
	//stagePadding:90,
	responsive: {
		0: {
			items: 1
		},
		480: {
			items: 2
		},
		768: {
			items: 3
		},
		992: {
			items: 3
		},
		1200: {
			items: 3
		}
	}
});


/* 
   Dark Testimonial Carousel
   ========================================================================== */
var owl = $("#testimonial-dark");
owl.owlCarousel({
	loop: true,
	nav: false,
	dots: true,
	autoplay: true,
	autoplayTimeout: 5000,
	autoplayHoverPause: false,
	smartSpeed: 450,
	items: 3,
	//margin:90,
	//stagePadding:90,
	responsive: {
		0: {
			items: 1
		},
		480: {
			items: 2
		},
		768: {
			items: 3
		},
		992: {
			items: 3
		},
		1200: {
			items: 3
		}
	}
});


/* 
   Single Testimonial Carousel
   ========================================================================== */
var owl = $("#single-testimonial-item");
owl.owlCarousel({
	loop: true,
	nav: false,
	dots: true,
	autoplay: true,
	autoplayTimeout: 5000,
	autoplayHoverPause: false,
	smartSpeed: 450,
	items: 1,
	singleItem: true,
	//margin:90,
	//stagePadding:90,
	responsive: {
		0: {
			items: 1
		},
		480: {
			items: 2
		},
		768: {
			items: 3
		},
		992: {
			items: 3
		},
		1200: {
			items: 3
		}
	}
});


/* 
   Blog Carousel
   ========================================================================== */
$(".carousel-image-slider").each(function() {
    $(this).owlCarousel({
		loop: true,
		nav: false,
		dots: false,
		autoplay: true,
		autoplayTimeout: 4000,
		autoplayHoverPause: false,
		smartSpeed: 1000,
		items: 4,
		//margin:90,
		//stagePadding:90,
		responsive: {
			0: {
				items: 1
			},
			480: {
				items: 1
			},
			768: {
				items: 1
			},
			992: {
				items: 1
			},
			1200: {
				items: 1
			}
		}
	});
});


/* 
   Image Carousel
   ========================================================================== */
var owl = $("#image-carousel");
owl.owlCarousel({
	loop: true,
	nav: false,
	dots: false,
	autoplay: true,
	autoplayTimeout: 3000,
	autoplayHoverPause: false,
	smartSpeed: 450,
	items: 4,
	//margin:90,
	//stagePadding:90,
	responsive: {
		0: {
			items: 1
		},
		480: {
			items: 1
		},
		768: {
			items: 1
		},
		992: {
			items: 1
		},
		1200: {
			items: 1
		}
	}
});


/* 
   Blog Post Carousel
   ========================================================================== */
var owl = $("#post-image-carousel");
owl.owlCarousel({
	loop: true,
	nav: false,
	dots: false,
	autoplay: true,
	autoplayTimeout: 3000,
	autoplayHoverPause: false,
	smartSpeed: 450,
	items: 4,
	//margin:90,
	//stagePadding:90,
	responsive: {
		0: {
			items: 1
		},
		480: {
			items: 2
		},
		768: {
			items: 3
		},
		992: {
			items: 3
		},
		1200: {
			items: 4
		}
	}
});


/* 
   Portfolio Carousel
   ========================================================================== */
var owl = $("#portfolio-image-slider");
owl.owlCarousel({
	loop: true,
	nav: false,
	dots: false,
	autoplay: true,
	autoplayTimeout: 8000,
	autoplayHoverPause: false,
	smartSpeed: 450,
	items: 1,
	//margin:90,
	//stagePadding:90,
	responsive: {
		0: {
			items: 1
		},
		480: {
			items: 1
		},
		768: {
			items: 1
		},
		992: {
			items: 1
		},
		1200: {
			items: 1
		}
	}
});


/* 
   Counters
   ========================================================================== */
jQuery(document).ready(function($) {
        $('.counter').counterUp({
            delay: 1,
            time: 800
        });
    }

);


/* 
   Skills
   ========================================================================== */
$('.skill-shortcode').appear(function() {
        $('.progress').each(function() {
            $('.progress-bar').css('width', function() {
                return ($(this).attr('data-percentage') + '%')
            });
        });
    }

    , {
        accY: -100
    }

);


/* 
   Back to top
   ========================================================================== */
var offset = 200;
var duration = 500;
$(window).scroll(function() {
        if ($(this).scrollTop() > offset) {
            $('.back-to-top').fadeIn(400);
        } else {
            $('.back-to-top').fadeOut(400);
        }
    }

);
$('.back-to-top').click(function(event) {
        event.preventDefault();
        $('html, body').animate({
            scrollTop: 0
        }, 600);
        return false;
    }

);