<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Article;
use App\Form\Type\ArticleStoreType;
use App\Helper\FormErrorsSerializer;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Message\ArticleNotification;
use App\Service\FileUploader;

class ArticleController extends AbstractController {

    public function indexAction(Request $request, ManagerRegistry $doctrine): Response {
        $articles = $doctrine
                ->getRepository(Article::class)
                ->getArticlePaginated(max(1, $request->query->getInt('page', 1)));
        return $this->json($articles);
    }

    public function showAction(Request $request, ManagerRegistry $doctrine): Response {
        $article = $doctrine
                ->getRepository(Article::class)
                ->findOneBy(['id' => $request->get('articleId')]);

        if (!$article) {
            return $this->json(['message' => 'Article not found',], Response::HTTP_NOT_FOUND);
        }

        return $this->json($article->convertToArray([]));
    }

    public function storeAction(Request $request, MessageBusInterface $bus, FileUploader $fileUploader): Response {
        $form = $this->buildForm(ArticleStoreType::class);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            $formSerializer = new FormErrorsSerializer();
            return $this->json(['errors' => $formSerializer->getFormErrors($form)], Response::HTTP_BAD_REQUEST);
        }

        $file = $form->get('image')->getData();
        $imageFileName = $fileUploader->upload($file);

        $article = $form->getData();
        $article->setImage($imageFileName);
        $bus->dispatch(new ArticleNotification($article));

        return $this->json($article->convertToArray([]));
    }

    public function updateAction(Request $request, ManagerRegistry $doctrine, MessageBusInterface $bus, FileUploader $fileUploader): Response {
        $article = $doctrine->getRepository(Article::class)->findOneBy(['id' => $request->get('articleId')]);
        if (!$article) {
            return $this->json(['message' => 'Article not found',], Response::HTTP_NOT_FOUND);
        }
        if ($request->files->has('image')) {
            $article->setImage("");
        }

        $form = $this->buildForm(ArticleStoreType::class, $article, ['method' => $request->getMethod(),]);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $formSerializer = new FormErrorsSerializer();
            return $this->json(['errors' => $formSerializer->getFormErrors($form)], Response::HTTP_BAD_REQUEST);
        }
        if ($request->files->has('image')) {
            $file = $form->get('image')->getData();
            $imageFileName = $fileUploader->upload($file);
            $article->setImage($imageFileName);
        }

        $bus->dispatch(new ArticleNotification($form->getData()));
        return $this->json($article->convertToArray([]));
    }

    public function deleteAction(Request $request, ManagerRegistry $doctrine): Response {
        $article = $doctrine
                ->getRepository(Article::class)
                ->findOneBy(['id' => $request->get('articleId')]);

        if (!$article) {
            return $this->json(['message' => 'Article not found',], Response::HTTP_NOT_FOUND);
        }

        $doctrine->getManager()->remove($article);
        $doctrine->getManager()->flush();
        return $this->json(['status' => 'success']);
    }

    private function buildForm(string $type, $data = null, array $options = []): FormInterface {
        return $this->container->get('form.factory')->createNamed('', $type, $data, $options);
    }

}
