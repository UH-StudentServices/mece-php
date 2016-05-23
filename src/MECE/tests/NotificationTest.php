<?php
/**
 * @license GPL, or GNU General Public License, version 3
 * @license http://opensource.org/licenses/GPL-3.0
 * @see README.md how to contribute to this project
 */

namespace UniversityofHelsinki\MECE\tests;

use UniversityofHelsinki\MECE\MultilingualStringValue;
use UniversityofHelsinki\MECE\Notification;
use DateTime;
use DateTimeZone;
use InvalidArgumentException;
use LogicException;

/**
 * Class MessageTest
 *
 * @coversDefaultClass \UniversityofHelsinki\MECE\Notification
 * @author Mikael Kundert <mikael.kundert@wunderkraut.com>
 */
class NotificationTest extends \PHPUnit_Framework_TestCase {

  protected $recipients = array('user1', 'user2', 'user3', 'user4');
  protected $source;

  public function setUp() {
    $this->source = $this->getRandomString();
  }

  protected function getRandomString() {
    return md5(uniqid(time(), TRUE));
  }

  /**
   * Test default datetime values.
   * @covers ::__construct
   * @covers ::getExpiration
   * @covers ::getDeadline
   * @covers ::getSubmitted
   */
  public function testDefaultDateTimeValues() {
    $defaultValue = new DateTime('now', new DateTimeZone('Etc/Zulu'));
    $class = new Notification($this->recipients, $this->source);
    $this->assertEquals($defaultValue, $class->getExpiration());
    $this->assertEquals($defaultValue, $class->getDeadline());
    $this->assertEquals($defaultValue, $class->getSubmitted());
  }

  /**
   * Test setting and getting datetime values.
   * @covers ::setExpiration
   * @covers ::getExpiration
   * @covers ::setDeadline
   * @covers ::getDeadline
   * @covers ::setSubmitted
   * @covers ::getSubmitted
   */
  public function testSetGetDateProperties() {
    $class = new Notification($this->recipients, $this->source);

    // Test setting expiration
    $newValue = new DateTime('+5 day', new DateTimeZone('Etc/Zulu'));
    $class->setExpiration($newValue);
    $this->assertEquals($newValue, $class->getExpiration());

    // Test setting deadline
    $newValue = new DateTime('+3 day', new DateTimeZone('Etc/Zulu'));
    $class->setDeadline($newValue);
    $this->assertEquals($newValue, $class->getDeadline());

    // Test setting submitted
    $newValue = new DateTime('+1 day', new DateTimeZone('Etc/Zulu'));
    $class->setSubmitted($newValue);
    $this->assertEquals($newValue, $class->getSubmitted());
  }

  /**
   * Expiration should not be able to be set with invalid timezone.
   * @covers ::setExpiration
   */
  public function testExpirationInvalidTimeZone() {
    $class = new Notification($this->recipients, $this->source);
    $incorrectValue = new DateTime('+5 day', new DateTimeZone('Europe/Helsinki'));
    $this->setExpectedException(LogicException::class, 'expiration DateTime value must be in timezone "Etc/Zulu"');
    $class->setExpiration($incorrectValue);
  }

  /**
   * Submitted should not be able to be set with invalid timezone.
   * @covers ::setSubmitted
   */
  public function testSubmittedInvalidTimeZone() {
    $class = new Notification($this->recipients, $this->source);
    $incorrectValue = new DateTime('-1 day', new DateTimeZone('Europe/Helsinki'));
    $this->setExpectedException(LogicException::class, 'submitted DateTime value must be in timezone "Etc/Zulu"');
    $class->setSubmitted($incorrectValue);
  }

  /**
   * Deadline should not be able to be set with invalid timezone.
   * @covers ::setDeadline
   */
  public function testDeadlineInvalidTimeZone() {
    $class = new Notification($this->recipients, $this->source);
    $class->setExpiration(new DateTime('+5 day', new DateTimeZone('Etc/Zulu')));
    $incorrectValue = new DateTime('+3 day', new DateTimeZone('Europe/Helsinki'));
    $this->setExpectedException(LogicException::class, 'deadline DateTime value must be in timezone "Etc/Zulu"');
    $class->setDeadline($incorrectValue);
  }

