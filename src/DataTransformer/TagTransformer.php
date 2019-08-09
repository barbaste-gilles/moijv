<?php

namespace App\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Cocur\Slugify\SlugifyInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class TagTransformer
 * @package App\DataTransformer
 */
class TagTransformer implements DataTransformerInterface
{
    /**
     * @var SlugifyInterface
     */
    private $slugify;

    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * TagTransformer constructor.
     * @param SlugifyInterface $slugify
     */
    public function __construct(SlugifyInterface $slugify, TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
        $this->slugify = $slugify;
    }

    public function transform($tagsCollection) // $tagsCollection est une collection
    {
        $tags = [];
        foreach ($tagsCollection as $tag) {
            if($tag !== null) $tags[] = $tag->getName();
        }
        // $tagCollection->map(function($tag) { return $tag->getName(); });
        return implode(", ", $tags);
    }

    public function reverseTransform($tagsAsString)
    {
        $tagsAsArray = array_map('trim', explode(",", $tagsAsString));
        $tagCollection = new ArrayCollection();
        foreach ($tagsAsArray as $tagName) {
            $slug = $this->slugify->slugify($tagName);
            // SELECT * FROM tag WHERE slug = $slug LIMIT 1
            $tag = $this->tagRepository->findOneBy(['slug' => $slug]);
            if($tag === null) {
                $tag = new Tag();
                $tag->setName($tagName);
                $tag->setSlug($slug);
            }
            $tagCollection->add($tag);
        }
        return $tagCollection;
    }
}
