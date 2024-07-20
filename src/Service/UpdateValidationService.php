<?php

namespace App\Service;

use App\Entity\LigneValidation;
use App\Entity\Notification;
use App\Entity\User;
use App\Entity\UserNotification;
use App\Entity\Validation;
use App\Repository\UserRepository;
use App\Repository\ValidationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class UpdateValidationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
        private UpdateSubmissionService $updateSubmissionService,
    ) {
    }

    public function updateValidation(string $validationID)
    {
        $this->entityManager->beginTransaction();

        try {
            /* @var null $validation */
            $validation = $this->entityManager->getRepository(Validation::class)->find($validationID);
            
            if($validation == null)
            {
                throw new \InvalidArgumentException('Validation introuvable.');
            }

            $array_ligneValidation = $validation->getLigneValidation()->toArray();
            $lastIndex = count($array_ligneValidation) - 1;

            for ($count = 0; $count <= $lastIndex; $count++) {
                if ($array_ligneValidation[$count]->getStatus() == 1) {
                    $array_ligneValidation[$count]->setStatus(2);
                    $array_ligneValidation[$count]->setUpdatedAt(new \DateTimeImmutable());
                    $array_ligneValidation[$count]->setReceivedAt(new \DateTimeImmutable());
                    $this->entityManager->persist($array_ligneValidation[$count]);
                    $this->entityManager->flush();

                    if ($count == $lastIndex) { //tout le monde a validé
                        $validation->setStatus(1);
                        $this->entityManager->persist($validation);
                        $this->entityManager->flush();
                    } else { //on passe la requete au prochain validateur
                        $array_ligneValidation[$count + 1]->setStatus(1);
                        $array_ligneValidation[$count + 1]->setUpdatedAt(new \DateTimeImmutable());
                        $this->entityManager->persist($array_ligneValidation[$count + 1]);
                        $this->entityManager->flush();
                    }
                    break;
                }
            }

            $this->entityManager->commit();
            //mise à jour du submission associé
            if($validation->getStatus() == 1)
                $this->updateSubmissionService->updateSubmission($validation->getSubmission(), 1);


            return $validation;
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            $this->logger->error('Erreur lors de la validation d\'une submssion', ['exception' => $e]);

            throw $e;
        }
    }
}