  /**
   * Expiration should not be able to set before submitted.
   * @covers ::setExpiration
   */
  public function testInvalidExpirationDateTimeBeforeSubmitted() {
    $class = new Notification($this->recipients, $this->source);
    $newInvalidValue = new DateTime('-1 day', new DateTimeZone('Etc/Zulu'));
    $this->setExpectedException(LogicException::class, 'Expiration can not be before submitted.');
    $class->setExpiration($newInvalidValue);
  }

  /**
   * Expiration should not be able to set before deadline.
   * @covers ::setExpiration
   * @covers ::setDeadline
   */
  public function testInvalidExpirationDateTimeBeforeDeadline() {
    $class = new Notification($this->recipients, $this->source);
    $class->setExpiration(new DateTime('+3 day', new DateTimeZone('Etc/Zulu')));
    $class->setDeadline(new DateTime('+2 day', new DateTimeZone('Etc/Zulu')));

    $newInvalidValue = new DateTime('+1 day', new DateTimeZone('Etc/Zulu'));
    $this->setExpectedException(LogicException::class, 'Expiration can not be before deadline.');
    $class->setExpiration($newInvalidValue);
  }

  /**
   * Submitted should not be able to be set after expiration.
   * @covers ::setSubmitted
   */
  public function testInvalidSubmittedDateTimeAfterExpiration() {
    $class = new Notification($this->recipients, $this->source);
    $newInvalidValue = new DateTime('+1 day', new DateTimeZone('Etc/Zulu'));
    $this->setExpectedException(LogicException::class, 'Submitted can not be after expiration.');
    $class->setSubmitted($newInvalidValue);
  }

  /**
   * Deadline should not be able to be set after expiration.
   * @covers ::setDeadline
   */
  public function testInvalidDeadlineDateTimeAfterExpiration() {
    $class = new Notification($this->recipients, $this->source);
    $newInvalidValue = new DateTime('+1 day', new DateTimeZone('Etc/Zulu'));
    $this->setExpectedException(LogicException::class, 'Deadline can not be after expiration.');
    $class->setDeadline($newInvalidValue);
  }

  /**
   * Deadline should be able to be set before submitted.
   * @covers ::setDeadline
   * @covers ::getDeadline
   */
  public function testValidDeadlineDateTimeBeforeSubmitted() {
    $class = new Notification($this->recipients, $this->source);
    $newValue = new DateTime('-1 day', new DateTimeZone('Etc/Zulu'));
    $class->setDeadline($newValue);
    $this->assertEquals($newValue, $class->getDeadline());
  }

  /**
   * Multilingual string property heading should be able to set and get.
   * @covers ::setHeading
   * @covers ::getHeading
   */
  public function testSetGetHeading() {
    $class = new Notification($this->recipients, $this->source);
    $newValue = new MultilingualStringValue();
    $newValue->setValue($this->getRandomString(), 'fi');
    $newValue->setValue($this->getRandomString(), 'en');
    $newValue->setValue($this->getRandomString(), 'sv');
    $class->setHeading($newValue);
    $this->assertEquals($newValue, $class->getHeading());
  }

  /**
   * Multilingual string property linkText should be able to set and get.
   * @covers ::setLinkText
   * @covers ::getLinkText
   */
  public function testSetGetLinkText() {
    $class = new Notification($this->recipients, $this->source);
    $newValue = new MultilingualStringValue();
    $newValue->setValue($this->getRandomString(), 'fi');
    $newValue->setValue($this->getRandomString(), 'en');
    $newValue->setValue($this->getRandomString(), 'sv');
    $class->setLinkText($newValue);
    $this->assertEquals($newValue, $class->getLinkText());
  }

  /**
   * Multilingual string property link should be able to set and get.
   * @covers ::setLink
   * @covers ::getLink
   */
  public function testSetGetLink() {
    $class = new Notification($this->recipients, $this->source);
    $newValue = new MultilingualStringValue();
    $newValue->setValue($this->getRandomString(), 'fi');
    $newValue->setValue($this->getRandomString(), 'en');
    $newValue->setValue($this->getRandomString(), 'sv');
    $class->setLink($newValue);
    $this->assertEquals($newValue, $class->getLink());
  }

