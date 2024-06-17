<?php

namespace App\Controller;

use App\Entity\Field;
use App\Repository\FormRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/fields')]
class FieldController extends AbstractController
{
    private $entityManager;
    private $serializer;
    private $validator;
    private $formRepository;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator, FormRepository $formRepository)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->formRepository = $formRepository;
    }
    #[Route('/{id}', name: 'get_field', methods: ['GET'])]
    public function getField(Field $field): Response
    {
        return $this->json($field, Response::HTTP_OK, [], ['groups' => 'field:read']);
    }

    #[Route('', name: 'create_field', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $field = new Field();
        $field->setLabel($data['label']);
        $field->setType($data['type']);
        $field->setRequired($data['required']);

        // Handle options if they are provided
        if (isset($data['options']) && is_array($data['options'])) {
            $field->setOptions($data['options']);
        } else {
            $field->setOptions(null);
        }

        // Handle form association if provided
        if (isset($data['form'])) {
            $form = $this->formRepository->find($data['form']);
            if ($form) {
                $field->setForm($form);
            } else {
                return $this->json(['error' => 'Invalid form ID'], Response::HTTP_BAD_REQUEST);
            }
        }

        // Validate the field
        $errors = $this->validator->validate($field);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return $this->json(['error' => $errorsString], Response::HTTP_BAD_REQUEST);
        }

        // Persist the field
        $this->entityManager->persist($field);
        $this->entityManager->flush();

        return $this->json($field, Response::HTTP_CREATED, [], ['groups' => 'field:read']);
    }
    
}
