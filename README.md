# nicehash-client
REST-api client for NiceHash

[![Latest Version](https://img.shields.io/github/release/andrederoos/nicehash-client.svg?style=flat-square)](https://github.com/andrederoos/nicehash-client/releases)
[![Build Status](https://img.shields.io/github/workflow/status/andrederoos/nicehash-client/CI?label=ci%20build&style=flat-square)](https://github.com/andrederoos/nicehash-client/actions?query=workflow%3ACI)

- Simple interface for executing messages for NiceHash REST api
- Abstracts away the underlying signing of requests

```php
$key = 'your-api-key';
$secret = 'your-api-secret';
$organisation = 'your-organisation-id';

$client = new \NiceHashClient\NiceHashClient($key, $secret, $organisation);
$message = new \NiceHashClient\message\MessageGetAccountBalance(\NiceHashClient\object\CryptoCurrency::BTC);
$response = $client->get($message->generateRequest());

echo $response->getBody();
```

## Help and docs

- [NiceHash REST api documentation](https://www.nicehash.com/docs/rest)
- [Getting your api key](https://github.com/nicehash/rest-clients-demo)

## Installing

The recommended way to install the client is through
[Composer](https://getcomposer.org/).

```bash
composer require andrederoos/nicehash-client
```
