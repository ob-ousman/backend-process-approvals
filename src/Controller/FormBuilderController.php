<?php

namespace App\Controller;

use App\Entity\Form;
use App\Entity\Field;
use App\Repository\FormRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/form-builder')]
class FormBuilderController extends AbstractController
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

    #[Route('', name: 'create_full_form', methods: ['POST'])]
    public function createFullForm(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['title']) || !isset($data['description']) || !isset($data['fields'])) {
            return $this->json(['error' => 'Invalid data.'], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->beginTransaction();

        try {
            // Create the form
            $form = new Form();
            $form->setTitle($data['title']);
            $form->setDescription($data['description']);
            $form->setCreatedAt(new \DateTimeImmutable());
            $form->setUpdatedAt(new \DateTimeImmutable());

            // Validate the form
            $formErrors = $this->validator->validate($form);
            if (count($formErrors) > 0) {
                $this->entityManager->rollback();
                $errorsString = (string) $formErrors;
                return $this->json(['error' => $errorsString], Response::HTTP_BAD_REQUEST);
            }

            $this->entityManager->persist($form);
            $this->entityManager->flush();

            // Create fields
            foreach ($data['fields'] as $fieldData) {
                $field = new Field();
                $field->setLabel($fieldData['label']);
                $field->setType($fieldData['type']);
                $field->setRequired($fieldData['required'] ?? false);

                if (isset($fieldData['options']) && is_array($fieldData['options'])) {
                    $field->setOptions($fieldData['options']);
                } else {
                    $field->setOptions(null);
                }

                $field->setForm($form);

                // Validate the field
                $fieldErrors = $this->validator->validate($field);
                if (count($fieldErrors) > 0) {
                    $this->entityManager->rollback();
                    $errorsString = (string) $fieldErrors;
                    return $this->json(['error' => $errorsString], Response::HTTP_BAD_REQUEST);
                }

                $this->entityManager->persist($field);
            }

            $this->entityManager->flush();
            $this->entityManager->commit();

            return $this->json($form, Response::HTTP_CREATED, [], ['groups' => 'form:read']);

        } catch (\Exception $e) {
            $this->entityManager->rollback();
            return $this->json(['error' => 'Aune erreur est survenue lors de la création du formulaire.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}