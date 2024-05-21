<?php

// src/Form/DataTransformer/IssueToNumberTransformer.php
namespace App\Datatransformer;

use App\Entity\Posts;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class PostImageTransformer implements DataTransformerInterface
{
    public function __construct(private ParameterBagInterface $params, private EntityManagerInterface $entityManager)
    {
    }

    /**
     * Transforms an object (issue) to a string (number).
     * de la base de données vers la vue
     * @param  Issue|null $issue
     */
    public function transform($value): string
    {
        if (null === $value) {
            return '';
        }

        // dd($value);

        // if (!$value instanceof Posts) {
        //     throw new \LogicException('The UserSelectTextType can only be used with User objects');
        // }

        return  new File(
            $this->params->get('images_posts_directory') . '/' . $value
        );
    }

    /**
     * Transforms a string (number) to an object (issue).
     * de la vue vers la base de données
     * @param  string $issueNumber
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($value): ?Posts
    {
        // no issue number? It's optional, so that's ok

        return $value;
    }
}
