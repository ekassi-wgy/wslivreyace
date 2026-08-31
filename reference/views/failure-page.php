<?php
$title = "Abidjan.net, Une Histoire d'Avenir - Confirmation de paiement"
?>
<?php $content = ob_get_clean(); ?>
<?php require('components/header.php'); ?>
<?php require('components/menu.php'); ?>

<style>

    .bg-points-vente {
        border-radius: 24px;
        box-shadow: 0px 30px 40px -20px hsl(229, 6%, 66%);
        /*box-shadow: 0px 18px 20px 5px hsl(229, 6%, 66%);*/
    }

    .btn--black {
        background: #000000;
        color: #ffffff;
        border-radius: 23px;
    }

    .btn--lg {
        line-height: 44px;
        padding: 0 42px;
        height: 46px;
    }

    .btn--black:hover {
        color: #ffffff;
    }

    .btn--black:focus {
        color: #ffffff;
    }

    .mt-15 {
        margin-top: 15px;
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
                        <h3 class="breadcrumb-title">Confirmation de paiement</h3>
                    </div>
                    <div class="col-lg-6  col-md-6 col-sm-6">
                        <!-- breadcrumb-list start -->
                        <ul class="breadcrumb-list text-center text-sm-end">
                            <li class="breadcrumb-item"><a href="javascript:;" onclick="backHome()"> <i class="fa fa-home"></i> Accueil</a></li>
                            <li class="breadcrumb-item active">Statut paiement</li>
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
<section class=" features section-spacing text-center border-bottom" id="points-de-vente">
    <div class="container">
        <header class="text-center">
            <h2 style="color: #dc3545;">ÉCHEC !</h2>
            <h3>Votre paiement n'a pas été effectué.</h3>
        </header>
        <br><br>
        <div class="row bg-points-vente">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <div class="text-center">
                    <img src="https://media-files.abidjan.net/livre/img/refusico.gif" width="350" data-at2x="https://media-files.abidjan.net/livre/img/refusico.gif" alt="success">
                </div>
            </div>
            <div class="col-sm-2"></div>
        </div>
        <br><br>
        <div class="button-box mt-15">
            <a href="javascript:;" class="btn btn--lg btn--black" onclick="backHome()">
                <i class="fa fa-home"></i>&nbsp; Accueil
            </a>
        </div>
        
        <br><br>

        <div class="row">
            <div class="col-12 mt-50">
                Vous avez un problème ? Appelez-nous <a href="tel:+2250747138047">+225 07 47 13 80 47</a>
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

    function backHome() {
        window.localStorage.clear() ;
        window.location.href = "/livre" ;
    }

</script>
