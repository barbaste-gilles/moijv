<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $user = new User();

        $user->setEmail('giba1955@orange.fr');
        $user->setRole('ROLE_USER,ROLE_ADMIN');
        $user->setUsername('gilles');
        $user->setPassword($this->encoder->encodePassword($user, 'gilles'));
        $manager->persist($user);

        for($i=0; $i<20; $i++) {
            $product = new Product();
            $product->setName($faker->text(30));
            $product->setDescription($faker->realText);
            $product->setCreationDate($faker->dateTimeBetween('-2 years'));
            $product->setImage($faker->imageUrl);
            $user->addProduct($product);
            $manager->persist($product);
        }


        $manager->flush();
    }
}
