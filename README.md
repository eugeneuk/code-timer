# CodeTimer
Simple PHP library to measuring code execution time with memory usage.

## Usage

```php
use Requtize\CodeTimer\CodeTimer;

$timer = new CodeTimer;

// Sets category color which is displayed on some timeline preview.
$timer->setCategoryColor('handle-request', 'green');
$timer->setCategoryColor('controller', 'red');


// Starts and stops recording the request.
// Between those thow methods call, all rest methods should be called.
$timer->begin();
$timer->end();

// Opens and closes section.
$timer->openSection('section-name', 'handle-request');
$timer->closeSection('section-name');

// Starts and stops some event to measure its duration and memory.
// It will be attached to current opened section
$timer->start('some event', 'controller');
$timer->stop('some event');


// Exports collected data to predefined formats.
$data = $timer->exportToArray();
$data = $timer->exportToJson();
```

## Result array

```php
$data = [
    'total-time' => [
        'start'    => 15623363564,
        'end'      => 15623373668,
        'duration' => 10104
    ],
    'sections' => [
        [
            'name'     => 'section-name',
            'start'    => 10,
            'duration' => 154,
            'memory'   => 17431234,
            'category' => 'handle-request',
            'category-color' => 'green'
        ],
        // Rest of sections...
    ],
    'stops' => [
        [
            'name'     => 'some event',
            'start'    => 20,
            'duration' => 30,
            'memory'   => 17431234,
            'category' => null,
            'category-color' => null,
            'section'  => 'section2'
        ],
        // Rest of stops...
    ]
]
```
