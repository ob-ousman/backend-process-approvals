<?php

namespace App\Controller;

use App\Entity\Submission;
use App\Entity\FieldValue;
use App\Entity\Form;
use App\Entity\LigneValidation;
use App\Entity\Validation;
use App\Repository\FormRepository;
use App\Repository\FieldRepository;
use App\Repository\SubmissionRepository;
use App\Repository\UserRepository;
use App\Repository\WorkflowRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/submissions')]
class SubmissionController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private FormRepository $formRepository,
        private SubmissionRepository $SubmissionRepository,
        private WorkflowRepository $workflowRepository,
        private FieldRepository $fieldRepository,
        private EntityManagerInterface $em
    ) {
    }

    #[Route('/create_submission', name: 'create_submission', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $this->em->getConnection()->beginTransaction();

        try {
            $form = $this->formRepository->find($data['form_id']);
            $user = $this->userRepository->find(1); // Il faudra changer cette ligne

            if (!$form || !$user) {
                throw new \Exception('Invalid form or user');
            }

            $submission = new Submission();
            $submission->setForm($form);
            $submission->setCreatedAt(new \DateTimeImmutable());
            $submission->setUser($user);
            $submission->setNumber($this->SubmissionRepository->getNextNumber());

            $this->em->persist($submission);
            $this->em->flush();

            foreach ($data['fields'] as $fieldData) {
                if ($fieldData['field'] != null && intval($fieldData['field'])) {
                    $field = $this->fieldRepository->find(intval($fieldData['field']));
                    if ($field != null) {
                        $fieldValue = new FieldValue();
                        $fieldValue->setField($field);
                        $fieldValue->setValue($fieldData['value']);
                        $fieldValue->setSubmission($submission);
                        //persist
                        $this->em->persist($fieldValue);
                        //add to submission for futture processing
                        $submission->addFieldValue($fieldValue);
                    }
                } else  throw new \Exception('Un champ a renvoyé un ID invalide');
            }

            $this->em->flush();

            //si tout se passe bien on crée la validation de la requete en fonction du workflow associé à ce formulaire
            $validation = $this->saveValidation($form, $submission);
            if (!$validation) {
                throw new \Exception('Echec creaction de la validation');
            }
            
            $this->em->getConnection()->commit();

            return $this->json($submission);
        } catch (\Exception $e) {
            $this->em->getConnection()->rollBack();
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function saveValidation(Form $form, Submission $submission) : Validation
    {
        // get workflow
        $workflow = $this->workflowRepository->find($form->getWorkflow());

        if(!$workflow) 
            return null; //workflow introuvable

        $validation = new Validation();
        $validation->setStatus(0);
        $validation->setSubmission($submission);

        $this->em->persist($validation);
        $this->em->flush();

        if(!$validation->getId()) 
            return null; //echec d'enregistrement validation

        $isPremierValidateur = true;
        foreach ($workflow->getLigneWorkflows() as $ligneWorkflow) {
            $ligneValidation = new LigneValidation();
            $ligneValidation->setNumero($ligneWorkflow->getNumero());
            $ligneValidation->setAction($ligneWorkflow->getAction());
            $ligneValidation->setCreatedAt(new \DateTimeImmutable());
            $ligneValidation->setValidation($validation);

            //si c'est la premiere etape, marquer comme statut en cours
            if($isPremierValidateur){
                $ligneValidation->setStatus(1);
                $isPremierValidateur = false;
            }else{
                $ligneValidation->setStatus(0);
            }

            if ($ligneWorkflow->getTypeDestinataire() == 1) {
                // Destinataire statique
                $ligneValidation->setDestinataire($ligneWorkflow->getDestinataire());
            } else if ($ligneWorkflow->getTypeDestinataire() === 2) {
                // destinataire dynamique
                foreach ($ligneWorkflow->getDestinataireDynamiques() as $destinataireDynamique) {
                    foreach ($submission->getFieldValues() as $fieldValue) { 
                        
                        //$ligneValidation->setDestinataire($fieldValue->getValue());
                        if (
                            $fieldValue->getField()->getId() === $destinataireDynamique->getField()->getId() &&
                            $fieldValue->getValue() == $destinataireDynamique->getValeur()
                        ) {
                            $ligneValidation->setDestinataire($destinataireDynamique->getDestinataire());
                            break 2; // Break out of both loops
                        }
                    }
                }
            }

            $this->em->persist($ligneValidation);
        }

        $this->em->flush();

        return $validation;
    }
}
