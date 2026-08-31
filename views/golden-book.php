<?php
$title = "ABIDJAN.NET Une histoire d'avenir - Livre d'or"
?>
<?php $content = ob_get_clean(); ?>
<?php require('components/header.php'); ?>
<?php require('components/menu.php'); ?>

<style>

    .rounded-circle {
        border-radius: 50% !important;
    }

    .d-flex {
        display: flex !important;
    }

    .flex-row {
        flex-direction: row !important;
    }

    .ml-2 {
        margin-left: 0.7rem !important;
    }

    .flex-column {
        flex-direction: column !important;
    }

    .font-weight-normal {
        font-weight: 400 !important;
        text-align: left;
    }

    .card {
        border: none;
        cursor: pointer;
        /*box-shadow: 0 0 40px rgba(51, 51, 51, .1);*/
        background: #fff;
        border-radius: 15px;
    }
    
    .testimonial-list {
        list-style: none
    }

    .testimonial-list li {
        margin-bottom: 20px
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
                        <h3 class="breadcrumb-title">Livre d'or</h3>
                    </div>
                    <div class="col-lg-6  col-md-6 col-sm-6">
                        <!-- breadcrumb-list start -->
                        <ul class="breadcrumb-list text-center text-sm-end">
                            <li class="breadcrumb-item"><a href="/livre"> <i class="fa fa-home"></i> Accueil</a></li>
                            <li class="breadcrumb-item active">Livre d'or</li>
                        </ul>
                        <!-- breadcrumb-list end -->
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<!--Header section end-->

<!--sub form-->
<section class="sub-form section-spacing text-center" id="commentaire">
    <div class="container">
        <header>
            <h2>LAISSEZ UN COMMENTAIRE</h2>
            <h3>
                Laissez votre empreinte sur le livre d'or
            </h3>
        </header>
        <div class="row">
            <div class="slide-bottom col-md-7">
                <img src="https://media-files.abidjan.net/livre/img/book--.png" alt="Abidjant.net book preface" data-no-retina>
                <br>
            </div>
            <div class="col-md-5">
                <form>
                    <div>
                        <input type="text" class="slide-bottom form-control" placeholder="Nom & prénom(s)" name="nom" id="fullname">
                    </div>
                    <br>
                    <div>
                        <input type="text" class="slide-bottom form-control" placeholder="Ville" name="ville" id="ville">
                    </div>
                    <br>
                    <div>
                        <textarea type="text" class="slide-bottom form-control" placeholder="Commentaire" id="commentaire"></textarea>
                    </div>
                    <br>
                    <button type="button" class="slide-bottom btn btn-default mt-4">Commenter</button>
                </form>
            </div>
        </div>
    </div>
</section>
<!--sub form-->

<section class="prices features section-spacing text-center" id="liste">
    <div class="container">
        <header>
            <h2>LISTE DES COMMENTAIRES</h2>
            <h3>
                Aucun commentaire disponible pour l'instant
            </h3>
        </header>
    </div>

    <!-- Commentaires -->
    <div class="container">
        <div class="accordion d-flex justify-content-center align-items-center">
            <div class="row">
                <div class="col-sm-12">
                    <div class="p-3">
                        <ul class="testimonial-list">
                            <li>
                                <div class="card p-3">
                                    <div class="d-flex flex-row align-items-center" style="align-items: center!important; padding: 8px;">
                                        <img src="https://media-files.abidjan.net/livre/img/avatar.png" width="50" class="rounded-circle">
                                        <div class="d-flex flex-column ml-2">
                                            <span class="font-weight-normal">
                                                <!-- Nom & Prénom(s) - Ville -->
                                                Luke Harper - Abidjan
                                            </span>
                                            <span style="text-align: left; font-size: 14px;">
                                                <!-- Commentaire -->
                                                Sales Team Lead,Sketch. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                                                labore et dolore magna aliqua.
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>

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
