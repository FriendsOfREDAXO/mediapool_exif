<?php

/**
 * Datei für ...
 *
 * @author        akrys
 */
namespace FriendsOfRedaxo\MediapoolExif\Cli;

use FriendsOfRedaxo\MediapoolExif\Enum\MediaFetchMode;
use FriendsOfRedaxo\MediapoolExif\Exif;
use FriendsOfRedaxo\MediapoolExif\MediapoolExif;
use rex_console_command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of Renew
 *
 * @author akrys
 * @codeCoverageIgnore
 */
class Read extends rex_console_command
{

	/**
	 * Konsolen-Aufruf konfigurieren.
	 */
	protected function configure(): void
	{
		$this
			->setName('mediapool_exif:read')
			->setDescription('Read non-existing exif data')
			->addOption('all', null, InputOption::VALUE_NONE, 'Renew EXIF-Data of all files.')
			->addOption('silent', null, InputOption::VALUE_NONE, 'No confirmation (when running --all)')
		;
	}

	/**
	 * Dateien lesen
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$command = $this->getStyle($input, $output);
		$command->title('Read EXIF data');

		$updateAll = $input->getOption('all');
		$updateSilent = $input->getOption('silent');

		$mode = MediaFetchMode::NULL_ONLY;
		if ($updateAll) {
			if ($updateSilent || $command->confirm('You are going to update all files. Are you sure?', false)) {
				$mode = MediaFetchMode::ALL;
			}
		}
		$files = Exif::getMediaToRead($mode);

		$numEntries = count($files);
		$command->writeln($numEntries.' entries to read');

		$counter = 0;
		foreach ($files as $file) {
			if (!isset($file['filename'])) {
				continue;
			}

			$counter++;
			$command->writeln('Process file '.$counter.' of '.$numEntries.': '.$file['filename']);

			MediapoolExif::readExifFromFile((string) $file['filename']);
		}

		$command->success('done');
		return self::SUCCESS;
	}
}
