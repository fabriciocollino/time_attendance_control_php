<!DOCTYPE html>
<html lang="es-AR">
<head>
    <meta charset="utf-8">
    <title><?php echo _("enPunto"); ?></title>
    <meta name="description" content="TASM">
    <meta name="author" content="enPunto">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- #CSS Links -->
    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="https://static.enpuntocontrol.com/app/v1/css/bootstrap.min.css">

    <!-- TESTING CSS LIBRARY -->




    <link rel="preload" onload="this.onload=null;this.rel='stylesheet'" as="style" media="screen" href="https://static.enpuntocontrol.com/app/v1/css/font-awesome.min.css">
    <link rel="preload" onload="this.onload=null;this.rel='stylesheet'" as="style" media="screen" href="https://static.enpuntocontrol.com/app/v1/css/bootstrap-datetimepicker.min.css">
    <link rel="preload" onload="this.onload=null;this.rel='stylesheet'" as="style" media="screen" href="https://static.enpuntocontrol.com/app/v1/css/smartadmin-production-plugins.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="https://static.enpuntocontrol.com/app/v1/css/smartadmin-production.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="https://static.enpuntocontrol.com/app/v1/css/smartadmin-skins.min.css">
    <link rel="preload" onload="this.onload=null;this.rel='stylesheet'" as="style" media="screen" href="https://static.enpuntocontrol.com/app/v1/css/chosen.min.css"/>
    <link rel="preload" onload="this.onload=null;this.rel='stylesheet'" as="style" media="screen" href="https://static.enpuntocontrol.com/app/v1/css/multi-select.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="https://static.enpuntocontrol.com/app/v1/css/style.css"/>

    <!-- #FAVICONS -->
    <link rel="icon" href="https://static.enpuntocontrol.com/app/v1/img/favicon/favicon.ico" type="image/x-icon">

    <!-- #GOOGLE FONT -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

    <!-- #APP SCREEN / ICONS -->
    <!-- Specifying a Webpage Icon for Web Clip
         Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
    <link rel="apple-touch-icon" href="https://static.enpuntocontrol.com/app/v1/img/splash/sptouch-icon-iphone.png">
    <link rel="apple-touch-icon" sizes="57x57" href="https://static.enpuntocontrol.com/app/v1/img/splash/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="https://static.enpuntocontrol.com/app/v1/img/splash/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="https://static.enpuntocontrol.com/app/v1/img/splash/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="https://static.enpuntocontrol.com/app/v1/img/splash/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="https://static.enpuntocontrol.com/app/v1/img/splash/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="https://static.enpuntocontrol.com/app/v1/img/splash/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="https://static.enpuntocontrol.com/app/v1/img/splash/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="https://static.enpuntocontrol.com/app/v1/img/splash/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="https://static.enpuntocontrol.com/app/v1/img/splash/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="https://static.enpuntocontrol.com/app/v1/img/splash/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="https://static.enpuntocontrol.com/app/v1/img/splash/favicon-194x194.png" sizes="194x194">
    <link rel="icon" type="image/png" href="https://static.enpuntocontrol.com/app/v1/img/splash/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="https://static.enpuntocontrol.com/app/v1/img/splash/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="https://static.enpuntocontrol.com/app/v1/img/splash/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="https://static.enpuntocontrol.com/app/v1/img/splash/manifest.json">
    <link rel="mask-icon" href="https://static.enpuntocontrol.com/app/v1/img/splash/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-TileImage" content="https://static.enpuntocontrol.com/app/v1/img/splash/mstile-144x144.png">
    <meta name="theme-color" content="#f3f3f3">


    <!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!-- Startup image for web apps -->
    <link rel="apple-touch-startup-image" href="https://static.enpuntocontrol.com/app/v1/img/splash/ipad-landscape.png"
          media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
    <link rel="apple-touch-startup-image" href="https://static.enpuntocontrol.com/app/v1/img/splash/ipad-portrait.png"
          media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
    <link rel="apple-touch-startup-image" href="https://static.enpuntocontrol.com/app/v1/img/splash/iphone.png" media="screen and (max-device-width: 320px)">

