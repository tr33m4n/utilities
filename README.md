# utilities
A basic set of utility classes for use in other modules.

## `\tr33m4n\Utilities\Registry`
A simple statically accessed registry class

- `Registry::set` Set an item in the registry
- `Registry::get` Get an item from the registry
- `Registry::has` Check if an item exists in the registry
- `Registry::setConfigProvider` A special case for globally setting a config provider
- `Registry::getConfigProvider` A special case for getting a globally set config provider

## `\tr33m4n\Utilities\Data\DataObject`
A simple, iterable object for storing data

- `$dataObject->set` Set an item to the data object
- `$dataObject->get` Get an item from the data object
- `$dataObject->has` Check if an item exists in the data object
- `$dataObject->setAll` Bulk set items to the data object
- `$dataObject->getAll` Get all items set against the data object

An array of items can also be passed to populate the data object when instantiating. The object can also be iterated, for example:
```php
<?php

$dataObject = new DataObject([
    'test1' => 'test1',
    'test2' => 'test2'
]);

foreach ($dataObject as $key => $value) {
    echo "$key : $value\n";
}
```

## `\tr33m4n\Utilities\Config\ConfigProvider`
A simple configuration parser and provider. This class inherits all the functionality of a data object, however when the class is instantiated
it will aggregate config files into a nested configuration collection structure. Config paths can be passed to the constructor or
defined by setting the global constant `ROOT_CONFIG_PATH`. The `ROOT_CONFIG_PATH` path will always take priority when parsing the files, so if you have a config file with the same name
in the constructor path and the global path, the global will overwrite it. A typical config file might look like:
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
These values can then be accessed using the data objects `get` method. For example:
```php
<?php

$deepValue = $this->configProvider->get('test1')
    ->get('test3')
    ->get('test3')
    ->get('test2')
    ->get('deep_value');
```
For convenience a global helper function `config` is also provided which allows access to a single instance of the config provider (set in the registry). Accessing the `deep_value` using this would look like:
```php
<?php

$deepValue = config('test1')->get('test3')
    ->get('test3')
    ->get('test2')
    ->get('deep_value');
```