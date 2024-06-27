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
}
