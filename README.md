# MECE PHP Library
Offers an PHP library for preparing an message for Message Center (MECE)
maintained by Center for Information Technology (University of Helsinki).

License: [GPLv3](LICENSE.txt)

## Installation

Install composer

```bash
curl -sS https://getcomposer.org/installer | php
```

Get package to your directory

```bash
php composer.phar require universityofhelsinki/mece:v1.1.1
```

## Usage with Guzzle

```php
use UniversityofHelsinki\MECE\NotificationMessage;
use UniversityofHelsinki\MECE\MultilingualStringValue;
use GuzzleHttp\Client;

$recipients = ['matti', 'liisa'];
$source = 'serviceXY';
$message = new NotificationMessage($recipients, $source);

// Set heading for all three default languages
$heading = new MultilingualStringValue();
$heading->setValue('Viesti', 'fi');
$heading->setValue('Message', 'en');
$heading->setValue('Meddelande', 'sv');
$message->setHeading($heading);

$client = new Client();
$host = 'https://www.example.com';
$response = $client->request('POST', $host, ['body' => $message->export()]);
if ($response->getStatusCode() == 200) {
  echo 'Done!';
}
```

## Projects that uses this library
* [University of Helsinki, MECE Drupal 7 module](https://github.com/UH-StudentServices/uh-mece/tree/7.x-1.x)

## Questions
Please post your question to doo-projekti@helsinki.fi
