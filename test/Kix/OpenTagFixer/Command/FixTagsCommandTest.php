<?php
/**
 * @author Stepan Anchugov <kixxx1@gmail.com>
 */

use Kix\OpenTagFixer\Command\FixTagsCommand;

/**
 * Class FixTagsCommandTest
 */
class FixTagsCommandTest extends \PHPUnit_Framework_TestCase
{

  /**
   * @var FixTagsCommand
   */
  private $command;

  public function setUp()
  {
    $this->command = new FixTagsCommand();
  }

  public function testNewlinesDoNotGetMangled()
  {
    $code = <<<CODE
<?php
/**
 * This is a block comment
 */
class TestClass
{
}
CODE;
    $result = $this->command->processCode($code);

    $this->assertTrue(
      strpos($result, "\r\n") == 6,
      "Did not preserve whitespace between opening tag and a comment"
    );

  }

  public function testInlineEchoNotBroken()
  {
    $code = "Test <?='code' ?>";

    $this->assertEquals("Test <?php echo 'code' ?>", $this->command->processCode($code));
  }

  public function testInlineCodeNotBroken()
  {
    $this->markTestSkipped('Working on a fix!');

    $code = "Test <? \$a = 1 ?>";

    $this->assertEquals("Test <?php \$a = 1 ?>", $this->command->processCode($code));
  }

} 