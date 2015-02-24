<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Dumper;

class CrowdinPullCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('crowdin:pull')
            ->setDescription('Crowdin pull command')
            ->setHelp(<<<EOF
Pulls translations from Crowdin.
EOF
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Setting up configurations');

        // $finder = new Finder();
        // $files = $finder->files()->in(__DIR__.'/../../../src')->name('*en.yml');

        $key = $this->getContainer()->getParameter('crowdin_key');

        $config = [
            'project_identifier' => 'ebayumelis',
            'api_key' => $key,
            'base_url' => 'https://api.crowdin.com',
            'base_path' => 'src',
            'preserve_hierarchy' => true,
            'files' => []
        ];

        $config['files'][] = [
            'source' => '/**/*en.yml',
            'translation' => '/**/messages.%two_letters_code%.yml'
        ];

        $dumper = new Dumper();
        $yaml = $dumper->dump($config);

        file_put_contents(__DIR__.'/../../../crowdin.yaml', $yaml);
        $output->writeln('Pulling from Crowdin');
        $command = 'java -jar app/Resources/Crowdin/crowdin-cli.jar download';
        exec($command);
    }
}
