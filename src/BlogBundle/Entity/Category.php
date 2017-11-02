<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Category
 *
 * @ORM\Entity
 * @ORM\Table(name="blog_categories")
 */
class Category extends AbstractTaxonomy
{

    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="category")
     */
    protected $posts;

}
