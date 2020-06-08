<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace FriendsOfRedaxo\addon\MediapoolExif\Cli;

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-08
 * @author        akrys
 */
use rex_console_command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of Renew
 *
 * @author akrys
 */
class Read
	extends rex_console_command
{

	/**
	 * Konsolen-Aufruf konfigurieren.
	 */
	protected function configure()
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
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$io = $this->getStyle($input, $output);
		$io->title('Read EXIF data');

		$updateAll = $input->getOption('all');
		$updateSilent = $input->getOption('silent');

		$mode = \FriendsOfRedaxo\addon\MediapoolExif\Exif::GETMEDIA_MODE_NULL_ONLY;
		if ($updateAll) {
			if ($updateSilent || $io->confirm('You are going to update all files. Are you sure?', false)) {
				$mode = \FriendsOfRedaxo\addon\MediapoolExif\Exif::GETMEDIA_MODE_ALL;
			}
		}
		$files = \FriendsOfRedaxo\addon\MediapoolExif\Exif::getMediaToRead($mode);

		$numEntries = count($files);
		$io->writeln($numEntries.' entries to read');

		$counter = 0;
		foreach ($files as $file) {
			$counter++;
			$io->writeln('Process file '.$counter.' of '.$numEntries.': '.$file['filename']);

			\rex_mediapool_exif::readExifFromFile($file['filename']);
		}

		$io->success('done');
	}
}
