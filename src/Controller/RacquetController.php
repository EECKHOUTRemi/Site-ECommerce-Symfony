<?php

namespace App\Controller;

use App\Entity\Racquet;
use App\Form\AddToCartType;
use App\Form\FilterType;
use App\Form\SearchType;
use App\Manager\CartManager;
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

        $allStringPatterns = $racquetRepository->getAllUniquesStringPatterns();
        $stringPatternChoices = $racquetChoiceService->arraySeter($allStringPatterns);

        $allGripSizes = $racquetRepository->getAllUniquesGripSizes();
        $gripSizeChoices = $racquetChoiceService->arraySeter($allGripSizes);
        
        $searchData = new SearchData();
        $searchForm = $this->createForm(SearchType::class, $searchData, [
            'string_pattern_choices' => $stringPatternChoices,
            'grip_size_choices' => $gripSizeChoices
        ]);
        $searchForm->handleRequest($request);

        $page = ($offset / RacquetRepository::PAGINATOR_PER_PAGE) + 1;
        $searchData->page = (int) $page;

        $hasSearch = $searchData->query !== null;
        $hasFilters = $searchData->weight !== null
            || $searchData->head_size !== null
            || $searchData->string_pattern !== null
            || $searchData->grip_size !== null;

        if ($hasSearch || $hasFilters) {
            $paginator = $racquetRepository->findWithSearch($searchData);
        } else {
            // No search or filters
            $paginator = $racquetRepository->getRacquetPaginator($offset);
        }

        return $this->render('racquet/index.html.twig', [
            'racquets' => $paginator,
            'count' => count($paginator),
            'previous' => $offset - RacquetRepository::PAGINATOR_PER_PAGE,
            'next' => $offset + RacquetRepository::PAGINATOR_PER_PAGE,
            'searchForm' => $searchForm->createView(),
            'query' => $searchData->query,
            'weight' => $searchData->weight,
            'head_size' => $searchData->head_size,
            'string_pattern' => $searchData->string_pattern,
            'grip_size' => $searchData->grip_size,
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