<script>
    /*! loadCSS. [c]2017 Filament Group, Inc. MIT License */
    /* This file is meant as a standalone workflow for
    - testing support for link[rel=preload]
    - enabling async CSS loading in browsers that do not support rel=preload
    - applying rel preload css once loaded, whether supported or not.
    */
    (function( w ){
        "use strict";
        // rel=preload support test
        if( !w.loadCSS ){
            w.loadCSS = function(){};
        }
        // define on the loadCSS obj
        var rp = loadCSS.relpreload = {};
        // rel=preload feature support test
        // runs once and returns a function for compat purposes
        rp.support = (function(){
            var ret;
            try {
                ret = w.document.createElement( "link" ).relList.supports( "preload" );
            } catch (e) {
                ret = false;
            }
            return function(){
                return ret;
            };
        })();

        // if preload isn't supported, get an asynchronous load by using a non-matching media attribute
        // then change that media back to its intended value on load
        rp.bindMediaToggle = function( link ){
            // remember existing media attr for ultimate state, or default to 'all'
            var finalMedia = link.media || "all";

            function enableStylesheet(){
                link.media = finalMedia;
            }

            // bind load handlers to enable media
            if( link.addEventListener ){
                link.addEventListener( "load", enableStylesheet );
            } else if( link.attachEvent ){
                link.attachEvent( "onload", enableStylesheet );
            }

            // Set rel and non-applicable media type to start an async request
            // note: timeout allows this to happen async to let rendering continue in IE
            setTimeout(function(){
                link.rel = "stylesheet";
                link.media = "only x";
            });
            // also enable media after 3 seconds,
            // which will catch very old browsers (android 2.x, old firefox) that don't support onload on link
            setTimeout( enableStylesheet, 3000 );
        };

        // loop through link elements in DOM
        rp.poly = function(){
            // double check this to prevent external calls from running
            if( rp.support() ){
                return;
            }
            var links = w.document.getElementsByTagName( "link" );
            for( var i = 0; i < links.length; i++ ){
                var link = links[ i ];
                // qualify links to those with rel=preload and as=style attrs
                if( link.rel === "preload" && link.getAttribute( "as" ) === "style" && !link.getAttribute( "data-loadcss" ) ){
                    // prevent rerunning on link
                    link.setAttribute( "data-loadcss", true );
                    // bind listeners to toggle media back
                    rp.bindMediaToggle( link );
                }
            }
        };

        // if unsupported, run the polyfill
        if( !rp.support() ){
            // run once at least
            rp.poly();

            // rerun poly on an interval until onload
            var run = w.setInterval( rp.poly, 500 );
            if( w.addEventListener ){
                w.addEventListener( "load", function(){
                    rp.poly();
                    w.clearInterval( run );
                } );
            } else if( w.attachEvent ){
                w.attachEvent( "onload", function(){
                    rp.poly();
                    w.clearInterval( run );
                } );
            }
        }


        // commonjs
        if( typeof exports !== "undefined" ){
            exports.loadCSS = loadCSS;
        }
        else {
            w.loadCSS = loadCSS;
        }
    }( typeof global !== "undefined" ? global : this ) );

</script>
</head>
<?
if(isset($_POST['set_sess'])){
	$_SESSION['filtro']['fechaD']=date('Y-m-01')." 00:00:00";
	$_SESSION['filtro']['fechaH']=date('Y-m-t')." 23:59:59";
	$_SESSION['Previous_URL'] = $_SERVER['REQUEST_URI'];
	die();
}
?>



<body class="smart-style-0 fixed-ribbon fixed-header fixed-navigation">

<!-- #HEADER -->
<header id="header">
    <div id="logo-group">

        <span id="logo"> <img src="https://static.enpuntocontrol.com/app/v1/img/logo_flat.png" alt="enPunto - TASM"> </span>

    </div>


    <div class="project-context hidden-xs">

        <!--	<h1>Time Attendance Server Manager</h1> -->

    </div>

    <div class="pull-right">


        <!-- collapse menu button -->
        <div id="hide-menu" class="btn-header pull-right">
            <span> <a href="javascript:void(0);" data-action="toggleMenu"><i
                            class="fa fa-reorder"></i></a> </span>
        </div>

        <div class="header-dropdown-list">
            <span>
                <ul class="header-dropdown-list">

                    <li>
                         <!-- DROPDOWN: GEAR ICON -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                            <span>
                                <i class="fa fa-lg fa-fw fa-gear"></i>
                            </span>

                            <i class="fa fa-angle-down"></i>
                         </a>

                        <!-- MENU OPTIONS -->
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a href="#usuarios">Usuarios</a>
                            </li>
                            <li>
                                <a href="#equipos">Equipos</a>
                            </li>
                            <!--<li>
                                 <a href="#configuracion">Configuración</a>
                             </li>
                             -->
                            <!--<li>
                             <a href="#api">Integraciones API</a>
                            </li>-->
                            <li>
                                <a href="#cuenta"> Perfil Empresa</a>
                            </li>
                            <!--<li>
                           <a href="#suscripcion"> Suscripción</a>
                       </li>-->

                   </ul>

               </li>
           </ul>
       </span>
   </div>

   <!--
    <div id="fullscreen" class="btn-header transparent pull-right">
        <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i
                    class="fa fa-arrows-alt"></i></a> </span>
    </div>

    -->


        <!-- multiple lang dropdown : find all flags in the flags page --
        <ul class="header-dropdown-list hidden-xs">
            <li>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img alt="" src="https://static.enpuntocontrol.com/app/v1/img/flags/ar.png">
                    <span> AR </span> <i class="fa fa-angle-down"></i> </a>

                <ul class="dropdown-menu pull-right">
                    <li class="active">
                        <a href="javascript:void(0);"><img alt="" src="https://static.enpuntocontrol.com/app/v1/img/flags/ar.png"> ES</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);"><img alt="" src="https://static.enpuntocontrol.com/app/v1/img/flags/us.png"> EN</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);"><img alt="" src="https://static.enpuntocontrol.com/app/v1/img/flags/de.png"> DE</a>
                    </li>
                </ul>
            </li>
        </ul>
        <!-- end multiple lang -->

    </div>
    <!-- end pulled right: nav area -->

