jQuery(document).ready(function($){    
    $(window).scroll(function(){
		if ($(this).scrollTop() > 100) {
			$('.mos-sticky-header').addClass('tiny');
		} else {
			$('.mos-sticky-header').removeClass('tiny');
		}
	});
    $('.slick-slider').slick({
        responsive: [
            {
                breakpoint: 599.98,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    
    });
});