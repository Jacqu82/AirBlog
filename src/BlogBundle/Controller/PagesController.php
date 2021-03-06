<?php

namespace BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PagesController extends Controller
{
    /**
     * @Route("/about",
     *     name="blog_about")
     *
     * @Template()
     */
    public function aboutAction()
    {
        return [];
    }

    /**
     * @Route("/contact",
     *     name="blog_contact")
     *
     * @Template()
     */
    public function contactAction()
    {
        return [];
    }
}
