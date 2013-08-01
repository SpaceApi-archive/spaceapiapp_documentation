<?php

//********************************************************************
// do not edit this section

if(!defined("APPSDIR"))
    die("Direct access is not allowed!");
//********************************************************************


$page->addStylesheet("css/style.css");
$page->addScript("scripts/documentation.js");

include("draft_warning.php");

// start left tabs
$page->addContent('
    <div class="tabbable tabs-left">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#left-tab-reference" data-toggle="tab">Reference</a></li>
        <li><a href="#left-tab-examples" data-toggle="tab">Examples</a></li>
        <li><a href="#left-tab-filters" data-toggle="tab">Filters</a></li>

      </ul>
      <div class="tab-content documentation-content-page">
');
/*
    <div class="tab-pane active" id="tab1">
      <p>I'm in Section 1.</p>
    </div>
    <div class="tab-pane" id="tab2">
      <p>Howdy, I'm in Section 2.</p>
    </div>
*/

//$app_dir = APPSDIR . $page->activePage();

// first tab
$page->addContent('<div class="tab-pane active" id="left-tab-reference">');
//$page->addContent("<h2>Reference</h2>");
//include("$app_dir/ref_notice.php");
include("section_reference.php");
$page->addContent('</div>');

// second tab
$page->addContent('<div class="tab-pane" id="left-tab-examples">');
include("section_minimal_example.php");
$page->addContent('</div>');

// third tab
$page->addContent('<div class="tab-pane" id="left-tab-filters">');
include("section_filters.php");
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
$page->addContent('</div>');



// end left tabs
$page->addContent('
  </div>
</div>
');