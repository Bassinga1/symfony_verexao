<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/contract')]
class AdminContractController extends AbstractController
{
    #[Route('/', name: 'app_admin_contract_index', methods: ['GET'])]
    public function index(ContactRepository $contactRepository): Response
    {
        return $this->render('admin_contract/index.html.twig', [
            'contacts' => $contactRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_contract_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_contract_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_contract/new.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_contract_show', methods: ['GET'])]
    public function show(Contact $contact): Response
    {
        return $this->render('admin_contract/show.html.twig', [
            'contact' => $contact,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_contract_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contact $contact, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_contract_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_contract/edit.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_contract_delete', methods: ['POST'])]
    public function delete(Request $request, Contact $contact, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
            $entityManager->remove($contact);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_contract_index', [], Response::HTTP_SEE_OTHER);
    }
}
