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
    #[Route('/', name: 'app_cow_home')]
    public function home(CowRepository $cowRepository): Response {
        $cows = $cowRepository->findByisAlive();
        $allFood = 0.0;
        $allMilk = 0.0;
        $total = count($cows);
        
        foreach($cows as $cow) {
            $allFood += $cow->getFoodAmount();
            $allMilk += $cow->getMilkAmount();
        }

        $cows = array_filter($cows, function($cow) {
            return $cow->getCowYear() <= 1 && $cow->getFoodAmount() > 500;
        });
        
        return $this->render('cow/home.html.twig', [
            'cows' => $cows,
            'milk' => $allMilk,
            'food' => $allFood,
            'total' => $total,
        ]);
    }

    #[Route('/cow', name: 'app_cow_index', methods: ['GET'])]
    public function index(CowRepository $cowRepository): Response
    {
        $cows = $cowRepository->findAll();
        $new = array_map( function ($cow) {
            return $cow = $cow->setAbate($cow);
        }, $cows);

        foreach($new as $cow) {
            $cowRepository->save($cow, true);
        }
       
        return $this->render('cow/index.html.twig', [
            'cows' => $new,
        ]);
    }

    #[Route('/list', name: 'app_cow_list', methods: ['GET'])]
    public function abateList(CowRepository $cowRepository): Response {
        $cows = $cowRepository->findByAbate();
        return $this->render('cow/listAbate.html.twig', ['cows' => $cows]);
    }

    #[Route('/list/dead', name: 'app_cow_dead_list', methods: ['GET'])]
    public function deadCowList(CowRepository $cowRepository): Response {
        $cows = $cowRepository->findByIsNotAlive();
        return $this->render('cow/deadlistAbate.html.twig', ['cows' => $cows]);
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

    #[Route('/{id}/edit/abate', name: 'app_cow_edit_abate')]
    public function abateCow(Cow $cow, CowRepository $cowRepository): Response {
        $cow->setIsAlive(false);
        $cowRepository->save($cow, true);
        return $this->redirectToRoute('app_cow_index', [], Response::HTTP_SEE_OTHER);
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
