# utilities
A basic set of utility classes for use in other modules.

## `\tr33m4n\Utilities\Data\DataCollection`
A simple, iterable collection for storing data

- `$dataCollection->set` Set an item to the data collection
- `$dataCollection->get` Get an item from the data collection
- `$dataCollection->add` Add a key value pair array to the data collection
- `$dataCollection->has` Check if an item exists in the data collection
- `$dataCollection->setAll` Bulk set items to the data collection
- `$dataCollection->getAll` Get all items set against the data collection

An array of items can also be passed to populate the data collection when instantiating. The collection can also be iterated, for example:
```php
<?php

$dataCollection = new DataCollection([
    'test1' => 'test1',
    'test2' => 'test2'
]);

/**
 * Or 
 * 
 * $dataCollection = DataCollection::from([
 *      'test1' => 'test1',
 *      'test2' => 'test2'
 * ]);
 */

foreach ($dataCollection as $key => $value) {
    echo "$key : $value\n";
}
```

## `\tr33m4n\Utilities\Config\ConfigProvider`
A simple configuration parser and provider. This class inherits all the functionality of a data collection, however when the class is instantiated
it will aggregate config files into a nested configuration collection structure. Config paths can be passed to the constructor or
defined by setting the global constant `ROOT_CONFIG_PATH`. The `ROOT_CONFIG_PATH` path will always take priority when parsing the files, so if you have a config file with the same name
in the constructor path and the global path, the global will overwrite it.

The config provider comes bundled with a PHP file adapter `\tr33m4n\Utilities\Config\Adapter\PhpFileAdapter`. An alternative file adapter can be passed when instantiating the class.
 
A typical config file using the default adapter might look like:
```php
<?php
// Filename: test1.php

return [
    'test1' => 'test1',
    'test2' => 123,
    'test3' => [
        'test1' => 'test1',
        'test2' => 123,
        'test3' => [
            'test1' => 'test1',
            'test2' => [
                'test1' => 'deep_value'
            ],
            'test3' => [
                'test1' => 'test1'
            ]
        ]
    ]
];
```
When parsing this configuration structure, the filename will be used for the root array key and each array item will be converted to a new `\tr33m4n\Utilities\Config\ConfigCollection`, this might look like:
```php
[
    'test1' => new ConfigCollection([
        'test1' => 'test1',
        'test2' => 123,
        'test3' => new ConfigCollection([
            'test1' => 'test1',
            'test2' => 123,
            'test3' => new ConfigCollection([
                'test1' => 'test1',
                'test2' => new ConfigCollection([
                    'test1' => 'deep_value'
                ]),
                'test3' => new ConfigCollection([
                    'test1' => 'test1'
                ])
            ])
        ])
    ])
];
```
These values can then be accessed using the data collections `get` method. For example:
```php
<?php

$deepValue = $this->configProvider->get('test1')
    ->get('test3')
    ->get('test3')
    ->get('test2')
    ->get('test1');

// $deepValue = 'deep_value'
```
For convenience a global helper function `config` is also provided which allows access to a single instance of the config provider (set in the registry). Accessing the `deep_value` using this would look like:
```php
<?php

$deepValue = config('test1')->get('test3')
    ->get('test3')
    ->get('test2')
    ->get('test1');

// $deepValue = 'deep_value'
```