<?php

namespace App\Controller;

use App\Entity\Racquet;
use App\Repository\RacquetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FakerController extends AbstractController
{
    /**
     * @Route("/faker", name="app_faker")
     * @IsGranted("ROLE_DEVELOPER")
     */
    public function index(RacquetRepository $racquetRepo)
    {
        $faker = Factory::create();
        
        for ($i = 0; $i < 1000; $i++) {
            $brand = $faker->word;
            $model = $faker->word;
            $price = $faker->numberBetween(60, 300);
            $quantity = $faker->numberBetween(0, 75);
            $rating = $faker->numberBetween(0,10);

            $racquet = new Racquet;
            $racquet->setBrand($brand)
                ->setModel($model)
                ->setPrice($price)
                ->setQuantity($quantity)
                ->setAvgRating($rating)
                ->setImgExtension('png')
            ;
            
            $racquetRepo->add($racquet, true);
        }

        return $this->render('/faker/index.html.twig');
    }
}
