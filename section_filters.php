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
                /*'before_text' => "In the following is a list of available filters. The sensor filters are listed in the next section.",*/
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
                /*'before_text' => "In the following is a list of implemented sensor filters.",*/
                'list_class' => 'list_filters',
            )
        );
    }
}

$filters = new Filterkeys();

//$page->addContent("<h2>Filters</h2>");

$html = <<<HTML
<p>
    The <a href="directory">directory</a> aggregates hackerspaces that provide an endpoint of their Space API implementation. The directory can then be loaded by web apps to do cool things such as rendering a map.
</p>

<p>
    As not every app needs every endpoint because some endpoints don't provide the fields that they're interested in, the endpoints can be filtered by appending <code>?filter=&lt;filter_key&gt;</code> or <code>?api=&lt;operator&gt;&lt;api_version&gt</code>.
</p>

<h3>Filter by API</h3>

<p>
Endpoints can also be filtered by their version with the <code>api</code> argument. However this cannot be used with <code>filter</code> together:
</p>

<p>
    <ul>
        <li><a href="http://%SITEURL%/directory.json?api=0.12" target="_blank">directory.json?api=0.12</a> (exact this version)</li>
        <li><a href="http://%SITEURL%/directory.json?api=!0.12" target="_blank">directory.json?api=!0.12</a> (all versions but not this one)</li>
        <li><a href="http://%SITEURL%/directory.json?api=<0.12" target="_blank">directory.json?api=<0.12</a> (older than this version)</li>
        <li><a href="http://%SITEURL%/directory.json?api=>0.12" target="_blank">directory.json?api=>0.12</a> (newer than this version)</li>
    </ul>
</p>

<p>
    <!--The filters can be combined as shown below.-->
    <!--
    <table>
        <tr>
            <th>Type</th><th>Scenario</th><th>Example</th>
        </tr>
        <tr>
            <td>Disjunction</td>
            <td>Get all the space JSONs which include the <code>contact</code> or <code>feeds</code> field.</td>
            <td><pre><code>or( contact , feeds )</code></pre>

                        Try <a href="http://%SITEURL%/directory.json?filter=or(contact,feeds)" target="_blank">directory.json?filter=or(contact,feeds)</a></td>
        </tr>
    </table>
    -->
</p>

<p>
HTML;

$page->addContent($html);

$page->addScript("/c/js/jquery-ui/jquery-ui-1.9.2/ui/jquery-ui.js", true);
$page->addStylesheet("c/js/jquery-ui/jquery-ui-1.9.2/themes/jquery-ui-bootstrap/jquery-ui-1.9.2.custom.css", true);

$html = <<<HTML

<h3>Filter by implemented keys</h3>

<table class="documentation-filters-example">
    <tr>
        <th>Example:</th><th>One filter</th>
    </tr>
    <tr>
        <td>Scenario:</td><td>Get all the endpoints which include the contact field.</td>
    </tr>
    <tr>
        <td>Expression:</td><td><code>contact</code></td>
    </tr>
    <tr>
        <td>Link:</td><td><a id="clicklink" href="http://%SITEURL%/directory.json?filter=contact" target="_blank">directory.json?filter=contact</a></td>
    </tr>
</table>

<table class="documentation-filters-example">
    <tr>
        <th>Example:</th><th>Disjunction</th>
    </tr>
    <tr>
        <td>Scenario:</td><td>Get all the endpoints which include the contact or feeds field.</td>
    </tr>
    <tr>
        <td>Expression:</td><td><code>or( contact , feeds )</code></td>
    </tr>
    <tr>
        <td>Link:</td><td><a href="http://%SITEURL%/directory.json?filter=or(contact,feeds)" target="_blank">directory.json?filter=or(contact,feeds)</a></td>
    </tr>
</table>

<table class="documentation-filters-example">
    <tr>
        <th>Example:</th><th>Conjunction</th>
    </tr>
    <tr>
        <td>Scenario:</td><td>Get all the endpoints which include the contact.irc and contact.phone field.</td>
    </tr>
    <tr>
        <td>Expression:</td><td><code>and( contact.irc , contact.phone )</code></td>
    </tr>
    <tr>
        <td>Link:</td><td><a href="http://%SITEURL%/directory.json?filter=and(contact.irc,contact.phone)" target="_blank">directory.json?filter=and(contact.irc,contact.phone)</a></td>
    </tr>
</table>

<table class="documentation-filters-example">
    <tr>
        <th>Example:</th><th>Mixed</th>
    </tr>
    <tr>
        <td>Scenario:</td><td>Get all the endpoints which have implemented contact and feeds and either sensors or stream.</td>
    </tr>
    <tr>
        <td>Expression:</td><td><code>and( contact , feeds , or( sensors , stream ) )</code></td>
    </tr>
    <tr>
        <td>Link:</td><td><a href="http://%SITEURL%/directory.json?filter=and(contact,feeds,or(sensors,stream))" target="_blank">directory.json?filter=and(contact,feeds,or(sensors,stream))</a></td>
    </tr>
</table>

<hr>




<!--
<blockquote>
<h3>1. Simple usage</h3>

<table>
    <tr>
        <td>Scenario:</td><td>Get all the endpoints which include the <code>contact</code> field.</td>
    </tr>
    <tr>
        <td>Expression:</td><td><code>contact</code></td>
    </tr>
    <tr>
        <td>Link:</td><td><a href="http://%SITEURL%/directory.json?filter=contact" target="_blank">directory.json?filter=contact</a></td>
    </tr>
</table>


<h3>2. Disjunction</h3>

<table>
    <tr>
        <td>Scenario:</td><td>Get all the endpoints which include the <code>contact</code> or <code>feeds</code> field.</td>
    </tr>
    <tr>
        <td>Expression:</td><td><code>or( contact , feeds )</code></td>
    </tr>
    <tr>
        <td>Link:</td><td><a href="http://%SITEURL%/directory.json?filter=or(contact,feeds)" target="_blank">directory.json?filter=or(contact,feeds)</a></td>
    </tr>
</table>


<h3>3. Conjunction</h3>


<table>
    <tr>
        <td>Scenario:</td><td>Get all the endpoints which include the <code>contact.irc</code> and <code>contact.phone</code> field.</td>
    </tr>
    <tr>
        <td>Expression:</td><td><code>and( contact.irc , contact.phone )</code></td>
    </tr>
    <tr>
        <td>Link:</td><td><a href="http://%SITEURL%/directory.json?filter=and(contact.irc,contact.phone)" target="_blank">directory.json?filter=and(contact.irc,contact.phone)</a></td>
    </tr>
</table>

<h3>4. Mixed</h3>

<table>
    <tr>
        <td style="vertical-align: top;">Scenario:</td><td>Get all the endpoints which have implemented

                                <code>contact</code> and <code>feeds</code> and either <code>sensors</code> or <code>stream</code>.

        </td>
    </tr>
    <tr>
        <td>Expression:</td><td><code>and( contact , feeds , or( sensors , stream ) )</code></td>
    </tr>
    <tr>
        <td>Link:</td><td><a href="http://%SITEURL%/directory.json?filter=and(contact,feeds,or(sensors,stream))" target="_blank">directory.json?filter=and(contact,feeds,or(sensors,stream))</a></td>
    </tr>
</table>
</blockquote>
-->

HTML;

$page->addContent($html);

//include("$app_dir/filters_notice.php");
$page->addContent("<h4>Filters - Overview</h4>");
$page->addContent($filters->render_overview());
$page->addContent("<hr>");
$page->addContent("<h4>Filters - Sensors</h4>");
$page->addContent($filters->render_overview_sensors());