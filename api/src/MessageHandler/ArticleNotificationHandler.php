<?php

namespace App\MessageHandler;

use App\Entity\Article;
use App\Message\ArticleNotification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]

class ArticleNotificationHandler {

    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function __invoke(ArticleNotification $message) {
        if ($message->getArticle()->getId()) {
            $item = $this->em->getRepository(Article::class)->find($message->getArticle()->getId());
            $item->setTitle($message->getArticle()->getTitle());
            $item->setContent($message->getArticle()->getContent());
            $item->setImage($message->getArticle()->getImage());
        } else {
            $item = $message->getArticle();
        }
        $this->em->persist($item);
        $this->em->flush();
    }

}
