<!DOCTYPE html>
<html lang="en-us" id="extr-page">

    <!-- RESOURCES  -->
    <head>
        <meta charset="utf-8">
        <title>enPunto</title>
        <meta name="description" content="">
        <meta name="author" content="enPunto">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- #CSS Links -->
        <!-- Basic Styles -->
        <link rel="stylesheet" type="text/css" media="screen" href="https://static.enpuntocontrol.com/app/v1/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="https://static.enpuntocontrol.com/app/v1/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="https://static.enpuntocontrol.com/app/v1/css/smartadmin-production-plugins.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="https://static.enpuntocontrol.com/app/v1/css/smartadmin-production.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="https://static.enpuntocontrol.com/app/v1/css/smartadmin-skins.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="https://static.enpuntocontrol.com/app/v1/css/style.css"/>

        <!-- #FAVICONS -->
        <link rel="shortcut icon" href="https://static.enpuntocontrol.com/app/v1/img/favicon/favicon.ico" type="image/x-icon">
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
        <meta name="theme-color" content="#f4f4f4">

        <!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">

        <!-- Startup image for web apps -->
        <link rel="apple-touch-startup-image" href="https://static.enpuntocontrol.com/app/v1/img/splash/ipad-landscape.png"
              media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
        <link rel="apple-touch-startup-image" href="https://static.enpuntocontrol.com/app/v1/img/splash/ipad-portrait.png"
              media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
        <link rel="apple-touch-startup-image" href="https://static.enpuntocontrol.com/app/v1/img/splash/iphone.png" media="screen and (max-device-width: 320px)">

    </head>



    <!-- CONTENT  -->
    <body class="animated fadeInDown">

        <header id="header">

            <div id="logo-group">
                <span id="logo"> <img src="https://static.enpuntocontrol.com/app/v1/img/logo_flat.png" alt="enPunto"> </span>
            </div>

            <span id="extr-page-header-space"></span>

        </header>

        <?php
        // LOGIN ADMIN
        if(!$T_LoginEmpleado){ ?>

            <div id="main" role="main">
            <div id="content" class="container">
                <div class="row centered">


                    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4 center-block" style="float:none;">

                        <!-- HEADER SETUP -->
                        <h1 class="txt-color-red login-header-big" style="padding-left: 10px;">
                            <span style="font-size:19px;">
                                <?php if ($subdominio == 'setup') { ?>
                                    Para conectar el equipo con tu cuenta, primero necesitas loguearte...
                                <? } ?>
                            </span>
                        </h1>

                        <!-- FORM SETUP/LOGIN  -->
                        <div class="well no-padding">


                            <form action="login.php" id="login-form" class="smart-form client-form" method="post">

                                <!-- LABEL SETUP:  WITH NO LOGIN  -->
                                <?php if ($subdominio == 'setup' || isset($_REQUEST['setup'])) { ?>
                                    <input type="hidden" name="setup" value="true">
                                <? } ?>

                                <!-- HEADER  -->
                                <header>
                                    <?php echo _("Ingreso Administradores"); ?>
                                </header>

                                <!-- IMPUT DATA: COMPANY / USER PASS REMEBER USER  -->
                                <fieldset>

                                    <!-- ERROR -->
                                    <?php if ($T_Error)
                                        echo "<div class=\"alert alert-danger fade in\"><strong>Error!</strong>&nbsp;&nbsp;" . $T_Error . "</div>"; ?>


                                    <!-- EMPRESA IMPUT TEXT  -->
                                    <?php if ($subdominio == 'login' || $subdominio == 'setup') { ?>


                                        <section>

                                            <!-- EMPRESA LABEL  -->
                                            <label class="label">
                                                <?php echo _("Empresa"); ?>
                                            </label>

                                            <!-- EMPRESA IMPUT  -->
                                            <label class="input"> <i class="icon-append fa fa-industry"></i>
                                                <input type="company" name="company">
                                                    <b class="tooltip tooltip-top-right">

                                                        <i class="fa fa-user txt-color-teal"></i>
                                                        <?php echo ' ' . _('Por favor, ingrese la URL de su empresa') ?>

                                                    </b>
                                            </label>

                                        </section>

                                    <?php }

                                 // <!-- EMAIL, PASSWORD & REMEMBER CHECKBOX -->
                                    else { ?>

                                        <!-- EMAIL  -->
                                        <section>
                                            <label class="label"><?php echo _("Email o DNI"); ?></label>

                                            <label class="input"> <i class="icon-append fa fa-user"></i>
                                                <input type="text" name="username" id="username" autofocus value="<?php echo $T_Usuario;?>">
                                                <b class="tooltip tooltip-top-right">
                                                    <i class="fa fa-user txt-color-teal"></i>
                                                    <?php echo ' ' . _('Por favor, ingrese su Email o DNI') ?>
                                                </b>
                                            </label>
                                        </section>

                                        <!-- PASSWORD, RESET PASSWORD  -->
                                        <section>
                                            <!-- LABEL  -->
                                            <label class="label">
                                                <?php echo _('Contraseña') ?>
                                            </label>

                                            <!-- PASSWORD INPUT  -->
                                            <label class="input">
                                                <i class="icon-append fa fa-lock"></i>
                                                <input type="password" name="password" id="password">
                                                <b class="tooltip tooltip-top-right">
                                                    <i class="fa fa-lock txt-color-teal"></i>
                                                    <?php echo ' ' . _('Ingrese su contraseña') ?>
                                                </b>
                                            </label>

                                            <!-- RESET PASSWORD  -->
                                            <div class="note">
                                                <a href="password.php">
                                                    <?php echo _('¿Olvidó su contraseña?') ?>
                                                </a>
                                            </div>

                                        </section>

                                        <!-- REMEMBER CHECKBOX  -->
                                        <section>
                                            <label class="checkbox">
                                                <input type="checkbox" name="remember" checked="">
                                                <i></i><?php echo _('Recordar') ?></label>
                                        </section>


                                    <?php } ?>


                                </fieldset>

                                <!-- SUBMIT CONTINUE/LOGIN  -->
                                <footer>
                                    <?php if ($subdominio == 'login'  || $subdominio == 'setup') { ?>
                                        <button type="submit" class="btn btn-primary" name="btnContinue">
                                            <?php echo _('Continuar') ?>
                                        </button>
                                    <?php } else { ?>

                                        <!-- LOGIN AS EMPLOYEE
                                        <div class="note">
                                            <a href="login.php?empleado">
                                                <?php //echo _('Ingresar como empleado') ?>
                                            </a>
                                        </div> -->

                                        <button type="submit" class="btn btn-primary" name="btnLogin">
                                            <?php echo _('Ingresar') ?>
                                        </button>
                                    <?php } ?>
                                </footer>

                            </form>


                        </div>

                    </div>

                </div>
            </div>
        </div>

        <?php }

        // LOGIN EMPLOYEE
        else { ?>

            <div id="main" role="main">
                <div id="content" class="container">
                    <div class="row centered">


                        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4 center-block" style="float:none;">


                            <!-- FORM LOGIN  -->
                            <div class="well no-padding">


                                <form action="login.php?empleado" id="login-form" class="smart-form client-form" method="post">


                                    <!-- HEADER  -->
                                    <header>
                                        <?php echo _("Ingreso Empleados"); ?>
                                    </header>

                                    <!-- IMPUT DATA: COMPANY / USER PASS REMEBER USER  -->
                                    <fieldset>

                                        <!-- ERROR -->
                                        <?php if ($T_Error)
                                            echo "<div class=\"alert alert-danger fade in\"><strong>Error!</strong>&nbsp;&nbsp;" . $T_Error . "</div>"; ?>


                                            <!-- DNI  -->
                                            <section>
                                                <label class="label"><?php echo _("Email o DNI"); ?></label>

                                                <label class="input"> <i class="icon-append fa fa-user"></i>
                                                    <input type="text" name="username" id="username" autofocus value="<?php echo $T_Usuario;?>">
                                                    <b class="tooltip tooltip-top-right">
                                                        <i class="fa fa-user txt-color-teal"></i>
                                                        <?php echo ' ' . _('Por favor, ingrese su Email o DNI') ?>
                                                    </b>
                                                </label>
                                            </section>

                                            <!-- PASSWORD, RESET PASSWORD  -->
                                            <section>
                                                <!-- LABEL  -->
                                                <label class="label">
                                                    <?php echo _('Contraseña') ?>
                                                </label>

                                                <!-- PASSWORD INPUT  -->
                                                <label class="input">
                                                    <i class="icon-append fa fa-lock"></i>
                                                    <input type="password" name="password" id="password">
                                                    <b class="tooltip tooltip-top-right">
                                                        <i class="fa fa-lock txt-color-teal"></i>
                                                        <?php echo ' ' . _('Ingrese su contraseña') ?>
                                                    </b>
                                                </label>

                                            </section>

                                            <!-- REMEMBER CHECKBOX  -->
                                            <section>
                                                <label class="checkbox">
                                                    <input type="checkbox" name="remember" checked="">
                                                    <i></i><?php echo _('Recordar') ?></label>
                                            </section>



                                    </fieldset>

                                    <!-- SUBMIT CONTINUE/LOGIN  -->
                                    <footer>

                                        <!-- LOGIN AS ADMIN  -->

                                        <div class="note">
                                            <a href="login.php">
                                                <?php echo _('Ingresar como Administrador') ?>
                                            </a>
                                        </div>


                                        <button type="submit" class="btn btn-primary" name="btnLogin">
                                            <?php echo _('Ingresar') ?>
                                        </button>

                                    </footer>

                                </form>


                            </div>

                        </div>

                    </div>
                </div>
            </div>

        <?php }?>







        <!-- SCRIPTS -->

        <!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)
        <script src="https://static.enpuntocontrol.com/app/v1/js/plugin/pace/pace.min.js"></script>-->

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

        <!-- JS TOUCH : include this plugin for mobile drag / drop touch events
        <script src="https://static.enpuntocontrol.com/app/v1/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

        <!-- BOOTSTRAP JS -->
        <script src="https://static.enpuntocontrol.com/app/v1/js/bootstrap/bootstrap.min.js"></script>

        <!-- JQUERY VALIDATE -->
        <script src="https://static.enpuntocontrol.com/app/v1/js/plugin/jquery-validate/jquery.validate.min.js"></script>

        <!-- JQUERY MASKED INPUT -->
        <script src="https://static.enpuntocontrol.com/app/v1/js/plugin/masked-input/jquery.maskedinput.min.js"></script>

        <!--[if IE 8]>

        <h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

        <![endif]-->

        <!-- MAIN APP JS FILE -->
        <script src="https://static.enpuntocontrol.com/app/v1/js/app.min.js"></script>




        <!-- SUBMIT SCRIPT -->
        <script type="text/javascript">
            runAllForms();

            $(function () {
                // Validation
                $("#login-form").validate({
                    // Rules for form validation
                    rules: {
                        username: {
                            required: true
                        },
                        password: {
                            required: true
                        }
                    },
                    // Messages for form validation
                    messages: {
                        username: {
                            required: '<?php echo _('Por favor, ingrese su nombre de usuario') ?>'
                        },
                        password: {
                            required: '<?php echo _('Por favor, ingrese su contraseña') ?>'
                        }
                    },
                    // Do not change code below
                    errorPlacement: function (error, element) {
                        error.insertAfter(element.parent());
                    }
                });
                $('#login-form input').on({
                    function() {
                        $(this).trigger('input');
                    }
                });

            });


        </script>




    </body>

</html>