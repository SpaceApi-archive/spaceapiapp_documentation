<?php

//$page->addContent("<h2>Minimal Example</h2>");

$html = <<<HTML

        <pre class="spaceapi-box documentation-example-minimal"><code>{
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
    "issue_report_channels": [
        "twitter"
    ],
    "state": {
        "open": true
    }
}
</code></pre>

        <pre class="spaceapi-box documentation-example-cache-projects"><code>{
    /* put here all the fields from the minimal example */
    ...
    "projects": [
        "http://github.com/spaceapi",
        "http://wiki.example.com"
    ],
    "cache": {
        "schedule": "m.02"
    }
}
</code></pre>

        <pre class="spaceapi-box documentation-example-feeds"><code>{
    /* put here all the fields from the minimal example */
    ...
    "feeds": {
        "blog": {
            "type": "application/rss+xml",
            "url": "https://example.com/feed/"
        },
        "calendar": {
            "type": "text/calendar",
            "url": "https://example.com/events.ics"
        }
    }
}
</code></pre>

        <pre class="spaceapi-box documentation-example-radio-show"><code>{
    /* put here all the fields from the minimal example */
    ...
    "radio_show": [
        {
            "name": "Signal",
            "url": "http://signal.hackerspaces.org:8090/signal.mp3",
            "type": "mp3",
            "start": "2013-06-10T12:00+02:00",
            "end": "2013-06-10T13:00+02:00"
        },
        {
            "name": "Random",
            "url": "http://85.214.64.213:8060/;",
            "type": "mp3",
            "start": "2013-06-13T09:00+02:00",
            "end": "2013-06-13T12:00+02:00"
        }
    ]
}
</code></pre>

<pre class="spaceapi-box documentation-example-sensors"><code>{
    /* put here all the fields from the minimal example */
    ...
    "sensors" : {
        "temperature": [
            {
                "value" : 3,
                "unit" : "°C",
                "location" : "Outside",
                "name" : "Roof"
            },
            {
                "value" : 3,
                "unit" : "°C",
                "location" : "Room 1",
                "name" : "Corner 1"
            },
            {
                "value" : 3,
                "unit" : "°C",
                "location" : "Room 1",
                "name" : "Corner 2"
            }
        ],
        "ext_spiff_diff" : [ /* unofficial extension */
            {
                "value" : 32,
                "unit" : "spdf",
                "location" : "locker1",
                "name" : "asdf"
            }
        ]
    }
}
</code></pre>
HTML;

$page->addContent($html);
unset($html);