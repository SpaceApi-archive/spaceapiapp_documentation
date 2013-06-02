<?php

$html = <<<HTML
    <h2>Minimal Example</h2>

        <pre class="spaceapi-box documentation-example"><code>{
    "api": "0.13",
    "space": "Slopspace",
    "logo": "http://your-space.org/img/logo.png",
    "url": "http://your-space.org",
    "location": {
        "address": "Ulmer Strasse 255, 70327 Stuttgart, Germany",
        "lon": 9.236,
        "lat": 48.777
    },
    "contact": {
        "twitter": "@spaceapi"
    },
    "issue-report-channels": [
        "twitter"
    ],
    "state": {
        "open": true
    }
}
</code></pre>
HTML;

$page->addContent($html);
unset($html);