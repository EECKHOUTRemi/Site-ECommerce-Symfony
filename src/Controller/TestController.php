<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Manager\UpdateRacquetManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{

    /** @var UpdateRacquetManager */
    private $updateRacquetManager;

    public function __construct(UpdateRacquetManager $updateRacquetManager)
    {
        $this->updateRacquetManager = $updateRacquetManager;
    }


    /**
     * @Route("/test", name="app_test")
     */
    public function index(OrderRepository $orderRepository): Response
    {
        $order = $orderRepository->find(10);
        $test = $this->updateRacquetManager->quantity($order);;

        dd($test);
    }
}
