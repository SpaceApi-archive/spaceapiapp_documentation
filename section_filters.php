<?php
class Filterkeys
{
    private $filterkeys_sorted_by_space = array();
    private $spaces_sorted_by_key = array();

    //
    private $filterkeys_of_sensor_type = array();

    // every filter which is not a sensor type
    private $filterkeys_of_other_type = array();

    function __construct()
    {
        global $page;
        $filterkeys = $page->process_backend_route("filterkeys", "get");
        $this->filterkeys = json_decode($filterkeys, true);

        $this->spaces_sorted_by_key = $this->filterkeys[1];
        $this->filterkeys_sorted_by_space = array_keys($this->filterkeys[0]);

        sort($this->filterkeys_sorted_by_space);

        foreach(array_keys($this->filterkeys[1]) as $filter)
        {
            if(preg_match("/^sensors\./", $filter))
                $this->filterkeys_of_sensor_type[] = $filter;
            else
                $this->filterkeys_of_other_type[] = $filter;

            sort($this->filterkeys_of_sensor_type);
            sort($this->filterkeys_of_other_type);
        }
    }

    public function render_overview()
    {
        $links = array();
        for($i=0; $i<count($this->filterkeys_of_other_type); $i++)
        {
            $filter = $this->filterkeys_of_other_type[$i];

            // assemble the tooltip text which is a list of hackerspaces that
            // are using the json field that is currently being hovered
            $spaces = '<ol>';
            foreach($this->spaces_sorted_by_key[$filter] as $space)
            {
                $spaces .= "<li>$space</li>";
            }
            $spaces .= "</ol>";

            $spaces = "<div style='font-weight: bold; font-size: larger; color: white; margin-bottom: 3px;'>Supported by</div>";
            $spaces .= join(', ', $this->spaces_sorted_by_key[$filter]);

            $link = new stdClass();
            $link->href = "#";
            $link->data = array();
            $link->data["toggle"] = "tooltip";
            $link->data["placement"] = "left";
            $link->title = $spaces;
            /*
            $link->class = array();
            $link->class[] = "filter";
            */
            $link->label = $filter;

            $links[] = $link;
        }

        return make_columns(
            $links, /* the data to be split into multiple columns */
            3, /* amount of columns */
            array(
                'list_type' => 'ol',
                'row_id' => 'filters-overview',
                'before_text' => "In the following is a list of available filters. The sensor filters are listed in the next section.",
                'list_class' => 'list_filters',
            )
        );
    }

    public function render_overview_sensors()
    {
        $links = array();

        // iterate over the filters
        for($i=0; $i<count($this->filterkeys_of_sensor_type); $i++)
        {
            $filter = $this->filterkeys_of_sensor_type[$i];

            // assemble the tooltip text which is a list of hackerspaces that
            // are using the json field that is currently being hovered
            $spaces = "<ol>";
            foreach($this->spaces_sorted_by_key[$filter] as $space)
            {
                $spaces .= "<li>$space</li>";
            }
            $spaces .= "</ol>";

            /*
            // this breaks the website because of the quotes in the title text
            $spaces =   make_columns(
                $this->spaces_sorted_by_key[$filter],
                2,
                array(
                    'list_type' => 'ol',
                    'row_id' => 'space_list',
                    'list_class' => 'list_spaces',
                )
            );
            */

            $spaces = "<div style='font-weight: bold; font-size: larger; color: white; margin-bottom: 3px;'>Supported by</div>";
            $spaces .= join(', ', $this->spaces_sorted_by_key[$filter]);

            $link = new stdClass();
            $link->href = "#";
            $link->data = array();
            $link->data["toggle"] = "tooltip";
            $link->data["placement"] = "left";
            $link->title = $spaces;
            /*
            $link->class = array();
            $link->class[] = "filter";
            */
            $link->label = $filter;

            $links[] = $link;
        }

        return make_columns(
            $links, /* the data to be split into multiple columns */
            2, /* amount of columns */
            array(
                'list_type' => 'ol',
                'row_id' => 'filters-sensors',
                'before_text' => "In the following is a list of implemented sensor filters.",
                'list_class' => 'list_filters',
            )
        );
    }
}

$filters = new Filterkeys();

$page->addContent("<h2>Filters - Overview</h2>");
include("$app_dir/filters_notice.php");
$page->addContent($filters->render_overview());
$page->addContent("<h2>Filters - Sensors</h2>");
$page->addContent($filters->render_overview_sensors());