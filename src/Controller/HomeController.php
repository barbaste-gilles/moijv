<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/{page<\d*>}", name="home")
     */
    public function index(
        ProductRepository $productRepository,
        PaginatorInterface $pagination,
        $page = 0
    )

    {
        $productsQuery = $productRepository->createQueryBuilder('p')
            ->orderBy('p.creationDate', 'desc')
            ->getQuery();
        $products = $pagination->paginate($productsQuery, (int)$page, 6);
        $lastProducts = $productRepository->findBy([], ['creationDate' => 'DESC'], 3);
        return $this->render('home/index.html.twig', [
            'products' => $products,
            'last_products' => $lastProducts
        ]);
    }
}
