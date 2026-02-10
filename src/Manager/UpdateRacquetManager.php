<?php

namespace App\Manager;

use App\Manager\NewRacquetManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UpdateRacquetManager{

    /** @var NewRacquetManager */
    private $newRacquetManager;

    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em, NewRacquetManager $newRacquetManager)
    {
        $this->em = $em;
        $this->newRacquetManager = $newRacquetManager;
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
}