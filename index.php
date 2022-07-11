<?php


/*
- url: /maincron
  script: cron.php
  login: admin

#  url: /demo-data-generator
#  script: demo.php
#  login: admin

- url: /api.*
  script: api.php

  #- url: /demo.php
  # script: demo.php
  # login: admin
  # secure: always

- url: /task
  script: task.php
  login: admin
  secure: always



 */

require_once('_ruta.php');


// LOGOUT CLICK
if (isset($_POST['btnLogout'])) {
    header('Location: ' . WEB_ROOT . '/logout.php');
    exit();
}

// CREDENCIALES
$T_Usuario = isset($_POST['username']) ? $_POST['username'] : '';
$T_Clave = isset($_POST['password']) ? $_POST['password'] : '';


// LOGIN CLICK
if (isset($_POST['btnLogin'])) {
    $o_Usuario = Usuario_L::obtenerPorLogin($T_Usuario, $T_Clave);

    // CONTRASEÑA INCORRECTA
    if (is_null($o_Usuario)) {
        $T_Error = _('Usuario o Contraseña incorrecta.');
        SeguridadHelper::Login(0, $T_Usuario, $T_Clave);
    }

    // LOGIN
    else {
        session_regenerate_id(true);
        $_SESSION['USUARIO']['id'] = $o_Usuario->getId();

        SeguridadHelper::Login($_SESSION['USUARIO']['id']);

        header('Location: ' . WEB_ROOT . '/index.php');
        exit();
    }
}


// TEMPLATE INICIO & LAYOUT
//$T_ContentScript = APP_PATH . '/ajax/inicio.html.php';
//require_once APP_PATH . '/templates/layout.html.php';
include_once 'codigo/templates/layout.html.php';
