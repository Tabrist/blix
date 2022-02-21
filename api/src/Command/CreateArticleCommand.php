<?php

namespace App\Command;

use App\Entity\Article;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\FileUploader;

class CreateArticleCommand extends Command {

    protected static $defaultName = 'app:create-article';
    protected static $defaultDescription = 'Add article to DB';
    private $em;
    private $fileUploader;

    public function __construct(EntityManagerInterface $em, FileUploader $fileUploader) {
        $this->em = $em;
        $this->fileUploader = $fileUploader;
        parent::__construct();
    }

    protected function configure(): void {
        $this->setDescription('You have to add title, content and path to image')
                ->setHelp('This command allows you to create a new article via CLI')
                ->addArgument('title', InputArgument::REQUIRED, 'Title')
                ->addArgument('content', InputArgument::REQUIRED, 'Content')
                ->addArgument('image', InputArgument::REQUIRED, 'Image')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        if (!$output instanceof ConsoleOutputInterface) {
            throw new \LogicException('This command accepts only an instance of "ConsoleOutputInterface".');
        }
        $output->writeln(['Article Creator', '',]);

        $article = new Article();
        $article->setTitle($input->getArgument('title'));
        $article->setContent($input->getArgument('content'));
        $article->setImage($this->fileUploader->copy($input->getArgument('image')));

        $this->em->persist($article);
        $this->em->flush();

        return Command::SUCCESS;
    }

}
