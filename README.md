# MECE PHP Library
Offers an PHP library for preparing an message for University of Helsinki
Message Center.

License: [GPLv3](LICENSE.txt)

## Usage with Guzzle

```php
use UniversityofHelsinki\MECE\Notification;
use GuzzleHttp\Client;

$recipients = ['matti', 'liisa'];
$source = 'serviceXY';
$message = new Notification($recipients, $source);

$client = new Client();
$host = 'https://www.example.com';
$response = $client->request('POST', $host, ['body' => $message->export()]);
if ($response->getStatusCode() == 200) {
  echo 'Done!';
}
```