<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/{id<\d+>}", name="product")
     */
    public function details(Product $product)
    {
        return $this->render('product/details.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/product/add", name="add_product")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function editProduct(Request $request, ObjectManager $objectManager)
    {
        $product = new Product();

        $productForm = $this->createForm(ProductType::class, $product);

        $productForm->handleRequest($request);

        if($productForm->isSubmitted() && $productForm->isValid()) {
            $product->setCreationDate(new \DateTime());
            if($product->getImageFile() !== null) {
                // on gère ici le déplacement du fichier uploadé depuis la localisation temporaire
                // vers la localisation permanente (public/uploads)
                /** @var UploadedFile $imageFile */
                $imageFile = $product->getImageFile();
                $folder = 'uploads'; $filename = uniqid();
                $imageFile->move($folder, $filename);
                $product->setImage($folder . DIRECTORY_SEPARATOR . $filename);
            }
            $objectManager->persist($product);
            $objectManager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('product/edit.html.twig', [
            'product_form' => $productForm->createView()
        ]);
    }
}
