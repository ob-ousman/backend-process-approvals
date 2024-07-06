<?php

namespace App\Controller;

use App\Entity\Workflow;
use App\Entity\LigneWorkflow;
use App\Entity\DestinataireDynamique;
use App\Entity\Form;
use App\Entity\Field;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Exception\RetryableException;

class WorkflowController extends AbstractController
{
    #[Route('/api/create_workflow', name: 'create_workflow', methods: ['POST'])]
    public function createWorkflow(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $entityManager->beginTransaction();

        try {
            // Récupérer le formulaire
            $form = $entityManager->getRepository(Form::class)->find($data['form']);
            if (!$form) {
                throw new \Exception('Erreur! Formulaire associé inexistant.');
            }

            // Créer le workflow
            $workflow = new Workflow();
            $workflow->setNom("Workflow " . $form->getTitle());
            $workflow->setForm($form);
            $workflow->setCreatedAt(new \DateTimeImmutable());

            $ligneWorkflows = [];

            // Ajouter les lignes workflow
            if ($data['ligneWorkflows'] != null) {
                $numero = 1;
                foreach ($data['ligneWorkflows'] as $ligneWorkflowData) {
                    $ligneWorkflow = new LigneWorkflow();
                    $ligneWorkflow->setTypeDestinataire($ligneWorkflowData['typeDestinataire']);
                    $ligneWorkflow->setAction($ligneWorkflowData['action']);
                    $ligneWorkflow->setDestinataire($ligneWorkflowData['destinataire']);
                    $ligneWorkflow->setWorkflow($workflow); // Lier la ligne de workflow au workflow
                    $ligneWorkflow->setNumero($numero);

                    $destinataireDynamiques = [];

                    // Ajouter les destinataires dynamiques
                    if($ligneWorkflowData['destinataireDynamiques'] != null){
                        foreach ($ligneWorkflowData['destinataireDynamiques'] as $destinataireDynamiqueData) {
                            $destinataireDynamique = new DestinataireDynamique();
                            $destinataireDynamique->setLogique($destinataireDynamiqueData['logique']);
                            $destinataireDynamique->setValeur($destinataireDynamiqueData['valeur']);
                            $destinataireDynamique->setDestinataire($destinataireDynamiqueData['destinataire']);

                            $field = $entityManager->getRepository(Field::class)->find($destinataireDynamiqueData['field']);
                            if (!$field) {
                                throw new \Exception('Erreur! Field associé inexistant.');
                            }
                            $destinataireDynamique->setField($field);
                            $destinataireDynamique->setLigneWorkflow($ligneWorkflow);

                            $entityManager->persist($destinataireDynamique);
                            $destinataireDynamiques[] = $destinataireDynamique;
                        }
                    }

                    foreach ($destinataireDynamiques as $destinataireDynamique) {
                        $ligneWorkflow->addDestinataireDynamiques($destinataireDynamique);
                    }

                    $entityManager->persist($ligneWorkflow);
                    $ligneWorkflows[] = $ligneWorkflow;
                }
                foreach ($ligneWorkflows as $ligneWorkflow) {
                    $workflow->addLigneWorkflow($ligneWorkflow);
                }
            }

            $entityManager->persist($workflow);
            $entityManager->flush();
            $entityManager->commit();

            return $this->json(['message' => 'Workflow enregistré avec succès !', 'id' => $workflow->getId()], 201);

        } catch (\Exception $e) {
            $entityManager->rollback();
            return $this->json(['error' => $e->getMessage()], 400);
        } catch (RetryableException $retryableException) {
            $entityManager->rollback();
            return $this->json(['error' => 'Erreur de la base de données, veuillez réessayer.'], 500);
        }
    }
}
