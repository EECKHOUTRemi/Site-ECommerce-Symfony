<?php

namespace App\Manager;

use App\Entity\Racquet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class NewRacquetManager
{
    /** @var SluggerInterface */
    private $slugger;

    /** @var EntityManagerInterface */
    private $em;
    

    public function __construct(SluggerInterface $slugger, EntityManagerInterface $em)
    {
        $this->slugger = $slugger;
        $this->em = $em;
    }

    public function handleFormData(Racquet $racquet, $form)
    {
        $racquet->setBrand($form->get('brand')->getData());
        $racquet->setModel($form->get('model')->getData());
        $racquet->setHeadSize($form->get('head_size')->getData());
        $racquet->setStringPattern($form->get('string_pattern')->getData());
        $racquet->setWeight($form->get('weight')->getData());
        $racquet->setGripSize($form->get('grip_size')->getData());
        $racquet->setPrice($form->get('price')->getData());
        $racquet->setQuantity($form->get('quantity')->getData());

        return $racquet;
    }

    public function handleForm($form, Racquet $racquet)
    {
        $repo = $this->em->getRepository(Racquet::class);

        $racquet = $this->handleFormData($racquet, $form);

        /** @var UploadedFile $imageFile */
        $imageFile = $form->get('img')->getData();
        $extension = $imageFile->guessExtension();
        $racquet->setImgExtension($extension);

        $repo->add($racquet, true);

        $newFilename = $racquet->getId() . '.' . $extension;
        try {
            $imageFile->move("img/racquet", $newFilename);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
    }
}
