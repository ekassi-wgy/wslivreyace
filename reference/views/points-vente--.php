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

</style>

<!-- Contenu de la page -->

<!--Header section-->
<section class="hero-2" id="Home">

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6 col-sm-6 text-center text-sm-start">
                        <h3 class="breadcrumb-title">Points de vente</h3>
                    </div>
                    <div class="col-lg-6  col-md-6 col-sm-6">
                        <!-- breadcrumb-list start -->
                        <ul class="breadcrumb-list text-center text-sm-end">
                            <li class="breadcrumb-item"><a href="/livre"> <i class="fa fa-home"></i> Accueil</a></li>
                            <li class="breadcrumb-item active">Points de vente</li>
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
            <h2>LES POINTS DE VENTE DU LIVRE</h2>
            <h3 style="font-size: 25px; color: #dc3545; position: relative; top: 12px;">
                <!-- <marquee > -->
<!--                " Le livre est disponible en précommande. La livraison est gratuite "-->
                <!-- </marquee> -->
            </h3>
        </header>
        <div class="row bg-points-vente">
            <div class="slide-left col-sm-4">
                <div class="text-center">
                    <img src="https://media-files.abidjan.net/livre/img/location-2.png" width="56" data-at2x="https://media-files.abidjan.net/livre/img/location-2.png" alt="point de vente livre">
                    <h4>Agence de Cocody</h4>
                    <hr>
                    <p>
                        Cocody Ambassade, Rue Booker Washington près du cabinet children of Africa. <br>
                        <a href="tel:2252522010114"><i class="fa fa-phone"></i>&nbsp; +225 25 22 01 01 14</a>
                    </p>
                </div>
                <div class="single-contact-info-item">
                    <div class="icon">
                        <i class="icon-clock3 fa fa-clock-o"></i>
                    </div>
                    <div class="iconbox-desc">
                        <h6 class="mb-10">Nos horaires</h6>
                        <p>
                            Lundi - Samedi : 08h00 - 21h00
                            <br>
                            Dimanche : 09h00 - 17h00
                        </p>
                    </div>
                </div>
            </div>

            <div class="slide-left col-sm-4">
                <div class="text-center">
                    <img src="https://media-files.abidjan.net/livre/img/location-2.png" width="56" data-at2x="https://media-files.abidjan.net/livre/img/location-2.png" alt="point de vente livre">
                    <h4>Agence du Plateau</h4>
                    <hr>
                    <p>
                        Plateau pyramide, rue BCEAO, après le pullman hôtel. Résidence PELIEU 2e étage. <br>
                        <a href="tel:2252720334952"><i class="fa fa-phone"></i>&nbsp; +225 27 20 33 49 52</a>
                    </p>
                </div>
                <div class="single-contact-info-item">
                    <div class="icon">
                        <i class="icon-clock3 fa fa-clock-o"></i>
                    </div>
                    <div class="iconbox-desc">
                        <h6 class="mb-10">Nos horaires</h6>
                        <p>
                            Lundi - Vendredi : 08h00 - 18h00
                            <br>
                            Samedi : 09h00 - 13h00
                        </p>
                    </div>
                </div>
            </div>

            <div class="slide-left col-sm-4">
                <div class="text-center">
                    <img src="https://media-files.abidjan.net/livre/img/location-2.png" width="56" data-at2x="https://media-files.abidjan.net/livre/img/location-2.png" alt="point de vente livre">
                    <h4>Agence de Prima</h4>
                    <hr>
                    <p>
                        Marcory Zone 4 immeuble pistache et chocolat.  <br>
                        <a href="tel:2252521006089"><i class="fa fa-phone"></i>&nbsp; +225 25 21 00 60 89</a>
                    </p>
                </div>
                <div class="single-contact-info-item" style="">
                    <div class="icon">
                        <i class="icon-clock3 fa fa-clock-o"></i>
                    </div>
                    <div class="iconbox-desc">
                        <h6 class="mb-10">Nos horaires</h6>
                        <p>
                            Lundi - Vendredi : 8h00 - 18h00
                            <br>
                            Samedi : 9h00 - 13h00
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <div style="margin-top: 35px; text-align: center;">
            * Le livre est maintenant disponible dans nos agences
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
