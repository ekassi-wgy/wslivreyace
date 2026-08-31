<?php
$title = "Abidjan.net, Une Histoire d'Avenir - Accueil"
?>
<?php $content = ob_get_clean(); ?>
<?php require('components/header.php'); ?>
<?php require('components/menu.php'); ?>

<style>
    @media (min-width: 992px) {
        .flottement {
            animation-name: flottement;
            animation-duration: 3s;
            animation-iteration-count: infinite;
            animation-timing-function: ease-in-out;
        }

        .anim-shopping-cart {
            position: relative;
            animation: action 2s infinite  alternate;
            -webkit-animation: action 2s infinite  alternate;
        }

        @-webkit-keyframes action {
            0% { transform: translateX(30px); }
            100% { transform: translateX(-10px); }
        }

        @keyframes action {
            0% { transform: translateX(30px); }
            100% { transform: translateX(-10px); }
        }

        @keyframes flottement {
            0% {
                transform: translate(0, 0px);
            }

            50% {
                transform: translate(0, 25px);
            }

            100% {
                transform: translate(0, -0px);
            }
        }
    }

    .displayBookSmallScreen {
        display: none;
    }

    .displayBookLargeScreen {
        display: block;
    }

    @media (max-width: 992px) {
        .displayBookSmallScreen {
            display: block;
        }

        .displayBookLargeScreen {
            display: none;
        }
    }

    .team-member-img {
        border-radius: 20px;
        width: 350px;
        box-shadow: 0px 30px 40px -20px hsl(229, 6%, 66%);
    }

    /*Videos Temoignages */
    .temoin-name {
        color: hsl(234, 12%, 34%);
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

    .testimony-video-wrap {
        position: relative;
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

    /* Box A propos de l'oeuvre */
    .box p {
        color: hsl(229, 6%, 66%);
        text-align: justify;
        text-align-last: center;
    }

    .box {
        border-radius: 5px;
        box-shadow: 0px 30px 40px -20px hsl(229, 6%, 66%);
        padding: 30px;
        margin: 20px;
        background: #ffffff;
    }

    .red {
        border-top: 3px solid #dc3545;
    }

    .box h2 {
        color: hsl(234, 12%, 34%);
        font-weight: 600;
    }

    .box img {
        float: right;
        margin-top: -20px;
        background: #ffffff;
        border-radius: 50%;
        padding: 5px;
    }

    @media (max-width: 450px) {
        .box {
            text-align: justify;
            text-align-last: center;
        }
    }

    @media (max-width: 950px) and (min-width: 450px) {
        .box {
            text-align: justify;
            text-align-last: center;
        }
    }

    .preface {
        font-weight: 600;
        font-size: 15px;
        margin-top: 30px;
        color: #fff;
    }

    .border-bottom {
        border-bottom: 1px solid #ededed !important;
    }
	
	
	
	
	
	.slider {
  max-width: 1000px;
  margin: 0 auto;
}
.slick-slide {
  margin: 0 5px;
}
.slick-list {
  margin: 0px -5px 0px -5px;
}

.slick-prev:before, .slick-next:before {
  font-size: 70px;
  color: #EA8496;
  line-height: inherit;
  font-weight: bold;
}
.slider img {
  height: 324px;
}

/* Slick Slider Styles -- Provided by https://kenwheeler.github.io/slick/ */
/* Slider */
.slick-slider
{
    position: relative;

    display: block;

    -moz-box-sizing: border-box;
         box-sizing: border-box;

    -webkit-user-select: none;
       -moz-user-select: none;
        -ms-user-select: none;
            user-select: none;

    -webkit-touch-callout: none;
    -khtml-user-select: none;
    -ms-touch-action: pan-y;
        touch-action: pan-y;
    -webkit-tap-highlight-color: transparent;
}

.slick-list
{
    position: relative;

    display: block;
    overflow: hidden;

    margin: 0;
    padding: 0;
}
.slick-list:focus
{
    outline: none;
}
.slick-list.dragging
{
    cursor: pointer;
    cursor: hand;
}

.slick-slider .slick-track,
.slick-slider .slick-list
{
    -webkit-transform: translate3d(0, 0, 0);
       -moz-transform: translate3d(0, 0, 0);
        -ms-transform: translate3d(0, 0, 0);
         -o-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
}

.slick-track
{
    position: relative;
    top: 0;
    left: 0;

    display: block;
}
.slick-track:before,
.slick-track:after
{
    display: table;

    content: '';
}
.slick-track:after
{
    clear: both;
}
.slick-loading .slick-track
{
    visibility: hidden;
}

.slick-slide
{
    display: none;
    float: left;

    height: 100%;
    min-height: 1px;
}
[dir='rtl'] .slick-slide
{
    float: right;
}
.slick-slide img
{
    display: block;
}
.slick-slide.slick-loading img
{
    display: none;
}
.slick-slide.dragging img
{
    pointer-events: none;
}
.slick-initialized .slick-slide
{
    display: block;
}
.slick-loading .slick-slide
{
    visibility: hidden;
}
.slick-vertical .slick-slide
{
    display: block;

    height: auto;

    border: 1px solid transparent;
}
.slick-arrow.slick-hidden {
    display: none;
}


/* Arrows */
.slick-prev,
.slick-next
{
    font-size: 0;
    line-height: 0;

    position: absolute;
    top: 50%;

    display: block;

    width: 20px;
    height: 20px;
    margin-top: -10px;
    padding: 0;

    cursor: pointer;

    color: transparent;
    border: none;
    outline: none;
    background: transparent;
}
.slick-prev:hover,
.slick-prev:focus,
.slick-next:hover,
.slick-next:focus
{
    color: transparent;
    outline: none;
    background: transparent;
}
.slick-prev:hover:before,
.slick-prev:focus:before,
.slick-next:hover:before,
.slick-next:focus:before
{
    opacity: 1;
}
.slick-prev.slick-disabled:before,
.slick-next.slick-disabled:before
{
    opacity: .25;
}

.slick-prev:before,
.slick-next:before
{
    font-family: 'slick';
    font-size: 20px;
    line-height: 1;

    opacity: .75;
    color: white;

    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.slick-prev
{
    left: -25px;
}
[dir='rtl'] .slick-prev
{
    right: -25px;
    left: auto;
}
.slick-prev:before
{
    content: '';
}
[dir='rtl'] .slick-prev:before
{
    content: '';
    font-weight: bold;
    font-size: 20px;
}

.slick-next
{
    right: -25px;
}
[dir='rtl'] .slick-next
{
    right: auto;
    left: -25px;
}
.slick-next:before
{
    content: '';
}
[dir='rtl'] .slick-next:before
{
    content: '';
}

/* Dots */
.slick-slider
{
    margin-bottom: 30px;
}

.slick-dots
{
    position: absolute;
    bottom: -45px;

    display: block;

    width: 100%;
    padding: 0;

    list-style: none;

    text-align: center;
}
.slick-dots li
{
    position: relative;

    display: inline-block;

    width: 20px;
    height: 20px;
    margin: 0 5px;
    padding: 0;

    cursor: pointer;
}
.slick-dots li button
{
    font-size: 0;
    line-height: 0;

    display: block;

    width: 20px;
    height: 20px;
    padding: 5px;

    cursor: pointer;

    color: transparent;
    border: 0;
    outline: none;
    background: transparent;
}
.slick-dots li button:hover,
.slick-dots li button:focus
{
    outline: none;
}
.slick-dots li button:hover:before,
.slick-dots li button:focus:before
{
    opacity: 1;
}
.slick-dots li button:before
{
    font-family: 'slick';
    font-size: 6px;
    line-height: 20px;

    position: absolute;
    top: 0;
    left: 0;

    width: 20px;
    height: 20px;

    content: 'ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢';
    text-align: center;

    opacity: .25;
    color: black;

    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
.slick-dots li.slick-active button:before
{
    opacity: .75;
    color: black;
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

/* modal intro css */
	
	.flexTitle{display:flex; justify-content:center; align-items:center; width:100%;}
.flexTitle h1{color:#a31313; margin:0;}
.close {
    float: right;
    font-size: 21px;
    font-weight: bold;
    line-height: 1;
    color: #000000;
    text-shadow: 0 1px 0 #ffffff;
    opacity: 0.7;
    filter: alpha(opacity=70);
}
.close:hover,.close:focus {
    color: #000000;
    text-decoration: none;
    cursor: pointer;
}
button.close {
    padding: 0;
    cursor: pointer;
    background: transparent;
    border: 0;
    -webkit-appearance: none;
}
.modal-open {
    overflow: hidden;
}
.modal {
    display: none;
    overflow: hidden;
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1050;
    -webkit-overflow-scrolling: touch;
    outline: 0;
}
.modal.fade .modal-dialog {
    -webkit-transform: translate(0, -25%);
    -ms-transform: translate(0, -25%);
    -o-transform: translate(0, -25%);
    transform: translate(0, -25%);
    -webkit-transition: -webkit-transform 0.3s ease-out;
    -o-transition: -o-transform 0.3s ease-out;
    transition: transform 0.3s ease-out;
}
.modal.in .modal-dialog {
    -webkit-transform: translate(0, 0);
    -ms-transform: translate(0, 0);
    -o-transform: translate(0, 0);
    transform: translate(0, 0);
}
.modal-open .modal {
    overflow-x: hidden;
    overflow-y: auto;
}
.modal-dialog {
    position: relative;
    width: auto;
    margin: 10px;
}
.modal-content {
    position: relative;
}
.modal-backdrop {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1040;
    background-color: #52637a;
}
.modal-backdrop.fade {
    opacity: 0;
    filter: alpha(opacity=0);
}
.modal-backdrop.in {
    opacity: 0.5;
    filter: alpha(opacity=50);
}
.modal-header {
    padding: 15px;
    min-height: 16.42857143px;
	background: #ffffff;
}
.modal-header .close {
    margin-top: -2px;
}
.modal-title {
    margin: 0;
    line-height: 1.42857143;
}
.modal-body {
    position: relative;
    padding: 15px 15px;
    background-color: #ffffff;
}
.modal-footer {
    padding: 15px;
    text-align: right;
    background-color: #fff;
}
.modal-footer .btn + .btn {
    margin-left: 5px;
    margin-bottom: 0;
}
.modal-footer .btn-group .btn + .btn {
    margin-left: -1px;
}
.modal-footer .btn-block + .btn-block {
    margin-left: 0;
}
.modal-scrollbar-measure {
    position: absolute;
    top: -9999px;
    width: 50px;
    height: 50px;
    overflow: scroll;
}
.clickable {
    cursor: pointer;
}
@media (min-width: 768px) {
    .modal-dialog {
    width: 600px;
    margin: 30px auto;
}
 .modal-sm {
    width: 300px;
}
}
@media (min-width: 992px) {
    .modal-lg {
    width: 900px;
}
}
.clearfix:before,.clearfix:after,.modal-footer:before,.modal-footer:after {
    content: " ";
    display: table;
}
.clearfix:after,.modal-footer:after {
    clear: both;
}
.center-block {
    display: block;
    margin-left: auto;
    margin-right: auto;
}
.pull-right {
    float: right !important;
}
.pull-left {
    float: left !important;
}
.hide {
    display: none !important;
}
.show {
    display: block !important;
}
.invisible {
    visibility: hidden;
}
.text-hide {
    font: 0/0 a;
    color: transparent;
    text-shadow: none;
    background-color: transparent;
    border: 0;
}
.hidden {
    display: none !important;
}
.affix {
    position: fixed;
}
.img-responsive-height {
    display: block;
    width: auto;
    max-height: 100%}
.img-responsive {
    display: block;
    max-width: 100%;
    height: auto;
}
.signCenter {
    text-align: center;
}
.closeSymbol {
    color: #fff;
    font-size: 40px;
    border: 3px solid #fff;
    width: 96px;
    heiht: 96px;
    border-radius: 50%;
    font-weight: 700;
    text-align: center;
    padding: 0 15px;
}
.snapHdr {
    color: #aa1515;
    font-size: 40px;
    margin: 0;
    font-weight: 700;
}
.info {
    margin: 0 0 10px 0;
}
.closeftr {
    background-color: #f6001c;
    color: #fff;
    padding: 5px 30px;
    border-radius: 10px;
    text-decoration: none;
    font-size: 16px;
    font-weight: 600;
}
	
button.close {
    color: #f6021c;
    
    top: 1px;
    font-size: 16px;
}
	

</style>


<!-- DEBUT MODAL INTRO -->
<!--<div id="modal-content" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                
            </div>
            <div class="modal-body">
                <img class="img-modal" src="https://media-files.abidjan.net/livre/img/Intro-LDF-SILA.gif"  data-at2x="https://media-files.abidjan.net/livre/img/Intro-LDF-SILA.gif" alt="team member">
            </div>
          <div class="modal-footer signCenter"> 
                <a href="#" class="btn closeftr" data-dismiss="modal">Fermer</a>
                
            </div>
        </div>
    </div>
</div>-->
<!-- DEBUT MODAL INTRO -->

<!-- Contenu de la page -->
<!--Hero section-->
<section class="hero home-section" id="Home" style="display: flex;">
    <canvas id="draw" style="position: absolute !important; top: -5px !important;"></canvas>
    <div class="container" style="display: flex; align-items: center;">
        <div class="row displayBookLargeScreen" data-0="opacity: 1" data-800="opacity: 0">
            <div class="col-md-7">
                <!--welcome message-->
                <header class="welcome-message">
                    <h1>
                        ABIDJAN.NET
                        <span class="history">
                            Une histoire d'avenir
                        </span>
                    </h1>
                    <h2>
                       […] Ce livre foisonne d’anecdotes et de moments contés avec verve, par nos deux entrepreneurs. Je crois que les conseils qu’ils prodiguent sont pertinents, pour toutes celles et tous ceux qui souhaitent devenir des entrepreneurs en Côte d’Ivoire ou ailleurs, quel que soit le secteur d’activité qu’ils choisissent. […] Ce que Jil-Alexandre et Daniel ont accompli est un message d'espoir pour notre continent.
                    </h2>
                    <p class="preface">* Préface de Tidjane THIAM.</p>
                </header>
                <!--welcome message end-->

                <!--action button-->
                <div class="action-button">
                    <a href="/livre/order" class="button learn-more text-center">
                        Commander <i class="fa fa-shopping-cart anim-shopping-cart"></i>
                    </a>
                </div>
                <!--action button end-->

            </div>
            <div class="col-md-5 text-center flottement">
                <a href="/livre/order">
                    <img src="https://media-files.abidjan.net/livre/img/Book--mockup.png" width="auto" height="auto" alt="Abidjan.net book cover" class="book-cover" data-no-retina>
                </a>
            </div>
        </div>
        <div class="row displayBookSmallScreen" data-0="opacity: 1" data-800="opacity: 0">
            <div class="col-md-5 text-center flottement">
                <h1>
                    ABIDJAN.NET
                    <span class="history">
                        Une histoire d'avenir
                    </span>
                </h1>
                <img src="https://media-files.abidjan.net/livre/img/Book--mockup.png" alt="Abidjan.net book" width="auto" height="auto" class="book-cover" alt="Abidjan.net book" data-no-retina>
            </div>
            <div class="col-md-7">
                <!--welcome message-->
                <header class="welcome-message">
                    <h2>
                       […] Ce livre foisonne d’anecdotes et de moments contés avec verve, par nos deux entrepreneurs. Je crois que les conseils qu’ils prodiguent sont pertinents, pour toutes celles et tous ceux qui souhaitent devenir des entrepreneurs en Côte d’Ivoire ou ailleurs, quel que soit le secteur d’activité qu’ils choisissent. […] Ce que Jil-Alexandre et Daniel ont accompli est un message d'espoir pour notre continent.
                    </h2>
                    <p class="preface">* Préface de Tidjane THIAM.</p>
                </header>
                <!--welcome message end-->

                <!--action button-->
                <div class="action-button">
                    <a href="/livre/order" class="button learn-more text-center">
                        Commander <i class="fa fa-shopping-cart"></i>
                    </a>
                </div>
                <!--action button end-->

            </div>
        </div>
    </div>
</section>
<!--Hero section end-->

<!--About-->
<section class="reviews features section-spacing text-center" id="Features">
    <div class="container">
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="slide-left col-sm-8">

                <div class="box red">
                    <h2>A PROPOS DE L'ŒUVRE</h2>
                    <br>
                    <p>
                       " Comme tous les grands entrepreneurs, de Henry Ford à Mike Bloomberg ou plus près de nous Bill Gates, Jil-Alexandre et Daniel avaient une vision, celle d’utiliser la technologie – Internet, en l’occurrence – pour créer un produit qui deviendrait rapidement indispensable aux Ivoiriens et à tous ceux qui s’intéressent à la Côte d’Ivoire. Et comme toutes les grandes innovations, leur vision s’est heurtée au départ à beaucoup de scepticisme. Comment deux jeunes Africains pouvaient-ils réussir en Afrique, dans un domaine où tant de start-up ont échoué dans des économies beaucoup plus avancées ? "

                        <br><br>

                        Extrait de la préface de Tidjane Thiam
                    </p>
                    <img src="https://media-files.abidjan.net/livre/img/book-2.png" width="auto" height="auto" data-at2x="https://media-files.abidjan.net/livre/img/book-2.png" alt="icon-book">
                </div>

            </div>
            <div class="col-sm-2"></div>

        </div>

        <hr>
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="slide-left col-sm-5">
                <a href="/livre/team#book">
                    <img src="https://media-files.abidjan.net/livre/img/golden-book-2.png" width="auto" height="auto" data-at2x="https://media-files.abidjan.net/livre/img/golden-book-2.png" alt="le livre">
                    <h4>Le livre</h4>
                </a>
            </div>
            <div class="slide-top col-sm-5">
                <a href="/livre/team#authors">
                    <img src="https://media-files.abidjan.net/livre/img/author-2.png" width="auto" height="auto" data-at2x="https://media-files.abidjan.net/livre/img/author-2.png" alt="les auteurs">
                    <h4>Les auteurs</h4>
                </a>
            </div>
            <div class="col-sm-1"></div>
        </div>
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="slide-right col-sm-5">
                <a href="/livre/team#prefacier">
                    <img src="https://media-files.abidjan.net/livre/img/author-2.png" width="auto" height="auto" data-at2x="https://media-files.abidjan.net/livre/img/author-2.png" alt="le prefacier">
                    <h4>Le préfacier</h4>
                </a>
            </div>
            <div class="slide-bottom col-sm-5">
                <a href="/livre/team#entrepreneurs">
                    <img src="https://media-files.abidjan.net/livre/img/entrepreneurs-2.png" width="auto" height="auto" data-at2x="https://media-files.abidjan.net/livre/img/entrepreneurs-2.png" alt="les entrepreneurs">
                    <h4>Les entrepreneurs</h4>
                </a>
            </div>
            <div class="col-sm-1"></div>
        </div>

    </div>
</section>
<!--About end-->

<!--Team-->
<section class="section-spacing text-center features" id="Team">
    <div class="container">
        <header>
            <h2>LA TEAM DU LIVRE</h2>
            <hr>
        </header>
        <br>
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="slide-left col-sm-4">
                <a href="/livre/team#entrepreneurs">
                    <img class="team-member-img" src="https://media-files.abidjan.net/livre/img/team/ahouassa.jpeg" width="auto" height="auto" data-at2x="https://media-files.abidjan.net/livre/img/team/ahouassa.jpeg" alt="team member">
                </a>
                <h4>Daniel <span class="name">AHOUASSA</span></h4>
                <p>
                    <i class="fa fa-tag"></i> Entrepreneur
                </p>
                <hr>
                <!--social-->
                <div class="social" style="margin-bottom: 0.75em;">
                    <ul>
                        <li class="slide-right"><a href="https://twitter.com/ahouassa?lang=fr" target="_blank"><i class="fa fa-twitter"></i></a></li>
                        <li class="slide-right"><a href="https://www.instagram.com/dahouassa/?hl=fr" target="_blank"><i class="fa fa-instagram"></i></a></li>
                        <li class="slide-right"><a href="https://web.facebook.com/ahouassa" target="_blank"><i class="fa fa-facebook"></i></a></li>
                        <li class="slide-right"><a href="https://www.linkedin.com/in/ahouassa/" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                    </ul>
                </div>
                <!--social end-->
            </div>
            <div class="slide-right col-sm-4">
                <a href="/livre/team#entrepreneurs">
                    <img class="team-member-img" src="https://media-files.abidjan.net/livre/img/team/ndia.jpeg" width="auto" height="auto" data-at2x="https://media-files.abidjan.net/livre/img/team/ndia.jpeg" alt="team member">
                </a>
                <h4>Jil-Alexandre <span class="name">N'DIA</span></h4>
                <p>
                    <i class="fa fa-tag"></i> Entrepreneur
                </p>
                <hr>
                <!--social-->
                <div class="social" style="margin-bottom: 0.75em;">
                    <ul>
                        <li class="slide-right"><a href="twitter : https://twitter.com/jndia" target="_blank"><i class="fa fa-twitter"></i></a></li>
                        <li class="slide-right"><a href="https://www.instagram.com/jil.ndia/?hl=fr" target="_blank"><i class="fa fa-instagram"></i></a></li>
                        <li class="slide-right"><a href="https://web.facebook.com/jil.ndia" target="_blank"><i class="fa fa-facebook"></i></a></li>
                        <li class="slide-right"><a href="https://www.linkedin.com/in/jil-alexandre-n-dia-3b00555/" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                    </ul>
                </div>
                <!--social end-->
            </div>
            <div class="col-sm-2"></div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="slide-right col-sm-4">
                <a href="/livre/team#prefacier">
                    <img class="team-member-img" src="https://media-files.abidjan.net/livre/img/team/thiam.jpeg" width="auto" height="auto" data-at2x="https://media-files.abidjan.net/livre/img/team/thiam.jpeg" alt="team member">
                </a>
                <h4>Tidjane <span class="name">THIAM</span></h4>
                <p>
                    <i class="fa fa-tag"></i> Préfacier
                </p>
                <hr>
                <!--social-->
                <div class="social" style="margin-bottom: 0.75em;">
                    <ul>
                        <li class="slide-right"><a href="https://twitter.com/tidjanethiam10?lang=fr" target="_blank"><i class="fa fa-twitter"></i></a></li>
                        <li class="slide-right"><a href="https://www.instagram.com/tidjane.thiam/?hl=fr" target="_blank"><i class="fa fa-instagram"></i></a></li>
                        <li class="slide-right"><a href="https://www.linkedin.com/in/tidjane-thiam-652284140/" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                    </ul>
                </div>
                <!--social end-->
            </div>
            <div class="col-sm-4"></div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="slide-left col-sm-4">
                <a href="/livre/team#authors">
                    <img class="team-member-img" src="https://media-files.abidjan.net/livre/img/team/konan.jpeg" width="auto" height="auto" data-at2x="https://media-files.abidjan.net/livre/img/team/konan.jpeg" alt="team member">
                </a>
                <h4>Venance <span class="name">KONAN</span></h4>
                <p style="margin-bottom: 0.75em;">
                    <i class="fa fa-tag"></i> Auteur
                </p>
                <hr>
                <!--social-->
                <div class="social" style="margin-bottom: 0.75em;">
                    <ul>
                        <li class="slide-right"><a href="https://www.instagram.com/venance.konan.58/?hl=fr" target="_blank"><i class="fa fa-instagram"></i></a></li>
                    </ul>
                </div>
                <!--social end-->
            </div>
            <div class="slide-right col-sm-4">
                <a href="/livre/team#authors">
                    <img class="team-member-img" src="https://media-files.abidjan.net/livre/img/team/ehouman.jpeg" width="auto" height="auto" data-at2x="https://media-files.abidjan.net/livre/img/team/ehouman.jpeg" alt="team member">
                </a>
                <h4>Faustin <span class="name">EHOUMAN</span></h4>
                <p>
                    <i class="fa fa-tag"></i> Auteur
                </p>
                <hr>
                <!--social-->
                <div class="social" style="margin-bottom: 0.75em;">
                    <ul>
                        <li class="slide-right"><a href="https://web.facebook.com/Maxime.Denopis" target="_blank"><i class="fa fa-facebook"></i></a></li>
                        <li class="slide-right"><a href="https://www.linkedin.com/in/faustin-ehouman-n-douba-285018a5/" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                    </ul>
                </div>
                <!--social end-->
            </div>
            <div class="col-sm-2"></div>
        </div>
    </div>
</section>
<!--Team end-->

<!--sub form-->
<section class="reviews sub-form section-spacing text-center border-bottom features" id="contact">
    <div class="container">
        <header>
            <h2>LAISSEZ-NOUS UN MESSAGE</h2>
            <hr>
            <h3>
                
             Nous serons ravis de répondre à  vos messages 😊 !
            
            </h3>
        </header>
        <div class="row">
            <div class="slide-bottom col-md-7"> <img src="https://media-files.abidjan.net/livre/img/book--.png" width="auto" height="auto" alt="Abidjant.net book preface" data-no-retina>
            </div>
            <div class="col-md-5">
                <form role="form" id="mc-form">
                    <div>
                        <input type="text" class="slide-bottom form-control" placeholder="Votre nom" name="fullname" id="fullname">
                        <i class="fa fa-user first-name-i"></i>
                    </div>
                    <div>
                        <input type="email" class="slide-bottom form-control" placeholder="Votre e-mail" name="email" id="email">
                        <i class="fa fa-envelope"></i>
                    </div>
                    <div>
                        <textarea type="text" class="slide-bottom form-control" placeholder="Votre message" id="message"></textarea>
                    </div>
                    <br>
                    <button type="button" class="slide-bottom btn btn-default mt-4" onclick="sendMessage()">Envoyer</button>
                </form>
            </div>
        </div>

        <br>

        <!--address-->
        <div class="container">
            <ul class="address row text-center">
                <li class="col-sm-4"><i class="slide-bottom fa fa-map-marker"></i>
                    <h4 class="slide-bottom">Adresse</h4>
                    <p class="slide-bottom">10 Rue Washington Booker Cocody-Ambassades - 01 BP 12324 01 Abidjan</p>
                </li>
                <li class="col-sm-4"><i class="slide-bottom fa fa-phone"></i>
                    <h4 class="slide-bottom">Téléphone</h4>
                    <p class="slide-bottom">+225 07 47 13 80 47</p>
                </li>
                <li class="col-sm-4"><i class="slide-bottom fa fa-envelope"></i>
                    <h4 class="slide-bottom">E-mail</h4>
                    <p class="slide-bottom">livre@weblogy.com</p>
                </li>
            </ul>
        </div>
        <!--address end-->
    </div>
</section>
<!--sub form-->


<!--Team-->
<section class="section-spacing text-center features" id="Team">
    <div class="container">
        <header>
            <h2>DEVENEZ PARTENAIRE ET ASSOCIEZ VOTRE IMAGE AU LIVRE</h2>
            <hr>
        </header>
        <br>
        <div class="row">
			
		<div class="slider">
<a href="https://media-files.abidjan.net/livre/img/phototeque/branding/b-1.jpg" data-fancybox="gallery" data-caption="VISITE A BLAISE PASCAL"><img src="https://media-files.abidjan.net/livre/img/phototeque/branding/br-1.jpg" /></a>

<a href="https://media-files.abidjan.net/livre/img/phototeque/branding/b-2.jpg" data-fancybox="gallery" data-caption="VISITE A BLAISE PASCAL"><img src="https://media-files.abidjan.net/livre/img/phototeque/branding/br-2.jpg" /></a>
			
<a href="https://media-files.abidjan.net/livre/img/phototeque/branding/b-3.jpg" data-fancybox="gallery" data-caption="VISITE A BLAISE PASCAL"><img src="https://media-files.abidjan.net/livre/img/phototeque/branding/br-3.jpg" /></a>
			
<a href="https://media-files.abidjan.net/livre/img/phototeque/branding/b-4.jpg" data-fancybox="gallery" data-caption="VISITE A BLAISE PASCAL"><img src="https://media-files.abidjan.net/livre/img/phototeque/branding/br-4.jpg" /></a>
			
<a href="https://media-files.abidjan.net/livre/img/phototeque/branding/b-1.jpg" data-fancybox="gallery" data-caption="VISITE A BLAISE PASCAL"><img src="https://media-files.abidjan.net/livre/img/phototeque/branding/br-1.jpg" /></a>

<a href="https://media-files.abidjan.net/livre/img/phototeque/branding/b-2.jpg" data-fancybox="gallery" data-caption="VISITE A BLAISE PASCAL"><img src="https://media-files.abidjan.net/livre/img/phototeque/branding/br-2.jpg" /></a>			

	</div>	
            
        </div>
        <br>
       
       
    </div>
</section>
<!--Team end-->

<!-- Contenu de la page -->

<?php require('components/footer.php'); ?>
<?php require('components/footer-scripts.php'); ?>
<script src="https://media-files.abidjan.net/livre/js/slick.js"></script>
<script src="https://media-files.abidjan.net/livre/js/jquery-ui.min.js"></script>
<script src="/assets/js/loadingoverlay.min.js"></script>
<script src="https://media-files.abidjan.net/livre/js/sweetalert2@11.js"></script>

<script>
    /* ==========================================================================
   sticky nav
   ========================================================================== */
    var menu = $('.navbar');
    var stickyNav = menu.offset().top;
    $(window).scroll(function() {
        if ($(window).scrollTop() > 100) {
            menu.addClass('stick');
        } else {
            menu.removeClass('stick');
        }
    });


    var c = document.getElementById("draw");
    var ctx = c.getContext("2d");

    //Mettre le Canvas matrixel en full width
    let section = document.querySelector('.home-section');
    let width = section.offsetWidth;
    let height = section.offsetHeight;
    c.height = height - 1;
    c.width = width;

    var matrix = "01";
    //convertion des string en tableau de caracteres unique
    matrix = matrix.split("");

    var font_size = 10;
    var columns = c.width / font_size; //nombre de collone de la matrix

    //Tableau de Drop - 1 par colonne
    var drops = [];

    //x below is the x coordinate
    //1 = y co-ordinate of the drop(same for every drop initially)
    for (var x = 0; x < columns; x++)
        drops[x] = 1;

    //drawing the characters
    function draw() {
        //Black BG for the canvas
        //translucent BG to show trail
        ctx.fillStyle = "rgba(0, 0, 0, 0.04)";
        ctx.fillRect(0, 0, c.width, c.height);

        ctx.fillStyle = "#d21d3a"; //Couleur matrix
        ctx.font = font_size + "px arial";
        //looping over drops
        for (var i = 0; i < drops.length; i++) {
            //a random chinese character to print
            var text = matrix[Math.floor(Math.random() * matrix.length)];
            //x = i*font_size, y = value of drops[i]*font_size
            ctx.fillText(text, i * font_size, drops[i] * font_size);

            //sending the drop back to the top randomly after it has crossed the screen
            //adding a randomness to the reset to make the drops scattered on the Y axis
            if (drops[i] * font_size > c.height && Math.random() > 0.975)
                drops[i] = 0;

            //incrementing Y coordinate
            drops[i]++;
        }
    }

    setInterval(draw, 35);

    window.addEventListener('resize', function() {

        var c = document.getElementById("c");
        var ctx = c.getContext("2d");

        //Mettre le Canvas matrixel en full width
        let section = document.querySelector('.home-section');
        let width = section.offsetWidth;
        let height = section.offsetHeight;
        c.height = height - 60;
        c.width = width;

        var matrix = "01";
        //convertion des string en tableau de caracteres unique
        matrix = matrix.split("");

        var font_size = 10;
        var columns = c.width / font_size; //nombre de collone de la matrix

        //Tableau de Drop - 1 par colonne
        var drops = [];

        //x below is the x coordinate
        //1 = y co-ordinate of the drop(same for every drop initially)
        for (var x = 0; x < columns; x++)
            drops[x] = 1;

        //drawing the characters
        function draw() {
            //Black BG for the canvas
            //translucent BG to show trail
            ctx.fillStyle = "rgba(0, 0, 0, 0.04)";
            ctx.fillRect(0, 0, c.width, c.height);

            ctx.fillStyle = "#d21d3a"; //Couleur matrix
            ctx.font = font_size + "px arial";
            //looping over drops
            for (var i = 0; i < drops.length; i++) {
                //a random chinese character to print
                var text = matrix[Math.floor(Math.random() * matrix.length)];
                //x = i*font_size, y = value of drops[i]*font_size
                ctx.fillText(text, i * font_size, drops[i] * font_size);

                //sending the drop back to the top randomly after it has crossed the screen
                //adding a randomness to the reset to make the drops scattered on the Y axis
                if (drops[i] * font_size > c.height && Math.random() > 0.975)
                    drops[i] = 0;

                //incrementing Y coordinate
                drops[i]++;
            }
        }

        setInterval(draw, 35);

    })

    function sendMessage(){
        let
            nom = $("#fullname").val(),
            email = $("#email").val(),
            message = $("#message").val()
        ;

        if (
            nom.replace(/\s/g, "") == "" ||
            email.replace(/\s/g, "") == "" ||
            message.replace(/\s/g, "") == ""
        ) {
            Swal.fire(
                'Attention !',
                'Veuillez renseigner tous les champs !',
                'info'
            )

            return false;
        }

        // Set datas
        var donnees = new FormData();
        donnees.append("fullname", nom);
        donnees.append("email", email);
        donnees.append("message", message);

        // Send datas
        var settings = {
            "async": true,
            "crossDomain": true,
            "url": "https://carte.abidjan.net/livre/message/message.php",
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

            if ( response.ResponseCode == "true" ) {

                Swal.fire(
                    'SuccÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¨s !',
                    response.message,
                    'success'
                )

                $("#fullname").val("") ;
                $("#email").val("") ;
                $("#message").val("") ;

            } else {

                Swal.fire(
                    'Erreur !',
                    'Une erreur est survenue !',
                    'error'
                )
            }
        });

    }

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

<script>

// set focus when modal is opened
$('#modal-content').on('shown.bs.modal', function () {
    $("#txtname").focus();
});

// show the modal onload
$('#modal-content').modal({
    show: true
});

// everytime the button is pushed, open the modal, and trigger the shown.bs.modal event
$('#openBtn').click(function () {
    $('#modal-content').modal({
        show: true
    });
});
</script>


