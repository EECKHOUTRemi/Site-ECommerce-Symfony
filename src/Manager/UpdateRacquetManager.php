<?php

namespace App\Manager;

use App\Entity\Order;
use App\Manager\NewRacquetManager;
use App\Repository\RacquetRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RacquetOrderedRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UpdateRacquetManager{

    /** @var NewRacquetManager */
    private $newRacquetManager;
    
    /** @var RacquetOrderedRepository */
    private $racquetOrderedRepository;
    
    /** @var RacquetRepository */
    private $racquetRepository;

    /** @var EntityManagerInterface */
    private $em;

    public function __construct(
        EntityManagerInterface $em,
        NewRacquetManager $newRacquetManager,
        RacquetOrderedRepository $racquetOrderedRepository,
        RacquetRepository $racquetRepository
    )
    {
        $this->em = $em;
        $this->newRacquetManager = $newRacquetManager;
        $this->racquetOrderedRepository = $racquetOrderedRepository;
        $this->racquetRepository = $racquetRepository;
    }

    public function handle($form, $racquet)
    {
        $this->newRacquetManager->handleFormData($racquet, $form);
        $this->em->flush();

        /** @var UploadedFile $imageFile */
        $imageFile = $form->get('img')->getData();

        if ($imageFile) {
            $newFileNameExtension = $imageFile->guessExtension();
            $newFileName = $racquet->getId() . '.' . $newFileNameExtension;
            $oldFileName = $racquet->getId() . '.' . $racquet->getImgExtension();

            $fileSystem = new Filesystem();
            $fileSystem->remove("img/racquet/" . $oldFileName);
            $imageFile->move("img/racquet", $newFileName);

            $racquet->setImgExtension($newFileNameExtension);
            $this->em->flush();
        }
    }

    public function quantity(Order $order){
        $orderId = $order->getId();
        $racquetsOrdered = $this->racquetOrderedRepository->findBy([
            'orderRef' => $orderId
        ]);
        
        foreach ($racquetsOrdered as $racquetOrdered){
            $racquet = $this->racquetRepository->find($racquetOrdered->getRacquet()->getId());
            $quantityRacquet = $racquet->getQuantity() - $racquetOrdered->getQuantity();
            $racquet->setQuantity($quantityRacquet);
            $this->em->flush();
        }
    }
}