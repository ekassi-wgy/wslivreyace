<?php
$title = "Abidjan.net, Une Histoire d'Avenir - Précommande"
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

    h6{
        font-size: 16px;
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
        padding: 20px 25px 30px;
        position: relative;
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
        padding: 25px 10px /*20px 40px*/;
        cursor: pointer;
        transition: all 0.15s ease;
        margin-right: 0px;
        /*width: max-content;*/
        white-space: nowrap;
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

    .customIconSearch {
        margin-left: -75px;
        color: #ffffff;
        background: #dc3544;
        border-radius: 20px;
        width: max-content;
        height: 30px;
        align-items: center;
        display: flex;
        justify-content: center;
        cursor: pointer;
        padding: 10px;
    }

    .btn-disabled--custom{
        margin-left: -75px;
        color: #8a959e;
        background: #d6d6d6;
        border-radius: 20px;
        width: max-content;
        height: 30px;
        align-items: center;
        display: flex;
        justify-content: center;
        cursor: pointer;
        padding: 10px;
    }

    .input-disabled {
        background: #ededed !important;
        border-color: #e4e4e4 !important;
    }

    .alert {
        position: relative;
        padding: 0.75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: 1.25rem;
    }

    .alert-info {
        color: #004085;
        background-color: #cce5ff;
        border-color: #b8daff;
        font-size: 13px;
    }

    .alert-dismissible .close {
        position: absolute;
        top: 0;
        right: 0;
        /*padding: 0.75rem 1.25rem;*/
        color: inherit;
    }

    button.close {
        padding: 0;
        background-color: transparent;
        border: 0;
        -webkit-appearance: none;
    }

    .qrcode {
        border: 6px solid black;
        background-clip: padding-box;
        border-image: url(data:image/svg+xml;base64,PHN2ZyBoZWlnaHQ9IjI1IiB3aWR0aD0iMjUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0iTTAgMTBIM1YzaDdWMEgwWk0xNSAwdjNoN3Y3aDNWMFpNMCAxNVYyNUgxMFYyMkgzdi03em0yMiAwdjdoLTd2M0gyNVYxNVoiLz48L3N2Zz4=) 10 repeat;
        border-image-outset: 2;
        border-image-width: 2;
        width: 220px;
        margin: 0 auto;
        margin-bottom: 45px;
    }

    .qrContent {
        display: flex;
        flex-direction: column;
        text-align: center;
        align-items: center;
    }

    .pQr {
        font-size: 16px;
        margin-bottom: 30px;
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
                        <h3 class="breadcrumb-title">Précommande</h3>
                    </div>
                    <div class="col-lg-6  col-md-6 col-sm-6">
                        <!-- breadcrumb-list start -->
                        <ul class="breadcrumb-list text-center text-sm-end">
                            <li class="breadcrumb-item"><a href="/livre"> <i class="fa fa-home"></i> Accueil</a></li>
                            <li class="breadcrumb-item active">Précommande</li>
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
                <div class="checkout-wrap">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="customer-zone mb-30">
                                <!-- <p class="cart-page-title">Informations de précommande</p> -->
                                <h4>Informations de précommande</h4>
                                <hr> <br>
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
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                                <option value="18">18</option>
                                                <option value="19">19</option>
                                                <option value="20">20</option>
                                                <option value="21">21</option>
                                                <option value="22">22</option>
                                                <option value="23">23</option>
                                                <option value="24">24</option>
                                                <option value="25">25</option>
                                                <option value="26">26</option>
                                                <option value="27">27</option>
                                                <option value="28">28</option>
                                                <option value="29">29</option>
                                                <option value="30">30</option>
                                                <option value="31">31</option>
                                                <option value="32">32</option>
                                                <option value="33">33</option>
                                                <option value="34">34</option>
                                                <option value="35">35</option>
                                                <option value="36">36</option>
                                                <option value="37">37</option>
                                                <option value="38">38</option>
                                                <option value="39">39</option>
                                                <option value="40">40</option>
                                                <option value="41">41</option>
                                                <option value="42">42</option>
                                                <option value="43">43</option>
                                                <option value="44">44</option>
                                                <option value="45">45</option>
                                                <option value="46">46</option>
                                                <option value="47">47</option>
                                                <option value="48">48</option>
                                                <option value="49">49</option>
                                                <option value="50">50</option>
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
                                            <label>À qui voulez-vous que le livre soit dédicacé ? (Maximum 5 livres) </label>
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
                                            <hr> <br>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="billing-select mb-25">
                                            <label>Pays <span class="required" title="required">*</span></label>
                                            <select class="select-active form-control" id="country" name="country">
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
                                <hr> <br>
                            </div>
                            <div class="your-order-wrappwer tablet-mt__60 small-mt__60">
                                <h6 class="mb-20">Votre précommande</h6>
                                <div class="your-order-area">

                                    <div class="row">

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                            <img src="https://media-files.abidjan.net/livre/img/pc.jpg" alt="Abidjan-net-book" data-at2x="https://media-files.abidjan.net/livre/img/pc.jpg" width="120" height="186">
                                        </div>

                                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
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

                                        <!-- ****************************** -->
                                        <!-- ****************************** -->

                                        <label class="payment__type col-lg-6 col-md-6 col-sm-6 col-xs-6 mb-20">
                                            <input type="radio" name="mode_paiement" value="APaym" onchange="choixModePaiement('APaym')">
                                            <img src="https://media-files.abidjan.net/livre/img/Apaym-Logo-02.png" data-at2x="https://media-files.abidjan.net/livre/img/Apaym-Logo-02.png" width="160" height="auto">
                                        </label>

                                        <label class="payment__type col-lg-6 col-md-6 col-sm-6 col-xs-6 mb-20">
                                            <input type="radio" name="mode_paiement" value="Visa QR" onchange="choixModePaiement('Visa QR')">
                                            <img src="https://media-files.abidjan.net/livre/img/Visa-Qr-Logo-02.png" data-at2x="https://media-files.abidjan.net/livre/img/Visa-Qr-Logo-02.png" width="160" height="auto">
                                        </label>

                                        <label class="payment__type col-lg-6 col-md-6 col-sm-6 col-xs-6 mb-20">
                                            <input type="radio" name="mode_paiement" value="VISA" onchange="choixModePaiement('VISA')">
                                            <img src="https://media-files.abidjan.net/livre/img/Visa-Logo-02.png" data-at2x="https://media-files.abidjan.net/livre/img/Visa-Logo-02.png" width="160" height="auto">
                                        </label>

                                        <label class="payment__type col-lg-6 col-md-6 col-sm-6 col-xs-6 mb-20">
                                            <input type="radio" name="mode_paiement" value="MASTERCARD" onchange="choixModePaiement('MASTERCARD')">
                                            <img src="https://media-files.abidjan.net/livre/img/Mastercard-Logo-02.png" data-at2x="https://media-files.abidjan.net/livre/img/Mastercard-Logo-02.png" width="160" height="auto">
                                        </label>

                                        <label class="payment__type col-lg-6 col-md-6 col-sm-6 col-xs-6 mb-20" id="omPay">
                                            <input type="radio" name="mode_paiement" value="orangemoney" onchange="choixModePaiement('orangemoney')">
                                            <img src="https://media-files.abidjan.net/livre/img/Om-Logo-02.png" data-at2x="https://media-files.abidjan.net/livre/img/Om-Logo-02.png" width="160" height="auto">
                                        </label>

                                        <label class="payment__type col-lg-6 col-md-6 col-sm-6 col-xs-6 mb-20" id="momoPay">
                                            <input type="radio" name="mode_paiement" value="mtnmobilemoney" onchange="choixModePaiement('mtnmobilemoney')">
                                            <img src="https://media-files.abidjan.net/livre/img/Momo-Logo-02.png" data-at2x="https://media-files.abidjan.net/livre/img/Momo-Logo-02.png" width="160" height="auto">
                                        </label>

                                        <label class="payment__type col-lg-6 col-md-6 col-sm-6 col-xs-6 mb-20" id="floozPay">
                                            <input type="radio" name="mode_paiement" value="flooz" onchange="choixModePaiement('flooz')">
                                            <img src="https://media-files.abidjan.net/livre/img/Moov-Logo-02.png" data-at2x="https://media-files.abidjan.net/livre/img/Moov-Logo-02.png" width="160" height="auto">
                                        </label>


                                        <!-- Les champs masqués selon le mode de paiement sélectionné -->
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 billing-info-wrap" style="margin-top: 25px;">

                                            <div class="billing-info mb-25" id="alertApaym" style="display: none">

                                                <div class="billing-info mb-25" id="alertApaym" style="">
                                                    <label>Votre numéro de téléphone APaym <span class="required" title="required">*</span></label>
                                                    <div style="display: flex; align-items: center;">
                                                        <select class="select-active " id="indicatif" name="indicatif" style="width: 100px; margin-right: 5px;">
                                                            <option value="" selected></option>
                                                        </select>
                                                        <input type="text" class="form-control" onkeyup="verif_nombre(this); checkApaymNumber();" name="numApaym" id="numApaym">
                                                    </div>
                                                </div>

                                                <label>Votre code de paiement <span class="required" title="required">*</span></label>
                                                <div style="display: flex; align-items: center;">
                                                    <input type="text" class="form-control" maxlength="5" onkeyup="verif_nombre(this); checkCodePaiement();" name="codePaiement" id="codePaiement" disabled>
                                                </div>

                                                <br>

                                                <div class="alert alert-info alert-dismissible" id="msgApaymCode" style="display: none !important;">
                                                    <strong><i class="fa fa-shopping-bag"></i>&nbsp; Code de paiement :</strong>
                                                    Rendez-vous dans votre application APaym (Android *), sélectionnez la carte à utiliser pour cet achat de <span id="prixAchat">5 000 FCFA</span> puis, cliquez sur Obtenir un Code de paiement.
                                                    <button type="button" class="close" onclick="hideMessage()">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                            </div>

                                            <div class="billing-info mb-25" id="alert1" style="display: none;">
                                                <label>Votre numéro MOOV money <span class="required" title="required">*</span></label>
                                                <input type="text" class="form-control" onkeyup="verif_nombre(this);" name="destMsisdn" id="destMsisdn">
                                            </div>

                                            <div class="billing-info mb-25" id="alert2" style="display: none;">
                                                <label>Votre numéro MoMo <span class="required" title="required">*</span></label>
                                                <input type="text" class="form-control" onkeyup="verif_nombre(this);" name="MSISDN" id="MSISDN">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="button-box mt-15">
                                    <a href="javascript:;" id="btnPay" class="btn btn--lg btn--black" onclick="validerPaiement()" disabled>
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

    document.getElementById("amountTotal").setAttribute('value', $("#quantite").val() * 5000);
    $("#amount").html((new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'XOF'
    }).format($("#quantite").val() * 5000)));


    $("#prixAchat").html((new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'XOF'
    }).format($("#quantite").val() * 5000)));

    $("#btnPay").removeClass("btn--black") ;
    $("#btnPay").addClass("btn-disabled") ;
    $("#btnVerify").removeClass("customIconSearch") ;
    $("#btnVerify").addClass("btn-disabled--custom") ;
    $("#codePaiement").addClass("input-disabled") ;

    function checkApaymNumber(){
        let msgApaymCode = document.getElementById("msgApaymCode") ;
        if ( $("#numApaym").val().replace(/\s/g, "") == "" ) {
            $("#codePaiement").attr("disabled", true);
            $("#codePaiement").addClass("input-disabled") ;
            msgApaymCode.style.display = "none" ;
        } else {
            $("#codePaiement").attr("disabled", false);
            $("#codePaiement").removeClass("input-disabled") ;
            msgApaymCode.style.display = "block" ;
        }
    }

    function checkCodePaiement() {
        if ( $("#codePaiement").val().replace(/\s/g, "") == "" ) {
            // Disable button "Payer"
            $("#btnPay").attr("disabled", true);
            $("#btnPay").removeClass("btn--black") ;
            $("#btnPay").addClass("btn-disabled") ;
        } else {
            checkField() ;
        }
    }

    function checkField(){

        // if ( document.querySelector('input[name="mode_paiement"]:checked').value == "APaym" ) {
        //     return false;
        // }

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

    function hideMessage() {
        $(".alert").attr("style", "display: none !important");
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

        $("#prixAchat").html((new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: 'XOF'
        }).format($("#quantite").val() * 5000)));

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
            if ( parseInt($("#quantite").val()) == 1 ) {
                dedicace1.style.display = "block";
                dedicace2.style.display = "none";
                dedicace3.style.display = "none";
                dedicace4.style.display = "none";
                dedicace5.style.display = "none";
            }else if ( parseInt($("#quantite").val()) == 2 ) {
                dedicace1.style.display = "block";
                dedicace2.style.display = "block";
                dedicace3.style.display = "none";
                dedicace4.style.display = "none";
                dedicace5.style.display = "none";
            } else if ( parseInt($("#quantite").val()) == 3 ) {
                dedicace1.style.display = "block";
                dedicace2.style.display = "block";
                dedicace3.style.display = "block";
                dedicace4.style.display = "none";
                dedicace5.style.display = "none";
            } else if ( parseInt($("#quantite").val()) == 4 ) {
                dedicace1.style.display = "block";
                dedicace2.style.display = "block";
                dedicace3.style.display = "block";
                dedicace4.style.display = "block";
                dedicace5.style.display = "none";
            } else if ( parseInt($("#quantite").val()) >= 5 ) {
                dedicace1.style.display = "block";
                dedicace2.style.display = "block";
                dedicace3.style.display = "block";
                dedicace4.style.display = "block";
                dedicace5.style.display = "block";
            }

            /*if ( $("#quantite").val() == "1" ) {
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
            } */

        } else {
            dedicace1.style.display = "none";
            dedicace2.style.display = "none";
            dedicace3.style.display = "none";
            dedicace4.style.display = "none";
            dedicace5.style.display = "none";
        }
    }

    function choixModePaiement(mode_paiement) {

        var
            a = document.getElementById("alert1"),
            b = document.getElementById("alert2"),
            apaym = document.getElementById("alertApaym"),
            displayVisaOptions = document.getElementById("displayVisaOptions")
        ;

        if (mode_paiement == 'APaym') {

            apaym.style.display = "block";
            a.style.display = "none";
            b.style.display = "none";

            $("#btnPay").attr("disabled", true);
            $("#btnPay").removeClass("btn--black") ;
            $("#btnPay").addClass("btn-disabled") ;

            // $(".alert").attr("style", "display: block !important");

        }else if (mode_paiement == 'VISA') {

            a.style.display = "none";
            b.style.display = "none";
            apaym.style.display = "none";

            checkField() ;
            $(".alert").attr("style", "display: none !important");

        } else if (mode_paiement == 'MASTERCARD') {

            a.style.display = "none";
            b.style.display = "none";
            apaym.style.display = "none";

            checkField() ;
            $(".alert").attr("style", "display: none !important");

        } else if (mode_paiement == 'flooz') {

            a.style.display = "block";
            b.style.display = "none";
            apaym.style.display = "none";

            checkField() ;
            $(".alert").attr("style", "display: none !important");

        } else if (mode_paiement == 'orangemoney') {

            a.style.display = "none";
            b.style.display = "none";
            apaym.style.display = "none";

            checkField() ;
            $(".alert").attr("style", "display: none !important");

        } else if (mode_paiement == 'mtnmobilemoney') {

            a.style.display = "none";
            b.style.display = "block";
            apaym.style.display = "none";

            checkField() ;
            $(".alert").attr("style", "display: none !important");

        } else {

            a.style.display = "none";
            b.style.display = "none";
            apaym.style.display = "none";

            checkField() ;
            $(".alert").attr("style", "display: none !important");
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
            if ( parseInt($("#quantite").val()) == 1 ) {
                dedicace1.style.display = "block";
                dedicace2.style.display = "none";
                dedicace3.style.display = "none";
                dedicace4.style.display = "none";
                dedicace5.style.display = "none";
            }else if ( parseInt($("#quantite").val()) == 2 ) {
                dedicace1.style.display = "block";
                dedicace2.style.display = "block";
                dedicace3.style.display = "none";
                dedicace4.style.display = "none";
                dedicace5.style.display = "none";
            } else if ( parseInt($("#quantite").val()) == 3 ) {
                dedicace1.style.display = "block";
                dedicace2.style.display = "block";
                dedicace3.style.display = "block";
                dedicace4.style.display = "none";
                dedicace5.style.display = "none";
            } else if ( parseInt($("#quantite").val()) == 4 ) {
                dedicace1.style.display = "block";
                dedicace2.style.display = "block";
                dedicace3.style.display = "block";
                dedicace4.style.display = "block";
                dedicace5.style.display = "none";
            } else if ( parseInt($("#quantite").val()) >= 5 ) {
                dedicace1.style.display = "block";
                dedicace2.style.display = "block";
                dedicace3.style.display = "block";
                dedicace4.style.display = "block";
                dedicace5.style.display = "block";
            }

            /*if ( $("#quantite").val() == "1" ) {
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
            }*/

        } else {
            dedicace1.style.display = "none";
            dedicace2.style.display = "none";
            dedicace3.style.display = "none";
            dedicace4.style.display = "none";
            dedicace5.style.display = "none";
        }
    }

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
            quantiteValue = $("#quantite").val(),
            choixPaiementValue = document.querySelector('input[name="mode_paiement"]:checked') !== null ? document.querySelector('input[name="mode_paiement"]:checked').value : ""
        ;

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
            ( choixPaiementValue == "" || choixPaiementValue == undefined )
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

            // Mode de paiement APaym
            if ( choixPaiementValue == "APaym" ) {

                let
                    numAPaymValue = document.getElementById("numApaym").value,
                    codePaiement = document.getElementById("codePaiement").value
                ;
                if (
                    codePaiement.replace(/\s/g, "") == "" ||
                    numAPaymValue.replace(/\s/g, "") == ""
                ) {

                    Swal.fire(
                        'Attention !',
                        'Veuillez renseigner votre numéro APaym et votre code de paiement APaym !',
                        'info'
                    )

                    return false;
                }

                // Call sendDatas() function with parameters
                datas.append("codePaiement", codePaiement);
                datas.append("numAPaym", $("#indicatif").val() +""+ numAPaymValue);
                sendDatas(datas, choixPaiementValue);
                return false;
            }

            // Orange Money et/ou Visa QR
            // Call sendDatas() function with parameters
            sendDatas(datas, choixPaiementValue);
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

            // Mode de paiement APaym
            if ( choixPaiementValue == "APaym" ) {

                let
                    numAPaymValue = document.getElementById("numApaym").value,
                    codePaiement = document.getElementById("codePaiement").value
                ;
                if (
                    codePaiement.replace(/\s/g, "") == "" ||
                    numAPaymValue.replace(/\s/g, "") == ""
                ) {

                    Swal.fire(
                        'Attention !',
                        'Veuillez renseigner votre numéro APaym et votre code de paiement APaym !',
                        'info'
                    )

                    return false;
                }

                // Call sendDatas() function with parameters
                datas.append("codePaiement", codePaiement);
                datas.append("numAPaym", $("#indicatif").val() +""+numAPaymValue);
                sendDatas(datas, choixPaiementValue);
                return false;
            }

            // Orange Money et/ou Visa QR
            // Call sendDatas() function with parameters
            sendDatas(datas, choixPaiementValue);
            return false ;
        }

    }

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
        } else if (choixPaiementValue == "APaym") {
            url = "https://carte.abidjan.net/livre/apaym/paiement_apaym";
        } else if ( choixPaiementValue == "Visa QR"){
            url = "https://carte.abidjan.net/livre/apaym/paiement_qr";
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
                // MTN
                getResponseMTN(response) ;

            } else if (response.ResponseCode == "INITIATED") {
                // Orange
                getResponseOM(response) ;

            } else if (response.ResponseCode == "VISA") {
                // Visa - Mastercard
                getResponseVisaMastercard(response);

            } else if (response.ResponseCode == 0) {
                // Moov
                getResponseFlooz(response);

            } else if (response.ResponseCode == "INITIALISE") {
                // APaym
                getResponseAPaym(response);

            }  else if (response.ResponseCode == "Visa-QR") {
                // Visa-QR
                getResponseVisaQr(response);

            } else {
                // Erreur dans l'exécution
                Swal.fire({
                    title: 'Échec !',
                    html: 'Une erreur est survenue. Vous avez un problème ? Appelez-nous <a href="tel:+2250747138047">+225 07 47 13 80 47</a>',
                    icon: 'error',
                    showCancelButton: false,
                    showCloseButton: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Ok'
                })
            }

        });
    }

    function getResponseMTN(response) {

        Swal.fire({
            title: 'Nous attendons votre validation !',
            text: response.message,
            icon: 'info',
            showConfirmButton: false,
            allowOutsideClick: false,
            //showCloseButton: true,
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

    }

    function getResponseOM(response) {

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
            //showCloseButton: true,
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                // Orange money
                window.location.href = response.payment_url;
            }
        }) ;
    }

    function getResponseVisaMastercard(response){

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
            //showCloseButton: true,
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
    }

    function getResponseFlooz(response){

        Swal.fire({
            title: 'Nous attendons votre validation !',
            text: "Rendez-vous sur votre téléphone et veuillez suivre les instructions de Moov pour valider le paiement.",
            icon: 'info',
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            //showCloseButton: true,
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

    }

    function getResponseAPaym(response) {
        
        if ( response.codeResponse == "1" ) {

            // Succès
            console.log(response) ;
            window.localStorage.setItem("numCommande", response.num_commande) ;
            window.location.href = "/livre/success-payment";
            return false ;
        }

        if ( response.codeResponse == "0" ) {

            // Échec
            Swal.fire({
                title: 'Échec de paiement !',
                text: "Votre paiement a échoué. Vous allez être redirigé.",
                icon: 'error',
                showCancelButton: false,
                //showCloseButton: true,
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

            return false;
        }

        Swal.fire({
            title: 'Échec de paiement !',
            text: response.messageResponse,
            icon: 'error',
            showCancelButton: false,
            showCloseButton: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Ok'
        }) ;
    }

    function getResponseVisaQr(response){

        Swal.fire({
            title: 'Nous attendons votre paiement',
            html: "<div class='qrContent'> <p class='pQr'>Scannez le QR code ci-dessous dans votre application APaym (Android) ou autre application Visa pour compléter le paiement.</p> <div class='qrcode'> <img src='"+response.url+"' style='width: inherit;'/> </div><div><img src=\"https://media-files.abidjan.net/livre/img/Visa-bottom.png\" data-at2x=\"https://media-files.abidjan.net/livre/img/Visa-bottom.png\" width=\"70\" height=\"auto\"><h4 style=\"font-weight: 600\">"+response.merchantName+"</h4></div></div>",
            // icon: 'info',
            // imageUrl: response.url,
            // imageWidth: 200,
            // imageAlt: 'Visa QR image prod by Weblogy Offshore',
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showCloseButton: true,
            didOpen: () => {
                Swal.showLoading()
            }
        }) ;

        // On lance le check de la transaction 9s après l'affichage du Visa QR
        var stopInterval = 0 ;
        setTimeout(()=>{
            stopInterval = setInterval(() => {

                var checkData = new FormData();
                checkData.append("billId", response.billId) ;

                $.ajax({
                    type: 'POST',
                    url: 'https://carte.abidjan.net/livre/apaym/check_visa_qr',
                    "method": "POST",
                    dataType: "json",
                    "timeout": 0,
                    "processData": false,
                    "mimeType": "multipart/form-data",
                    "contentType": false,
                    "data": checkData,
                    success: function(rep) {

                        if (rep.ResponseCode == "01") {

                            // Sauvegarder le numéro de la commande
                            clearInterval(stopInterval);
                            window.localStorage.setItem("numCommande", response.num_commande) ;
                            window.location.href = "/livre/success-payment";
                            return false;

                        } else if (rep.ResponseCode == "00" ) {

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

                        } else if (rep.ResponseCode == "404") {

                            // Transaction is still on pending, maybe
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

            }, 9000);

        }, 9000) ;
    }

    let jsonData = [ { "name": "Afghanistan", "dial_code": "93", "code": "AF", "flag": "🇦🇫" }, { "name": "Albania", "dial_code": "355", "code": "AL", "flag": "🇦🇱" }, { "name": "Algeria", "dial_code": "213", "code": "DZ", "flag": "🇩🇿" }, { "name": "AmericanSamoa", "dial_code": "1684", "code": "AS", "flag": "🇦🇸" }, { "name": "Andorra", "dial_code": "376", "code": "AD", "flag": "🇦🇩" }, { "name": "Angola", "dial_code": "244", "code": "AO", "flag": "🇦🇴" }, { "name": "Anguilla", "dial_code": "1264", "code": "AI", "flag": "🇦🇮" }, { "name": "Antarctica", "dial_code": "672", "code": "AQ", "flag": "🇦🇶" }, { "name": "Antigua and Barbuda", "dial_code": "1268", "code": "AG", "flag": "🇦🇬" }, { "name": "Argentina", "dial_code": "54", "code": "AR", "flag": "🇦🇷" }, { "name": "Armenia", "dial_code": "374", "code": "AM", "flag": "🇦🇲" }, { "name": "Aruba", "dial_code": "297", "code": "AW", "flag": "🇦🇼" }, { "name": "Australia", "dial_code": "61", "code": "AU", "preferred": true, "flag": "🇦🇺" }, { "name": "Austria", "dial_code": "43", "code": "AT", "flag": "🇦🇹" }, { "name": "Azerbaijan", "dial_code": "994", "code": "AZ", "flag": "🇦🇿" }, { "name": "Bahamas", "dial_code": "1242", "code": "BS", "flag": "🇧🇸" }, { "name": "Bahrain", "dial_code": "973", "code": "BH", "flag": "🇧🇭" }, { "name": "Bangladesh", "dial_code": "880", "code": "BD", "flag": "🇧🇩" }, { "name": "Barbados", "dial_code": "1246", "code": "BB", "flag": "🇧🇧" }, { "name": "Belarus", "dial_code": "375", "code": "BY", "flag": "🇧🇾" }, { "name": "Belgium", "dial_code": "32", "code": "BE", "flag": "🇧🇪" }, { "name": "Belize", "dial_code": "501", "code": "BZ", "flag": "🇧🇿" }, { "name": "Benin", "dial_code": "229", "code": "BJ", "flag": "🇧🇯" }, { "name": "Bermuda", "dial_code": "1441", "code": "BM", "flag": "🇧🇲" }, { "name": "Bhutan", "dial_code": "975", "code": "BT", "flag": "🇧🇹" }, { "name": "Bolivia, Plurinational State of", "dial_code": "591", "code": "BO", "flag": "🇧🇴" }, { "name": "Bosnia and Herzegovina", "dial_code": "387", "code": "BA", "flag": "🇧🇦" }, { "name": "Botswana", "dial_code": "267", "code": "BW", "flag": "🇧🇼" }, { "name": "Brazil", "dial_code": "55", "code": "BR", "flag": "🇧🇷" }, { "name": "British Indian Ocean Territory", "dial_code": "246", "code": "IO", "flag": "🇮🇴" }, { "name": "Brunei Darussalam", "dial_code": "673", "code": "BN", "flag": "🇧🇳" }, { "name": "Bulgaria", "dial_code": "359", "code": "BG", "flag": "🇧🇬" }, { "name": "Burkina Faso", "dial_code": "226", "code": "BF", "flag": "🇧🇫" }, { "name": "Burundi", "dial_code": "257", "code": "BI", "flag": "🇧🇮" }, { "name": "Cambodia", "dial_code": "855", "code": "KH", "flag": "🇰🇭" }, { "name": "Cameroon", "dial_code": "237", "code": "CM", "flag": "🇨🇲" }, { "name": "Canada", "dial_code": "1", "code": "CA", "flag": "🇨🇦" }, { "name": "Cape Verde", "dial_code": "238", "code": "CV", "flag": "🇨🇻" }, { "name": "Cayman Islands", "dial_code": "345", "code": "KY", "flag": "🇰🇾" }, { "name": "Central African Republic", "dial_code": "236", "code": "CF", "flag": "🇨🇫" }, { "name": "Chad", "dial_code": "235", "code": "TD", "flag": "🇹🇩" }, { "name": "Chile", "dial_code": "56", "code": "CL", "flag": "🇨🇱" }, { "name": "China", "dial_code": "86", "code": "CN", "flag": "🇨🇳" }, { "name": "Christmas Island", "dial_code": "61", "code": "CX", "flag": "🇨🇽" }, { "name": "Cocos (Keeling) Islands", "dial_code": "61", "code": "CC", "flag": "🇨🇨" }, { "name": "Colombia", "dial_code": "57", "code": "CO", "flag": "🇨🇴" }, { "name": "Comoros", "dial_code": "269", "code": "KM", "flag": "🇰🇲" }, { "name": "Congo", "dial_code": "242", "code": "CG", "flag": "🇨🇬" }, { "name": "Congo, The Democratic Republic of the", "dial_code": "243", "code": "CD", "flag": "🇨🇩" }, { "name": "Cook Islands", "dial_code": "682", "code": "CK", "flag": "🇨🇰" }, { "name": "Costa Rica", "dial_code": "506", "code": "CR", "flag": "🇨🇷" }, { "name": "Cote d'Ivoire", "dial_code": "225", "code": "CI", "flag": "🇨🇮" }, { "name": "Croatia", "dial_code": "385", "code": "HR", "flag": "🇭🇷" }, { "name": "Cuba", "dial_code": "53", "code": "CU", "flag": "🇨🇺" }, { "name": "Cyprus", "dial_code": "537", "code": "CY", "flag": "🇨🇾" }, { "name": "Czech Republic", "dial_code": "420", "code": "CZ", "flag": "🇨🇿" }, { "name": "Denmark", "dial_code": "45", "code": "DK", "flag": "🇩🇰" }, { "name": "Djibouti", "dial_code": "253", "code": "DJ", "flag": "🇩🇯" }, { "name": "Dominica", "dial_code": "1767", "code": "DM", "flag": "🇩🇲" }, { "name": "Dominican Republic", "dial_code": "1849", "code": "DO", "flag": "🇩🇴" }, { "name": "Ecuador", "dial_code": "593", "code": "EC", "flag": "🇪🇨" }, { "name": "Egypt", "dial_code": "20", "code": "EG", "flag": "🇪🇬" }, { "name": "El Salvador", "dial_code": "503", "code": "SV", "flag": "🇸🇻" }, { "name": "Equatorial Guinea", "dial_code": "240", "code": "GQ", "flag": "🇬🇶" }, { "name": "Eritrea", "dial_code": "291", "code": "ER", "flag": "🇪🇷" }, { "name": "Estonia", "dial_code": "372", "code": "EE", "flag": "🇪🇪" }, { "name": "Ethiopia", "dial_code": "251", "code": "ET", "flag": "🇪🇹" }, { "name": "Falkland Islands (Malvinas)", "dial_code": "500", "code": "FK", "flag": "🇫🇰" }, { "name": "Faroe Islands", "dial_code": "298", "code": "FO", "flag": "🇫🇴" }, { "name": "Fiji", "dial_code": "679", "code": "FJ", "flag": "🇫🇯" }, { "name": "Finland", "dial_code": "358", "code": "FI", "flag": "🇫🇮" }, { "name": "France", "dial_code": "33", "code": "FR", "flag": "🇫🇷" }, { "name": "French Guiana", "dial_code": "594", "code": "GF", "flag": "🇬🇫" }, { "name": "French Polynesia", "dial_code": "689", "code": "PF", "flag": "🇵🇫" }, { "name": "Gabon", "dial_code": "241", "code": "GA", "flag": "🇬🇦" }, { "name": "Gambia", "dial_code": "220", "code": "GM", "flag": "🇬🇲" }, { "name": "Georgia", "dial_code": "995", "code": "GE", "flag": "🇬🇪" }, { "name": "Germany", "dial_code": "49", "code": "DE", "flag": "🇩🇪" }, { "name": "Ghana", "dial_code": "233", "code": "GH", "flag": "🇬🇭" }, { "name": "Gibraltar", "dial_code": "350", "code": "GI", "flag": "🇬🇮" }, { "name": "Greece", "dial_code": "30", "code": "GR", "flag": "🇬🇷" }, { "name": "Greenland", "dial_code": "299", "code": "GL", "flag": "🇬🇱" }, { "name": "Grenada", "dial_code": "1473", "code": "GD", "flag": "🇬🇩" }, { "name": "Guadeloupe", "dial_code": "590", "code": "GP", "flag": "🇬🇵" }, { "name": "Guam", "dial_code": "1671", "code": "GU", "flag": "🇬🇺" }, { "name": "Guatemala", "dial_code": "502", "code": "GT", "flag": "🇬🇹" }, { "name": "Guernsey", "dial_code": "44", "code": "GG", "flag": "🇬🇬" }, { "name": "Guinea", "dial_code": "224", "code": "GN", "flag": "🇬🇳" }, { "name": "Guinea-Bissau", "dial_code": "245", "code": "GW", "flag": "🇬🇼" }, { "name": "Guyana", "dial_code": "595", "code": "GY", "flag": "🇬🇾" }, { "name": "Haiti", "dial_code": "509", "code": "HT", "flag": "🇭🇹" }, { "name": "Holy See (Vatican City State)", "dial_code": "379", "code": "VA", "flag": "🇻🇦" }, { "name": "Honduras", "dial_code": "504", "code": "HN", "flag": "🇭🇳" }, { "name": "Hong Kong", "dial_code": "852", "code": "HK", "flag": "🇭🇰" }, { "name": "Hungary", "dial_code": "36", "code": "HU", "flag": "🇭🇺" }, { "name": "Iceland", "dial_code": "354", "code": "IS", "flag": "🇮🇸" }, { "name": "India", "dial_code": "91", "code": "IN", "preferred": true, "flag": "🇮🇳" }, { "name": "Indonesia", "dial_code": "62", "code": "ID", "flag": "🇮🇩" }, { "name": "Iran, Islamic Republic of", "dial_code": "98", "code": "IR", "flag": "🇮🇷" }, { "name": "Iraq", "dial_code": "964", "code": "IQ", "flag": "🇮🇶" }, { "name": "Ireland", "dial_code": "353", "code": "IE", "flag": "🇮🇪" }, { "name": "Isle of Man", "dial_code": "44", "code": "IM", "flag": "🇮🇲" }, { "name": "Israel", "dial_code": "972", "code": "IL", "flag": "🇮🇱" }, { "name": "Italy", "dial_code": "39", "code": "IT", "flag": "🇮🇹" }, { "name": "Jamaica", "dial_code": "1876", "code": "JM", "flag": "🇯🇲" }, { "name": "Japan", "dial_code": "81", "code": "JP", "flag": "🇯🇵" }, { "name": "Jersey", "dial_code": "44", "code": "JE", "flag": "🇯🇪" }, { "name": "Jordan", "dial_code": "962", "code": "JO", "flag": "🇯🇴" }, { "name": "Kazakhstan", "dial_code": "77", "code": "KZ", "flag": "🇰🇿" }, { "name": "Kenya", "dial_code": "254", "code": "KE", "flag": "🇰🇪" }, { "name": "Kiribati", "dial_code": "686", "code": "KI", "flag": "🇰🇮" }, { "name": "Korea, Democratic People's Republic of", "dial_code": "850", "code": "KP", "flag": "🇰🇵" }, { "name": "Korea, Republic of", "dial_code": "82", "code": "KR", "flag": "🇰🇷" }, { "name": "Kuwait", "dial_code": "965", "code": "KW", "flag": "🇰🇼" }, { "name": "Kyrgyzstan", "dial_code": "996", "code": "KG", "flag": "🇰🇬" }, { "name": "Lao People's Democratic Republic", "dial_code": "856", "code": "LA", "flag": "🇱🇦" }, { "name": "Latvia", "dial_code": "371", "code": "LV", "flag": "🇱🇻" }, { "name": "Lebanon", "dial_code": "961", "code": "LB", "flag": "🇱🇧" }, { "name": "Lesotho", "dial_code": "266", "code": "LS", "flag": "🇱🇸" }, { "name": "Liberia", "dial_code": "231", "code": "LR", "flag": "🇱🇷" }, { "name": "Libyan Arab Jamahiriya", "dial_code": "218", "code": "LY", "flag": "🇱🇾" }, { "name": "Liechtenstein", "dial_code": "423", "code": "LI", "flag": "🇱🇮" }, { "name": "Lithuania", "dial_code": "370", "code": "LT", "flag": "🇱🇹" }, { "name": "Luxembourg", "dial_code": "352", "code": "LU", "flag": "🇱🇺" }, { "name": "Macao", "dial_code": "853", "code": "MO", "flag": "🇲🇴" }, { "name": "Macedonia, The Former Yugoslav Republic of", "dial_code": "389", "code": "MK", "flag": "🇲🇰" }, { "name": "Madagascar", "dial_code": "261", "code": "MG", "flag": "🇲🇬" }, { "name": "Malawi", "dial_code": "265", "code": "MW", "flag": "🇲🇼" }, { "name": "Malaysia", "dial_code": "60", "code": "MY", "flag": "🇲🇾" }, { "name": "Maldives", "dial_code": "960", "code": "MV", "flag": "🇲🇻" }, { "name": "Mali", "dial_code": "223", "code": "ML", "flag": "🇲🇱" }, { "name": "Malta", "dial_code": "356", "code": "MT", "flag": "🇲🇹" }, { "name": "Marshall Islands", "dial_code": "692", "code": "MH", "flag": "🇲🇭" }, { "name": "Martinique", "dial_code": "596", "code": "MQ", "flag": "🇲🇶" }, { "name": "Mauritania", "dial_code": "222", "code": "MR", "flag": "🇲🇷" }, { "name": "Mauritius", "dial_code": "230", "code": "MU", "flag": "🇲🇺" }, { "name": "Mayotte", "dial_code": "262", "code": "YT", "flag": "🇾🇹" }, { "name": "Mexico", "dial_code": "52", "code": "MX", "flag": "🇲🇽" }, { "name": "Micronesia, Federated States of", "dial_code": "691", "code": "FM", "flag": "🇫🇲" }, { "name": "Moldova, Republic of", "dial_code": "373", "code": "MD", "flag": "🇲🇩" }, { "name": "Monaco", "dial_code": "377", "code": "MC", "flag": "🇲🇨" }, { "name": "Mongolia", "dial_code": "976", "code": "MN", "flag": "🇲🇳" }, { "name": "Montenegro", "dial_code": "382", "code": "ME", "flag": "🇲🇪" }, { "name": "Montserrat", "dial_code": "1664", "code": "MS", "flag": "🇲🇸" }, { "name": "Morocco", "dial_code": "212", "code": "MA", "flag": "🇲🇦" }, { "name": "Mozambique", "dial_code": "258", "code": "MZ", "flag": "🇲🇿" }, { "name": "Myanmar", "dial_code": "95", "code": "MM", "flag": "🇲🇲" }, { "name": "Namibia", "dial_code": "264", "code": "NA", "flag": "🇳🇦" }, { "name": "Nauru", "dial_code": "674", "code": "NR", "flag": "🇳🇷" }, { "name": "Nepal", "dial_code": "977", "code": "NP", "flag": "🇳🇵" }, { "name": "Netherlands", "dial_code": "31", "code": "NL", "flag": "🇳🇱" }, { "name": "Netherlands Antilles", "dial_code": "599", "code": "AN", "flag": "🇦🇳" }, { "name": "New Caledonia", "dial_code": "687", "code": "NC", "flag": "🇳🇨" }, { "name": "New Zealand", "dial_code": "64", "code": "NZ", "flag": "🇳🇿" }, { "name": "Nicaragua", "dial_code": "505", "code": "NI", "flag": "🇳🇮" }, { "name": "Niger", "dial_code": "227", "code": "NE", "flag": "🇳🇪" }, { "name": "Nigeria", "dial_code": "234", "code": "NG", "flag": "🇳🇬" }, { "name": "Niue", "dial_code": "683", "code": "NU", "flag": "🇳🇺" }, { "name": "Norfolk Island", "dial_code": "672", "code": "NF", "flag": "🇳🇫" }, { "name": "Northern Mariana Islands", "dial_code": "1670", "code": "MP", "flag": "🇲🇵" }, { "name": "Norway", "dial_code": "47", "code": "NO", "flag": "🇳🇴" }, { "name": "Oman", "dial_code": "968", "code": "OM", "flag": "🇴🇲" }, { "name": "Pakistan", "dial_code": "92", "code": "PK", "flag": "🇵🇰" }, { "name": "Palau", "dial_code": "680", "code": "PW", "flag": "🇵🇼" }, { "name": "Palestinian Territory, Occupied", "dial_code": "970", "code": "PS", "flag": "🇵🇸" }, { "name": "Panama", "dial_code": "507", "code": "PA", "flag": "🇵🇦" }, { "name": "Papua New Guinea", "dial_code": "675", "code": "PG", "flag": "🇵🇬" }, { "name": "Paraguay", "dial_code": "595", "code": "PY", "flag": "🇵🇾" }, { "name": "Peru", "dial_code": "51", "code": "PE", "flag": "🇵🇪" }, { "name": "Philippines", "dial_code": "63", "code": "PH", "flag": "🇵🇭" }, { "name": "Pitcairn", "dial_code": "872", "code": "PN", "flag": "🇵🇳" }, { "name": "Poland", "dial_code": "48", "code": "PL", "flag": "🇵🇱" }, { "name": "Portugal", "dial_code": "351", "code": "PT", "flag": "🇵🇹" }, { "name": "Puerto Rico", "dial_code": "1939", "code": "PR", "flag": "🇵🇷" }, { "name": "Qatar", "dial_code": "974", "code": "QA", "flag": "🇶🇦" }, { "name": "Romania", "dial_code": "40", "code": "RO", "flag": "🇷🇴" }, { "name": "Russia", "dial_code": "7", "code": "RU", "flag": "🇷🇺" }, { "name": "Rwanda", "dial_code": "250", "code": "RW", "flag": "🇷🇼" }, { "name": "Réunion", "dial_code": "262", "code": "RE", "flag": "🇷🇪" }, { "name": "Saint Barthélemy", "dial_code": "590", "code": "BL", "flag": "🇧🇱" }, { "name": "Saint Helena, Ascension and Tristan Da Cunha", "dial_code": "290", "code": "SH", "flag": "🇸🇭" }, { "name": "Saint Kitts and Nevis", "dial_code": "1869", "code": "KN", "flag": "🇰🇳" }, { "name": "Saint Lucia", "dial_code": "1758", "code": "LC", "flag": "🇱🇨" }, { "name": "Saint Martin", "dial_code": "590", "code": "MF", "flag": "🇲🇫" }, { "name": "Saint Pierre and Miquelon", "dial_code": "508", "code": "PM", "flag": "🇵🇲" }, { "name": "Saint Vincent and the Grenadines", "dial_code": "1784", "code": "VC", "flag": "🇻🇨" }, { "name": "Samoa", "dial_code": "685", "code": "WS", "flag": "🇼🇸" }, { "name": "San Marino", "dial_code": "378", "code": "SM", "flag": "🇸🇲" }, { "name": "Sao Tome and Principe", "dial_code": "239", "code": "ST", "flag": "🇸🇹" }, { "name": "Saudi Arabia", "dial_code": "966", "code": "SA", "flag": "🇸🇦" }, { "name": "Senegal", "dial_code": "221", "code": "SN", "flag": "🇸🇳" }, { "name": "Serbia", "dial_code": "381", "code": "RS", "flag": "🇷🇸" }, { "name": "Seychelles", "dial_code": "248", "code": "SC", "flag": "🇸🇨" }, { "name": "Sierra Leone", "dial_code": "232", "code": "SL", "flag": "🇸🇱" }, { "name": "Singapore", "dial_code": "65", "code": "SG", "flag": "🇸🇬" }, { "name": "Slovakia", "dial_code": "421", "code": "SK", "flag": "🇸🇰" }, { "name": "Slovenia", "dial_code": "386", "code": "SI", "flag": "🇸🇮" }, { "name": "Solomon Islands", "dial_code": "677", "code": "SB", "flag": "🇸🇧" }, { "name": "Somalia", "dial_code": "252", "code": "SO", "flag": "🇸🇴" }, { "name": "South Africa", "dial_code": "27", "code": "ZA", "flag": "🇿🇦" }, { "name": "South Georgia and the South Sandwich Islands", "dial_code": "500", "code": "GS", "flag": "🇬🇸" }, { "name": "Spain", "dial_code": "34", "code": "ES", "flag": "🇪🇸" }, { "name": "Sri Lanka", "dial_code": "94", "code": "LK", "flag": "🇱🇰" }, { "name": "Sudan", "dial_code": "249", "code": "SD", "flag": "🇸🇩" }, { "name": "Suriname", "dial_code": "597", "code": "SR", "flag": "🇸🇷" }, { "name": "Svalbard and Jan Mayen", "dial_code": "47", "code": "SJ", "flag": "🇸🇯" }, { "name": "Swaziland", "dial_code": "268", "code": "SZ", "flag": "🇸🇿" }, { "name": "Sweden", "dial_code": "46", "code": "SE", "flag": "🇸🇪" }, { "name": "Switzerland", "dial_code": "41", "code": "CH", "flag": "🇨🇭" }, { "name": "Syrian Arab Republic", "dial_code": "963", "code": "SY", "flag": "🇸🇾" }, { "name": "Taiwan, Province of China", "dial_code": "886", "code": "TW", "flag": "🇹🇼" }, { "name": "Tajikistan", "dial_code": "992", "code": "TJ", "flag": "🇹🇯" }, { "name": "Tanzania, United Republic of", "dial_code": "255", "code": "TZ", "flag": "🇹🇿" }, { "name": "Thailand", "dial_code": "66", "code": "TH", "flag": "🇹🇭" }, { "name": "Timor-Leste", "dial_code": "670", "code": "TL", "flag": "🇹🇱" }, { "name": "Togo", "dial_code": "228", "code": "TG", "flag": "🇹🇬" }, { "name": "Tokelau", "dial_code": "690", "code": "TK", "flag": "🇹🇰" }, { "name": "Tonga", "dial_code": "676", "code": "TO", "flag": "🇹🇴" }, { "name": "Trinidad and Tobago", "dial_code": "1868", "code": "TT", "flag": "🇹🇹" }, { "name": "Tunisia", "dial_code": "216", "code": "TN", "flag": "🇹🇳" }, { "name": "Turkey", "dial_code": "90", "code": "TR", "flag": "🇹🇷" }, { "name": "Turkmenistan", "dial_code": "993", "code": "TM", "flag": "🇹🇲" }, { "name": "Turks and Caicos Islands", "dial_code": "1649", "code": "TC", "flag": "🇹🇨" }, { "name": "Tuvalu", "dial_code": "688", "code": "TV", "flag": "🇹🇻" }, { "name": "Uganda", "dial_code": "256", "code": "UG", "flag": "🇺🇬" }, { "name": "Ukraine", "dial_code": "380", "code": "UA", "flag": "🇺🇦" }, { "name": "United Arab Emirates", "dial_code": "971", "code": "AE", "preferred": true, "flag": "🇦🇪" }, { "name": "United Kingdom", "dial_code": "44", "code": "GB", "preferred": true, "flag": "🇬🇧" }, { "name": "United States", "dial_code": "1", "code": "US", "preferred": true, "flag": "🇺🇸" }, { "name": "Uruguay", "dial_code": "598", "code": "UY", "flag": "🇺🇾" }, { "name": "Uzbekistan", "dial_code": "998", "code": "UZ", "flag": "🇺🇿" }, { "name": "Vanuatu", "dial_code": "678", "code": "VU", "flag": "🇻🇺" }, { "name": "Venezuela, Bolivarian Republic of", "dial_code": "58", "code": "VE", "flag": "🇻🇪" }, { "name": "Viet Nam", "dial_code": "84", "code": "VN", "flag": "🇻🇳" }, { "name": "Virgin Islands, British", "dial_code": "1284", "code": "VG", "flag": "🇻🇬" }, { "name": "Virgin Islands, U.S.", "dial_code": "1340", "code": "VI", "flag": "🇻🇮" }, { "name": "Wallis and Futuna", "dial_code": "681", "code": "WF", "flag": "🇼🇫" }, { "name": "Yemen", "dial_code": "967", "code": "YE", "flag": "🇾🇪" }, { "name": "Zambia", "dial_code": "260", "code": "ZM", "flag": "🇿🇲" }, { "name": "Zimbabwe", "dial_code": "263", "code": "ZW", "flag": "🇿🇼" }, { "name": "Åland Islands", "dial_code": "358", "code": "AX", "flag": "🇦🇽" } ];
    let optionValue = '' ;
    for ( let donnee = 0; donnee < jsonData.length; donnee ++ ) {
        // console.log(jsonData[donnee]['flag'])
        if ( jsonData[donnee]['dial_code'] == '225' ) {
            optionValue += `<option value="${jsonData[donnee]['dial_code']}" selected> ${jsonData[donnee]['flag']} ${jsonData[donnee]['code']} </option>`
        } else {
            optionValue += `<option value="${jsonData[donnee]['dial_code']}"> ${jsonData[donnee]['flag']} ${jsonData[donnee]['code']} </option>`
        }
    }

    $("#indicatif").html(optionValue) ;

    // console.table(jsonData);
    console.log($("#indicatif").val())

</script>
