<?php 

namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\Bootstrap;


$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,[ 'cache' =>Bootstrap::CACHE_DIR]);

$context = [];

$template = $twig->loadTemplate('contact.html.twig');
$template->display($context);




?>