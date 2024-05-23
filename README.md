<h1 align="center"> sdk-base </h1>

<p align="center"> .</p>


## Installing

```shell
$ composer require lihq1403/sdk-base -vvv
```

## Usage

作为 sdk 包的基础，提供了可更换的基础能力
- config
- exception
- logger
- cache
- client

## Contributing

```php
$config = [
    'sdk_name' => 'xxx',
    'exception_class' => BusinessException::class,
    'component' => [
        'logger' => new EchoLogger(),
        'cache' => new FileCache(),
        'client' => new GuzzleClient(),
    ],
];

$app = new SdkContainer($config);
```

## License

MIT