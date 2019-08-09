<?php

namespace App\Form;

use App\DataTransformer\TagTransformer;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * @var TagTransformer
     */
    private $tagTransformer;

    /**
     * ProductType constructor.
     * @param TagTransformer $tagTransformer
     */
    public function __construct(TagTransformer $tagTransformer)
    {
        $this->tagTransformer = $tagTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('imageFile', FileType::class, [
                'required' => false
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('tags', TextType::class, [
                'label' => 'Tags'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter'
            ])
            ->get('tags')->addViewTransformer($this->tagTransformer)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
