<?php
/**
 * @license GPL, or GNU General Public License, version 3
 * @license http://opensource.org/licenses/GPL-3.0
 * @see README.md how to contribute to this project
 */

namespace UniversityofHelsinki\MECE\tests;

use PHPUnit_Framework_TestCase;
use UniversityofHelsinki\MECE\Message;
use UniversityofHelsinki\MECE\MultilingualStringValue;
use UniversityofHelsinki\MECE\NotificationMessage;

/**
 * Class VersionTraitTest
 * @package UniversityofHelsinki\MECE\tests
 * @author Mikael Kundert <mikael.kundert@wunderkraut.com>
 */
class VersionTraitTest extends PHPUnit_Framework_TestCase {
  private $expectedVersion = '1.0.x-dev';

  /**
   * @covers Message::getVersion
   */
  public function testVersionTraitFromMessage() {
    $class = new Message([], 'source');
    $this->assertEquals($this->expectedVersion, $class->getVersion());
  }

  /**
   * @covers Notification::getVersion
   */
  public function testVersionTraitFromNotification() {
    $class = new NotificationMessage([], 'source');
    $this->assertEquals($this->expectedVersion, $class->getVersion());
  }

  /**
   * @covers MultilingualStringValue::getVersion
   */
  public function testVersionTraitFromMultilingualStringValue() {
    $class = new MultilingualStringValue([], 'source');
    $this->assertEquals($this->expectedVersion, $class->getVersion());
  }
}
