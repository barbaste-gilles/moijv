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
     * @Route("/product/myproducts", name="myproducts")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function myproducts()
    {
        return $this->render('product/myproducts.html.twig');
    }

    /**
     * @Route("/product/add", name="add_product")
     * @Route("/product/edit/{id<\d+>}", name="edit_product")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function editProduct(Request $request, ObjectManager $objectManager, Product $product = null)
    {
        if($product === null) {
            $product = new Product();
        } elseif($product->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

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
            $product->setOwner($this->getUser());
            $objectManager->persist($product);
            $objectManager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('product/edit.html.twig', [
            'product_form' => $productForm->createView()
        ]);
    }

    /**
     * @Route("/product/delete/{id<\d+>}", name="delete_product")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function deleteProduct(Product $product, ObjectManager $objectManager)
    {
        // si l'utilisateur connecté n'est pas le propriétaire du produit, on déclenche
        // une exception (qui sera traduite par une réponse avec un code 403)
        if($product->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        // on supprime le produit
        $objectManager->remove($product);
        $objectManager->flush();
        return $this->redirectToRoute('myproducts');
    }
}
