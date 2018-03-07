<?php
/**
 * @license GPL, or GNU General Public License, version 3
 * @license http://opensource.org/licenses/GPL-3.0
 * @see README.md how to contribute to this project
 */

namespace UniversityofHelsinki\MECE;

/**
 * Class VersionTrait
 * @package UniversityofHelsinki\MECE
 *
 * Simple trait that can be used across all library classes.
 *
 * @author Mikael Kundert <mikael.kundert@wunderkraut.com>
 */
trait VersionTrait {
  /**
   * Returns the version of this library.
   * @return string
   */
  function getVersion() {
    return '1.1.1';
  }
}
