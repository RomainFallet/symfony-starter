<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CatRepository;
use App\Entity\Cat;
use App\Form\CatType;

class CatController extends AbstractController
{
    /**
     * @Route("/", name="list_cat")
     */
    public function index(CatRepository $catRepository): Response
    {
        return $this->render('cat/list.html.twig', [
            'cats' => array_reverse($catRepository->findAll()),
        ]);
    }

    /**
     * @Route("/add", name="add_cat")
     */
    public function add(
        Request $request,
        CatRepository $catRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $cat = new Cat();
        $cat->setUrl($catRepository->getRandomUrl());

        $form = $this->createForm(CatType::class, $cat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cat = $form->getData();
            $entityManager->persist($cat);
            $entityManager->flush();
            return $this->redirectToRoute('list_cat');
        }

        return $this->render('cat/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
