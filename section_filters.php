<?php
class Filterkeys
{
    private $filterkeys_sorted_by_space = array();
    private $filterkeys_of_sensor_type = array();

    // every filter which is not a sensor type
    private $filterkeys_of_other_type = array();

    function __construct()
    {
        global $page;
        $filterkeys = $page->process_backend_route("filterkeys", "get");
        $this->filterkeys = json_decode($filterkeys, true);

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
        return make_columns(
            $this->filterkeys_of_other_type, /* the data to be split into multiple columns */
            3, /* amount of columns */
            array(
                'list_type' => 'ol',
                'row_id' => 'filters-overview',
                'before_text' => "In the following is a list of available filters. The sensor filters are listed in the next section.",
            )
        );
    }

    public function render_overview_sensors()
    {
        return make_columns(
            $this->filterkeys_of_sensor_type, /* the data to be split into multiple columns */
            2, /* amount of columns */
            array(
                'list_type' => 'ol',
                'row_id' => 'filters-sensors',
                'before_text' => "In the following is a list of implemented sensor filters.",
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