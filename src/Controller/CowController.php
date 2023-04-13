<?php

namespace App\Controller;

use App\Entity\Cow;
use App\Form\CowType;
use App\Repository\CowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cow')]
class CowController extends AbstractController
{
    #[Route('/', name: 'app_cow_index', methods: ['GET'])]
    public function index(CowRepository $cowRepository): Response
    {
        return $this->render('cow/index.html.twig', [
            'cows' => $cowRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cow_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CowRepository $cowRepository): Response
    {
        $cow = new Cow();
        $form = $this->createForm(CowType::class, $cow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cowRepository->save($cow, true);

            return $this->redirectToRoute('app_cow_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cow/new.html.twig', [
            'cow' => $cow,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cow_show', methods: ['GET'])]
    public function show(Cow $cow): Response
    {
        return $this->render('cow/show.html.twig', [
            'cow' => $cow,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cow_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cow $cow, CowRepository $cowRepository): Response
    {
        $form = $this->createForm(CowType::class, $cow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cowRepository->save($cow, true);

            return $this->redirectToRoute('app_cow_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cow/edit.html.twig', [
            'cow' => $cow,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cow_delete', methods: ['POST'])]
    public function delete(Request $request, Cow $cow, CowRepository $cowRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cow->getId(), $request->request->get('_token'))) {
            $cowRepository->remove($cow, true);
        }

        return $this->redirectToRoute('app_cow_index', [], Response::HTTP_SEE_OTHER);
    }
}
