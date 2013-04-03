<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$environment_prefix = isset($_SERVER['SERVER_ENVIRONMENT']) ? 'bowser.' : '';

$config['favicon_url'] = "http://{$environment_prefix}newballot.org/images_path/favicon.ico";

$config['site_doctype'] = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';

$config['site_title'] = "NewBallot.org - tell your Congress member how to vote.";

$config['yui_path'] = 'http://yui.yahooapis.com/2.7.0/build/';

$config['default_style_urls'] = array("/style/reset-min.css",
									  '/style/newballot.css'
                                );

$config['default_js_pre_load_urls'] = array();

$config['default_js_post_load_urls'] = array("{$config['yui_path']}yahoo-dom-event/yahoo-dom-event.js");