</header>
<!-- END HEADER -->


<aside id="left-panel">

    <!-- USER INFO: DROPDOWN MENU -->
    <div>
        <span>
            <ul class="dropdown" style="padding-top: 10px;padding-left: 10px;">

                <li style="list-style-type:none;">

                     <!-- DROPDOWN: USER IMAGE AND USER NAME -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                        <span>
                            <!-- DROPDOWN: USER IMAGE -->
                            <?php
                                if (isset($_SESSION['USUARIO']['id']) && Registry::getInstance()->Usuario->getImagen() == '') {
                                    echo '<img style="border-radius: 50%;max-height: 40px;" src="https://static.enpuntocontrol.com/app/v1/img/avatars/male-big.png" alt="me" />';
                                } else {
                                    echo '<img style="border-radius: 50%;max-height: 40px;" src="imagen.php?usu_id=' . Registry::getInstance()->Usuario->getId() . '" alt="me" />';
                                }
                            ?>
                            <!-- DROPDOWN: USER NAME -->
                            <span style="padding-left:10px;">
                                <strong>
                                    <?php

                                    $nombre_usuario_layout = isset($_SESSION['USUARIO']['id']) ? Registry::getInstance()->Usuario->getNombre() . ' ' . Registry::getInstance()->Usuario->getApellido() : _('Anónimo');
                                    if(strlen($nombre_usuario_layout ) > 17) {
                                        $nombre_usuario_layout = substr($nombre_usuario_layout, 0, 17) . "...";
                                    }
                                    echo $nombre_usuario_layout;

                                    ?>
                                </strong>
                            </span>
                        </span>

                        <i class="fa fa-angle-down"></i>
                     </a>

                    <!-- MENU OPTIONS -->
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a href="#mi-usuario"> Mi Usuario</a>
                        </li>
                         <li>
                            <a href="/logout.php"><i class="fa fa-fw fa-sign-out"></i> Cerrar Sesión</a>
                        </li>
                    </ul>

                </li>
            </ul>
        </span>
    </div>

    <?php require_once(APP_PATH . '/includes/menu.inc.php'); ?>

    <!--<span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>-->

</aside>
<!-- END NAVIGATION -->

<!-- #MAIN PANEL -->
<div id="main" role="main">

    <!-- RIBBON -->
    <div id="ribbon" style="display:none;" >

        <!-- breadcrumb -->
        <ol class="breadcrumb">
            <!-- This is auto generated -->
        </ol>
        <!-- end breadcrumb -->
        <?php
        $multiEmpresa = 'display:none;';
        if (Config_L::p('usar_multi_empresa'))
            $multiEmpresa = '';

        ?>
        <div id="selEmpresaDiv" class="widget-toolbar smart-form" style="<?php echo $multiEmpresa; ?>">

            <label class="select"> <span class="icon-prepend fa fa-industry"></span>
                <select name="selEmpresa" id="selEmpresa" style="padding-left: 32px;height: 30px;">
                    <?php echo HtmlHelper::array2htmloptions(Empresa_L::obtenerTodos(), 0, true, true, '', 'Todas Las Empresas'); ?>
                </select> <i style="top: 10px;height: 10px;"></i> </label>

        </div>

    </div>
    <!-- END RIBBON -->

    <!-- #MAIN CONTENT -->
    <div id="content">

    </div>
    <!-- END #MAIN CONTENT -->

</div>
<!-- END #MAIN PANEL -->


<!--================================================== -->


<!-- #PLUGINS -->
<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->


<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script> if (!window.jQuery) {
        document.write('<script src="https://static.enpuntocontrol.com/app/v1/js/libs/jquery-2.1.1.min.js"><\/script>');
    } </script>



