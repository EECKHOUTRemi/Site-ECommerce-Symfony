<?php

namespace App\Controller;

use App\Entity\Racquet;
use App\Form\AddToCartType;
use App\Form\FilterType;
use App\Form\SearchType;
use App\Manager\CartManager;
use App\Model\FilterData;
use App\Model\SearchData;
use App\Repository\RacquetRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @IsGranted("ROLE_USER")
 */
class RacquetController extends AbstractController
{

    /**
     * @Route("/racquets", name="racquets")
     */
    public function racquets(Request $request, RacquetRepository $racquetRepository): Response
    {
        // Global searchbar

        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $racquetRepository->getRacquetPaginator($offset);

        $searchData = new SearchData();

        $searchForm = $this->createForm(SearchType::class, $searchData);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchData->page = $request->query->getInt('page', 1);
            $paginator = $racquetRepository->findBrandAndModelBySearch($searchData);
        }

        // Weight filter

        $allWeights = $racquetRepository->getAllUniquesWeights();
        // Convert to associative array for ChoiceType: ['300g' => '300', '310g' => '310']
        $weightChoices = array_combine(
            array_map(function ($w) {
                return $w . 'g';
            }, $allWeights),
            $allWeights
        );

        $filterData = new FilterData();
        $filterData->spec = "weight";

        $filterForm = $this->createForm(FilterType::class, $filterData, [
            'weight_choices' => $weightChoices
        ]);

        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $filterData->query = $request->query->getInt('query', 1);
            $paginator = $racquetRepository->findSpecsBySearch($filterData);
        }

        return $this->render('racquet/index.html.twig', [
            'racquets' => $paginator,
            'previous' => $offset - RacquetRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + RacquetRepository::PAGINATOR_PER_PAGE),
            'searchForm' => $searchForm->createView(),
            'filterForm' => $filterForm->createView(),
        ]);
    }

    /**
     * @Route("/racquet/{id}", name="racquet_detail")
     */
    public function detail(Racquet $racquet, Request $request, CartManager $cartManager)
    {
        $form = $this->createForm(AddToCartType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $data->setRacquet($racquet);

            $cart = $cartManager->getCurrentCart();
            $cart
                ->addRacquet($data)
                ->setUpdatedAt(new \DateTime())
                ->setUser($this->getUser());

            $cartManager->save($cart);

            return $this->redirectToRoute('racquet_detail', ['id' => $racquet->getId()]);
        }

        return $this->render('racquet/detail.html.twig', [
            'racquet' => $racquet,
            'form' => $form->createView()
        ]);
    }
}
