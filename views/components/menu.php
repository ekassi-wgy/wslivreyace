<style>
    .dropbtn {
        /*background-color: #4CAF50;*/
        color: white;
        padding: 16px;
        font-size: 16px;
        border: none;
        cursor: pointer;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 190px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .dropdown-content a {
        /*color: black;*/
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #f1f1f1;
        color: #dc3545;
        font-weight: 600;
        /*transition-duration: 3s, 2s;*/
        transition: background 1s linear, border 1s linear;
        /*transition-property: margin-right, color;*/
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

</style>
<!--nav-->
<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
            <a class="navbar-brand" href="/livre"><img src="https://media-files.abidjan.net/livre/img/logo.png" data-at2x="https://media-files.abidjan.net/livre/img/logo@2x.png" style="width: 165px !important" alt="Logo Weblogy"></a>
        </div>
        <div class="collapse navbar-collapse" id="bs-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" id="livreMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">Livre</a>
                    <ul class="dropdown-menu" aria-labelledby="livreMenu">
                        <li class="dropdown-item">
                            <a href="/livre/team#book">Le livre</a>
                        </li>
						
						
                        <li class="dropdown-item">
                            <a class="dropdown-item" href="/livre/team#authors">Les auteurs</a>
                        </li>
                        </li>
                        <li class="dropdown-item">
                            <a class="dropdown-item" href="/livre/team#prefacier">Le préfacier</a>
                        </li>
                        <li class="dropdown-item">
                            <a class="dropdown-item" href="/livre/team#entrepreneurs">Les entrepreneurs</a>
                        </li>
                    </ul>
                </li>
				<li class="dropdown-item"> <a href="/livre/photos">Photos</a> </li>
				<li class="dropdown-item"> <a href="/livre/videos">Vidéos</a> </li>
                <li><a href="/livre/points-vente">Points de vente</a></li>
                <li><a href="/livre#contact">Contacts</a></li>
                <li><a href="/livre/order" class="button">Commander </a></li>
            </ul>
        </div>
    </div>
</nav>
<!--nav end-->