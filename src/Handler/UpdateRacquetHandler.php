<?php

namespace App\Handler;

use App\Handler\NewRacquetHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UpdateRacquetHandler{

    /** @var NewRacquetHandler */
    private $newRacquetHandler;

    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em, NewRacquetHandler $newRacquetHandler)
    {
        $this->em = $em;
        $this->newRacquetHandler = $newRacquetHandler;
    }

    public function handle($form, $racquet)
    {
        $this->newRacquetHandler->handleFormData($racquet, $form);
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