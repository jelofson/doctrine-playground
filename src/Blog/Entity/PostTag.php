<?php
namespace Blog\Entity;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class PostTag 
{
    public static function loadMetadata(ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable('post_tag');
        $builder->createField('id', 'integer')->makePrimaryKey()->generatedValue()->build();
        $builder->addIndex(['post_id'], 'post_id')
                ->addIndex(['tag_id'], 'tag_id')
                ->addManyToOne('post', Post::class, 'postTags')
                ->addManyToOne('tag', Tag::class, 'postTags');
    }
    /**
     * @var int
     */
    private $id;

    /**
     * @var \Blog\Entity\Post
     */
    private $post;

    /**
     * @var \Blog\Entity\Tag
     */
    private $tag;


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
     * Set post.
     *
     * @param \Blog\Entity\Post|null $post
     *
     * @return PostTag
     */
    public function setPost(\Blog\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post.
     *
     * @return \Blog\Entity\Post|null
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set tag.
     *
     * @param \Blog\Entity\Tag|null $tag
     *
     * @return PostTag
     */
    public function setTag(\Blog\Entity\Tag $tag = null)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag.
     *
     * @return \Blog\Entity\Tag|null
     */
    public function getTag()
    {
        return $this->tag;
    }
}
