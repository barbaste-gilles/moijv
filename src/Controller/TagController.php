<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    /**
     * @Route("/tag/search", name="search_tag")
     */
    public function search(Request $request, TagRepository $tagRepository)
    {
        $search = $request->get('search');
        if(!$search) {
            throw $this->createNotFoundException();
        }
        $tags = $tagRepository->searchBySlug($search);
        $tags = array_map(function(Tag $tag){
            return ['name'=> $tag->getName(), 'id' => $tag->getId(), 'slug' => $tag->getSlug()];
        }, $tags);
        return $this->json($tags);
    }
}
