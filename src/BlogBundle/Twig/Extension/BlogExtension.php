<?php

namespace BlogBundle\Twig\Extension;

use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Symfony\Component\Security\Core\SecurityContext;


class BlogExtension extends \Twig_Extension {
    
    /**
     * @var Doctrine
     */
    private $doctrine;
    
    /**
     * @var SecurityContext
     */
    private $securityContext;
        
    /**
     *
     * @var \Twig_Environment
     */
    private $environment;
    
    private $categoriesList;
    
            
    function __construct(Doctrine $doctrine, SecurityContext $securityContext) {
        $this->doctrine = $doctrine;
        $this->securityContext = $securityContext;
    }
    
    public function initRuntime(\Twig_Environment $environment) {
        $this->environment = $environment;
    }
    
    public function getName() {
        return 'air_blog_extension';
    }
    
    
    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('print_categories_list', array($this, 'printCategoriesList'), array('is_safe' => array('html'), 'needs_environment' => true)),
            new \Twig_SimpleFunction('print_main_menu', array($this, 'printMainMenu'), array('is_safe' => array('html'), 'needs_environment' => true)),
            new \Twig_SimpleFunction('print_tags_cloud', array($this, 'tagsCloud'), array('is_safe' => array('html'), 'needs_environment' => true)),
            new \Twig_SimpleFunction('print_recent_commented', array($this, 'recentCommented'), array('is_safe' => array('html')))
        );
    }
    
    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('ab_shorten', array($this, 'shorten'), array('is_safe' => array('html')))
        );
    }

    public function printCategoriesList(\Twig_Environment $environment){
        if(!isset($this->categoriesList)){
            $CategoryRepo = $this->doctrine->getRepository('BlogBundle:Category');
            $this->categoriesList = $CategoryRepo->findAll();
        }
        
        return $environment->render('BlogBundle:Template:categoriesList.html.twig', array(
            'categoriesList' => $this->categoriesList
        ));
    }
    
    public function printMainMenu(\Twig_Environment $environment){
        $mainMenu = array(
            'home' => 'blog_index',
            'o mnie' => 'blog_about',
            'kontakt' => 'blog_contact'
        );
        
        if($this->securityContext->isGranted('ROLE_EDITOR')){
            $mainMenu['admin'] = 'admin_dashboard';
        }
        
        return $environment->render('BlogBundle:Template:mainMenu.html.twig', array(
            'mainMenu' => $mainMenu
        ));
    }
    
    public function tagsCloud(\Twig_Environment $environment, $limit = 40, $minFontSize = 1, $maxFontSize = 3.5) {
        $TagRepo = $this->doctrine->getRepository('BlogBundle:Tag');
        $tagsList = $TagRepo->getTagsListOcc();
        $tagsList = $this->prepareTagsCloud($tagsList, $limit, $minFontSize, $maxFontSize);
        
        return $environment->render('BlogBundle:Template:tagsCloud.html.twig', array(
            'tagsList' => $tagsList
        ));
    }
    
    protected function prepareTagsCloud($tagsList, $limit, $minFontSize, $maxFontSize){
        $occs = array_map(function($row){
            return (int)$row['occ'];
        }, $tagsList);
        
        $minOcc = min($occs);
        $maxOcc = max($occs);
        
        $spread = $maxOcc - $minOcc;
        
        $spread = ($spread == 0) ? 1 : $spread;
        
        usort($tagsList, function($a, $b){
            $ao = $a['occ'];
            $bo = $b['occ'];
            
            if($ao === $bo) return 0;
            
            return ($ao < $bo) ? 1 : -1;
        });
        
        $tagsList = array_slice($tagsList, 0, $limit);
        
        shuffle($tagsList);
        
        foreach($tagsList as &$row){
            $row['fontSize'] = round(($minFontSize + ($row['occ'] - $minOcc) * ($maxFontSize - $minFontSize) / $spread), 2);
        }

        return $tagsList;
    }
    
    public function shorten($text, $length = 200, $wrapTag = 'p') {
        $text = strip_tags($text);
        $text = substr($text, 0, $length).'[...]';
        $openTag = "<{$wrapTag}>";
        $closeTag = "</{$wrapTag}>";
        
        return $openTag.$text.$closeTag;
    }
    
    public function recentCommented($limit = 3){
        
        $PostRepo = $this->doctrine->getRepository('BlogBundle:Post');
        
        $postsList = $PostRepo->getRecentCommented($limit);
        
        return $this->environment->render('BlogBundle:Template:recentCommented.html.twig', array(
            'postsList' => $postsList
        ));
    }

}
