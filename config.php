<?php 
define('BASEURL', $_SERVER['DOCUMENT_ROOT'].'/ecom/');
define('CART_COOKIE','SBwi72UCklwiqzz2');
define('CART_COOKIE_EXPIRE',time() + (86400*30));

define('CURRENCY','usd');
define('CHECKOUTMODE','TEST');

if(CHECKOUTMODE == 'TEST'){
	define('STRIPE_PRIVATE','sk_test_UXld0TrE9L4nmgeiGvAIyiCl');
	define('STRIPE_PUBLIC','pk_test_wTCto6sLzkqmAXgGUUFU1Z42');
}