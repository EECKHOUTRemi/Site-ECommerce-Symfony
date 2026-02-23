<?php

namespace App\Controller;

use App\Entity\Racquet;
use App\Entity\RacquetRating;
use App\Form\Cart\AddToCartType;
use App\Form\RacquetRatingType;
use App\Form\Filter\FilterType;
use App\Manager\CartManager;
use App\Model\FilterData;
use App\Repository\RacquetRatingRepository;
use App\Repository\RacquetRepository;
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
    public function racquets(Request $request, RacquetRepository $racquetRepository): Response
    {
        // Filter form
        $offset = max(0, $request->query->getInt('offset', 0));

        $brands = $racquetRepository->getUniqueBrands();
        
        $filterData = new FilterData();
        $filterForm = $this->createForm(FilterType::class, $filterData, [
            'brand_choices' => $brands
        ]);
        $filterForm->handleRequest($request);

        $page = ($offset / RacquetRepository::PAGINATOR_PER_PAGE) + 1;
        $filterData->page = (int) $page;
        $hasFilters = $filterData->brand !== null 
            || $filterData->price !== null
            || $filterData->quantity !== null
            || $filterData->rating !== null
        ;

        if ($hasFilters) {
            $paginator = $racquetRepository->findWithFilter($filterData);
        } else {
            $paginator = $racquetRepository->getRacquetPaginator($offset);
        }

        // Search bar (select2)
        $racquetId = $request->request->get('racquet_id');
        
        if ($racquetId) {
            $racquet = $racquetRepository->find($racquetId);
            
            if ($racquet) {
                return $this->redirectToRoute('racquet_detail', ['id' => $racquetId]);
            }
        }

        return $this->render('racquet/index.html.twig', [
            'racquets' => $paginator,
            'count' => count($paginator),
            'previous' => $offset - RacquetRepository::PAGINATOR_PER_PAGE,
            'next' => $offset + RacquetRepository::PAGINATOR_PER_PAGE,
            'filterForm' => $filterForm->createView(),
            'brand' => $filterData->brand,
            'price' => $filterData->price,
            'quantity' => $filterData->quantity,
            'rating' => $filterData->rating,
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

    /**
     * @Route("/racquets/data", name="racquet_data")
     */
    public function data(Request $request, RacquetRepository $racquetRepository){
        $searchTerm = $request->query->get('q', '');
        $racquets = $racquetRepository->findByFilterTerm($searchTerm);
        return $this->json(['racquets' => $racquets]);
    }

    /**
     * @Route("/racquets/submit", name="app_select2_submit", methods={"POST"})
     */
    public function submit(Request $request, RacquetRepository $racquetRepository): Response
    {
        $racquetId = $request->request->get('racquet_id');
        
        if ($racquetId) {
            $racquet = $racquetRepository->find($racquetId);
            
            if ($racquet) {
                return $this->render('select2/index.html.twig', [
                    'controller_name' => 'Select2Controller',
                    'selectedRacquet' => [
                        'id' => $racquet->getId(),
                    ]
                ]);
            }
        }
        
        return $this->redirectToRoute('app_select2');
    }
}
