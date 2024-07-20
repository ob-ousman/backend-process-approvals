<?php

namespace App\Controller;

use App\Entity\Signature;
use App\Service\UpdateValidationService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsController]
class SignatureController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
        private UpdateValidationService $updateValidationService
    ) {
    }

    public function __invoke(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Signature
    {

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');
        $validationID = $request->request->get('validationID');
        
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }
        if (!$validationID) {
            throw new BadRequestHttpException('"validationID" is required');
        }
        
        $this->entityManager->beginTransaction();

        try {
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

            $uploadedFile->move(
                $this->getParameter('signatures_directory'),
                $newFilename
            );

            $signature = new Signature();
            $signature->setFilename($newFilename);

            $em->persist($signature);
            $em->flush();

            //changement du status de la validation
            $this->updateValidationService->updateValidation($validationID);

            $this->entityManager->commit();

            return $signature;
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            $this->logger->error('Erreur lors de la création de la notification', ['exception' => $e]);

            throw $e;
        }
    }
}
