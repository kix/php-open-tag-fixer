<?php /**
 * @author Stepan Anchugov <kixxx1@gmail.com>
 */

namespace Kix\OpenTagFixer;

use \Symfony\Component\Console\Application;
use \Kix\OpenTagFixer\Command\FixTagsCommand;

/**
 * Class OpenTagFixerApplication
 */
class OpenTagFixerApplication extends Application
{

  const APP_NAME = 'OpenTagFixer';

  const APP_VERSION = '0.0.1';

  /**
   * @param string $name
   * @param string $version
   */
  public function __construct()
  {
    parent::__construct(self::APP_NAME, self::APP_VERSION);

    $this->addCommands(array(
      new FixTagsCommand(),
    ));
  }


} 