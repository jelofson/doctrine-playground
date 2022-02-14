<?php
namespace Blog\Entity;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class Tag 
{
    public static function loadMetadata(ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable('tag');
        $builder->createField('id', 'integer')->makePrimaryKey()->generatedValue()->build();
        $builder->addField('tag', 'string', ['length'=>50, 'unique'=>true])
                ->addOneToMany('postTags', PostTag::class, 'tag')
                ->addInverseManyToMany('posts', Post::class, 'tags');

        //$builder->createManyToMany('posts', Post::class)->setJoinTable('post_tag')->mappedBy('tags')->build();
    }
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $tag;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $postTags;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $posts;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->postTags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tag.
     *
     * @param string $tag
     *
     * @return Tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag.
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Add postTag.
     *
     * @param \Blog\Entity\PostTag $postTag
     *
     * @return Tag
     */
    public function addPostTag(\Blog\Entity\PostTag $postTag)
    {
        $this->postTags[] = $postTag;

        return $this;
    }

    /**
     * Remove postTag.
     *
     * @param \Blog\Entity\PostTag $postTag
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePostTag(\Blog\Entity\PostTag $postTag)
    {
        return $this->postTags->removeElement($postTag);
    }

    /**
     * Get postTags.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPostTags()
    {
        return $this->postTags;
    }

    /**
     * Add post.
     *
     * @param \Blog\Entity\Post $post
     *
     * @return Tag
     */
    public function addPost(\Blog\Entity\Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post.
     *
     * @param \Blog\Entity\Post $post
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePost(\Blog\Entity\Post $post)
    {
        return $this->posts->removeElement($post);
    }

    /**
     * Get posts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }
}
