<?php
$title = "Abidjan.net, Une Histoire d'Avenir - Nouvelle précommande"
?>
<?php $content = ob_get_clean(); ?>
<?php require('components/header.php'); ?>
<?php require('components/menu.php'); ?>

<style>
    /* Checkout css */
    .border-bottom {
        border-bottom: 1px solid #ededed !important;
    }

    .site-wrapper-reveal {
        position: relative;
        z-index: 2;
        background: #ffffff;
    }

    .site-wrapper-reveal {
        background: #ffffff;
    }

    .section-space--ptb_90 {
        padding-top: 90px;
        padding-bottom: 90px;
    }

    .mb-30 {
        margin-bottom: 30px;
    }

    .mb-25 {
        margin-bottom: 25px;
    }

    .customer-zone>p {
        position: relative;
        /* padding: 14px 30px 14px 60px; */
        /* background-color: #f4f5f7; */
        margin: 0;
        font-weight: 600;
        color: #000000;
    }

    .mt-15 {
        margin-top: 15px;
    }

    .mt-50 {
        margin-top: 50px;
    }

    .checkout-login-info .single-input input {
        border: 1px solid #CDCDCD;
        height: 50px;
        background-color: transparent;
        width: 100%;
        color: #777;
        font-size: 14px;
        padding: 0 20px;
    }

    input[type="radio"] {
        position: relative;
        background: 0 0;
        border-width: 0;
        -webkit-box-shadow: none;
        box-shadow: none;
        margin: 0 10px 0 3px;
        cursor: pointer;
    }

    .btn--black {
        background: #dc3545;
        color: #ffffff;
        border-radius: 23px;
    }

    .btn--lg {
        line-height: 44px;
        padding: 0 42px;
        height: 46px;
    }

    @media only screen and (min-width: 992px) and (max-width: 1199px) {
        .billing-info-wrap.mr-100 {
            margin-right: 30px;
        }
    }

    .billing-info-wrap .additional-info-wrap {
        margin: 3px 0 0;
    }

    .billing-info-wrap .additional-info-wrap textarea {
        min-height: 120px;
        background-color: transparent;
        border-color: #e8e8e8;
        padding: 20px;
        color: #8a8a8a;
        width: 100%;
    }

    .billing-info-wrap .billing-info input {
        border: 1px solid #e8e8e8;
        height: 50px;
        background-color: transparent;
        padding: 2px 20px;
        color: #777;
        width: 100%;
    }

    .billing-info-wrap .billing-select .select-active {
        border: 1px solid #e6e6e6;
        color: #262626;
        border-radius: 0;
        background: transparent url(https://media-files.abidjan.net/livre/img/selector-icon.webp) no-repeat center right 20px !important;
    }

    select {
        padding: 3px 20px;
        height: 56px;
        max-width: 100%;
        width: 100%;
        outline: none;
        border: 1px solid #f8f8f8;
        border-radius: 5px;
        background: #f8f8f8 url(https://media-files.abidjan.net/livre/img/selector-icon.webp) no-repeat center right 20px;
        background-color: #f8f8f8;
        -moz-appearance: none;
        -webkit-appearance: none;
    }

    .your-order-area {
        /*padding: 40px 45px 50px;*/
        /* border-width: 2px; */
        /* border-style: solid; */
        padding: 20px 25px 30px;
        position: relative;
        /* border: 1px solid #bfbfbf; */
        /* background-color: #f6f6f6; */
        background-color: #f7f7f7;
        box-shadow: 0px 30px 40px -20px hsl(229deg 6% 66%);
    }

    @media only screen and (min-width: 992px) and (max-width: 1199px) {
        .your-order-area {
            padding: 40px 25px 50px;
        }
    }

    .your-order-area .your-order-wrap .your-order-info {
        border-bottom: 1px solid #CDCDCD;
    }

    .your-order-area .your-order-wrap .your-order-info.order-subtotal {
        padding: 15px 0 10px;
    }

    .your-order-area .your-order-wrap .your-order-info ul li span {
        float: right;
    }

    .your-order-area .your-order-wrap .your-order-middle {
        border-bottom: 1px solid #CDCDCD;
        padding: 24px 0 23px;
    }

    .your-order-area .your-order-wrap .your-order-middle ul li span {
        float: right;
        font-size: 16px;
        font-weight: 800;
    }

    .btn--black:hover {
        color: #ffffff;
    }

    .btn--black:focus {
        color: #ffffff;
    }

    .btn--lg {
        line-height: 44px;
        padding: 0 42px;
        height: 46px;
    }

    .payment__type {
        padding: 20px 40px;
        cursor: pointer;
        transition: all 0.15s ease;
        margin-right: 0px;
    }

    .features hr {
        float: left;
        margin-top: 10px;
    }

    label{
        font-weight: 100;
    }

    .btn-disabled {
        color: #8a959e;
        background-color: #d6d6d6;
        border-color: #e4e4e4;
        border-radius: 23px;
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
                        <h3 class="breadcrumb-title">Nouvelle précommande</h3>
                    </div>
                    <div class="col-lg-6  col-md-6 col-sm-6">
                        <!-- breadcrumb-list start -->
                        <ul class="breadcrumb-list text-center text-sm-end">
                            <li class="breadcrumb-item"><a href="/livre"> <i class="fa fa-home"></i> Accueil</a></li>
                            <li class="breadcrumb-item active">Nouvelle précommande</li>
                        </ul>
                        <!-- breadcrumb-list end -->
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<!--Header section end-->


<div id="main-wrapper">
    <div class="site-wrapper-reveal border-bottom features">
        <!-- checkout start -->
        <div class="checkout-main-area section-space--ptb_90">
            <div class="container">
                <div class="text-center">
                    * Vous avez tenté de payer le livre mais avez rencontré un souci ?
                    Renseignez ci-dessous votre numéro de téléphone afin de reprendre
                    le processus de paiement. Si vous avez encore des difficultés, contactez le <a href="tel:+2250747138047">+225 07 47 13 80 47</a>
                </div>
                <br><br>

                <!-- Form search data by phone number -->
                <div class="checkout-wrap" id="formSearchInfos">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="customer-zone mb-30">
                                <!-- <p class="cart-page-title">Informations de précommande</p> -->
                                <h4>Informations de précommande</h4>
                                <hr>
                                <br>
                            </div>
                            <div class="billing-info-wrap mr-100">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="billing-info mb-25">
                                            <label>Votre Numéro de téléphone <span class="required" title="required">*</span></label>
                                            <div style="display: flex; flex-direction: row">
                                                <input
                                                    type="text" name="phoneSearch" id="phoneSearch"
                                                    onkeyup="verif_nombre(this); checkField()"
                                                    class="form-control"
                                                    placeholder="Renseignez ici le numéro de téléphone utilisé lors de votre précommande"
                                                >
                                                <a href="javascript:;" class="btn btn--lg btn--black" onclick="searchData()" style="margin-left: 30px;">
                                                    Rechercher
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Reorder -->
                <div style="display: none" id="formReorder" class="checkout-wrap">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="customer-zone mb-30">
                                <!-- <p class="cart-page-title">Informations de précommande</p> -->
                                <h4>Informations de précommande</h4>
                                <hr>
                                <br>
                            </div>
                            <div class="billing-info-wrap mr-100">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="billing-info mb-25">
                                            <label>Nom <span class="required" title="required">*</span></label>
                                            <input type="text" name="name" id="nom" class="form-control" onkeyup="checkField()">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="billing-info mb-25">
                                            <label>Prénom(s) <span class="required" title="required">*</span></label>
                                            <input type="text" name="prenom" id="prenom" class="form-control" onkeyup="checkField()">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="billing-info mb-25">
                                            <label>Numéro de téléphone <span class="required" title="required">*</span></label>
                                            <input type="text" name="phone" id="phone" onkeyup="verif_nombre(this); checkField()" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="billing-info mb-25">
                                            <label>E-mail <span class="required" title="required">*</span></label>
                                            <input type="email" name="email" id="email" class="form-control" onkeyup="checkField()">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="billing-select mb-25">
                                            <label>Quantité <span class="required" title="required">*</span></label>
                                            <select class="select-active form-control" name="quantite" id="quantite" onchange="calculPrix()">
                                                <option value="1" selected>1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="billing-select mb-25">
                                            <label>Souhaitez-vous une dédicace ? </label>
                                            <select class="select-active form-control" name="dedicaceQ" id="dedicaceQuestion" onchange="dedicaceMode()">
                                                <option value="" selected disabled>Selectionnez une réponse</option>
                                                <option value="Oui">Oui</option>
                                                <option value="Non">Non</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12" id="alert12" style="display: none;">

                                        <div class="billing-select mb-25">
                                            <label>À qui voulez-vous que le livre soit dédicacé ? </label>
                                            <input type="text" class="form-control" name="dedicace" id="dedicace" placeholder="Vous même ou quelqu'un d'autre (inscrivez le nom de la personne)">
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12" id="alert124" style="display: none;">
                                        <div class="billing-select mb-25">
                                            <label>Dédicace 2 </label>
                                            <input type="text" class="form-control" name="dedicace2" id="dedicace2" placeholder="Vous même ou quelqu'un d'autre (inscrivez le nom de la personne)">
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12" id="alert125" style="display: none;">
                                        <div class="billing-select mb-25">
                                            <label>Dédicace 3 </label>
                                            <input type="text" class="form-control" name="dedicace3" id="dedicace3" placeholder="Vous même ou quelqu'un d'autre (inscrivez le nom de la personne)">
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12" id="alert1256" style="display: none;">
                                        <div class="billing-select mb-25">
                                            <label>Dédicace 4 </label>
                                            <input type="text" class="form-control" name="dedicace4" id="dedicace4" placeholder="Vous même ou quelqu'un d'autre (inscrivez le nom de la personne)">
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12" id="alert12567" style="display: none;">
                                        <div class="billing-select mb-25">
                                            <label>Dédicace 5 </label>
                                            <input type="text" class="form-control" name="dedicace5" id="dedicace5" placeholder="Vous même ou quelqu'un d'autre (inscrivez le nom de la personne)">
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="customer-zone mb-30">
                                            <!-- <p class="cart-page-title">Informations de livraison</p> -->
                                            <h4>Informations de livraison</h4>
                                            <hr>
                                            <br>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="billing-select mb-25">
                                            <label>Pays <span class="required" title="required">*</span></label>
                                            <select class="select-active form-control" onchange="chooseCountry()" id="country" name="country">
                                                <option value="CI" selected>Côte d'Ivoire</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="billing-info mb-25">
                                            <label>Ville <span class="required" title="required">*</span></label>
                                            <input type="text" class="form-control" name="city" id="city" onkeyup="checkField()">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="billing-select mb-25">
                                            <label>Choix de livraison <span class="required" title="required">*</span></label>
                                            <select
                                                class="select-active form-control"
                                                name="livraison" id="livraison"
                                                onchange="choixLivraison(); checkField()"
                                            >
                                                <option value="" selected>Sélectionnez une réponse</option>
                                                <option value="Agences">Livraison dans les agences Weblogy</option>
                                                <option value="Domicile">Livraison à domicile</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12" id="agences" style="display: none">
                                        <div class="billing-select mb-25">
                                            <label>Sélectionnez l'agence <span class="required" title="required">*</span></label>
                                            <select
                                                class="select-active form-control" name="agence" id="agence"
                                                onchange="displayAgenceInfos()">
                                                <option value="" selected>Sélectionnez</option>
                                                <option value="Agence de Cocody">Agence de Cocody</option>
                                                <option value="Agence du Plateau">Agence du Plateau</option>
                                                <option value="Agence de Prima">Agence de Prima</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12" id="infosAgence" style="display: none">
                                    </div>

                                    <div class="col-lg-12 col-md-12" id="domiciles" style="display: none">
                                        <div class="billing-info mb-25">
                                            <label>Lieu de livraison <span class="required" title="required">*</span></label>
                                            <input type="text" class="form-control" name="domicile" id="domicile">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="customer-zone mb-30">
                                <!-- <p class="cart-page-title">Mode de paiement</p> -->
                                <h4>Mode de paiement</h4>
                                <hr>
                                <br>
                            </div>
                            <div class="your-order-wrappwer tablet-mt__60 small-mt__60">
                                <h6 class="mb-20">Votre précommande</h6>
                                <div class="your-order-area">

                                    <div class="row">

                                        <div class="col-lg-3">
                                            <img src="https://media-files.abidjan.net/livre/img/pc.jpg" alt="Abidjan-net-book" data-at2x="https://media-files.abidjan.net/livre/img/pc.jpg" style="width: 120px;" width="120" height="186">
                                        </div>

                                        <div class="col-lg-9">
                                            <div class="your-order-wrap gray-bg-4">
                                                <div class="your-order-info-wrap">
                                                    <div class="your-order-info">
                                                        <ul>
                                                            <li>Livre <span>Total</span></li>
                                                        </ul>
                                                    </div>
                                                    <div class="your-order-middle">
                                                        <ul>
                                                            <li>Quantité X <strong id="qte">1</strong> <span id="amount"> 5 000 </span></li>
                                                            <li id="livraisonDomicile" style="display: none">Livraison à domicile <span id=""> 0 CFA </span></li>
                                                            <li id="livraisonAgence" style="display: none">Livraison en agence <span id=""> 0 CFA </span></li>
                                                            <input type="text" style="display: none;" id="amountTotal">
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <h6 class="mb-20 mt-50">Mode de paiement *</h6>
                                <div class="payment-method">
                                    <div class="row">
                                        <label class="payment__type col-lg-6 col-md-6 col-sm-6 col-xs-6 mb-20">
                                            <input type="radio" name="mode_paiement" id="VISA" checked="checked" value="VISA" onchange="choixModePaiement('VISA')">
                                            <img src="https://media-files.abidjan.net/livre/img/visa.png" data-at2x="https://media-files.abidjan.net/livre/img/visa.png" width="120" height="26">
                                        </label>

                                        <label class="payment__type col-lg-6 col-md-6 col-sm-6 col-xs-6 mb-20">
                                            <input type="radio" name="mode_paiement" id="MASTERCARD" value="MASTERCARD" onchange="choixModePaiement('MASTERCARD')">
                                            <img src="https://media-files.abidjan.net/livre/img/mastercard.png" data-at2x="https://media-files.abidjan.net/livre/img/mastercard.png" width="120" height="26">
                                        </label>

                                        <label class="payment__type col-lg-6 col-md-6 col-sm-6 col-xs-6 mb-20" id="omPay">
                                            <input type="radio" name="mode_paiement" id="orangemoney" value="orangemoney" onchange="choixModePaiement('orangemoney')">
                                            <img src="https://media-files.abidjan.net/livre/img/om.png" data-at2x="https://media-files.abidjan.net/livre/img/om.png" width="120" height="26">
                                        </label>

                                        <label class="payment__type col-lg-6 col-md-6 col-sm-6 col-xs-6 mb-20" id="momoPay">
                                            <input type="radio" name="mode_paiement" id="mtnmobilemoney" value="mtnmobilemoney" onchange="choixModePaiement('mtnmobilemoney')">
                                            <img src="https://media-files.abidjan.net/livre/img/momo.png" data-at2x="https://media-files.abidjan.net/livre/img/momo.png" width="120" height="26">
                                        </label>

                                        <label class="payment__type col-lg-6 col-md-6 col-sm-6 col-xs-6 mb-20" id="floozPay">
                                            <input type="radio" name="mode_paiement" id="flooz" value="flooz" onchange="choixModePaiement('flooz')">
                                            <img src="https://media-files.abidjan.net/livre/img/flooz.png" data-at2x="https://media-files.abidjan.net/livre/img/flooz.png" width="120" height="26">
                                        </label>


                                        <!-- Les champs masqués selon le mode de paiement sélectionné -->
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 billing-info-wrap" style="margin-top: 25px;">
                                            <div class="billing-info mb-25" id="alert1" style="display: none;">
                                                <label>Votre numéro MOOV money <span class="required" title="required">*</span></label>
                                                <input type="text" class="form-control" onkeyup="verif_nombre(this);" name="destMsisdn" id="destMsisdn">
                                            </div>

                                            <div class="billing-info mb-25" id="alert2" style="display: none;">
                                                <label>Votre numéro MTN money <span class="required" title="required">*</span></label>
                                                <input type="text" class="form-control" onkeyup="verif_nombre(this);" name="MSISDN" id="MSISDN">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="button-box mt-15">
                                    <!-- onclick="validerPaiement()" -->
                                    <a href="javascript:;" id="btnPay" class="btn btn--lg btn--black" disabled>
                                        Payer
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row container">
                        <div class="col-12 mt-50">
                            Vous avez un problème ? Appelez-nous <a href="tel:+2250747138047">+225 07 47 13 80 47</a>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <!-- checkout end -->

        <div class="container">
            <div id="formVisa"></div>
        </div>

    </div>

</div>

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


    /*** Search data by phone number ****/
    function searchData() {

        if ( $("#phoneSearch").val() == "" ) {

            Swal.fire(
                'Veuillez renseigner votre numéro de téléphone !',
                '',
                'info'
            )

            return false ;
        }

        var settings = {
            "async": true,
            "crossDomain": true,
            "url": "https://carte.abidjan.net/livre/apaym/search?numero="+ $("#phoneSearch").val(),
            "method": "GET",
            dataType: "json",
            //"timeout": 0,
            //"processData": false,
            //"mimeType": "multipart/form-data",
            "contentType": false,
            //"data": donnees,
            beforeSend: function() {
                $.LoadingOverlay("show", {
                    image: "",
                    fontawesome: "fa fa-spinner fa-spin",
                    maxSize: "50",
                    background: "rgba(255, 255, 255, 0.7)"
                });
            },
            success: function(response) {},
            error: function(resultat, statut, error) {},
            complete: function(resultat, statut) {
                $.LoadingOverlay("hide");
            }
        }

        $.ajax(settings).done(function(response) {

            var
                divReorder = document.getElementById("formReorder") ,
                divSearch = document.getElementById("formSearchInfos")
            ;

            if ( response.codeResp == "found" ) {

                divReorder.style.display = "block" ;
                divSearch.style.display = "none" ;

                // Set les values
                $("#nom").val( response.nom ) ;
                $("#prenom").val( response.prenom ) ;
                $("#phone").val( response.contact ) ;
                $("#email").val( response.email ) ;
                $("#city").val( response.ville ) ;
                $("#quantite").val( response.quantite ) ;
                calculPrix() ;
                $("#livraison").val( response.choixLivraison ) ;
                // Mode de paiement
                document.getElementById(response.mode_paimement).checked = true ;
                choixModePaiement(response.mode_paimement) ;

                if ( response.mode_paimement == "flooz" ) {
                    $("#destMsisdn").val( response.numero ) ;
                } else if ( response.mode_paimement == "mtnmobilemoney" ) {
                    $("#MSISDN").val( response.numero ) ;
                }

                // Choix de livraison
                if ( response.choixLivraison == "Agences" ) {
                    $("#agence").val( response.lieuLivraison ) ;
                    choixLivraison() ;
                    displayAgenceInfos() ;
                } else {
                    $("#domicile").val( response.lieuLivraison ) ;
                    choixLivraison() ;
                }

                // Dédicace
                if ( response.dedicace.replace(/\s/g, "") !== "" ) {

                    // L'utilisateur a renseigné au moins une dédicace, le reste sera en fonction du nombre de livre
                    // Set la value de Dédicace à 'Oui'
                    $("#dedicaceQuestion").val('Oui') ;
                    $("#dedicace").val(response.dedicace) ;
                    dedicaceMode() ;
                    $("#dedicace2").val(response.dedicace2) ;
                    $("#dedicace3").val(response.dedicace3) ;
                    $("#dedicace4").val(response.dedicace4) ;
                    $("#dedicace5").val(response.dedicace5) ;

                } else {

                    $("#dedicace").val(response.dedicace) ;
                    $("#dedicace2").val(response.dedicace2) ;
                    $("#dedicace3").val(response.dedicace3) ;
                    $("#dedicace4").val(response.dedicace4) ;
                    $("#dedicace5").val(response.dedicace5) ;
                }

                // Enable button "Payer"
                checkField() ;

                return false ;
            }


            // Aucune info trouvée
            Swal.fire(
                response.messageResp,
                '',
                'info'
            )

        });
    }



    /*** Send data ****/

    document.getElementById("amountTotal").setAttribute('value', $("#quantite").val() * 5000);
    $("#amount").html((new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'XOF'
    }).format($("#quantite").val() * 5000)));


    $("#btnPay").removeClass("btn--black") ;
    $("#btnPay").addClass("btn-disabled") ;

    function checkField(){
        //console.log(event.target.value) event.target.value
        if (
            $("#nom").val().replace(/\s/g, "") == "" ||
            $("#prenom").val().replace(/\s/g, "") == "" ||
            $("#phone").val().replace(/\s/g, "") == "" ||
            $("#email").val().replace(/\s/g, "") == "" ||
            $("#city").val().replace(/\s/g, "") == "" ||
            $("#livraison").val().replace(/\s/g, "") == ""
        ) {
            // Disable button "Payer"
            $("#btnPay").attr("disabled", true);
            $("#btnPay").removeClass("btn--black") ;
            $("#btnPay").addClass("btn-disabled") ;
        } else {
            // Enable button "Payer"
            $("#btnPay").attr("disabled", false);
            $("#btnPay").addClass("btn--black") ;
            $("#btnPay").removeClass("btn-disabled") ;
        }
    }

    function displayAgenceInfos() {

        var infosAgence = document.getElementById("infosAgence") ;

        if ( $("#agence").val() == "Agence de Cocody" ) {
            infosAgence.style.display = "block" ;
            infosAgence.innerHTML = '<div class="text-left">\n' +
                '                        <p style="font-size: 15px">\n' +
                '                            Rendez-vous à l\'agence de Cocody pour récupérer votre commande. <br><i class="fa fa-map-marker"></i>&nbsp; Cocody Ambassade, Rue Booker Washington près du cabinet children of Africa. <br>\n' +
                '                            <a href="tel:002252522010114"><i class="fa fa-phone"></i>&nbsp; +225 25 22 01 01 14</a>\n' +
                '                        </p>\n' +
                '                    </div>\n' +
                '                    <div class="text-left">\n' +
                '                            <p style="font-size: 15px"><i class="icon-clock3 fa fa-clock-o"></i>&nbsp;Nos horaires</p>\n' +
                '                            <p style="font-size: 15px">\n' +
                '                                Lundi - Samedi : 08h00 - 21h00\n' +
                '                                <br>\n' +
                '                                Dimanche : 09h00 - 17h00\n' +
                '                            </p>\n' +
                '                    </div>'
        } else if ( $("#agence").val() == "Agence du Plateau" ) {
            infosAgence.style.display = "block" ;
            infosAgence.innerHTML = '<div class="text-left">\n' +
                '                        <p  style="font-size: 15px">\n' +
                '                            Rendez-vous à l\'agence du Plateau pour récupérer votre commande. <br><i class="fa fa-map-marker"></i>&nbsp; Plateau pyramide, rue BCEAO, après le pullman hôtel. Résidence PELIEU 2e étage. <br>\n' +
                '                            <a href="tel:2252720334952"><i class="fa fa-phone"></i>&nbsp; +225 27 20 33 49 52</a>\n' +
                '                        </p>\n' +
                '                    </div>\n' +
                '                    <div class="text-left">\n' +
                '                            <p style="font-size: 15px"><i class="icon-clock3 fa fa-clock-o"></i>&nbsp;Nos horaires</p>\n' +
                '                            <p style="font-size: 15px">\n' +
                '                                Lundi - Vendredi : 08h00 - 18h00\n' +
                '                                <br>\n' +
                '                                Samedi : 09h00 - 13h00\n' +
                '                            </p>\n' +
                '                    </div>'
        } else if ( $("#agence").val() == "Agence de Prima" ) {
            infosAgence.style.display = "block" ;
            infosAgence.innerHTML = '<div class="text-left">\n' +
                '                        <p style="font-size: 15px;">\n' +
                '                            Rendez-vous à l\'agence de Prima pour récupérer votre commande. <br><i class="fa fa-map-marker"></i>&nbsp; Marcory Zone 4 immeuble pistache et chocolat.  <br>\n' +
                '                            <a href="tel:2252521006089"><i class="fa fa-phone"></i>&nbsp; +225 25 21 00 60 89</a>\n' +
                '                        </p>\n' +
                '                    </div>\n' +
                '                    <div class="text-left ">\n' +
                '                            <p style="font-size: 15px"><i class="icon-clock3 fa fa-clock-o"></i>&nbsp;Nos horaires</p>\n' +
                '                            <p style="font-size: 15px">\n' +
                '                                Lundi - Vendredi : 8h00 - 18h00\n' +
                '                                <br>\n' +
                '                                Samedi : 9h00 - 13h00\n' +
                '                            </p>\n' +
                '                    </div>'
        } else {
            infosAgence.style.display = "none" ;
            infosAgence.innerHTML = "" ;
        }
    }

    function choixLivraison() {

        var
            domicile = document.getElementById("domiciles"),
            agences = document.getElementById("agences"),
            infosAgence = document.getElementById("infosAgence"),
            livraisonDomicile = document.getElementById("livraisonDomicile"),
            livraisonAgence = document.getElementById("livraisonAgence")
        ;

        if ( $("#livraison").val() == "Domicile" ) {
            domicile.style.display = "block";
            livraisonDomicile.style.display = "block";
            livraisonAgence.style.display = "none";
            agences.style.display = "none";
            infosAgence.style.display = "none";
        } else if ( $("#livraison").val() == "Agences"  ) {
            domicile.style.display = "none";
            agences.style.display = "block";
            livraisonAgence.style.display = "block";
            livraisonDomicile.style.display = "none";
        } else {
            domicile.style.display = "none";
            agences.style.display = "none";
            infosAgence.style.display = "none";
            livraisonAgence.style.display = "none";
            livraisonDomicile.style.display = "none";
        }

    }

    function calculPrix() {

        $("#qte").html($("#quantite").val());
        $("#amount").html((new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: 'XOF'
        }).format($("#quantite").val() * 5000)));

        document.getElementById("amountTotal").setAttribute('value', $("#quantite").val() * 5000)

        // Dedicace
        var
            dedicace1 = document.getElementById("alert12"),
            dedicace2 = document.getElementById("alert124"),
            dedicace3 = document.getElementById("alert125"),
            dedicace4 = document.getElementById("alert1256"),
            dedicace5 = document.getElementById("alert12567")
        ;

        if ($("#dedicaceQuestion").val() == 'Oui') {
            // Dedicace autres
            if ( $("#quantite").val() == "1" ) {
                dedicace1.style.display = "block";
                dedicace2.style.display = "none";
                dedicace3.style.display = "none";
                dedicace4.style.display = "none";
                dedicace5.style.display = "none";
            }else if ( $("#quantite").val() == "2" ) {
                dedicace1.style.display = "block";
                dedicace2.style.display = "block";
                dedicace3.style.display = "none";
                dedicace4.style.display = "none";
                dedicace5.style.display = "none";
            } else if ( $("#quantite").val() == "3" ) {
                dedicace1.style.display = "block";
                dedicace2.style.display = "block";
                dedicace3.style.display = "block";
                dedicace4.style.display = "none";
                dedicace5.style.display = "none";
            } else if ( $("#quantite").val() == "4" ) {
                dedicace1.style.display = "block";
                dedicace2.style.display = "block";
                dedicace3.style.display = "block";
                dedicace4.style.display = "block";
                dedicace5.style.display = "none";
            } else if ( $("#quantite").val() == "5" ) {
                dedicace1.style.display = "block";
                dedicace2.style.display = "block";
                dedicace3.style.display = "block";
                dedicace4.style.display = "block";
                dedicace5.style.display = "block";
            }
        } else {
            dedicace1.style.display = "none";
        }
    }

    function chooseCountry() {

        var
            omPay = document.getElementById("omPay"),
            momoPay = document.getElementById("momoPay"),
            floozPay = document.getElementById("floozPay");

        if ($("#country").val() !== "CI") {
            omPay.style.display = "none";
            momoPay.style.display = "none";
            floozPay.style.display = "none";
        } else {
            omPay.style.display = "block";
            momoPay.style.display = "block";
            floozPay.style.display = "block";
        }
    }

    function choixModePaiement(mode_paiement) {

        var
            a = document.getElementById("alert1"),
            b = document.getElementById("alert2")
            //e = document.getElementById("alert01")
        ;

        if (mode_paiement == 'VISA') {

            a.style.display = "none";
            b.style.display = "none";
            //e.style.display = "block";
            /*d.style.display = "none";*/

        } else if (mode_paiement == 'MASTERCARD') {

            a.style.display = "none";
            b.style.display = "none";
            //e.style.display = "block";
            /*d.style.display = "none";*/

        } else if (mode_paiement == 'flooz') {

            a.style.display = "block";
            b.style.display = "none";
            //e.style.display = "none";
            /*d.style.display = "none";*/

        } else if (mode_paiement == 'orangemoney') {

            a.style.display = "none";
            b.style.display = "none";
            //e.style.display = "none";
            /*d.style.display = "none";*/

        } else if (mode_paiement == 'mtnmobilemoney') {

            a.style.display = "none";
            b.style.display = "block";
            //e.style.display = "none";
            /*d.style.display = "none";*/

        } else {

            a.style.display = "none";
            b.style.display = "none";
            //e.style.display = "block";
            /*d.style.display = "none";*/
        }

        if ($("#destMsisdn").is(":visible")) {

            //document.getElementById("destMsisdn").required = true;
            //alert ("#destMsisdn est visible");
            $("#destMsisdn").attr("required", "true");

        }
        if ($("#destMsisdn").is(":hidden")) {

            //alert ("#destMsisdn est caché");
            //document.getElementById("destMsisdn").required = false;
            //document.getElementById('destMsisdn').value="";
            //$("input").attr("required", "false");
            $('#destMsisdn').removeAttr('required')
            $('#destMsisdn').val('');

        }

        if ($("#MSISDN").is(":visible")) {
            $("#MSISDN").attr("required", "true");
        }
        if ($("#MSISDN").is(":hidden")) {
            $('#MSISDN').removeAttr('required')
            $('#MSISDN').val('');
        }

        if ($("#idclient").is(":visible")) {
            $("#idclient").attr("required", "true");
        }

        if ($("#idclient").is(":hidden")) {
            $('#idclient').removeAttr('required')
            $('#idclient').val('');
        }
    }

    function dedicaceMode() {

        var
            dedicace1 = document.getElementById("alert12"),
            dedicace2 = document.getElementById("alert124"),
            dedicace3 = document.getElementById("alert125"),
            dedicace4 = document.getElementById("alert1256"),
            dedicace5 = document.getElementById("alert12567")
        ;

        if ($("#dedicaceQuestion").val() == 'Oui') {

            // Dedicace autres
            if ( $("#quantite").val() == "1" ) {
                dedicace1.style.display = "block";
                dedicace2.style.display = "none";
                dedicace3.style.display = "none";
                dedicace4.style.display = "none";
                dedicace5.style.display = "none";
            }else if ( $("#quantite").val() == "2" ) {
                dedicace1.style.display = "block";
                dedicace2.style.display = "block";
                dedicace3.style.display = "none";
                dedicace4.style.display = "none";
                dedicace5.style.display = "none";
            } else if ( $("#quantite").val() == "3" ) {
                dedicace1.style.display = "block";
                dedicace2.style.display = "block";
                dedicace3.style.display = "block";
                dedicace4.style.display = "none";
                dedicace5.style.display = "none";
            } else if ( $("#quantite").val() == "4" ) {
                dedicace1.style.display = "block";
                dedicace2.style.display = "block";
                dedicace3.style.display = "block";
                dedicace4.style.display = "block";
                dedicace5.style.display = "none";
            } else if ( $("#quantite").val() == "5" ) {
                dedicace1.style.display = "block";
                dedicace2.style.display = "block";
                dedicace3.style.display = "block";
                dedicace4.style.display = "block";
                dedicace5.style.display = "block";
            }
        } else {
            dedicace1.style.display = "none";
            dedicace2.style.display = "none";
            dedicace3.style.display = "none";
            dedicace4.style.display = "none";
            dedicace5.style.display = "none";
        }
    }

    // Empêcher la saisie de textes et autres caractères différents des chiffres
    function verif_nombre(champ) {

        var chiffres = new RegExp("[0-9]"),
            verif, points = 0;

        for (x = 0; x < champ.value.length; x++) {
            verif = chiffres.test(champ.value.charAt(x));
            if (champ.value.charAt(x) == ".") {
                points++;
            }
            if (points > 1) {
                verif = false;
                points = 1;
            }
            if (verif == false) {
                champ.value = champ.value.substr(0, x) + champ.value.substr(x + 1, champ.value.length - x + 1);
                x--;
            }
        }
    }

    function validerPaiement() {

        // Récupérer les valeurs du formulaire pour vérification
        let
            nomValue = $("#nom").val(), // document.getElementById("nom").value, // champ requis
            prenomValue = $("#prenom").val(), // document.getElementById("prenom").value, // champ requis
            phoneValue = $("#phone").val(), // document.getElementById("phone").value, // champ requis
            livraisonValue = $("#livraison").val(), // document.getElementById("livraison").value, // champ requis
            dedicaceValue = $("#dedicace").val(), // document.getElementById("dedicace").value, // champ requis,
            dedicace2Value = $("#dedicace2").val(),
            dedicace3Value = $("#dedicace3").val(),
            dedicace4Value = $("#dedicace4").val(),
            dedicace5Value = $("#dedicace5").val(),
            emailValue = $("#email").val(),
            countryValue = $("#country").val(),
            cityValue = $("#city").val(),
            // addressValue = $("#address").val(),
            // regionValue = $("#region").val(),
            // codePostalValue = $("#code_postal").val(),
            quantiteValue = $("#quantite").val(),
            choixPaiementValue = document.querySelector('input[name="mode_paiement"]:checked').value;


        // console.log($("#livraison").val())

        if (
            nomValue.replace(/\s/g, "") == "" ||
            prenomValue.replace(/\s/g, "") == "" ||
            phoneValue.replace(/\s/g, "") == "" ||
            livraisonValue.replace(/\s/g, "") == "" ||
            emailValue.replace(/\s/g, "") == "" ||
            quantiteValue.replace(/\s/g, "") == "" ||
            countryValue.replace(/\s/g, "") == "" ||
            cityValue.replace(/\s/g, "") == "" ||
            //addressValue.replace(/\s/g, "") == "" ||
            // codePostalValue.replace(/\s/g, "") == "" ||
            choixPaiementValue == ""
        ) {

            Swal.fire(
                'Veuillez renseigner tous les champs obligatoires !',
                '',
                'info'
            )

            return false;
        }

        // Set datas
        var datas = new FormData();
        // datas.append("Amount", $("#amountTotal").val());
        // datas.append("Amount", "100");
        datas.append("nom", nomValue);
        datas.append("prenom", prenomValue);
        datas.append("contact", phoneValue);
        datas.append("email", emailValue);
        datas.append("dedicace", dedicaceValue);
        datas.append("dedicace2", dedicace2Value);
        datas.append("dedicace3", dedicace3Value);
        datas.append("dedicace4", dedicace4Value);
        datas.append("dedicace5", dedicace5Value);
        datas.append("mode_paimement", choixPaiementValue);
        datas.append("quantite", quantiteValue);
        datas.append("country", countryValue);
        datas.append("city", cityValue);


        // Disable button "Payer"
        $("#btnPay").attr("disabled", false);

        // Lieu de livraison
        if ( livraisonValue == "Agences" ) {

            if ( $("#agence").val() == "" ) {

                Swal.fire(
                    'Attention !',
                    'Veuillez sélectionnez une agence de livraison !',
                    'info'
                )

                return false;
            }

            // Send Data with parameters
            datas.append("Amount", parseInt($("#amountTotal").val()) + 0);
            // datas.append("Amount", "5");
            datas.append("choixLivraison", livraisonValue);
            datas.append("lieuLivraison", $("#agence").val());
            // sendDatas(datas, choixPaiementValue);

            //*************************///
            // Mode de paiement
            // MTN money
            if (choixPaiementValue == "mtnmobilemoney") {

                let numeroMTNValue = document.getElementById("MSISDN").value;
                if (numeroMTNValue.replace(/\s/g, "") == "") {

                    Swal.fire(
                        'Attention !',
                        'Veuillez renseigner votre numéro MTN money !',
                        'info'
                    )

                    return false;
                }

                // Call sendDatas() function with parameters
                datas.append("MSISDN", numeroMTNValue);
                sendDatas(datas, choixPaiementValue);
                return false;
            }

            // Moov money
            if (choixPaiementValue == "flooz") {

                let numeroFloozValue = document.getElementById("destMsisdn").value;
                if (numeroFloozValue.replace(/\s/g, "") == "") {

                    Swal.fire(
                        'Attention !',
                        'Veuillez renseigner votre numéro Flooz !',
                        'info'
                    )

                    return false;
                }

                // Call sendDatas() function with parameters
                datas.append("MSISDN", numeroFloozValue);
                sendDatas(datas, choixPaiementValue);
                return false;
            }

            // Visa ou Mastercard
            if (choixPaiementValue == "VISA" || choixPaiementValue == "MASTERCARD") {

                // Call sendDatas() function with parameters
                datas.append("typecarte", choixPaiementValue);
                sendDatas(datas, choixPaiementValue);
                return false;
            }



            // Orange Money
            // Call sendDatas() function with parameters
            sendDatas(datas, choixPaiementValue);
            return false ;
            return false ;
        }

        if ( livraisonValue == "Domicile" ) {

            if ( $("#domicile").val().replace(/\s/g, "") == "" ) {

                Swal.fire(
                    'Attention !',
                    'Veuillez renseigner le lieu de livraison !',
                    'info'
                )

                return false;
            }

            // Send Data with parameters
            datas.append("Amount", parseInt($("#amountTotal").val()) + 0);
            // datas.append("Amount", "5");
            datas.append("choixLivraison", livraisonValue);
            datas.append("lieuLivraison", $("#domicile").val());
            // sendDatas(datas, choixPaiementValue);

            //*************************///
            // Mode de paiement
            // MTN money
            if (choixPaiementValue == "mtnmobilemoney") {

                let numeroMTNValue = document.getElementById("MSISDN").value;
                if (numeroMTNValue.replace(/\s/g, "") == "") {

                    Swal.fire(
                        'Attention !',
                        'Veuillez renseigner votre numéro MTN money !',
                        'info'
                    )

                    return false;
                }

                // Call sendDatas() function with parameters
                datas.append("MSISDN", numeroMTNValue);
                sendDatas(datas, choixPaiementValue);
                return false;
            }

            // Moov money
            if (choixPaiementValue == "flooz") {

                let numeroFloozValue = document.getElementById("destMsisdn").value;
                if (numeroFloozValue.replace(/\s/g, "") == "") {

                    Swal.fire(
                        'Attention !',
                        'Veuillez renseigner votre numéro Flooz !',
                        'info'
                    )

                    return false;
                }

                // Call sendDatas() function with parameters
                datas.append("MSISDN", numeroFloozValue);
                sendDatas(datas, choixPaiementValue);
                return false;
            }

            // Visa ou Mastercard
            if (choixPaiementValue == "VISA" || choixPaiementValue == "MASTERCARD") {

                // Call sendDatas() function with parameters
                datas.append("typecarte", choixPaiementValue);
                sendDatas(datas, choixPaiementValue);
                return false;
            }

            // Orange Money
            // Call sendDatas() function with parameters
            sendDatas(datas, choixPaiementValue);
            return false ;
        }

    }

    // Send data to save payment
    function sendDatas(donnees, choixPaiementValue) {

        var url = "";
        if (choixPaiementValue == "mtnmobilemoney") {
            url = "https://carte.abidjan.net/livre/mtn-money.php";
        } else if (choixPaiementValue == "orangemoney") {
            url = "https://carte.abidjan.net/livre/orange-money.php";
        } else if (choixPaiementValue == "VISA" || choixPaiementValue == "MASTERCARD") {
            url = "https://carte.abidjan.net/livre/visa.php";
        } else if (choixPaiementValue == "flooz") {
            url = "https://carte.abidjan.net/livre/moov-money.php";
        }

        var settings = {
            "async": true,
            "crossDomain": true,
            "url": url,
            "method": "POST",
            dataType: "json",
            "timeout": 0,
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": false,
            "data": donnees,
            beforeSend: function() {
                $.LoadingOverlay("show", {
                    image: "",
                    fontawesome: "fa fa-spinner fa-spin",
                    maxSize: "50",
                    background: "rgba(255, 255, 255, 0.7)"
                });
            },
            success: function(response) {},
            error: function(resultat, statut, error) {},
            complete: function(resultat, statut) {
                $.LoadingOverlay("hide");
            }
        }

        $.ajax(settings).done(function(response) {

            /** ********************************************************************************** **/
            /** Sauvegarder les infos de paiement pour afficher un récapitulatif en cas de succès **/
            // Sauvegarder le nom & prénom(s)
            window.localStorage.setItem("nomClient", $("#prenom").val() +" "+ $("#nom").val()) ;
            // Sauvegarder le mode de paiement sélectionné
            window.localStorage.setItem("modePaiement", choixPaiementValue) ;
            // Sauvegarder la quantité
            window.localStorage.setItem("qte", $("#quantite").val()) ;
            // Sauvegarder le choix de livraison ainsi que le lieu
            window.localStorage.setItem("choixLivraison", $("#livraison").val()) ;
            window.localStorage.setItem("lieuLivraison", $("#livraison").val() == "Agences" ? $("#agence").val() : $("#domicile").val()) ;
            // Date de la commande
            window.localStorage.setItem("dateCommande", response.dateCommande) ;
            /** ********************************************************************************** **/
            /** Sauvegarder les infos de paiement pour afficher un récapitulatif en cas de succès **/

            // console.log(response)
            if (response.ResponseCode == "1000") {

                Swal.fire({
                    title: 'Nous attendons votre validation !',
                    text: response.message,
                    icon: 'info',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                })

                // MTN money
                var stopInterval = 0 ;
                stopInterval = setInterval(() => {

                    $.ajax({
                        type: 'POST',
                        url: 'https://carte.abidjan.net/livre/cronapimtn.php',
                        dataType: 'json',
                        cache: true,
                        global: false,
                        data: {
                            "code_transaction": response.code_transaction
                        },
                        success: function(rep) {

                            // console.log(rep);
                            if (rep.ResponseCode == "01") {

                                // Sauvegarder le numéro de la commande
                                clearInterval(stopInterval);
                                window.localStorage.setItem("numCommande", response.num_commande) ;
                                window.location.href = "/livre/success-payment";
                                return false;

                            } else if (rep.ResponseCode == "529" ) {

                                clearInterval(stopInterval);
                                Swal.fire({
                                    title: 'Échec de paiement !',
                                    text: "<strong>Votre solde est insuffisant</strong>, votre paiement a échoué. Vous allez être redirigé.",
                                    icon: 'error',
                                    showCancelButton: false,
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    confirmButtonColor: '#dc3545',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.localStorage.clear();
                                        window.location.href = "/livre/failure-payment";
                                    }
                                }) ;

                            } else if ( rep.ResponseCode == "100" ) {

                                clearInterval(stopInterval);
                                Swal.fire({
                                    title: 'Échec de paiement !',
                                    text: "<strong>Votre transaction est incomplète</strong>, votre paiement a échoué. Vous allez être redirigé.",
                                    icon: 'error',
                                    showCancelButton: false,
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    confirmButtonColor: '#dc3545',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.localStorage.clear();
                                        window.location.href = "/livre/failure-payment";
                                    }
                                }) ;

                            } else if (rep.ResponseCode == "515") {

                                clearInterval(stopInterval);
                                Swal.fire({
                                    title: 'Échec de paiement !',
                                    text: "<strong>Votre numéro est incorrect</strong>, votre paiement a échoué. Vous allez être redirigé.",
                                    icon: 'error',
                                    showCancelButton: false,
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    confirmButtonColor: '#dc3545',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.localStorage.clear();
                                        window.location.href = "/livre/failure-payment";
                                    }
                                }) ;

                            } else if (rep.ResponseCode == "9") {

                                clearInterval(stopInterval);
                                Swal.fire({
                                    title: 'Échec de paiement !',
                                    html: "<strong>Votre mot de passe est incorrect</strong>, votre paiement a échoué. Vous allez être redirigé.",
                                    icon: 'error',
                                    showCancelButton: false,
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    confirmButtonColor: '#dc3545',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.localStorage.clear();
                                        window.location.href = "/livre/failure-payment";
                                    }
                                }) ;

                            } else if (rep.ResponseCode == "1000") {

                                // Transaction is still on pending
                                return false;

                            } else  {

                                console.log(rep.ResponseCode) ;
                                clearInterval(stopInterval);
                                Swal.fire({
                                    title: 'Échec de paiement !',
                                    html: 'Erreur système. Vous avez un problème ? Appelez-nous <a href="tel:+2250747138047">+225 07 47 13 80 47</a>',
                                    icon: 'error',
                                    showCancelButton: false,
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    confirmButtonColor: '#dc3545',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.localStorage.clear();
                                        window.location.href = "/livre/failure-payment";
                                    }
                                }) ;
                            }
                        }
                    });

                }, 10000);

            } else if (response.ResponseCode == "INITIATED") {

                // Sauvegarder le numéro de la commande
                window.localStorage.setItem("numCommande", response.num_commande) ;
                Swal.fire({
                    title: 'Vous allez être redirigé sur la page de paiement',
                    html: "Veuillez y renseigner votre <strong>numéro orange money</strong> ainsi que le code de paiement à 4 chiffres (Composez <strong>#144*82#</strong>).",
                    icon: 'info',
                    showCancelButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Orange money
                        window.location.href = response.payment_url;
                    }
                }) ;

            } else if (response.ResponseCode == "VISA") {

                // Sauvegarder le numéro de la commande
                window.localStorage.setItem("numCommande", response.num_commande) ;
                // Transaction initiée, redirection vers le formulaire de paiement visa/mastercard
                Swal.fire({
                    title: 'Vous allez être redirigé sur la page de paiement',
                    html: "Veuillez y renseigner les informations de votre carte (<strong>PAN, CVV et date d'expiration</strong>) afin de valider votre paiement.",
                    icon: 'info',
                    showCancelButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("#formVisa").html(response.Content);
                        $(document).ready(function() {
                            $('#formcomstatus').submit();
                        });
                    }
                }) ;

            } else if (response.ResponseCode == 0) {

                Swal.fire({
                    title: 'Nous attendons votre validation !',
                    text: "Rendez-vous sur votre téléphone et veuillez suivre les instructions de Moov pour valider le paiement.",
                    icon: 'info',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                })

                // Flooz
                var stopInterval = 0 ;
                stopInterval = setInterval(() => {

                    $.ajax({
                        type: 'POST',
                        url: 'https://carte.abidjan.net/livre/checkmoov.php',
                        dataType: 'json',
                        cache: true,
                        global: false,
                        data: {
                            "code_transaction": response.code_transaction
                        },
                        success: function(rep) {

                            if (rep.statusCode == 0) {

                                // Sauvegarder le numéro de la commande
                                window.localStorage.setItem("numCommande", response.num_commande) ;
                                clearInterval(stopInterval);
                                window.location.href = "/livre/success-payment";
                                return false;

                            } else if (rep.statusCode == 49) {

                                clearInterval(stopInterval);
                                Swal.fire({
                                    title: 'Échec de paiement !',
                                    text: "Votre paiement a échoué. Vous allez être redirigé.",
                                    icon: 'error',
                                    showCancelButton: false,
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    confirmButtonColor: '#dc3545',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.localStorage.clear();
                                        window.location.href = "/livre/failure-payment";
                                    }
                                }) ;

                            } else if ( rep.statusCode == 1) {

                                clearInterval(stopInterval);
                                Swal.fire({
                                    title: 'Échec de paiement !',
                                    text: "Aucune transaction trouvée, votre paiement a échoué. Vous allez être redirigé.",
                                    icon: 'error',
                                    showCancelButton: false,
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    confirmButtonColor: '#dc3545',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.localStorage.clear();
                                        window.location.href = "/livre/failure-payment";
                                    }
                                }) ;

                            } else if (rep.statusCode == 47 ) {

                                // Transaction is still on pending
                                return false;

                            } else if ( rep.statusCode == 50 ) {

                                // Transaction is timeout
                                clearInterval(stopInterval);
                                Swal.fire({
                                    title: 'Échec de paiement !',
                                    text: "Votre session est échue, le paiement a échoué. Vous allez être redirigé.",
                                    icon: 'error',
                                    showCancelButton: false,
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    confirmButtonColor: '#dc3545',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.localStorage.clear();
                                        window.location.href = "/livre/failure-payment";
                                    }
                                });

                            } else {

                                clearInterval(stopInterval);
                                Swal.fire({
                                    title: 'Échec de paiement !',
                                    text: "Transaction interrompue, le paiement a échoué. Vous allez être redirigé.",
                                    icon: 'error',
                                    showCancelButton: false,
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    confirmButtonColor: '#dc3545',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.localStorage.clear();
                                        window.location.href = "/livre/failure-payment";
                                    }
                                });
                            }
                        }
                    });

                }, 10000);

            } else {

                Swal.fire({
                    title: 'Échec !',
                    html: 'Une erreur est survenue. Vous avez un problème ? Appelez-nous <a href="tel:+2250747138047">+225 07 47 13 80 47</a>',
                    icon: 'error',
                    showCancelButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Ok'
                })
            }

        });
    }

</script>
