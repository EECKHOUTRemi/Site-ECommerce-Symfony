<?php

namespace App\Controller\Admin;

use App\Entity\Racquet;
use App\Form\RacquetType;
use App\Manager\NewRacquetManager;
use App\Manager\UpdateRacquetManager;
use App\Repository\RacquetRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/racquet")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminRacquetController extends AbstractController
{

    /** @var NewRacquetManager */
    private $newRacquetManager;

    /** @var UpdateRacquetManager */
    private $updateRacquetManager;

    public function __construct(NewRacquetManager $newRacquetManager, UpdateRacquetManager $updateRacquetManager)
    {
        $this->newRacquetManager = $newRacquetManager;
        $this->updateRacquetManager = $updateRacquetManager;
    }

    /**
     * @Route("/", name="app_admin_racquet_index", methods={"GET"})
     */
    public function index(RacquetRepository $racquetRepository): Response
    {
        return $this->render('admin_racquet/index.html.twig', [
            'racquets' => $racquetRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_admin_racquet_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $racquet = new Racquet();
        $form = $this->createForm(RacquetType::class, $racquet);
        $form->HandleRequest($request);

        if ($form->isSubmitted()) {
            $imageFile = $form->get('img')->getData();
            if (!$imageFile) {
                $form->get('img')->addError(new FormError('An image is required when creating a new racquet.'));
            }
            
            if ($form->isValid() && $imageFile) {
                $this->newRacquetManager->handleForm($form, $racquet);
                return $this->redirectToRoute('app_admin_racquet_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('admin_racquet/new.html.twig', [
            'racquet' => $racquet,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_racquet_show", methods={"GET"})
     */
    public function show(Racquet $racquet): Response
    {
        return $this->render('admin_racquet/show.html.twig', [
            'racquet' => $racquet,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_racquet_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Racquet $racquet, RacquetRepository $racquetRepository): Response
    {
        $form = $this->createForm(RacquetType::class, $racquet);
        $form->HandleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->updateRacquetManager->handle($form, $racquet);

            return $this->redirectToRoute('app_admin_racquet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_racquet/edit.html.twig', [
            'racquet' => $racquet,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_racquet_delete", methods={"POST"})
     */
    public function delete(Request $request, Racquet $racquet, RacquetRepository $racquetRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $racquet->getId(), $request->request->get('_token'))) {
            $racquetRepository->remove($racquet, true);
        }

        return $this->redirectToRoute('app_admin_racquet_index', [], Response::HTTP_SEE_OTHER);
    }
}
