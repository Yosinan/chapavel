# Chapavel

A custom Laravel package for seamless integration with [Chapa](https://chapa.co/) payment gateway. Provides a clean API for initializing payments, verifying transactions, and handling secure webhooks.

---

## Features

- **Easy Payment Initialization**: Start payments with minimal code.
- **Webhook Verification**: Securely verify Chapa webhooks using HMAC SHA256.
- **Transaction Status**: Query and update payment status.
- **Idempotent Webhook Handling**: Prevents duplicate processing on retries.

---

## Installation

1. **Require the package**:

    ```bash
    composer require yosinan/chapavel
    ```

2. **Publish the configuration file**:

    ```bash
    php artisan vendor:publish --tag=chapa-config
    ```

3. **Add your Chapa keys to `.env`**:

    ```
    CHAPA_SECRET_KEY=your_secret_key
    CHAPA_PUBLIC_KEY=your_public_key
    CHAPA_BASE_URL=https://api.chapa.co
    FRONTEND_URL=http://localhost:3000
    ```

---

## Usage

### Initialize Payment

```php
use Yosinan\Chapavel\ChapaClient;

// Example: $payment is your payment model or array
$payload = [
    'amount'       => (string) $payment->amount,
    'currency'     => 'ETB',
    'email'        => $payment->email,
    'first_name'   => $payment->name,
    'tx_ref'       => $payment->tx_ref,
    'callback_url' => route('payment.return') . '?tx_ref=' . $payment->tx_ref,
    'return_url'   => route('payment.return') . '?tx_ref=' . $payment->tx_ref,
    'customization'=> [
        'title' => 'Order Payment',
        'description' => 'Checkout'
    ],
];

$chapa = new ChapaClient();
$response = $chapa->initialize($payload);
// $response['data']['checkout_url'] contains the payment URL
```

### Verify Payment

```php
$response = $chapa->verify($txRef);
// $response contains transaction details
```

### Webhook Verification

```php
use Yosinan\Chapavel\WebhookVerifier;

$isValid = WebhookVerifier::isValid($rawBody, $sig1, $sig2, $secretKey);
if (!$isValid) {
    // Handle invalid signature
}
```

- Accepts both `Chapa-Signature` and `x-chapa-signature` headers.
- Uses HMAC SHA256 with your secret key.

---

## License

MIT © Yoseph Zewdu

---

## Support

For issues or feature requests, open an issue.