<?php

class Accordion {

    private $id = "";

    private $specs = null;
    private $specs_version = "";

    /**
     * @param $specs Object representing the Space API
     * @param $specs_version
     * @param $id
     */
    function __construct($specs, $specs_version, $id)
    {
        $this->specs = $specs;
        $this->id = $id;
        $this->specs_version = $specs_version;
    }

    public function render()
    {
        global $app_dir;

        // load the templates
        $accordion_template = file_get_contents("templates/accordion.html");
        $accordion_group_template = file_get_contents("templates/accordion-group.html");
        $accordion_group_inner_template = file_get_contents("templates/accordion-group-inner.html");

        // create the accordion groups
        $accordion_groups = "";
        foreach($this->specs as $property => $sub_specs)
        {
            // define the id for the sub accordion which consists of the parent's id
            // postfixed by the name of the property which is currently being processed
            $sub_id = $this->id .'-'. $property;

            // 1. do recursive
            $sub_accordion = null;
            if( isset($sub_specs) && isset($sub_specs->type) )
            {
                switch($sub_specs->type)
                {
                    case "array":

                        // if the array elements are of the type 'object'
                        // then we need to iterate over them too
                        if (property_exists($sub_specs, "items") &&
                            property_exists($sub_specs->items, "type") &&
                            $sub_specs->items->type == "object")
                        {
                            if (property_exists($sub_specs->items, "properties"))
                                $sub_accordion = new Accordion($sub_specs->items->properties, $this->specs_version, $sub_id);
                        }

                        break;

                    case "object":

                        if(property_exists($sub_specs, "properties"))
                            $sub_accordion = new Accordion($sub_specs->properties, $this->specs_version, $sub_id);

                        break;

                    default:
                        // do something
                }
            }

            // 2. assemble the accordion group

            // use a copy to not override the template variable
            $accordion_group = $accordion_group_template . PHP_EOL;

            // set the required icon for this property being processed right now
            //$required = $sub_specs->required ? ', required' : '';
            $required_icon = $sub_specs->required ? 'icon-exclamation-sign'/*'icon-star'*/ : 'icon-exclamation-sign-white';
            $accordion_group = str_replace("%REQUIRED_ICON%", $required_icon, $accordion_group);

            //--------------------------------
            $accordion_group = str_replace("%PROPERTY%", $property, $accordion_group);

            //--------------------------------
            $field_type = "";
            if(property_exists($sub_specs, "type"))
            {
                // multiple types are allowed in a JSON schema
                // this is also the way to create a tristate variable which is either
                // of the type boolean or null.
                if(is_array($sub_specs->type))
                    $field_type = join(', ', $sub_specs->type);
                else
                    $field_type = $sub_specs->type;
            }
            $accordion_group = str_replace("%PROPERTY_TYPE%", $field_type, $accordion_group);

            /*** start accordion group inner ***/
            if(true) // the if statement here is just to let the editor fold the whole code block
            {
                $accordion_group_inner = $accordion_group_inner_template;

                //--------------------------------
                $visibility = "none";
                if(property_exists($sub_specs, "description") && !empty($sub_specs->description))
                {
                    $visibility = "";
                    $field_description = $sub_specs->description;
                    $accordion_group_inner = str_replace("%PROPERTY_DESCRIPTON%", $sub_specs->description, $accordion_group_inner);
                }

                $accordion_group_inner = str_replace("%VISIBILITY_PROPERTY_DESCRIPTON%", $visibility, $accordion_group_inner);
                unset($visibility);

                //--------------------------------
                $visibility = "none";
                if(property_exists($sub_specs, "enum") && !empty($sub_specs->enum))
                {
                    $visibility = "";
                    $accordion_group_inner = str_replace(
                        "%PROPERTY_ENUM%",
                        join(", ", $sub_specs->enum),
                        $accordion_group_inner
                    );
                }
                else if(property_exists($sub_specs, "items") &&
                    property_exists($sub_specs->items, "enum") &&
                    !empty($sub_specs->items->enum))
                {
                    $visibility = "";
                    $accordion_group_inner = str_replace(
                        "%PROPERTY_ENUM%",
                        join(", ", $sub_specs->items->enum),
                        $accordion_group_inner
                    );
                }

                $accordion_group_inner = str_replace("%VISIBILITY_PROPERTY_ENUM%", $visibility, $accordion_group_inner);
                unset($visibility);

                //--------------------------------
                $visibility = "none";
                if(property_exists($sub_specs, "minItems") && !empty($sub_specs->minItems))
                {
                    $visibility = "";
                    $accordion_group_inner = str_replace(
                        "%PROPERTY_AMOUNT_MIN%",
                        $sub_specs->minItems,
                        $accordion_group_inner
                    );
                }

                $accordion_group_inner = str_replace("%VISIBILITY_PROPERTY_AMOUNT_MIN%", $visibility, $accordion_group_inner);
                unset($visibility);

                //--------------------------------
                $visibility = "none";
                if(property_exists($sub_specs, "maxItems") && !empty($sub_specs->maxItems))
                {
                    $visibility = "";
                    $accordion_group_inner = str_replace(
                        "%PROPERTY_AMOUNT_MAX%",
                        $sub_specs->maxItems,
                        $accordion_group_inner
                    );
                }

                $accordion_group_inner = str_replace("%VISIBILITY_PROPERTY_AMOUNT_MAX%", $visibility, $accordion_group_inner);
                unset($visibility);

                //--------------------------------
                $visibility = "none";
                if(property_exists($sub_specs, "items") &&
                    property_exists($sub_specs->items, "type") &&
                    !empty($sub_specs->items->type))
                {
                    $visibility = "";
                    $accordion_group_inner = str_replace(
                        "%PROPERTY_ITEM_TYPE%",
                        $sub_specs->items->type,
                        $accordion_group_inner
                    );
                }

                $accordion_group_inner = str_replace("%VISIBILITY_PROPERTY_ITEM_TYPE%", $visibility, $accordion_group_inner);
                unset($visibility);

                //--------------------------------
                $visibility = "none !important";
                if ( ! is_null($sub_accordion) )
                {
                    $visibility = "";
                    $accordion_group_inner = str_replace(
                        "%PROPERTY_NESTED_ELEMENTS%",
                        $sub_accordion->render(),
                        $accordion_group_inner
                    );
                }
                $accordion_group_inner = str_replace("%VISIBILITY_PROPERTY_NESTED_ELEMENTS%", $visibility, $accordion_group_inner);
                unset($visibility);
            }
            /*** end accordion group inner ***/

            $accordion_group = str_replace("{ACCORDION_GROUP_INNER}", $accordion_group_inner, $accordion_group);
            $accordion_group = str_replace("%PROPERTY_PATH%", $sub_id, $accordion_group);

            //--------------------------------
            // append the processed accordion group to the previous ones
            $accordion_groups .= $accordion_group;

        }

        // replace the template tag
        $html = str_replace("{ACCORDION_GROUPS}", $accordion_groups, $accordion_template);

        // replace some variable tags, we don't need to worry about the tags of
        // the sub accordions because they're already replaced at this point
        $html = str_replace("%SPECS_VERSION%", $this->specs_version, $html);
        $html = str_replace("%ACCORDION_ID%", $this->id, $html);

        return $html;
    }
}

