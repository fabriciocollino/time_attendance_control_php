<?php

require_once get_required_php_file(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

function get_required_php_file(string $path): string {

    // just require the index file
    if ($path === '/') {
        return 'index.php';
    }
    if ($path === '/login') {
        return 'login.php';
    }
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








    // getting the last character to strip it off if it's a "/"
    $last_char = \substr($path, -1);
    $pathname = ($last_char === '/' ? rtrim($path, '/') : $path);

    // stripping the leading "/" to get the true path
    $pathname = ltrim($pathname, '/');

    // setting the full require path
    $full_php_path = $pathname;

    // returning the path as long as it exists, if it doesn't, return 404.php
    return (\file_exists($full_php_path) ? $full_php_path : '404.php');

}
