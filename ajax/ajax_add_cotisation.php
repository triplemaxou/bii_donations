<?php
ini_set('display_errors', '1');
require_once(plugin_dir_path(__FILE__)."../config.php");
cotisation::newDonateurPaypal($_POST);
pre($_POST);