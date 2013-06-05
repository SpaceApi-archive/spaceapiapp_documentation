<?php

$html = <<<HTML
        <div class="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h3>Notice</h3>
            <p>
                It's highly recommended to use the explicit specified fields from the reference. If you need other fields additionally, please <a href="add-your-space">make a change request</a>. Or prefix custom fields with <code>ext_</code> to make it clear the field is not part of the documented API. Consumers are not obligated to interpret any custom fields
            </p>
        </div>
HTML;

$page->addContent($html);
unset($html);