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
        // Global searchbar

        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $racquetRepository->getRacquetPaginator($offset);

        $searchData = new SearchData();

        $searchForm = $this->createForm(SearchType::class, $searchData);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchData->page = $request->query->getInt('page', 1);
            $paginator = $racquetRepository->findByBrandAndModel($searchData);
        }

        // Weight filter

        $allWeights = $racquetRepository->getAllUniquesWeights();
        $weightChoices = $racquetChoiceService->arraySeter($allWeights, 'g');

        $allHeadSizes = $racquetRepository->getAllUniquesHeadSizes();
        $headSizeChoices = $racquetChoiceService->arraySeter($allHeadSizes, ' cm²');

        $allStringPatterns = $racquetRepository->getAllUniquesStringPatterns();
        $stringPatternChoices = $racquetChoiceService->arraySeter($allStringPatterns);

        $allGripSizes = $racquetRepository->getAllUniquesGripSizes();
        $gripSizeChoices = $racquetChoiceService->arraySeter($allGripSizes);

        $filterData = new FilterData();

        $filterForm = $this->createForm(FilterType::class, $filterData, [
            'weight_choices' => $weightChoices,
            'head_size_choices' => $headSizeChoices,
            'string_pattern_choices' => $stringPatternChoices,
            'grip_size_choices' => $gripSizeChoices
        ]);

        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $paginator = $racquetRepository->findBySpecs($filterData);

            // dd($paginator);
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
