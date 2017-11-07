<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CategoryController extends Controller
{
    /**
     * @Route("/categories", name="show_categories")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCategories()
    {
        $categoryRepo = $this->getDoctrine()->getRepository(Category::class);
        $categoriesList = $categoryRepo->findAll();

        return $this->render('::base.html.twig', [
            'categoriesList' => $categoriesList
        ]);
    }
}
