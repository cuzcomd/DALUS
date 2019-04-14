<body class="app sidebar-mini rtl">
    <!-- Navbar-->
    <header class="app-header"><a class="app-header__logo"><img src="images/dalus_logo_header.png" alt="DALUS Logo"></a>
      <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
      <!-- Navbar Right Menu-->
      <ul class="app-nav">
        <li class="setHand" data-toggle="tooltip" data-placement="bottom" title="Auswahl" role="button"><a class="app-nav__item" href="javascript:;"><i class="fa fa-mouse-pointer"></i></a></li>
        <li class="setMarkWhite" data-toggle="tooltip" data-placement="bottom" title="Messpunkt" role="button"><a class="app-nav__item" href="javascript:;"><i class="fa fa-flag-o"></i></a></li>
        <li class="setComment" data-toggle="tooltip" data-placement="bottom" title="Kommentar" role="button"><a class="app-nav__item" href="javascript:;"><i class="fa fa-commenting-o"></i></a></li>
        <li class="dropdown"><a class="app-nav__item" data-toggle="dropdown" href="#" title="WerkzeugeToggle"><i class="fa fa-pencil"></i></a>
          <ul class="dropdown-menu dropdown-menu-right">
            <li class="app-notification__title">Zeichenwerkzeuge</li>
            <div class="app-notification__content">
              <li class="setCirc" data-toggle="tooltip" data-placement="bottom" title="Kreis zeichnen" role="button"><a class="dropdown-item" href="javascript:;"><i class="fa fa-circle-thin"></i> Kreis</a></li>
              <li class="setPoly" data-toggle="tooltip" data-placement="bottom" title="Polygon zeichnen" role="button"><a class="dropdown-item" href="javascript:;"> <i class="fa fa-bookmark-o"></i> Polygon</a></li>
              <li class="setPath" data-toggle="tooltip" data-placement="bottom" title="Pfad zeichnen" role="button"><a class="dropdown-item" href="javascript:;"><i class="fa fa-pencil"></i> Pfad</a></li>
          </ul>
        </li>
        <li class="deleteActiveObject" data-toggle="tooltip" data-placement="bottom" title="Objekt löschen" role="button"><a class="app-nav__item" href="javascript:;"><i class="fa fa-trash"></i></a></li>
        <li id = "switchMesskataster" data-click-state="0" role="button"><a class="app-nav__item" href="javascript:;"><i class="fa fa-thumb-tack icon-inactive" aria-hidden="true"></i></a></li>
        <li class="app-search">
          <input id="pac-input" class="app-search__input" type="search" placeholder="Ort suche ...">
          <button class="app-search__button" id="startSearch"><i class="fa fa-search"></i></button>
        </li>
        <!-- User Menu-->
        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Optionen"><i class="fa fa-ellipsis-v fa-lg"></i></a>
          <ul class="dropdown-menu settings-menu dropdown-menu-right">
            <li><a class="dropdown-item" data-toggle="modal"  href='#modal_options'"><i class="fa fa-cogs fa-lg"></i> Einstellungen</a></li>
            <li><a class="dropdown-item" onclick="printMap()" href="javascript:;"><i class="fa fa-print fa-lg"></i> Karte drucken</a></li>
            <li><a class="dropdown-item" data-toggle="modal" href='#modal_license' ><i class="fa fa-info-circle fa-lg"></i> Über DALUS</a></li>
            <li><a class="dropdown-item" href="php/logout"><i class="fa fa-sign-out fa-lg"></i> Abmelden</a></li>
          </ul>
        </li>
      </ul>
    </header>
    <!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
      <div class="app-sidebar__user"><!-- <img class="app-sidebar__user-avatar" src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/48.jpg" alt="Benutzerbild"> -->
        <div>
          <span class="fa fa-user-circle" aria-hidden="true">&nbsp;</span>
          <span class="app-sidebar__user-name">Kein Benutzer aktiv</span><br/>
          <span class="fa fa-folder-open" aria-hidden="true">&nbsp;</span>
          <span class="app-sidebar__project-information">Kein Projekt geöffnet</span>
        </div>
      </div>
      <div class="container app-sidebar__weather"><!-- <img class="app-sidebar__user-avatar" src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/48.jpg" alt="Benutzerbild"> -->
        <div class="row">
          <div class="col weathercity">Wetterdaten werden geladen ...</div>
        </div>
        <div class="row">
          <div class="temp col"></div>
          <div class="wind-speed col"></div>
           <div class="w-100"></div>
          <div class="wind-direction col"></div>
          <div class="clouds col"></div>
        </div>
      </div> <!-- Ende Wetterinformationen -->
      </div>
      <ul class="app-menu">
        <li class="treeview is-expanded"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-laptop"></i><span class="app-menu__label">Projekt</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li id="newProject"><a class="treeview-item" data-toggle="modal" href="#modal_new_project"><i class="icon fa fa-edit"></i> Neues Projekt</a></li>
            <li id="openProject"><a class="treeview-item" data-toggle="modal" href="#modal_open_project"><i class="icon fa fa-folder-open-o"></i> Projekt öffnen</a></li>
            <li id="editProject"><a class="treeview-item" data-toggle="modal" href="#modal_edit_project"><i class="icon fa fa-edit"></i> Projekt ändern</a></li>
            <li id="saveProject"><a class="treeview-item" href="javascript:;"><i class="icon fa fa-floppy-o"></i> Projekt speichern</a></li>
            <li id="deleteProject"><a class="treeview-item" href="javascript:;"><i class="icon fa fa-trash-o"></i> Projekt löschen</a></li>
            <li id="exportKML" onclick="toKML()"><a class="treeview-item" id="download-link" href="data:;base64," download><i class="icon fa fa-floppy-o"></i> kml-Datei exportieren</a></li>
          </ul>
        </li>
        <li id ="switch_winkel"><a class="app-menu__item" data-toggle="modal" href="#modalMET"><i class="app-menu__icon fa fa-location-arrow"></i><span class="app-menu__label">MET Modell</span></a></li>
      </ul>
    </aside>
    <main class="app-content" id ="map">    
    </main>
    <div id="modul-Kompass" class="modul-Kompass"><img src="images/arrow.png" alt="Windrose" id="arrow"/></div>