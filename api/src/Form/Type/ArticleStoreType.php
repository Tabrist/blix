<?php

namespace App\Form\Type;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\File;

class ArticleStoreType extends AbstractType {

    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository) {
        $this->articleRepository = $articleRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        /** @var Article|null $article */
        $article = $options['data'] ?? null;
        $isEdit = $article && $article->getId();

        $builder->add('title', TextType::class, [
                    'required' => true,
                    'constraints' => [
                        new NotNull([
                            'message' => 'Title can not be empty',
                                ]),
                        new Length([
                            'min' => 10,
                            'max' => 80,
                            'minMessage' => 'Title must be at least {{ limit }} characters long',
                            'maxMessage' => 'Title cannot be longer than {{ limit }} characters',
                                ]),
                    ]
                ])
                ->add('content', TextareaType::class, [
                    'required' => true,
                    'constraints' => [
                        new NotNull([
                            'message' => 'Content can not be empty',
                                ]),
                        new Length([
                            'min' => 20,
                            'minMessage' => 'Content must be at least {{ limit }} characters long',
                                ]),
                    ]
        ]);

        $builder->get('content')
                ->addModelTransformer(new CallbackTransformer(
                                function ($content) {
                            return strip_tags($content, '<ul><li><ol><p><strong>');
                        },
                                function ($content) {
                            return strip_tags($content, '<ul><li><ol><p><strong>');
                        }
                ))
        ;

        if (!$isEdit || ($isEdit && $article->getImage() == "")) {
            $builder->add('image', FileType::class, [
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid JPG',
                            ]),
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
//            'id' => null,
            'data_class' => Article::class,
        ]);
    }

}
