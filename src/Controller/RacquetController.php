<?php

namespace App\Controller;

use App\Entity\Racquet;
use App\Entity\RacquetRating;
use App\Form\Cart\AddToCartType;
use App\Form\RacquetRatingType;
use App\Form\Search\SearchType;
use App\Manager\CartManager;
use App\Model\SearchData;
use App\Repository\RacquetRatingRepository;
use App\Repository\RacquetRepository;
use App\Service\RacquetChoiceService;
use Doctrine\ORM\EntityManagerInterface;
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
        
        $searchData = new SearchData();
        $searchForm = $this->createForm(SearchType::class, $searchData);
        $searchForm->handleRequest($request);

        $page = ($offset / RacquetRepository::PAGINATOR_PER_PAGE) + 1;
        $searchData->page = (int) $page;

        $hasSearch = $searchData->query !== null;
        $hasFilters = $searchData->quantity !== null || $searchData->rating !== null;

        if ($hasSearch || $hasFilters) {
            $paginator = $racquetRepository->findWithSearch($searchData);
        } else {
            $paginator = $racquetRepository->getRacquetPaginator($offset);
        }

        return $this->render('racquet/index.html.twig', [
            'racquets' => $paginator,
            'count' => count($paginator),
            'previous' => $offset - RacquetRepository::PAGINATOR_PER_PAGE,
            'next' => $offset + RacquetRepository::PAGINATOR_PER_PAGE,
            'searchForm' => $searchForm->createView(),
            'query' => $searchData->query,
            'quantity' => $searchData->quantity,
            'rating' => $searchData->rating,
        ]);
    }

    /**
     * @Route("/racquet/{id}", name="racquet_detail")
     */
    public function detail(
        Racquet $racquet, 
        Request $request, 
        CartManager $cartManager, 
        EntityManagerInterface $em,
        RacquetRatingRepository $racquetRatingRepository
    )
    {
        // Add to cart form
        $cartForm = $this->createForm(AddToCartType::class);
        $cartForm->handleRequest($request);


        if ($cartForm->isSubmitted() && $cartForm->isValid()) {
            $data = $cartForm->getData();
            $data->setRacquet($racquet);

            $cart = $cartManager->getCurrentCart();
            $cart->addRacquet($data)
                ->setUpdatedAt(new \DateTime())
                ->setUser($this->getUser());

            $cartManager->save($cart);

            return $this->redirectToRoute('racquet_detail', ['id' => $racquet->getId()]);
        }
        
        // Rating form
        $ratingForm = $this->createForm(RacquetRatingType::class);
        $ratingForm->handleRequest($request);

        if ($ratingForm->isSubmitted() && $ratingForm->isValid()) {

            /** @var \App\Entity\User $user */
            $user = $this->getUser();
            $submittedRating = $ratingForm->getData()['rating'];
            
            $racquetRating = new RacquetRating();
            $racquetRating->setRacquet($racquet)
                ->setUser($user)
                ->setRating($submittedRating)
            ;
            $em->persist($racquetRating);
            $em->flush();
            
            $nbRatings = $racquet->getRacquetRatings()->count();
            $ratings = $racquetRatingRepository->getRatingByUser($racquet);
            $sumRatings = array_sum($ratings);

            $newAvgRating = $sumRatings / $nbRatings;

            $racquet->setAvgRating($newAvgRating);
            $em->flush();

            return $this->redirectToRoute('racquet_detail', ['id' => $racquet->getId()]);
        }

        return $this->render('racquet/detail.html.twig', [
            'racquet' => $racquet,
            'cartForm' => $cartForm->createView(),
            'ratingForm' => $ratingForm->createView(),
            ]);
    }
}
