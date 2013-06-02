<?php
error_reporting(E_ALL);

//********************************************************************
// do not edit this section

if(!defined("APPSDIR"))
    die("Direct access is not allowed!");

$app_dir = realpath(dirname(__FILE__));
// remove the full path of the document root
$app_dir = str_replace(ROOTDIR, "", $app_dir);

$page->setActivePage(basename($app_dir));

//********************************************************************

//********************************************************************
// Access to the backend

// define the auto class loader
function class_loader($classname)
{
    $classfile = CLASSDIR . "$classname.class.php";

    if (file_exists($classfile))
    {
        require_once($classfile);
        return true;
    }

    // this is not so ideal, when the config cannot be loaded this fails
    // so just be sure the Config class is always included!
    $logger = KLogger::instance(LOGDIR, DEBUG_LEVEL);
    $logger->logEmerg("The class '$classname' cannot be loaded!");

    return false;
}

spl_autoload_register("class_loader");

// whenever the backend classes are used, we most probably need the logger and the SAPI constant
$logger = KLogger::instance(LOGDIR, DEBUG_LEVEL);
define('SAPI', 'apache');

//********************************************************************

$page->addStylesheet("$app_dir/css/style.css");
$page->addScript("$app_dir/scripts/documentation.js");

include("$app_dir/draft_warning.php");
include("$app_dir/section_minimal_example.php");
include("$app_dir/section_filters.php");

$html = <<<HTML
    <div id="directory-tabs">
        <!--
        <ul>
            <li><a href="#directory-tab0">Overview</a></li>
            <li><a href="#directory-tab1">My space offers ...</a></li>
            <li><a href="#directory-tab2">Filter supported by ...</a></li>
        </ul>
        -->
HTML;

$page->addContent("<h2>Reference</h2>");
include("$app_dir/ref_notice.php");
include("$app_dir/section_reference.php");