  /**
   * Multilingual string property message should be able to set and get.
   * @covers ::setMessage
   * @covers ::getMessage
   */
  public function testSetGetMessage() {
    $class = new Notification($this->recipients, $this->source);
    $newValue = new MultilingualStringValue();
    $newValue->setValue($this->getRandomString(), 'fi');
    $newValue->setValue($this->getRandomString(), 'en');
    $newValue->setValue($this->getRandomString(), 'sv');
    $class->setMessage($newValue);
    $this->assertEquals($newValue, $class->getMessage());
  }

  /**
   * Text property avatarImageUrl should be able to set and get the value.
   * @covers ::setAvatarImageUrl
   * @covers ::getAvatarImageUrl
   */
  public function testSetGetAvatarImageUrl() {
    $class = new Notification($this->recipients, $this->source);
    $newValue = $this->getRandomString();
    $class->setAvatarImageUrl($newValue);
    $this->assertEquals($newValue, $class->getAvatarImageUrl());
  }

  /**
   * AvatarImageUrl should be always an string.
   * @covers ::setAvatarImageUrl
   */
  public function testInvalidAvatarImageUrl() {
    $class = new Notification($this->recipients, $this->source);
    $this->setExpectedException(InvalidArgumentException::class, "Given value type 'boolean' for 'avatarImageUrl' property is not a string.");
    $class->setAvatarImageUrl(TRUE);
  }

  /**
   * Export should be able to export and JSON formatted object.
   * @covers ::setSubmitted
   * @covers ::setDeadline
   * @covers ::setExpiration
   * @covers ::setSourceId
   * @covers ::setAvatarImageUrl
   * @covers ::setHeading
   * @covers ::setMessage
   * @covers ::setLinkText
   * @covers ::setLink
   * @covers ::export
   */
  public function testExport() {
    $class = new Notification($this->recipients, 'John Doe');
    $defaultTimezone = new DateTimeZone('Etc/Zulu');
    $class->setSubmitted(new DateTime('2016-01-24 11:00', $defaultTimezone));
    $class->setDeadline(new DateTime('2016-01-24 13:00', $defaultTimezone));
    $class->setExpiration(new DateTime('2016-01-24 15:00', $defaultTimezone));
    $class->setSourceId('ABC12345');
    $class->setAvatarImageUrl('https://www.example.com/avatarXy.jpg');

    // Heading
    $heading = new MultilingualStringValue();
    $heading->setValues(array('fi' => 'Heading FI', 'en' => 'Heading EN', 'sv' => 'Heading SV'));
    $class->setHeading($heading);

    // Message
    $message = new MultilingualStringValue();
    $message->setValues(array('fi' => 'Message FI', 'en' => 'Message EN', 'sv' => 'Message SV'));
    $class->setMessage($message);

    // LinkText
    $linkText = new MultilingualStringValue();
    $linkText->setValues(array('fi' => 'LinkText FI', 'en' => 'LinkText EN', 'sv' => 'LinkText SV'));
    $class->setLinkText($linkText);

    // Link
    $link = new MultilingualStringValue();
    $link->setValues(array('fi' => 'http://www.example.com/fi', 'en' => 'http://www.example.com/en', 'sv' => 'http://www.example.com/sv'));
    $class->setLink($link);

    // Now finally assert
    $expectedJSON = '{"recipients":["user1","user2","user3","user4"],"priority":"1","deadline":"2016-01-24T13:00:00Z","expiration":"2016-01-24T15:00:00Z","submitted":"2016-01-24T11:00:00Z","source":"John Doe","sourceId":"ABC12345","headingFI":"Heading FI","headingEN":"Heading EN","headingSV":"Heading SV","heading":"Heading FI","messageFI":"Message FI","messageEN":"Message EN","messageSV":"Message SV","message":"Message FI","linkTextFI":"LinkText FI","linkTextEN":"LinkText EN","linkTextSV":"LinkText SV","linkText":"LinkText FI","linkFI":"http:\/\/www.example.com\/fi","linkEN":"http:\/\/www.example.com\/en","linkSV":"http:\/\/www.example.com\/sv","link":"http:\/\/www.example.com\/fi","avatarImageUrl":"https:\/\/www.example.com\/avatarXy.jpg"}';
    $this->assertEquals($expectedJSON, $class->export());
  }

}
