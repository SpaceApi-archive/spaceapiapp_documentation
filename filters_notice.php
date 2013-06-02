<?php

$html = <<<HTML
        <div class="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h3>Notice</h3>
            <p>
                The list shows filters created from all the Space API fields that are used by at least one hackerspace, and also the unofficial fields are considered.
            </p>
            <p>
                It's highly recommended to use the explicit specified fields from the reference. If you need other fields additionally, please <a href="add-your-space">make a change request</a>.
            </p>
        </div>
HTML;

$page->addContent($html);
unset($html);