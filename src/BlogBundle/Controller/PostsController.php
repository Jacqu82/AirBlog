<?php

namespace BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PostsController extends Controller
{
    /**
     * @Route(
     *     "/{page}",
     *     name="blog_index",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"}
     * )
     *
     * @Template()
     */
    public function indexAction($page)
    {

        return [];
    }

    /**
     * @Route(
     *     "/{slug}",
     *     name="blog_post"
     * )
     *
     * @Template()
     */
    public function postAction($slug)
    {
        return [];
    }

    /**
     * @Route(
     *     "/category/{slug}/{page}",
     *     name="blog_category",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"}
     * )
     *
     * @Template()
     */
    public function categoryAction($slug)
    {
        return [];
    }

    /**
     * @Route(
     *     "/tag/{slug}/{page}",
     *     name="blog_tag",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"}
     * )
     *
     * @Template()
     */
    public function tagAction($slug)
    {
        return [];
    }
}
