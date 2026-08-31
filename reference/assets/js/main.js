$(function() {
    "use strict";
    /* ==========================================================================
   Preloader
   ========================================================================== */
    
    $(window).load(function() {
        
        $("#status").fadeOut();
        
        $("#preloader").delay(1000).fadeOut("slow");
    });
		
		
		 /* ==========================================================================
   animation au scroll 
   ========================================================================== */
    
    if ($(window).width() > 992) {
        
        $(window).fadeThis({
            'reverse': false
        });
    }

    /* ==========================================================================
   parallax scroll 
   ========================================================================== */
    
    if (!(/Android|BlackBerry|Windows Phone|iPhone|iPad|iPod/i).test(navigator.userAgent || navigator.vendor || window.opera)) {
        		if($(window).width()>992){skrollr.init({forceHeight:false})}$(window).on("resize",function(){if($(window).width()<=	992){skrollr.init().destroy()}});$(window).on("resize",function(){if($(window).width()>992){skrollr.init({forceHeight:false})}});
    }

    /* ==========================================================================
        Animation Nombre
       ========================================================================== */
 
    $('.counter').waypoint(function() {
        
        var comma_separator_number_step = $.animateNumber.numberStepFactories.separator(',');
        
        $('.total-number-1').animateNumber({
            number: 100, // Changement de valeur des element du compteur
            numberStep: comma_separator_number_step
        }, 2000);
        
        $('.total-number-2').animateNumber({
            number: 1000, // Changement de valeur des element du compteur
            numberStep: comma_separator_number_step
        }, 2000);
        
        $('.total-number-3').animateNumber({
            number: 1200, // Changement de valeur des element du compteur
            numberStep: comma_separator_number_step
        }, 2000);
        
        $('.total-number-4').animateNumber({
            number: 1500, // Changement de valeur des element du compteur
            numberStep: comma_separator_number_step
        }, 2000);
    
    
    
    }, {
        offset: '80%'
    
    });



    /* ==========================================================================
    Gestion du Scroll en smooth 
   ========================================================================== */
    
    $('a[href*=#]:not([href=#])').click(function() {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html,body').animate({
                    scrollTop: (target.offset().top - 40)
                }, 1000);
                return false;
            }
        }
    });





    /* ==========================================================================
       Contact Form
       ========================================================================== */
    
    
    $('#contact-form').validate({
        
        rules: {
            name: {
                required: true,
                minlength: 3
            },
            email: {
                required: true,
                email: true
            },
            
            message: {
                required: true,
                minlength: 10
            }
        },
        messages: {
            name: "<i class='bi bi-exclamation-square'></i>Entrez votre nom s'il vous plaît.",
            email: {
                required: "<i class='bi bi-exclamation-square'></i>Nous avons besoin de votre adresse mail pour vous contacter.",
                email: "<i class='bi bi-exclamation-square'></i>Veuillez entrer une adresse mail valide."
            },
            message: "<i class='bi bi-exclamation-square'></i>Entrez un message s'il vous plaît."
        },
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                type: "POST",
                data: $(form).serialize(),
                url: "php/contact-me.php",
                success: function() {
                    $('#contact-form :input').attr('disabled', 'disabled');
                    $('#contact-form').fadeTo("slow", 0.15, function() {
                        $(this).find(':input').attr('disabled', 'disabled');
                        $(this).find('label').css('cursor', 'default');
                        $('.success-cf').fadeIn();
                    });
										$('#contact-form')[0].reset();
                },
                error: function() {
                    $('#contact-form').fadeTo("slow", 0.15, function() {
                        $('.error-cf').fadeIn();
                    });
                }
            });
        }
    });


    /* ==========================================================================
   Boutton ScrollTop 
   ========================================================================== */
    
    
    $(window).scroll(function() {
        if ($(this).scrollTop() > 200) {
            $('.scroll-top a').fadeIn(200);
        } else {
            $('.scroll-top a').fadeOut(200);
        }
    });
    
    
    $('.scroll-top a').click(function(event) {
        event.preventDefault();
        
        $('html, body').animate({
            scrollTop: 0
        }, 1000);
    });


    /* ==========================================================================
   sticky Nav Menu
   ========================================================================== */
    
    
    
    var menu = $('.navbar');
    
    var stickyNav = menu.offset().top;
    
    $(window).scroll(function() {
        if ($(window).scrollTop() > $(window).height()) {
            menu.addClass('stick');
        } else {
            menu.removeClass('stick');
        
        }
    });


	/* ==========================================================================
	   Collapse nav bar
	   ========================================================================== */

		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
 	$(".navbar-nav li a").on('click', function() {
	    $(".navbar-collapse").collapse('hide');
	});
}


});
