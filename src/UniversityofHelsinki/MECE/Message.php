<?php
/**
 * @license GPL, or GNU General Public License, version 3
 * @license http://opensource.org/licenses/GPL-3.0
 * @see README.md how to contribute to this project
 */

namespace UniversityofHelsinki\MECE;

use DateTime;
use InvalidArgumentException;
use LogicException;

/**
 * Class Message
 *
 * Provides an class for setting and dumping the message that is designed to be
 * sent to MECE Service.
 *
 * @author Mikael Kundert <mikael.kundert@wunderkraut.com>
 */
class Message {

  /*
   * Following list of properties are part of the service message.
   */

  /**
   * @var array
   */
  protected $recipients = array();

  /**
   * @var string
   */
  protected $priority = '';

  /**
   * @var string
   */
  protected $source = '';

  /**
   * @var string
   */
  protected $sourceId = '';

  /*
   * Following list of properties are for this class implementation.
   */

  /**
   * @var array
   */
  protected $supportedLanguages = array();

  /**
   * Class constructor for Message.
   * @param array $recipients
   * @param string $source
   * @param array $options
   *   'priority':           String value for priority. Optional (default: '1').
   *   'supportedLanguages': List of languages to support. Optional (default:
   *                         'fi', 'en', 'sv')
   */
  public function __construct(array $recipients, $source, array $options = array()) {

    // Set recipients
    $this->setRecipients($recipients);

    // Check source and set it
    if (!is_string($source)) {
      throw new InvalidArgumentException('Source must be an string.');
    }
    $this->setSource($source);

    // Priority and supported langauges can be set from options.
    $options['priority'] = !empty($options['priority']) ? $options['priority'] : '1';
    $this->setPriority($options['priority']);
    $options['supportedLanguages'] = (isset($options['supportedLanguages']) && is_array($options['supportedLanguages'])) ? $options['supportedLanguages'] : array('fi', 'en', 'sv');
    $this->supportedLanguages = $options['supportedLanguages'];

  }

  /**
   * Sets recipients to given recipients.
   * @param array $recipients
   * @return void
   */
  public function setRecipients(array $recipients) {
    $this->recipients = $recipients;
  }

  /**
   * Appends an recipient to list of recipients.
   * @param string $recipient
   * @return void
   * @throws InvalidArgumentException
   */
  public function appendRecipient($recipient) {
    if (!is_string($recipient)) {
      throw new InvalidArgumentException('Recipient argument must be string.');
    }
    $this->recipients[] = $recipient;
  }

  /**
   * Returns list of recipients.
   * @return array
   */
  public function getRecipients() {
    return $this->recipients;
  }

  /**
   * Setter method for setting priority.
   * @param string $priority
   * @return void
   */
  public function setPriority($priority) {
    $this->setStringProperty($priority, 'priority');
  }

  /**
   * Getter method for getting priority.
   * @return string
   */
  public function getPriority() {
    return $this->priority;
  }

  /**
   * Setter method for setting source.
   * @param string $source
   * @return void
   */
  public function setSource($source) {
    $this->setStringProperty($source, 'source');
  }

  /**
   * Getter method for getting source.
   * @return string
   */
  public function getSource() {
    return $this->source;
  }

  /**
   * Setter method for setting source id.
   * @param string $sourceId
   * @return void
   */
  public function setSourceId($sourceId) {
    $this->setStringProperty($sourceId, 'sourceId');
  }

  /**
   * Getter method for getting source id.
   * @return string
   */
  public function getSourceId() {
    return $this->sourceId;
  }

  /**
   * An internal private string setter method that handles type validation.
   * @param string $value
   * @param string $property
   * @throws LogicException
   * @throws InvalidArgumentException
   */
  protected function setStringProperty($value, $property) {

    // Check that given $property is string
    if (!is_string($property)) {
      throw new InvalidArgumentException("Property should be type of string.");
    }

    // Check that property is found and its type of string
    if (!isset($this->{$property}) || (isset($this->{$property}) && !is_string($this->{$property}))) {
      throw new LogicException("There is no such string property as '$property'");
    }

    // Check that given string value is string
    if (!is_string($value)) {
      throw new InvalidArgumentException("Given value type '".gettype($value)."' for '$property' property is not a string.");
    }

    $this->{$property} = $value;
  }

}