<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script> if (!window.jQuery.ui) {
        document.write('<script src="https://static.enpuntocontrol.com/app/v1/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
    } </script>



<!-- IMPORTANT: APP CONFIG -->
<script src="https://static.enpuntocontrol.com/app/v1/js/app.config.js"></script>

<!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
<script src="https://static.enpuntocontrol.com/app/v1/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script>

<!-- BOOTSTRAP JS -->
<script src="https://static.enpuntocontrol.com/app/v1/js/bootstrap/bootstrap.min.js"></script>

<!-- BOOTSTRAP JS
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

-->









<!-- CUSTOM NOTIFICATION -->
<script src="https://static.enpuntocontrol.com/app/v1/js/notification/SmartNotification.min.js"></script>

<!-- JARVIS WIDGETS -->
<script src="https://static.enpuntocontrol.com/app/v1/js/smartwidgets/jarvis.widget.min.js"></script>

<!-- EASY PIE CHARTS -->
<script src="https://static.enpuntocontrol.com/app/v1/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>

<!-- SPARKLINES -->
<script src="https://static.enpuntocontrol.com/app/v1/js/plugin/sparkline/jquery.sparkline.min.js"></script>

<!-- JQUERY VALIDATE -->
<script src="https://static.enpuntocontrol.com/app/v1/js/plugin/jquery-validate/jquery.validate.min.js"></script>

<!-- JQUERY MASKED INPUT -->
<script src="https://static.enpuntocontrol.com/app/v1/js/plugin/masked-input/jquery.maskedinput.min.js"></script>

<!-- JQUERY SELECT2 INPUT -->
<script src="https://static.enpuntocontrol.com/app/v1/js/plugin/select2/select2.min.js"></script>

<!-- JQUERY UI + Bootstrap Slider -->
<script src="https://static.enpuntocontrol.com/app/v1/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>

<!-- browser msie issue fix -->
<script src="https://static.enpuntocontrol.com/app/v1/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>

<!-- FastClick: For mobile devices: you can disable this in app.js -->
<script src="https://static.enpuntocontrol.com/app/v1/js/plugin/fastclick/fastclick.min.js"></script>

<!-- aCollaptable -->
<script src="https://static.enpuntocontrol.com/app/v1/js/plugin/acollaptable/jquery.acollaptable.min.js"></script>

<!--[if IE 8]>
<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
<![endif]-->

<!-- MAIN APP JS FILE -->
<script src="https://static.enpuntocontrol.com/app/v1/js/app.min.js"></script>

<!-- MOMENT -->
<script src="https://static.enpuntocontrol.com/app/v1/js/plugin/moment/moment.min.js"></script>
<script src="https://static.enpuntocontrol.com/app/v1/js/plugin/moment/es.js"></script>
<script src="https://static.enpuntocontrol.com/app/v1/js/plugin/moment/moment-timezone-with-data-2012-2022.js"></script>

<!-- SOCKET.IO -->
<script src="https://static.enpuntocontrol.com/app/v1/js/plugin/socket-io/socket.io.js"></script>

<!-- DATETIME PICKER -->
<script src="https://static.enpuntocontrol.com/app/v1/js/plugin/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>

<script type="text/javascript">
    //localStorage.setItem('API_URL', 'https://' + window.location.hostname.split('.')[0] + '.enpuntocontrol.com/api/v2');
    //localStorage.setItem('AUTH_TOKEN', '<?php echo $_SESSION['authToken']; ?>');

</script>
<script async src="https://static.enpuntocontrol.com/app/v1/js/api.js"></script>


<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-R7M036K4BZ"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-R7M036K4BZ');
</script>


<script type="text/javascript">

    $(document).unbind('keydown').bind('keydown', function (event) {
        if (event.keyCode === 8) {
            var doPrevent = true;
            var types = ["text", "password", "file", "search", "email", "number", "date", "color", "datetime", "datetime-local", "month", "range", "search", "tel", "time", "url", "week"];
            var d = $(event.srcElement || event.target);
            var disabled = d.prop("readonly") || d.prop("disabled");
            if (!disabled) {
                if (d[0].isContentEditable) {
                    doPrevent = false;
                } else if (d.is("input")) {
                    var type = d.attr("type");
                    if (type) {
                        type = type.toLowerCase();
                    }
                    if (types.indexOf(type) > -1) {
                        doPrevent = false;
                    }
                } else if (d.is("textarea")) {
                    doPrevent = false;
                }
            }
            if (doPrevent) {
                event.preventDefault();
                return false;
            }
        }
    });

</script>


<script>
        $(document).ready(function(){
            $("#menu a").click(function(){
                $("#menu a").css('color','#666');
                $(this).css('color','#19b0c7');
            });
        });

        jQuery(document).on('click', '.set_session', function(){
        urll = window.location.href;
        set_sess=true;

        $.ajax({
            url: urll+"set_sess",
            type: "post",
            data: {set_sess:set_sess}

        });

    });

</script>

</body>

</html>
