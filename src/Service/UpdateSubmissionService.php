<?php

namespace App\Service;

use App\Entity\Notification;
use App\Entity\Submission;
use App\Entity\User;
use App\Entity\UserNotification;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class UpdateSubmissionService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
        private NotificationService $notificationService
    ) {
    }

    public function updateSubmission(Submission $submission, int $status)
    {
        if (!$submission || !$status) {
            throw new \InvalidArgumentException('Invalid data.');
        }

        $this->entityManager->beginTransaction();

        try {
            $submission->setStatus($status);
            $this->entityManager->persist($submission);
            $this->entityManager->flush();

            //notifier
            $titre = $message = "";
            switch($status) {
                case 1: $titre = "Requête terminée";
                    $message = "Votre requête #". $submission->getNumber() . " est terminée.";
                    break;
                case 2: $titre = "Requête rejetée";
                    $message = "Votre requête #". $submission->getNumber() . " a été rejétée.";
                    break;
                case 3: $titre = "Requête annulée";
                    $message = "Votre requête #". $submission->getNumber() . " a été annulée.";
                    break;
                default: $titre = $message = "";
            }
            $user = $this->entityManager->getRepository(User::class)->find(1);
            $this->notificationService->createNotification($user, 1, $titre, $message);

            $this->entityManager->commit();

            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            $this->logger->error('Erreur lors de la création de la notification', ['exception' => $e]);

            throw $e;
        }
    }
}
