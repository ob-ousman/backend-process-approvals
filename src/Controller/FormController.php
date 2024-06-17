<?php

namespace App\Controller;

use App\Entity\Form;
use App\Entity\Field;
use App\Repository\FieldRepository;
use App\Repository\FormRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/forms')]
class FormController extends AbstractController
{
    public function __construct(
        private FormRepository $formRepository, 
        private FieldRepository $fieldRepository,
        private EntityManagerInterface $entityManager)
    {}

    #[Route('', name: 'new_form', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $em)
    {
        $data = json_decode($request->getContent(), true);

        $form = new Form();
        $form->setTitle($data['title']);
        $form->setDescription($data['description']);
        $form->setCreatedAt(new \DateTimeImmutable());
        $form->setUpdatedAt(new \DateTimeImmutable());
        // Associez un utilisateur si nécessaire
        // $form->setUser($this->getUser());
        $em->persist($form);
        $em->flush();
    }

    public function create(Request $request, EntityManagerInterface $em)
    {
        $data = json_decode($request->getContent(), true);

        $form = new Form();
        $form->setTitle($data['title']);
        $form->setDescription($data['description']);
        $form->setCreatedAt(new \DateTimeImmutable());
        $form->setUpdatedAt(new \DateTimeImmutable());
        // Associez un utilisateur si nécessaire
        // $form->setUser($this->getUser());

        $this->entityManager->persist($form);
        $this->entityManager->flush();

        // Ajouter les champs
        foreach ($data['fields'] as $fieldData) {
            $field = new Field();
            $field->setLabel($fieldData['label']);
            $field->setType($fieldData['type']);
            $field->setRequired($fieldData['required']);
            $field->setOptions($fieldData['options'] ?? []);
            $field->setForm($form);

            $this->entityManager->persist($field);
            $this->entityManager->flush();
        }

        return $this->json($form, Response::HTTP_CREATED, [], ['groups' => 'form:read']);
    }

    #[Route('/{id}', name: 'get_form', methods: ['GET'])]
    public function getForm(Form $form): Response
    {
        return $this->json($form, Response::HTTP_OK, [], ['groups' => 'form:read']);
    }
}
