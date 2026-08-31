<?php
    $title = "Abidjan.net, Une Histoire d'Avenir - La team du livre"
?>
<?php $content = ob_get_clean(); ?>
<?php require('components/header.php'); ?>
<?php require('components/menu.php'); ?>

<style>
    .bio-team{
        text-align: justify !important;
        text-align-last: center !important;
        font-size: 15px;
        padding-top: 8px;
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
        margin-top: -30px;
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
                        <h3 class="breadcrumb-title">Le livre</h3>
                    </div>
                    <div class="col-lg-6  col-md-6 col-sm-6">
                        <!-- breadcrumb-list start -->
                        <ul class="breadcrumb-list text-center text-sm-end">
                            <li class="breadcrumb-item"><a href="/livre"> <i class="fa fa-home"></i> Accueil</a></li>
                            <li class="breadcrumb-item active">Le livre</li>
                        </ul>
                        <!-- breadcrumb-list end -->
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<!--Header section end-->

<!--Book-->
<section class="features section-spacing text-center" id="book">
    <div class="container">
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <div class="box red">
                    <h2>PRÉSENTATION DU LIVRE</h2>
                    <br>
                    <p>
                        Le livre raconte l'histoire de deux jeunes Africains qui ont réussi à s'imposer dans leur domaine.
                        Ce livre foisonne d'anecdotes, de conseils pertinents, de moments pour toutes celles ou tous ceux qui souhaitent devenir des entrepreneurs en
                        Côte d'Ivoire ou ailleurs, quel que soit le secteur d'activité qu'ils choisissent.
                        <br><br><br>
                    </p>
                    <img src="https://media-files.abidjan.net/livre/img/pc.jpg" data-at2x="https://media-files.abidjan.net/livre/img/pc.jpg" width="120px" alt="abjnet-book-img">
                </div>

            </div>
            <div class="col-sm-2"></div>
        </div>
    </div>
</section>
<!--Book end-->

<!--Les auteurs-->
<section class="reviews features section-spacing text-center" id="authors">
    <div class="container">
        <h2>LES AUTEURS</h2>
        <hr>
        <h3></h3>
        <br>
        <div class="row">

            <div class="slide-left col-sm-6">
                <img src="https://media-files.abidjan.net/livre/img/team/konan.jpeg" data-at2x="https://media-files.abidjan.net/livre/img/team/konan.jpeg" style="border-radius: 23px; width: 380px" alt="auteur">
                <h4>Venance <span class="name">KONAN</span></h4>
                <hr>
                <p class="bio-team">
                    Titulaire d'un doctorat en droit ,  Venance Konan né en 1958 est un journaliste écrivain ivoirien de renom , il est l'auteur d'une dizaine d'ouvrages dont certains lui
                    ont valu des prix internationaux dont le grand prix littéraire d’Afrique NOIRE EN 2012. L'hebdomadaire Jeune Afrique l'a même classé : "parmi les plus doués de sa génération".
                    C'est avec son best-seller les prisonniers de la haine , paru en 2003
                </p>
            </div>
            <div class="slide-top col-sm-6">
                <img src="https://media-files.abidjan.net/livre/img/team/ehouman.jpeg" data-at2x="https://media-files.abidjan.net/livre/img/team/ehouman.jpeg" style="border-radius: 23px; width: 380px" alt="auteur">
                <h4>Faustin <span class="name">EHOUMAN</span></h4>
                <hr>
                <p class="bio-team">
                    Après des études en géographie urbaine à l’université Félix  Houphouët-Boigny d’Abidjan et des diplômes en musique et communication obtenus à l’Insaac, Faustin Ehouman,
                    ivoirien né en 1989, décide finalement d’embrasser le métier de journaliste, qui l’a toujours passionné. Il est recruté à Fraternité Matin, le plus grand quotidien ivoirien
                    dirigé par son mentor Venance Konan, où il co-anime dans un premier temps la page culture avant de rejoindre plus tard le service économie. Esprit libre et critique, sa curiosité
                    toujours en éveil, il écrit régulièrement sur plusieurs sujets dont la transformation numérique qui occupe une place de plus en plus importante dans l’économie ivoirienne.
                </p>

            </div>
        </div>
    </div>
</section>
<!--Les auteurs end-->

<!--Le préfacier-->
<section class="features section-spacing text-center" id="prefacier">
    <div class="container">
        <h2>LE PRÉFACIER</h2>
        <hr>
        <br>
        <div class="row">
            <div class="slide-left col-sm-4">
                <img src="https://media-files.abidjan.net/livre/img/team/thiam.jpeg" data-at2x="https://media-files.abidjan.net/livre/img/team/thiam.jpeg" style="border-radius: 23px;" alt="auteur">
                <h4>Tidjane <span class="name">THIAM</span></h4>
            </div>
            <div class="slide-top col-sm-8 text-left">
                <p class="bio-team" style="text-align-last: left !important;">
                    Tidjane Thiam a occupé le poste de Directeur général de Crédit Suisse entre juillet 2015 et février 2020.
                    Tout au long de sa carrière, en dirigeant des entreprises du secteur privé et du secteur public, Tidjane Thiam a développé de grands projets qui ont contribué de manière positive à l’économie et à la société.
                    Au sein de Crédit Suisse, il a mis en place un programme de restructuration sur trois ans, reconnu par Euromoney qui a nommé Tidjane Thiam Banker of the Year en 2018 et en 2019, il a permis à Crédit Suisse de réaliser ses profits annuels les plus élevés depuis 2010.
                    Lorsqu’il était à la tête de Prudential de 2009 à 2015, la capitalisation boursière du groupe d’assurance a triplé et a dépassé les 60 milliards de dollars américains. De 2012 à 2014, il a été Président du Conseil d’administration de l’Association des assureurs britanniques.
                    Auparavant, il a occupé diverses positions managériales chez Aviva de 2002 à 2007, dont le poste de Directeur général Europe.
                    Entre 1994 et 1999, ... <a href="" data-toggle="modal" data-target="#bio-thiam">(Lire la suite)</a>
                </p>
            </div>
        </div>
    </div>
</section>
<!--Le préfacier end-->

<!--Les entrepreneurs-->
<section class="reviews features section-spacing text-center border-bottom" id="entrepreneurs">
    <div class="container">
        <h2>LES ENTREPRENEURS</h2>
        <hr>
        <br>
        <div class="row">

            <div class="col-sm-6">
                <img src="https://media-files.abidjan.net/livre/img/team/ahouassa.jpeg" data-at2x="https://media-files.abidjan.net/livre/img/team/ahouassa.jpeg" style="border-radius: 23px; width: 380px" alt="auteur">
                <h4>Daniel <span class="name">AHOUASSA</span></h4>
                <span>
                    Co-fondateur de Weblogy
                </span>
                <hr>
                <p class="bio-team">
                    Daniel AHOUASSA est ivoirien diplômé d’un Bachelor en International Management de Moravian College & Lehigh University Bethlemen, Pennsylvanie and Master en Science du Commerce Électronique de National University à San Diego, Californie aux États-Unis Amérique. Il est co-fondateur d’Abidjan.net, le site web le plus populaire d’Afrique francophone.
                    Au cours de ses années-collèges, il effectue de nombreux stages dans différentes sociétés de renom notamment Merrill Lynch, Northwestern Mutual et starts-up aux États-Unis.
                    Il va co-fonder en 1999, en pleine année universitaire pendant son Master à San Diego, CA aux Etats-Unis, avec Jil-Alexandre N’DIA, son collègue et ami, Weblogy la société holding propriétaire, d’Abidjan.net, de aBamako.com, aOuaga.com, aCotonou.com, aLome.com, aLibreville.com, aBangui.com et aNiamey.com. En plus des sites d’informations Weblogy propose... <a href="" data-toggle="modal" data-target="#bio-ahouassa">(Lire la suite)</a>
                </p>
            </div>
            <div class="col-sm-6">
                <img src="https://media-files.abidjan.net/livre/img/team/ndia.jpeg" data-at2x="https://media-files.abidjan.net/livre/img/team/ndia.jpeg" style="border-radius: 23px; width: 380px" alt="auteur">
                <h4>Jil-Alexandre <span class="name">N'DIA</span></h4>
                <span>
                    Co-fondateur de Weblogy
                </span>
                <hr>
                <p class="bio-team">
                    Jil-Alexandre N'DIA est le directeur général et co-fondateur de Weblogy,  pionnier et leader du marketing digital et des systèmes d'information communautaires en Afrique francophone.
                    En 23 ans d’existence, Jil-Alexandre N'DIA a piloté la croissance stratégique de la startup ABIDJAN.NET en synergie avec Daniel AHOUASSA co-fondateur de l'entreprise. Présente sur 4
                    continents, avec Abidjan.net et son réseau de sites pays UEMOA, AfriqueFemme.com et JobAfrique.com. Ils attirent plus de six millions de visiteurs quotidiens uniques  depuis 2012.
                    En 2016, Weblogy innove avec les systèmes de paiement électronique en Afrique Sub-Saharienne en lançant sa première Carte Visa Prépayée Abidjan.net. Ce nouveau produit, permet à l’entreprise
                    de se positionner comme première Fintech gestionnaire de programme de cartes prépayées en Afrique de l'Ouest Francophone en Septembre 2020... <a href="" data-toggle="modal" data-target="#bio-ndia">(Lire la suite)</a>
                </p>

            </div>
        </div>
    </div>
</section>
<!--Les entrepreneurs end-->


<!-- Bio-Ahouassa Modal -->
<div class="modal fade" id="bio-ahouassa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-ahouassa" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
                <h4 class="modal-title" id="myModalLabel-privacy">Biographie de Daniel AHOUASSA</h4>
            </div>
            <div class="modal-body text-left">
                <p class="bio-team" style="line-height: 1.8">
                    <strong>Daniel AHOUASSA</strong> est ivoirien diplômé d’un Bachelor en International Management de Moravian College & Lehigh University Bethlemen, Pennsylvanie and Master en Science du Commerce Électronique de National University à San Diego, Californie aux États-Unis Amérique. Il est co-fondateur d’Abidjan.net, le site web le plus populaire d’Afrique francophone.
                    Au cours de ses années-collèges, il effectue de nombreux stages dans différentes sociétés de renom notamment Merrill Lynch, Northwestern Mutual et starts-up aux États-Unis.
                    Il va co-fonder en 1999, en pleine année universitaire pendant son Master à San Diego, CA aux Etats-Unis, avec Jil-Alexandre N’DIA, son collègue et ami, Weblogy la société holding propriétaire, d’Abidjan.net, de aBamako.com, aOuaga.com, aCotonou.com, aLome.com, aLibreville.com, aBangui.com et aNiamey.com. En plus des sites d’informations Weblogy propose des sites specialises tels que JobAfrique.com, plateforme de recherche d’emplois en ligne couvrant l’ Afrique de l’Ouest, Afriquefemme.com portail pour valorisation de la femme africaine ou encore Monkiosk.com plateforme e-commerce de vente par téléchargement des journaux et magazines africains en format numérique.
                    Weblogy œuvre, aussi, à la promotion de la technologie de l’information et de la communication en Afrique à travers le conseil en marketing et communication numérique pour le compte des multinationales et Institutions Internationale en Afrique (Western Union, Air France, Orange, ou encore le FMI et la BCEAO…) le e-commerce, l’édition d’applications mobiles et le service d’hébergement. Weblogy est aussi un partenaire stratégique de Google en Afrique depuis 2012 et un partenaire stratégique pour Visa depuis
                    2017. Weblogy est gestionnaire de programme de carte bancaire Visa en Afrique francophone et développe en Côte d’Ivoire la carte prépayée Abidjan.net Visa avec NSIA banque Côte d’Ivoire.
                    Installé à Casablanca pendant 12 années, Daniel AHOUASSA a assuré le développement et de la stratégie du groupe en Afrique. Il est marié et père de 3 enfants.
                    <br><br>
                    Distinctions
                    <br><br>
                    <span style="text-align-last: left !important;">
                        <strong>Top 50 Managers Africain de l’année 2002 du Magazine Francophone Africain Economia</strong> , <strong>International Visitor Leadership Program Alumni en 2005</strong>,
                        <strong>Officier dans l’Ordre du mérite des Postes et Télécommunications de Côte d’Ivoire en 2009</strong>, <strong>Officier dans l’Ordre du mérite Ivoirien de Côte d’Ivoire en 2019</strong> <br>
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>
<!-- Bio-Ahouassa Modal end -->

<!-- Bio-Ndia Modal -->
<div class="modal fade" id="bio-ndia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-ndia" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
                <h4 class="modal-title" id="myModalLabel-privacy">Biographie de Jil-Alexandre N'DIA</h4>
            </div>
            <div class="modal-body text-left">
                <p class="bio-team" style="line-height: 1.8">
                    <strong>Jil-Alexandre N'DIA</strong> est le directeur général et co-fondateur de Weblogy,  pionnier et leader du marketing digital et des systèmes d'information communautaires en Afrique francophone.
                    En 23 ans d’existence, Jil-Alexandre N'DIA a piloté la croissance stratégique de la startup ABIDJAN.NET en synergie avec Daniel AHOUASSA co-fondateur de l'entreprise. Présente sur 4
                    continents, avec Abidjan.net et son réseau de sites pays UEMOA, AfriqueFemme.com et JobAfrique.com. Ils attirent plus de six millions de visiteurs quotidiens uniques  depuis 2012.
                    En 2016, Weblogy innove avec les systèmes de paiement électronique en Afrique Sub-Saharienne en lançant sa première Carte Visa Prépayée Abidjan.net. Ce nouveau produit, permet à l’entreprise
                    de se positionner comme première Fintech gestionnaire de programme de cartes prépayées en Afrique de l'Ouest Francophone en Septembre 2020.
                    Weblogy signe un partenariat stratégique avec la société Visa, leader du paiement dans le monde, et propose ainsi une super application mobile dernière génération dénommé APAYM. Celle-ci permet
                    la dématérialisation de toutes les cartes bancaires et ainsi facilite le paiement sans contact. Cette application, la 1ere dans l’UEMOA, est auréolée de la certification PCI-DSS Level 1 depuis le
                    10 septembre 2020 qui est le standard de sécurité des données qui s'applique aux différents acteurs de la chaîne monétique. Le développement de cette application, ses services à valeurs ajoutées ainsi
                    que le volume de rechargement et de transactions de ses cartes Visa Prépayées lui ont permis d’obtenir le statut de meilleur Finetech de l’année 2021 en Afrique Francophone par le plus gros processeur
                    de la région GTP et Visa.
                    Jil-Alexandre N'DIA est lauréat du Prix d’Excellence de l’Entreprenariat Jeune du Président de la République en 2013 et 2ème du Prix d’Excellence pour le Développement de la Communication en 2019.
                    Il est Officier de l’Ordre du Mérite des Postes et Télécommunications, Officier dans l’ordre du Mérite Ivoirien et Officier de l'Ordre National de Côte d'Ivoire.
                    Jil-Alexandre N'DIA est titulaire d’un diplôme en Gestion des Systèmes d'Information de l'Université d’Indiana, Kelly School of Business aux États-Unis et a été admis en 2020 au programme de
                    transformation Stanford Seed de la Stanford Graduate School of Business. En plus de ses charges de Directeur Général de Weblogy, Il sert à plusieurs postes de conseiller et de consultant pour
                    différentes entités gouvernementales et entreprises privées à travers l'Afrique. Président de l’ONG J’aime Jacqueville pour l’inclusion financière et membre de la Fondation Kaydan pour la promotion
                    de l'entreprenariat en charge de l'économie numérique, le co-fondateur de Weblogy est engagé dans le social.
                    Jil-Alexandre N'DIA est <strong>lauréat du Prix d’Excellence de l’Entreprenariat Jeune du Président de la République en 2013</strong> et <strong>2ème du Prix d’Excellence pour le Développement de la Communication en 2019</strong>. Il est Officier de l’Ordre du Mérite des Postes et Télécommunications, Officier dans l’ordre du Mérite Ivoirien et Officier de l'Ordre National de Côte d'Ivoire.

                </p>
            </div>
        </div>
    </div>
</div>
<!-- Bio-Ndia Modal end -->

<!-- Bio-Thiam Modal -->
<div class="modal fade" id="bio-thiam" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-thiam" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
                <h4 class="modal-title" id="myModalLabel-privacy">Biographie de Tidjane THIAM</h4>
            </div>
            <div class="modal-body text-left">
                <p class="bio-team" style="line-height: 1.8">
                    <strong>Tidjane Thiam</strong> a occupé le poste de Directeur général de Crédit Suisse entre juillet 2015 et février 2020.
                    Tout au long de sa carrière, en dirigeant des entreprises du secteur privé et du secteur public, Tidjane Thiam a développé de grands projets qui ont contribué de manière positive à l’économie et à la société.
                    Au sein de Crédit Suisse, il a mis en place un programme de restructuration sur trois ans, reconnu par Euromoney qui a nommé Tidjane Thiam Banker of the Year en 2018 et en 2019, il a permis à Crédit Suisse de réaliser ses profits annuels les plus élevés depuis 2010.
                    Lorsqu’il était à la tête de Prudential de 2009 à 2015, la capitalisation boursière du groupe d’assurance a triplé et a dépassé les 60 milliards de dollars américains. De 2012 à 2014, il a été Président du Conseil d’administration de l’Association des assureurs britanniques.
                    Auparavant, il a occupé diverses positions managériales chez Aviva de 2002 à 2007, dont le poste de Directeur général Europe.
                    Entre 1994 et 1999, Tidjane Thiam avait rejoint la Côte d’Ivoire pour exercer les missions de Directeur général du BNETD (Bureau national d’études techniques et de développement) et représentant du pays auprès du FMI et de la Banque mondiale. Il dirigea parmi les plus grands projets de privatisation et d’infrastructure des pays émergents.
                    En 1997, il figurait parmi les « 100 jeunes décideurs du monde de demain » (Young Global leaders of Tomorrow) du Forum économique mondial de Davos, et en 1999, il était élu membre du Dream Cabinet du Forum.
                    Précédemment, Tidjane Thiam a travaillé dix ans au sein du cabinet de conseil en stratégie McKinsey où il a occupé le poste d’Associé. Tidjane Thiam est membre du think tank Group of Thirty (G30) depuis 2014.
                    Tidjane Thiam est depuis novembre 2020 le Président du Conseil d’administration de Rwanda Finance, en charge de la promotion du Rwanda comme centre financier international.
                    Tidjane Thiam est depuis 2019 membre du Council for Inclusive Capitalism où il a le titre de Guardian, qui sont les membres du Groupe qui conseillent Sa Sainteté le pape.
                    En 2019, il est devenu membre du Comité international olympique (CIO), et depuis 2020 est membre de la Commission Finances du CIO.
                    Tidjane Thiam est membre du Council on State Fragility présidé par David Cameron, ancien Premier Ministre du Royaume-Uni.
                    De 2014 à 2019, il a siégé au Conseil d’administration de la 21st Century Fox.
                    En 2011, Tidjane Thiam a reçu les insignes de chevalier de la Légion d’honneur. Il a été distingué au sein de la liste TIME 100 en 2010.
                    Il est diplômé de l’École polytechnique, de l’École nationale supérieure des mines de Paris et titulaire d’un MBA de l’INSEAD.
                    Tidjane Thiam est Administrateur de Kering depuis le 16 juin 2020. Son mandat prendra fin à l’issue de l’Assemblée générale appelée à statuer sur les comptes clos le 31 décembre 2023
                </p>
            </div>
        </div>
    </div>
</div>
<!-- Bio-Thiam Modal end -->

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