<?php
/**
 * @license GPL, or GNU General Public License, version 3
 * @license http://opensource.org/licenses/GPL-3.0
 * @see README.md how to contribute to this project
 */

namespace UniversityofHelsinki\MECE\tests;

use UniversityofHelsinki\MECE\MultilingualStringValue;
use InvalidArgumentException;
use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \UniversityofHelsinki\MECE\MultilingualStringValue
 * @author Mikael Kundert <mikael.kundert@wunderkraut.com>
 */
class MultilingualStringValueTest extends PHPUnit_Framework_TestCase {

  /**
   * @var MultilingualStringValue
   */
  private $class;

  public function setUp() {
    $this->class = new MultilingualStringValue();
  }

  /**
   * @covers ::__construct
   * @covers ::getSupportedLanguages
   */
  public function testConstruct() {

    // Assert default supported languages
    $class = new MultilingualStringValue();
    $this->assertEquals(['fi', 'en', 'sv'], $class->getSupportedLanguages());

    // Assert that given supported languages are set
    $class = new MultilingualStringValue(['supportedLanguages' => ['ru']]);
    $this->assertEquals(['ru'], $class->getSupportedLanguages());
  }

  /**
   * @covers ::setValue
   */
  public function testSetValueLangaugeMustBeString() {
    $this->setExpectedException(InvalidArgumentException::class, 'Language must be an string type.');
    $this->class->setValue('value', TRUE);
  }

  /**
   * @covers ::setValue
   */
  public function testSetValueValueMustBeString() {
    $this->setExpectedException(InvalidArgumentException::class, 'Value must be an string type.');
    $this->class->setValue(TRUE, 'fi');
  }

  /**
   * @covers ::setValue
   */
  public function testSetValueNotSupportedLanguage() {
    $this->setExpectedException(InvalidArgumentException::class, 'Language "ru" is not supported.');
    $this->class->setValue('value', 'ru');
  }

  /**
   * @covers ::setValue
   * @covers ::setValues
   * @covers ::getValues
   */
  public function testValues() {

    // Test setValue() and getValue()
    $this->class->setValue('hello', 'en');
    $this->assertEquals('hello', $this->class->getValue('en'));
    $this->class->setValue('terve', 'fi');
    $this->assertEquals('terve', $this->class->getValue('fi'));

    // Test multiple values
    $this->assertEquals(['en' => 'hello', 'fi' => 'terve'], $this->class->getValues());

    // Test whole set/get values
    $values = ['fi' => 'terve', 'en' => 'hello', 'sv' => 'hej'];
    $this->class->setValues($values);
    $this->assertEquals($values, $this->class->getValues());

  }

  /**
   * @covers ::setSupportedLanguages
   * @covers ::getSupportedLanguages
   */
  public function testSupportedLanguages() {
    // Test setter and getter
    $values = ['fi', 'en'];
    $this->class->setSupportedLanguages($values);
    $this->assertEquals($values, $this->class->getSupportedLanguages());
  }

}
