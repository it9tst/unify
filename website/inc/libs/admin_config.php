<?php

//directory importanti
define('LIBS', '/home2/unifyuls/public_html/inc/libs/');
define('CONTROLLER', '/home2/unifyuls/public_html/inc/admin_controller/');
define('VIEW', '/home2/unifyuls/public_html/inc/admin_view/');
define('TEMPLATE', '/home2/unifyuls/public_html/inc/admin_view/template/');
define('MODEL', '/home2/unifyuls/public_html/inc/model/');
define('UTILS', '/home2/unifyuls/public_html/inc/utils/');
define('PHOTO', 'images/photo/');
define('JSPATH', 'js/');
define('CSSPATH', 'css/');
define('IMGPATH', 'images/');
define('FONTPATH', 'fonts/');


// dati accesso al DB
define('DBHOST', 'localhost');
define('DBUSER', 'unifyuls_admin');
define('DBPASS', 'DeaTwewCyecWorjet5');
define('DBNAME', 'unifyuls_unify');

define('STANDARDSALT', 'b3Pix3Btrh2tWS79UpTNad32AMgZ6aT3wsRuLNl9');



//require importanti
require_once LIBS."Database.php";
require_once LIBS."Session.php";
require_once LIBS."Post.php";
require_once LIBS."Controller.php";
require_once LIBS."View.php";
