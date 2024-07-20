<?php

namespace App\Controller;

use App\Repository\SubmissionRepository;
use App\Repository\ValidationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/validations')]
class ValidationController extends AbstractController
{
    public function __construct(
        private ValidationRepository $validationRepository,
        private SubmissionRepository $submissionRepository,
        private SerializerInterface $serializer,
    ) {
    }

    /*
    #[Route('/combined_submissions', name: 'app_validation', methods: ['GET'])]
    public function index(): Response
    {
        $validations = $this->validationRepository->findBy(['status' => 1]);
        $submissions = $this->submissionRepository->findBy(['status' => 1]);

        $combined = [];
        foreach ($submissions as $submission) {
            $validation = array_filter($validations, function ($v) use ($submission) {
                return $v->getSubmission()->getId() === $submission->getId();
            });
            if ($validation) {
                $submissionData = $this->serializer->normalize($submission, null, ['groups' => 'submission:read']);
                $validationData = $this->serializer->normalize($validation, null, ['groups' => 'validation:read']);
                $submissionData['validation'] = $validationData;
                $combined[] = $submissionData;
            }
        }

        return $this->json($combined);
    }
    */
}
