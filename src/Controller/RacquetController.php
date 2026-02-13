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
use App\Service\RacquetChoiceService;
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
    public function racquets(Request $request, RacquetRepository $racquetRepository, RacquetChoiceService $racquetChoiceService): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));

        // Global searchbar
        $searchData = new SearchData();
        $searchForm = $this->createForm(SearchType::class, $searchData);
        $searchForm->handleRequest($request);

        // Get all unique values for filter choices
        $allWeights = $racquetRepository->getAllUniquesWeights();
        $weightChoices = $racquetChoiceService->arraySeter($allWeights, 'g');

        $allHeadSizes = $racquetRepository->getAllUniquesHeadSizes();
        $headSizeChoices = $racquetChoiceService->arraySeter($allHeadSizes, ' cm²');

        $allStringPatterns = $racquetRepository->getAllUniquesStringPatterns();
        $stringPatternChoices = $racquetChoiceService->arraySeter($allStringPatterns);

        $allGripSizes = $racquetRepository->getAllUniquesGripSizes();
        $gripSizeChoices = $racquetChoiceService->arraySeter($allGripSizes);

        // Initialize filter data
        $filterData = new FilterData();

        $filterForm = $this->createForm(FilterType::class, $filterData, [
            'weight_choices' => $weightChoices,
            'head_size_choices' => $headSizeChoices,
            'string_pattern_choices' => $stringPatternChoices,
            'grip_size_choices' => $gripSizeChoices
        ]);

        $filterForm->handleRequest($request);

        $page = ($offset / RacquetRepository::PAGINATOR_PER_PAGE) + 1;
        $filterData->page = (int) $page;
        $searchData->page = (int) $page;

        $hasSearch = $searchData->query !== null;

        $hasFilters = $filterData->weight !== null
            || $filterData->head_size !== null
            || $filterData->string_pattern !== null
            || $filterData->grip_size !== null;

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $paginator = $racquetRepository->findByBrandAndModel($searchData);
        } elseif ($hasFilters) {
            $paginator = $racquetRepository->findBySpecs($filterData);
        } elseif ($hasSearch) {
            $paginator = $racquetRepository->findByBrandAndModel($searchData);
        } else {
            $paginator = $racquetRepository->getRacquetPaginator($offset);
        }

        return $this->render('racquet/index.html.twig', [
            'racquets' => $paginator,
            'previous' => $offset - RacquetRepository::PAGINATOR_PER_PAGE,
            'next' => $offset + RacquetRepository::PAGINATOR_PER_PAGE,
            'searchForm' => $searchForm->createView(),
            'filterForm' => $filterForm->createView(),
            'weight' => $filterData->weight,
            'head_size' => $filterData->head_size,
            'string_pattern' => $filterData->string_pattern,
            'grip_size' => $filterData->grip_size,
            'query' => $searchData->query
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
