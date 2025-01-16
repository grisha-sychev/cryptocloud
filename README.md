# CryptoCloud PHP SDK Documentation

## Overview
The `CryptoCloud` PHP SDK provides (https://cryptocloud.plus) a convenient way to interact with the CryptoCloud API, enabling users to create, manage, and retrieve information about invoices, balances, and statistics for cryptocurrency transactions.

## Installation
Install the SDK using Composer:
```bash
composer require cryptocloud/cryptocloud
```

## Initialization
To use the SDK, initialize the `CryptoCloud` class with your API token:

```php
use CryptoCloud\CryptoCloud;

$cryptoCloud = new CryptoCloud('your-api-token');
```

### Optional Parameters
- `verify` (boolean): Enables or disables SSL verification. Default is `true`.

```php
$cryptoCloud = new CryptoCloud('your-api-token', false);
```

## Methods

### `create($shopId, $amount, $currency = 'USD', $order_id = '', $email = '')`
Creates a new invoice.

#### Parameters:
- `shopId` (string): The ID of the shop.
- `amount` (float): The amount for the invoice.
- `currency` (string): Currency code (default: `USD`).
- `order_id` (string): Optional order ID.
- `email` (string): Optional email address.

#### Returns:
- JSON response from the API.

### `cancel($uuid)`
Cancels an existing invoice.

#### Parameters:
- `uuid` (string): The UUID of the invoice to cancel.

#### Returns:
- JSON response from the API.

### `list($start, $end, $offset = 0, $limit = 10)`
Lists invoices within a specific date range.

#### Parameters:
- `start` (string): Start date (YYYY-MM-DD).
- `end` (string): End date (YYYY-MM-DD).
- `offset` (int): Pagination offset (default: `0`).
- `limit` (int): Number of invoices to retrieve (default: `10`).

#### Returns:
- JSON response from the API.

### `info($uuids)`
Retrieves information about specific invoices.

#### Parameters:
- `uuids` (array): An array of invoice UUIDs.

#### Returns:
- JSON response from the API.

### `balance()`
Retrieves the balance of the merchant's wallet.

#### Returns:
- JSON response from the API.

### `statistics($start, $end)`
Retrieves transaction statistics for a given period.

#### Parameters:
- `start` (string): Start date (YYYY-MM-DD).
- `end` (string): End date (YYYY-MM-DD).

#### Returns:
- JSON response from the API.

### `static($shopId, $currency, $identify)`
Creates a static invoice.

#### Parameters:
- `shopId` (string): The ID of the shop.
- `currency` (string): Currency code.
- `identify` (string): Identifier for the static invoice.

#### Returns:
- JSON response from the API.

### `postback()`
Handles postback data from CryptoCloud.

#### Returns:
- An associative array containing postback data:
  - `status`: The status of the transaction.
  - `invoice_id`: The invoice ID.
  - `amount_crypto`: The amount in cryptocurrency.
  - `currency`: The currency code.
  - `order_id`: The order ID.
  - `token`: The postback token.

## Error Handling
The SDK uses Guzzle HTTP client to handle requests. In case of an error, the `request` method catches `RequestException` and returns the error message.

## Example Usage
```php
use CryptoCloud\CryptoCloud;

$cryptoCloud = new CryptoCloud('your-api-token');

// Create an invoice
$response = $cryptoCloud->create('shop123', 100.00);

// Cancel an invoice
$cancelResponse = $cryptoCloud->cancel('uuid-12345');

// List invoices
$listResponse = $cryptoCloud->list('2025-01-01', '2025-01-31');

// Get wallet balance
$balanceResponse = $cryptoCloud->balance();

// Get statistics
$statsResponse = $cryptoCloud->statistics('2025-01-01', '2025-01-31');
```

## License
This SDK is released under the MIT License. See the LICENSE file for more details.

