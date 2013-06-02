<?php

$html = <<<HTML
        <div class="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h3>Notice</h3>
            <p>
                If you add custom fields prefix the field name with <code>ext_</code> to make it clear the field is not part of the documented API. Consumers are not obligated to interpret any custom fields.
            </p>
        </div>
HTML;

$page->addContent($html);
unset($html);