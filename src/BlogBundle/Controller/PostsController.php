<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Category;
use BlogBundle\Entity\Post;
use BlogBundle\Entity\Tag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class PostsController extends Controller
{

    protected $itemsLimit = 3;

    /**
     * @Route(
     *     "/{page}",
     *     name="blog_index",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"}
     * )
     *
     * @Template("BlogBundle:Posts:postsList.html.twig")
     */
    public function indexAction($page)
    {
        $pagination = $this->getPaginatedPosts([
            'status' => 'published',
            'orderBy' => 'p.publishedDate',
            'orderDir' => 'DESC'
        ], $page);

        return ['pagination' => $pagination, 'listTitle' => 'Najnowsze wpisy'];
    }


    /**
     * @Route(
     *     "/search/{page}",
     *     name="blog_search",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"}
     * )
     *
     * @Template("BlogBundle:Posts:postsList.html.twig")
     */
    public function searchAction(Request $request, $page)
    {
        $searchParam = $request->query->get('search');

        $pagination = $this->getPaginatedPosts([
            'status' => 'published',
            'orderBy' => 'p.publishedDate',
            'orderDir' => 'DESC',
            'search' => $searchParam
        ], $page);

        return [
            'pagination' => $pagination,
            'listTitle' => sprintf('Wyniki wyszukiwania frazy "%s"', $searchParam),
            'searchParam' => $searchParam
        ];
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
        $postRepo = $this->getDoctrine()->getRepository(Post::class);
        $post = $postRepo->getPublishedPost($slug);

        if ($post === null) {
            throw $this->createNotFoundException('Post nie został odnaleziony!');
        }

        return ['post' => $post];
    }

    /**
     * @Route(
     *     "/category/{slug}/{page}",
     *     name="blog_category",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"}
     * )
     *
     * @Template("BlogBundle:Posts:postsList.html.twig")
     */
    public function categoryAction($slug, $page)
    {
        $pagination = $this->getPaginatedPosts([
            'status' => 'published',
            'orderBy' => 'p.publishedDate',
            'orderDir' => 'DESC',
            'categorySlug' => $slug
        ], $page);

        $categoryRepo = $this->getDoctrine()->getRepository(Category::class);
        $category = $categoryRepo->findOneBySlug($slug);

        return [
            'pagination' => $pagination,
            'listTitle' => sprintf('Wpisy w kategorii "%s"', $category->getName())
        ];
    }

    /**
     * @Route(
     *     "/tag/{slug}/{page}",
     *     name="blog_tag",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"}
     * )
     *
     * @Template("BlogBundle:Posts:postsList.html.twig")
     */
    public function tagAction($slug, $page)
    {
        $tagRepo = $this->getDoctrine()->getRepository(Tag::class);
        $tag = $tagRepo->findOneBySlug($slug);

        $pagination = $this->getPaginatedPosts([
            'status' => 'published',
            'orderBy' => 'p.publishedDate',
            'orderDir' => 'DESC',
            'tagSlug' => $slug
        ], $page);

        return [
            'pagination' => $pagination,
            'listTitle' => sprintf('Wpisy z tagiem "%s"', $tag->getName())
        ];
    }

    protected function getPaginatedPosts(array $params = [], $page)
    {
        $postRepo = $this->getDoctrine()->getRepository(Post::class);
        $qb = $postRepo->getQueryBuilder($params);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $this->itemsLimit);

        return $pagination;
    }
}
