<?php

namespace App\Controller;

use App\Entity\Submission;
use App\Entity\FieldValue;
use App\Repository\FormRepository;
use App\Repository\FieldRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/submissions')]
class SubmissionController extends AbstractController
{
    #[Route('', name: 'create_submission', methods: ['POST'])]
    public function create(Request $request, FormRepository $formRepository, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);
        $form = $formRepository->find($data['form_id']);
        
        $submission = new Submission();
        $submission->setForm($form);
        $submission->setCreatedAt(new \DateTimeImmutable());
        
        $em->persist($submission);
        $em->flush();
        
        foreach ($data['fields'] as $fieldData) {
            $fieldValue = new FieldValue();
            $fieldValue->setField($fieldData['field']);
            $fieldValue->setValue($fieldData['value']);
            $fieldValue->setSubmission($submission);
            
            $em->persist($fieldValue);
        }
        
        $em->flush();
        
        return $this->json($submission);
    }
}