$html = <<<HTML
    <p>
        It's highly recommended to use the explicit specified fields from the reference. If you need other fields additionally, please <a href="add-your-space">make a change request</a>. Or prefix custom fields with <code>ext_</code> to make it clear the field is not part of the documented API. Consumers are not obligated to interpret any custom fields
    </p>
HTML;

$page->addContent($html);

$tab_panes = "";
$versions = array();

// iterate over all the versions and create a tab for each
foreach(glob(SPECSDIR."*") as $file)
//$file = SPECSDIR . "13.json";
{
    $specs = file_get_contents($file);
    $specs = json_decode($specs);

    $specs = $specs->properties;
    $version = $specs->api->enum[0];

    // the version number is of the format 0.13 but we need the last digits
    $version = str_replace("0.", "", $version);

    // fill the versions array in order to create the version tabs after this loop
    $versions[] = $version;

    $acc = new Accordion($specs, $version, "root");

    $tab_pane = <<<TABPANE
            <div class="tab-pane" id="documentation-tab-$version">

                <!--
                <div style="margin-bottom: 20px;">
                    <a href="#documentation-ref-$version">Collapse all</a> | <a href="#documentation-ref-$version">Expand all</a>
                </div>
                -->

                %ACCORDION%

            </div>
TABPANE;

    $accordion = "";

    foreach($specs as $property => $object)
    {
        $required = $object->required ? ', required' : '';
        $required_icon = $object->required ? 'icon-exclamation-sign'/*'icon-star'*/ : 'icon-exclamation-sign-white';

        $description = "";
        if(property_exists($object, "description"))
            $description = $object->description;

        $accordion .= <<<ACCORDIONGROUP

            <div class="accordion-group">

                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#documentation-ref-$version" href="#documentation-ref-$version-$property">
                        <i class="$required_icon">&nbsp;</i>$property ($object->type)
                    </a>
                </div>

                <div id="documentation-ref-$version-$property" class="accordion-body collapse in">
                    <div class="accordion-inner">
                        $description
                    </div>
                </div>

            </div>
ACCORDIONGROUP;
    }

    $tab_pane = str_replace("%ACCORDION%", $acc->render(), $tab_pane);
    $tab_panes .= $tab_pane;
}

$html = '<div class="tab-content">';
$html .= '    <ul class="nav nav-tabs" id="documentation-specs-tab">';

sort($versions);
foreach($versions as $version)
{
    $html .= "    <li><a href=\"#documentation-tab-$version\">$version</a></li>";
}

$html .= "   </ul>";
$html .= "   $tab_panes";
$html .= "</div> <!-- end tab content -->";

$page->addContent($html);
