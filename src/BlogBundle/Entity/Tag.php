<?php

namespace BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Tags
 *
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\TagRepository")
 * @ORM\Table(name="blog_tags")
 */
class Tag extends AbstractTaxonomy
{

    /**
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="tags")
     */
    protected $posts;

}
