<?php
/**
 * @license GPL, or GNU General Public License, version 3
 * @license http://opensource.org/licenses/GPL-3.0
 * @see README.md how to contribute to this project
 */

namespace UniversityofHelsinki\MECE\tests;


class MessageBaseTestCase extends \PHPUnit_Framework_TestCase {

  protected $recipients = array('user1', 'user2', 'user3', 'user4');
  protected $source;

  public function setUp() {
    $this->source = $this->getRandomString();
  }

  protected function getRandomString() {
    return md5(uniqid(time(), TRUE));
  }

}
