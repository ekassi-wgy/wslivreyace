<?php
$title = "ABIDJAN.NET Une histoire d'avenir - Points de vente"
?>
<?php $content = ob_get_clean(); ?>
<?php require('components/header.php'); ?>
<?php require('components/menu.php'); ?>

<style>

    .single-contact-info-item {
        margin-top: 60px;
    }
    .single-contact-info-item .icon {
        float: left;
        margin-right: 26px;
    }

    .single-contact-info-item .icon i {
        line-height: 1;
        font-size: 36px;
        color: hsl(229, 6%, 66%);
    }

    .single-contact-info-item .iconbox-desc {
        display: inline-block;
    }

    .mb-10 {
        margin-bottom: 10px;
        color: hsl(229, 6%, 66%);
    }

    h6 {
        font-size: 18px;
        font-weight: 400;
    }

    .bg-points-vente {
        border-radius: 24px;
        box-shadow: 0px 30px 40px -20px hsl(229, 6%, 66%);
        padding: 30px;
    }

    .border-bottom {
        border-bottom: 1px solid #ededed !important;
    }
	
	.team-member-img {
    border-radius: 20px;
    width: 350px;
    box-shadow: 0px 30px 40px -20px hsl(229deg 6% 66%);
}
	
	.title{
		text-align: center;
    font-weight: bold;
	}
	
	.play{
	display: block;
    position: absolute;
    top: 20px;
    right: 42px;
    width: 45px;
    height: 45px;
    background: transparent url('https://media-files.abidjan.net/public/img/ui/play.svg') no-repeat;
    background-size: cover;
	}
	
	.team-member-img{
	max-width: 750px;
	width:100%	
		
	}
	
	/*SLIDER FANCYBOX*/
	
	@media all and (min-width: 800px) {
  .fancybox-thumbs {
    top: auto;
    width: auto;
    bottom: 0;
    left: 0;
    right : 0;
    height: 95px;
    padding: 10px 10px 5px 10px;
    box-sizing: border-box;
    background: rgba(0, 0, 0, 0.3);
  }
  
  .fancybox-show-thumbs .fancybox-inner {
    right: 0;
    bottom: 95px;
  }
}

</style>

<!-- Contenu de la page -->

<!--Header section-->
<section class="hero-2" id="Home">

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6 col-sm-6 text-center text-sm-start">
                        <h3 class="breadcrumb-title">La Vidéothèque</h3>
                    </div>
                    <div class="col-lg-6  col-md-6 col-sm-6">
                        <!-- breadcrumb-list start -->
                        <ul class="breadcrumb-list text-center text-sm-end">
                            <li class="breadcrumb-item"><a href="/livre"> <i class="fa fa-home"></i> Accueil</a></li>
                            <li class="breadcrumb-item active">La Vidéothèque</li>
                        </ul>
                        <!-- breadcrumb-list end -->
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<!--Header section end-->

<!--Les entrepreneurs-->
<section class=" features section-spacing border-bottom" id="points-de-vente">
    <div class="container">
       
        <div class="row">
            
            <div class="slide-left col-sm-4" style="opacity: 1; visibility: visible; position: relative; right: 0px;">
                <a href="https://youtu.be/TjGnug7-vro" data-fancybox="gallery" data-caption="Présentation de l'ouvrage Une histoire d'Avenir dans le JT présentée par Hamza Diaby">
                    <img class="team-member-img" src="https://media-files.abidjan.net/livre/img/phototeque/videos/v1-lvr.jpg" height="234" data-at2x="https://media-files.abidjan.net/livre/img/phototeque/videos/v1-lvr.jpg" alt="team member">
					<span class="play"></span>
                </a>
                <h4 class="title">Présentation de l'ouvrage "Une histoire d'Avenir" dans le JT présentée par Hamza Diaby</h4>
               
                <hr>
         
            </div>
			
			<div class="slide-left col-sm-4" style="opacity: 1; visibility: visible; position: relative; right: 0px;">
                <a href="https://youtu.be/NZzAsGoiJDo" data-fancybox="gallery" data-caption="Présentation de l'ouvrage Une histoire d'Avenir, le message de l'économiste Tidjane Thiam">
                    <img class="team-member-img" src="https://media-files.abidjan.net/livre/img/phototeque/videos/v2-lvr.jpg" height="234" data-at2x="https://media-files.abidjan.net/livre/img/phototeque/videos/v2-lvr.jpg" alt="team member">
					<span class="play"></span>
                </a>
                <h4 class="title">Présentation de l'ouvrage "Une histoire d'Avenir", le message de l'économiste Tidjane Thiam</h4>
               
                <hr>
         
            </div>
			
			<div class="slide-left col-sm-4" style="opacity: 1; visibility: visible; position: relative; right: 0px;">
                <a href="https://youtu.be/9qgJECFjgCc" data-fancybox="gallery" data-caption="Arrivée des stocks de l'ouvrage Une histoire d'Avenir au siège de Weblogy">
                    <img class="team-member-img" src="https://media-files.abidjan.net/livre/img/phototeque/videos/v3-lvr.jpg" height="234" data-at2x="https://media-files.abidjan.net/livre/img/phototeque/videos/v3-lvr.jpg" alt="team member">
					<span class="play"></span>
                </a>
                <h4 class="title">Arrivée des stocks de l'ouvrage<br> "Une histoire d'Avenir" <br>au siège de Weblogy</h4>
               
                <hr>
         
            </div>
			
			
			<div class="slide-left col-sm-4" style="opacity: 1; visibility: visible; position: relative; right: 0px;">
                <a href="https://youtu.be/YqGFYcw-fB4" data-fancybox="gallery" data-caption="L'entrepreneuriat Digital: présentation de l'ouvrage: Une histoire d'avenir">
                    <img class="team-member-img" src="https://media-files.abidjan.net/livre/img/phototeque/videos/v4-lvr.jpg" height="234" data-at2x="https://media-files.abidjan.net/livre/img/phototeque/videos/v4-lvr.jpg" alt="team member">
					<span class="play"></span>
                </a>
                <h4 class="title">L'entrepreneuriat Digital: présentation de l'ouvrage "Une histoire d'avenir"</h4>
               
                <hr>
         
            </div>
           
           
        </div>
		
	
    </div>
</section>
<!--Les entrepreneurs end-->

<!-- Contenu de la page -->

<?php require('components/footer.php'); ?>
<?php require('components/footer-scripts.php'); ?>
<script>
/* ==========================================================================
sticky nav
========================================================================== */
var menu = $('.navbar');
var stickyNav = menu.offset().top;
menu.addClass('stick');
// $(window).scroll(function () {
//     if ($(window).scrollTop()) {
//         // $(window).height()
//         menu.addClass('stick');
//     } else {
//         menu.removeClass('stick');
//
//     }
// });

</script>


<script>
	// Fancybox Configuration
$('[data-fancybox="gallery"]').fancybox({
  buttons: [
    "slideShow",
    "thumbs",
    "zoom",
    "fullScreen",
    "share",
    "close"
  ],
  loop: false,
  protect: true
});

	</script>
		
		
		
		<!-- fancybox script-->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
<script>

$('[data-fancybox="images"]').fancybox({
  margin : [44,0,22,0],
  thumbs : {
    autoStart : true,
    axis      : 'x'
  }
})
</script>
