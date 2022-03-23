<?php

namespace Ares\Core\Command;

use Predis\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
//sometimes it is so easy, why is it in my mind so difficult lmao idk xD
class TestCommand extends Command
{
    /** @var string */
    private const COMMAND_NAME = 'cosmic:cool';

    /** @var string */
    private const TMP_PATH = 'tmp';

    /** @var string */
    private const CACHE_TYPE = 'Predis';

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Clears the application cache');
    }

    /**
     * Your logic here
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->deleteCache(self::TMP_PATH);
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');

            return 1;
        }

        $output->writeln('<info>Application cache successfully cleared</info>');

        return 0;
    }

    /**
     * Deletes the cache recursive
     *
     * @param $dir
     *
     * @return bool
     */

    private function deleteCache($dir): bool
    {
        /** @var Client $predisClient */
        $predisClient = container()->get(Client::class);

        if ($_ENV['CACHE_TYPE'] == self::CACHE_TYPE) {
            $predisClient->flushall();
            return true;
        }

        if (is_dir($dir)) {
            array_map([$this, 'deleteCache'], glob($dir . DIRECTORY_SEPARATOR . '{,.[!.]}*', GLOB_BRACE));
            return @rmdir($dir);
        }

        return @unlink($dir);
    }
}