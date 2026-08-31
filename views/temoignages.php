<?php
$title = "ABIDJAN.NET Une histoire d'avenir - Témoignages"
?>
<?php $content = ob_get_clean(); ?>
<?php require('components/header.php'); ?>
<?php require('components/menu.php'); ?>

<style>

    .testimony-video-wrap{
        position: relative;
    }

    .embed-responsive{
        box-shadow: 0px 18px 20px -9px hsl(229, 6%, 66%);
    }

    .testimony-video-wrap .video-btn {
        height: 40px;
        width: 40px;
        background: #fff;
        line-height: 40px;
        text-align: center;
        position: absolute;
        top: 25%;
        left: 85%;
        transform: translate(-50%, -50%);
        border-radius: 50%;
        font-size: 20px;
        padding-left: 5px;
        z-index: 30;
    }

    .temoin-name {
        color:  hsl(234, 12%, 34%);
        text-align: justify;
        text-align-last: center;
        font-size: 18px;
        padding: 30px;
        font-weight: 700;
    }

    .temoin-function {
        color: hsl(229, 6%, 66%);
        text-align: center;
        font-size: 13px;
        padding: 30px;
        font-weight: 500;
        position: relative;
        top: -15px;
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
                        <h3 class="breadcrumb-title">Vidéos témoignages</h3>
                    </div>
                    <div class="col-lg-6  col-md-6 col-sm-6">
                        <!-- breadcrumb-list start -->
                        <ul class="breadcrumb-list text-center text-sm-end">
                            <li class="breadcrumb-item"><a href="/livre"> <i class="fa fa-home"></i> Accueil</a></li>
                            <li class="breadcrumb-item active">Témoignages</li>
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
<section class="features video-review section-spacing text-center" id="videos">
    <div class="container">
        <header>
            <h2>VIDÉOS TÉMOIGNAGES</h2>
        </header>
        <div class="row">
            <!--video 1-->
            <div class="col-md-4">
                <div class="embed-responsive embed-responsive-16by9 testimony-video-wrap" style="background: #efefef; border-radius: 20px;">
                    <img src="https://media-files.abidjan.net/livre/img/video-bg/video-bg-1.jpg" alt="video temoignage">
                    <a data-fancybox data-width="840" data-height="560" href="https://media-files.abidjan.net/livre/videos/1.mp4" class="video-btn"><i class="fa fa-play"></i></a>
                </div>
                <br>
                <span class="temoin-name">
                    Franck BERTHOD
                </span>
                <hr style="margin-top: 0;">
                <span class="temoin-function">Directeur Général de Antilop Capital (ADN)</span>
            </div>
            <!--video 1 end-->

            <!--video 2-->
            <div class="col-md-4">
                <div class="embed-responsive embed-responsive-16by9 testimony-video-wrap" style="background: #efefef; border-radius: 20px;">
                    <img src="https://media-files.abidjan.net/livre/img/video-bg/video-bg-2.jpg" alt="video temoignage">
                    <a data-fancybox data-width="840" data-height="560" href="https://media-files.abidjan.net/livre/videos/2.mp4" class="video-btn"><i class="fa fa-play"></i></a>
                </div>
                <br>
                <span class="temoin-name">
                    Serge DOH
                </span>
                <hr style="margin-top: 0;">
                <span class="temoin-function">Vice-président de Global Technology Partners (GTP)</span>
            </div>
            <!--video 2 end-->

            <!--video 3-->
            <div class="col-md-4">
                <div class="embed-responsive embed-responsive-16by9 testimony-video-wrap" style="background: #efefef; border-radius: 20px;">
                    <img src="https://media-files.abidjan.net/livre/img/video-bg/video-bg-3.jpg" alt="video temoignage">
                    <a data-fancybox data-width="840" data-height="560" href="https://media-files.abidjan.net/livre/videos/3.mp4" class="video-btn"><i class="fa fa-play"></i></a>
                </div>
                <br>
                <span class="temoin-name">
                    Jean-David N'DA
                </span>
                <hr style="margin-top: 0;">
                <span class="temoin-function">Écrivain</span>
            </div>
            <!--video 3 end-->
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
