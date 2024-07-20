<?php

namespace App\Service;

use App\Entity\Notification;
use App\Entity\User;
use App\Entity\UserNotification;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class NotificationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger
    ) {
    }

    public function createNotification(User $user, int $type, string $title, string $message)
    {
        if (!$title || !$message || !$user || !$type) {
            throw new \InvalidArgumentException('Invalid data.');
        }

        $this->entityManager->beginTransaction();

        try {
            $notification = new Notification();
            $notification->setTitle($title);
            $notification->setMessage($message);
            $notification->setType($type);
            $notification->setCreatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($notification);
            $this->entityManager->flush();

            $userNotification = new UserNotification();
            $userNotification->setUser($user);
            $userNotification->setNotification($notification);
            $userNotification->setCreatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($userNotification);
            $this->entityManager->flush();

            $this->entityManager->commit();

            return $notification;
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            $this->logger->error('Erreur lors de la création de la notification', ['exception' => $e]);

            throw $e;
        }
    }
}
