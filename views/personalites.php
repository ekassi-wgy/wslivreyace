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
                        <h3 class="breadcrumb-title">La photothèque</h3>
                    </div>
                    <div class="col-lg-6  col-md-6 col-sm-6">
                        <!-- breadcrumb-list start -->
                        <ul class="breadcrumb-list text-center text-sm-end">
                            <li class="breadcrumb-item"><a href="/livre"> <i class="fa fa-home"></i> Accueil</a></li>
							<li class="breadcrumb-item"><a href="/livre/photos"> La photothèque</a></li>
                            <li class="breadcrumb-item active">Les Personnalités</li>
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
		
		<header class="text-center">
            <h2>Les Personnalités</h2>
            <h3 style="font-size: 25px; color: #dc3545; position: relative; top: 12px;">
                <!-- <marquee > -->
<!--                " Le livre est disponible en précommande. La livraison est gratuite "-->
                <!-- </marquee> -->
            </h3>
        </header>
       
        <div class="row">
			
			
			<div class="card col-md-3">
      <div class="card-image">
        <a href="https://media-files.abidjan.net/livre/img/phototeque/personalites/1.jpg" data-fancybox="gallery" data-caption="Mme la Première dame de la République de CI Dominique Ouattara et les digital entrepreneurs : Jil- Alexandre N'Dia et Daniel Ahouassa">
          <img src="https://media-files.abidjan.net/livre/img/phototeque/personalites/1.jpg" alt="Image Gallery">
        </a>
      </div>
    </div>
			
			 <div class="card col-md-3">
      <div class="card-image">
        <a href="https://media-files.abidjan.net/livre/img/phototeque/personalites/2.jpg" data-fancybox="gallery" data-caption="Mr l'ex Ministre de la Promotion des PME Felix Anoblé, de l'Artisanat et de la Transformation du Secteur et les digital entrepreneurs : Jil- Alexandre N'Dia et Daniel Ahouassa">
          <img src="https://media-files.abidjan.net/livre/img/phototeque/personalites/2.jpg" alt="Image Gallery">
        </a>
      </div>
    </div>
			
			 <div class="card col-md-3">
      <div class="card-image">
        <a href="https://media-files.abidjan.net/livre/img/phototeque/personalites/3.jpg" data-fancybox="gallery" data-caption="Mme la Première dame de la République de CI Dominique Ouattara et les digital entrepreneurs : Jil- Alexandre N'Dia et Daniel Ahouassa">
          <img src="https://media-files.abidjan.net/livre/img/phototeque/personalites/3.jpg" alt="Image Gallery">
        </a>
      </div>
    </div>
			
			 <div class="card col-md-3">
      <div class="card-image">
        <a href="https://media-files.abidjan.net/livre/img/phototeque/personalites/4.jpg" data-fancybox="gallery" data-caption="Alain kouadio PDG du Groupe Kaydan et fondateur du Fonds d`Investissement Kaydan Real Estate Pcc et les digital entrepreneurs : Jil- Alexandre N'Dia et Daniel Ahouassa">
          <img src="https://media-files.abidjan.net/livre/img/phototeque/personalites/4.jpg" alt="Image Gallery">
        </a>
      </div>
    </div>
			
		</div>	
		
		<div class="row">
			
			 <div class="card col-md-3">
      <div class="card-image">
        <a href="https://media-files.abidjan.net/livre/img/phototeque/personalites/5.jpg" data-fancybox="gallery" data-caption="Mr Nader Séklaoui PDG du groupe NASCO et les digital entrepreneurs : Jil- Alexandre N'Dia et Daniel Ahouassa">
          <img src="https://media-files.abidjan.net/livre/img/phototeque/personalites/5.jpg" alt="Image Gallery">
        </a>
      </div>
    </div>
			
			
			 <div class="card col-md-3">
      <div class="card-image">
        <a href="https://media-files.abidjan.net/livre/img/phototeque/personalites/6.jpg" data-fancybox="gallery" data-caption="Mr l'ambassadeurs de France, Georges Serre et les digital entrepreneurs : Jil- Alexandre N'Dia et Daniel Ahouassa">
          <img src="https://media-files.abidjan.net/livre/img/phototeque/personalites/6.jpg" alt="Image Gallery">
        </a>
      </div>
    </div>
			
			
			 <div class="card col-md-3">
      <div class="card-image">
        <a href="https://media-files.abidjan.net/livre/img/phototeque/personalites/7.jpg" data-fancybox="gallery" data-caption="Mr Andrew tores Head Visa's sub-Saharan Africa business et les digital entrepreneurs : Jil- Alexandre N'Dia et Daniel Ahouassa">
          <img src="https://media-files.abidjan.net/livre/img/phototeque/personalites/7.jpg" alt="Image Gallery">
        </a>
      </div>
    </div>
			
			 <div class="card col-md-3">
      <div class="card-image">
        <a href="https://media-files.abidjan.net/livre/img/phototeque/personalites/8.jpg" data-fancybox="gallery" data-caption="Mr l'ex Ministre de la Promotion des PME Felix Anoblé, de l'Artisanat et de la Transformation du Secteur et les digital entrepreneurs : Jil- Alexandre N'Dia et Daniel Ahouassa">
          <img src="https://media-files.abidjan.net/livre/img/phototeque/personalites/8.jpg" alt="Image Gallery">
        </a>
      </div>
    </div>
			
			
			
			 <div class="card col-md-3">
      <div class="card-image">
        <a href="https://media-files.abidjan.net/livre/img/phototeque/personalites/10.jpg" data-fancybox="gallery" data-caption="Mr  Toh Bi Irié Vincent Ancien préfet d’Abidjan  et le digital entrepreneur : Jil- Alexandre N'Dia">
          <img src="https://media-files.abidjan.net/livre/img/phototeque/personalites/10.jpg" alt="Image Gallery">
        </a>
      </div>
    </div>
			
			
			 <div class="card col-md-3">
      <div class="card-image">
        <a href="https://media-files.abidjan.net/livre/img/phototeque/personalites/11.jpg" data-fancybox="gallery" data-caption="Mr Toh Bi Irié Vincent Ancien préfet d’Abidjan ">
          <img src="https://media-files.abidjan.net/livre/img/phototeque/personalites/11.jpg" alt="Image Gallery">
        </a>
      </div>
    </div>
            
            <div class="card col-md-3">
      <div class="card-image">
        <a href="https://media-files.abidjan.net/livre/img/phototeque/personalites/12.jpg" data-fancybox="gallery" data-caption="Mr le Maire de Cocody Jean-Marc Yacé et les digital entrepreneurs : Jil- Alexandre N'Dia et Daniel Ahouassa">
          <img src="https://media-files.abidjan.net/livre/img/phototeque/personalites/12.jpg" alt="Image Gallery">
        </a>
      </div>
    </div>
			
			 <div class="card col-md-3">
      <div class="card-image">
        <a href="https://media-files.abidjan.net/livre/img/phototeque/personalites/13.jpg" data-fancybox="gallery" data-caption="Mr l'ambassadeurs de France, Georges Serre">
          <img src="https://media-files.abidjan.net/livre/img/phototeque/personalites/13.jpg" alt="Image Gallery">
        </a>
      </div>
    </div>
			
	 <div class="card col-md-3">
      <div class="card-image">
        <a href="https://media-files.abidjan.net/livre/img/phototeque/personalites/9.jpg" data-fancybox="gallery" data-caption="Mr Toussaint akadjé">
          <img src="https://media-files.abidjan.net/livre/img/phototeque/personalites/9.jpg" alt="Image Gallery">
        </a>
      </div>
    </div>
			
			<div class="card col-md-3">
      <div class="card-image">
        <a href="https://media-files.abidjan.net/livre/img/phototeque/personalites/14.jpg" data-fancybox="gallery" data-caption="Mme Oulimata Sarr / Directrice Régionale ONU Femmes pour l’Afrique de l'Ouest et Centrale et les digital entrepreneurs : Jil- Alexandre N'Dia et Daniel Ahouassa">
          <img src="https://media-files.abidjan.net/livre/img/phototeque/personalites/14.jpg" alt="Image Gallery">
        </a>
      </div>
    </div>
			
			<div class="card col-md-3">
      <div class="card-image">
        <a href="https://media-files.abidjan.net/livre/img/phototeque/personalites/15.jpg" data-fancybox="gallery" data-caption="Mr le Ministre de la Communication et des Médias, Porte-Parole du Gouvernement ivoirien Sidi Touré et les digital entrepreneurs : Jil- Alexandre N'Dia et Daniel Ahouassa">
          <img src="https://media-files.abidjan.net/livre/img/phototeque/personalites/15.jpg" alt="Image Gallery">
        </a>
      </div>
    </div>
			
			
	<div class="row">	
			
			<div class="card col-md-3">
      <div class="card-image">
        <a href="https://media-files.abidjan.net/livre/img/phototeque/personalites/16.jpg" data-fancybox="gallery" data-caption="Mr l'ex Ministre de l Economie Numerique des Telecommunications et de l Innovation Roger adom">
          <img src="https://media-files.abidjan.net/livre/img/phototeque/personalites/16.jpg" alt="Image Gallery">
        </a>
      </div>
    </div>
			
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