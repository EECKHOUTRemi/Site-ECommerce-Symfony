<?php

namespace App\Handler;

use App\Entity\Racquet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class NewRacquetHandler
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

    public function handleImg($form, $racquet)
    {
        /** @var UploadedFile $imageFile */
        $imageFile = $form->get('img')->getData();

        $extension = $imageFile->guessExtension();
        $newFilename = $racquet->getId() . '.' . $extension;

        try {
            $imageFile->move("img/racquet", $newFilename);
            $racquet->setImgExtension($extension);
            $this->em->flush();
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
    }

    public function handleForm($form, Racquet $racquet)
    {
        $repo = $this->em->getRepository(Racquet::class);

        $racquet = $this->handleFormData($racquet, $form);

        $repo->add($racquet, true);

        $this->handleImg($form, $racquet);
    }
}
