<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\User;
use App\Entity\UserNotification;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/notifications')]
class NotificationController extends AbstractController
{
    public function __construct(
        private NotificationRepository $notificationRepository,
        private LoggerInterface $logger,
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    /*
    #[Route('/create_notification', name: 'create_notification', methods: ['POST'])]
    public function createNotification(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['title']) || !isset($data['message']) || !isset($data['user'])) {
            return $this->json(['error' => 'Invalid data.'], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->beginTransaction();

        try {
            $user = $this->userRepository->find($data['user']);
            
            if(!$user)
                return $this->json(['error' => 'Une erreur est survenue lors de la création de la notification.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        
            $notification = new Notification();
            $notification->setTitle($data['title']);
            $notification->setMessage($data['message']);
            $notification->setType($data['type']);
            $notification->setCreatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($notification);
            $this->entityManager->flush();

            $userNotification = new UserNotification();
            $userNotification->setUser($user);
            $userNotification->setNotification($notification);
            $userNotification->setCreatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($userNotification);
            $this->entityManager->flush();

            $this->entityManager->commit(); // Valider la transaction

            return $this->json($notification, Response::HTTP_CREATED, [], ['groups' => 'notification:read']);
        } catch (\Exception $e) {
            $this->entityManager->rollback(); // Annuler la transaction
            
            // Enregistrer l'erreur pour le débogage
            $this->logger->error('Erreur lors de la création de la notification', ['exception' => $e]);

            return $this->json(['error' => 'Une erreur est survenue lors de la création de la notification. '. $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    */
}
