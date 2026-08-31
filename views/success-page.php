<?php
    $title = "Abidjan.net, Une Histoire d'Avenir - Confirmation de paiement"
?>
<?php $content = ob_get_clean(); ?>
<?php require('components/header.php'); ?>
<?php require('components/menu.php'); ?>
<script src="https://media-files.abidjan.net/livre/js/printjs/print.min.js"></script>
<link rel="stylesheet" href="https://media-files.abidjan.net/livre/js/printjs/print.min.css">
<style>

    .bg-points-vente {
        border-radius: 24px;
        /*box-shadow: 0px 30px 40px -20px hsl(229, 6%, 66%);*/
        box-shadow: 0px 18px 20px 5px hsl(229, 6%, 66%);
    }

    .nom-client {
        font-weight: 700;
        font-size: 16px;
        text-transform: capitalize;
    }

    .btn--red {
        background: #dc3545;
        color: #ffffff;
        border-radius: 23px;
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

    .btn--black:hover, .btn--red:hover {
        color: #ffffff;
    }

    .btn--black:focus, .btn--red:focus {
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
                        <h3 class="breadcrumb-title"></h3>
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

<!-- Invoice -->
<section class=" features section-spacing text-center border-bottom" id="points-de-vente" style="margin-top: -60px;">
    <div class="container">

        <header class="text-center">
            <h2 style="font-size: 28px;">CONFIRMATION DE VOTRE COMMANDE</h2>
            <h3>Numéro de commande : #<span id="numCommande"></span> </h3>
        </header>
        <div class="row" style="margin-top: -35px;">
            <div class="col-12">
                <h3><strong style="color: #65c368">SUCCÈS !</strong></h3>
                <h5>Votre paiement a été effectué avec succès.</h5>
            </div>
            <hr>
            <div class="col-12">
                <p>
                    Merci <span class="nom-client" id="nomClient">Opportune</span> pour votre achat du livre <strong>ABIDJAN.NET UNE HISTOIRE D'AVENIR</strong>.
                    <br>
                    <span id="">
                        Nous vous informerons lorsque votre commande sera prête.
                    </span>
                </p>
            </div>
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <div class="text-center">
                    <img src="https://media-files.abidjan.net/livre/img/confirmico.gif" width="250" data-at2x="https://media-files.abidjan.net/livre/img/confirmico.gif" alt="success">
                </div>
            </div>
            <div class="col-sm-2"></div>
        </div>
        <form method="post" action="#" id="printJS-form">
            <!-- Recap de la commande -->
            <div class="row">

                <div class="col-sm-2"></div>
                <div class="col-sm-8">
                    <div class="row bg-points-vente" style="padding-bottom: 12px;">
                        <div class="col-3"></div>
                        <div class="col-6">
                            <h3 style="font-size: 24px;"><strong>Récapitulatif de votre commande</strong></h3>
                            <h5>Commande #<strong id="commandeNum"></strong></h5>
                            <p> Passée le <strong id="dateCommande"></strong> </p>
                        </div>
                        <div class="col-3"></div>

                        <hr>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-2" style="margin-top: 25px">
                            <div class="text-center">
                                <img src="https://media-files.abidjan.net/livre/img/pc.jpg" width="150" data-at2x="https://media-files.abidjan.net/livre/img/pc.jpg" alt="le livre">
                            </div>
                        </div>
                        <div class="col-sm-6" style="margin-top: 15px">
                            <p style="text-align: left">
                                <strong>ABIDJAN.NET UNE HISTOIRE D'AVENIR</strong> <br>
                                <span>
                                    Quantité : <strong id="qte">1</strong> <br>
                                    Prix : <strong id="prix">5 000</strong>
                                </span>
                                <br>
                                Lieu de livraison : <strong id="livraisonLieu"></strong> <br>
                                Mode de paiement : <strong id="modePayment">VISA</strong> <br>
                                Coût de livraison : <strong id="coutLivraison"></strong> <br>
                                Total de la commande : <strong id="coutTotal"></strong>
                            </p>
                        </div>
                        <div class="col-sm-2"></div>
                    </div>

                </div>
                <div class="col-sm-2"></div>

            </div>

        </form>

        <br><br>

        <div class="button-box mt-15">

            <a href="javascript:;" class="btn btn--lg btn--red" onclick="printJS({ printable: 'printJS-form', type: 'html', header: 'ABIDJAN.NET - Réçu de paiement' })">
                <i class="fa fa-print"></i>&nbsp; Imprimer
            </a>

            <a href="javascript:;" class="btn btn--lg btn--black" onclick="backHome()">
                <i class="fa fa-home"></i>&nbsp; Accueil
            </a>

        </div>

    </div>

</section>
<!--Invoice end-->

<!-- Contenu de la page -->

<?php require('components/footer.php'); ?>
<?php require('components/footer-scripts.php'); ?>

<script src="https://media-files.abidjan.net/livre/js/jquery-ui.min.js"></script>
<script src="/assets/js/loadingoverlay.min.js"></script>
<script src="https://media-files.abidjan.net/livre/js/sweetalert2@11.js"></script>
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


    // Display invoice
    if ( window.location.search.includes("?code=") ) {

        let code = window.location.search.replace('?code=', '') ;

        $.ajax({
            type: 'POST',
            url: 'https://carte.abidjan.net/livre/visagetdatas.php',
            dataType: 'json',
            cache: true,
            global: false,
            data: {
                "code": code
            },
            success: function(rep) {
                
                // Set les values
                $("#numCommande").html( rep.numero_commande ) ;
                $("#commandeNum").html( rep.numero_commande ) ;
                $("#nomClient").html( rep.prenom +" "+ rep.nom ) ;
                $("#qte").html( rep.quantite ) ;
                $("#prix").html( new Intl.NumberFormat('fr-FR', {style: 'currency', currency: 'XOF'}).format( parseInt(rep.quantite) * 5000 ) ) ;

                // Mode de paiement
                if ( rep.mode_paimement == "VISA" ) {
                    $("#modePayment").html("Carte Visa") ;
                } else if( rep.mode_paimement == "MASTERCARD" ) {
                    $("#modePayment").html("Carte Mastercard") ;
                } else if( rep.mode_paimement == "mtnmobilemoney" ) {
                    $("#modePayment").html("MTN money") ;
                } else if( rep.mode_paimement == "orangemoney" ) {
                    $("#modePayment").html("Orange money") ;
                } else if( rep.mode_paimement == "flooz" ) {
                    $("#modePayment").html("Moov money") ;
                } else if( rep.mode_paimement == "APaym" ) {
                    $("#modePayment").html("APaym") ;
                } else if( rep.mode_paimement == == "wave" ) {
                    $("#modePayment").html("Wave") ;
                }

                $("#livraisonLieu").html( rep.lieuLivraison ) ;
                $("#dateCommande").html( rep.dateCommande ) ;
                $("#coutLivraison").html( new Intl.NumberFormat('fr-FR', {style: 'currency', currency: 'XOF'}).format(rep.coutLivraison) ) ;
                $("#coutTotal").html( new Intl.NumberFormat('fr-FR', {style: 'currency', currency: 'XOF'}).format( (parseInt(rep.quantite) * 5000) +  parseInt(rep.coutLivraison)) ) ;

                return false ;
            }
        });

    }


    if ( window.localStorage.getItem("numCommande") == undefined || window.localStorage.getItem("numCommande") == null || window.localStorage.getItem("numCommande") == "") {

        window.localStorage.clear() ;
        window.location.href = "/livre" ;
        // console.log("Dsl")

    } else {

        // Set les values
        $("#numCommande").html( window.localStorage.getItem("numCommande") ) ;
        $("#commandeNum").html( window.localStorage.getItem("numCommande") ) ;
        $("#nomClient").html( window.localStorage.getItem("nomClient") ) ;
        $("#qte").html( window.localStorage.getItem("qte") ) ;
        $("#prix").html( new Intl.NumberFormat('fr-FR', {style: 'currency', currency: 'XOF'}).format( parseInt(window.localStorage.getItem("qte")) * 5000 ) ) ;

        // Mode de paiement
        if ( window.localStorage.getItem("modePaiement") == "VISA" ) {
            $("#modePayment").html("Carte Visa") ;
        } else if( window.localStorage.getItem("modePaiement") == "MASTERCARD" ) {
            $("#modePayment").html("Carte Mastercard") ;
        } else if( window.localStorage.getItem("modePaiement") == "mtnmobilemoney" ) {
            $("#modePayment").html("MTN money") ;
        } else if( window.localStorage.getItem("modePaiement") == "orangemoney" ) {
            $("#modePayment").html("Orange money") ;
        } else if( window.localStorage.getItem("modePaiement") == "flooz" ) {
            $("#modePayment").html("Moov money") ;
        } else if( window.localStorage.getItem("modePaiement") == "APaym" ) {
            $("#modePayment").html("APaym") ;
        } else if( window.localStorage.getItem("modePaiement") == "wave" ) {
            $("#modePayment").html("Wave") ;
        }


        $("#livraisonLieu").html( window.localStorage.getItem("lieuLivraison") ) ;
        $("#dateCommande").html( window.localStorage.getItem("dateCommande") ) ;
        $("#coutLivraison").html( window.localStorage.getItem("choixLivraison") == "Agences" ? "0 FCFA" : new Intl.NumberFormat('fr-FR', {style: 'currency', currency: 'XOF'}).format(0) ) ;
        $("#coutTotal").html( window.localStorage.getItem("choixLivraison") !== "Agences" ? new Intl.NumberFormat('fr-FR', {style: 'currency', currency: 'XOF'}).format( (parseInt(window.localStorage.getItem("qte")) * 5000) +  0) : new Intl.NumberFormat('fr-FR', {style: 'currency', currency: 'XOF'}).format(parseInt(window.localStorage.getItem("qte")) * 5000)) ;

    }

</script>
