<?php /**
 * @author Stepan Anchugov <kixxx1@gmail.com>
 */

namespace Kix\OpenTagFixer\Command;

use \Symfony\Component\Console\Command\Command;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Input\InputOption;
use \Symfony\Component\Console\Input\InputArgument;

/**
 * Class FixTagsCommand
 */
class FixTagsCommand extends Command
{

  private $processedFiles    = 0;

  private $processedOpenTags = 0;

  private $processedEchoTags = 0;

  protected function configure()
  {
    $this->setName('fix')
         ->addOption('only-echo', 'e', InputOption::VALUE_OPTIONAL, 'Whether we should only fix <?= syntax', false)
         ->addOption('only-code', 'c', InputOption::VALUE_OPTIONAL, 'Whether we should only fix <? syntax',  false)
         ->addArgument('path', InputArgument::OPTIONAL, 'Path to check', './')
         ->setHelp(<<<HELP
This command actually fixes the PHP open tag syntax. Can ignore echos (due to they are supported since PHP 5.5), can
ignore <? syntax too. Requires path to process as an argument.
HELP
  )
         ->setDescription('This command actually fixes the PHP open tag syntax')
    ;
  }

  /**
   * @param InputInterface  $input
   * @param OutputInterface $output
   *
   * @return int|null|void
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $path = $input->getArgument('path');

    $output->writeln("<info>Started fixing in path </info>\"$path\"");

    foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path)) as $x) {
      /** @var \splFileInfo $x */
      if (preg_match('/\.php$/', $x->getFilename())) {
        $this->fixShortTags($x->getPathName());

        if ($output->getVerbosity() == 1) {
          $output->writeln("Processed ". $x->getPathName());
        }

        $this->processedFiles++;
      }
    }

    $output->writeln("<info>Complete:</info> looked into {$this->processedFiles} files, found {$this->processedOpenTags}"
                    ." open tags, {$this->processedEchoTags} echo tags.");
  }

  /**
   * Actually fixes the code and saves the file
   *
   * @param string $filePath
   */
  private function fixShortTags($filePath)
  {
    $content = file_get_contents($filePath);
    $tokens = token_get_all($content);

    $output = '';

    foreach($tokens as $token) {
      if (is_array($token)) {
        list($index, $code, $line) = $token;
        switch($index) {
          case T_OPEN_TAG_WITH_ECHO:
            $output .= '<?php echo ';
            break;
          case T_OPEN_TAG:
            $output .= '<?php ';
            break;
          default:
            $output .= $code;
            break;
        }
      } else {
        $output .= $token;
      }
    }

    file_put_contents($filePath, $output);
  }

} 