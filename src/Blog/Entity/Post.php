<?php
namespace Blog\Entity;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class Post implements EntityInterface
{
    public static function loadMetadata(ClassMetadata $metadata)
    {
        
        // See Doctrine\Dbal\Types\Types for the type names
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable('post');
        $builder->createField('id', 'integer')->makePrimaryKey()->generatedValue()->build();

        $builder->addField('title', 'string')
                ->addField('post', 'text')
                ->addField('created', 'datetime')
                ->addField('updated', 'datetime')
                ->addIndex(['user_id'], 'user_id')
                ->addOneToMany('postTags', PostTag::class, 'post')
                ->addOwningManyToMany('tags', Tag::class, 'posts')
                ->addLifecycleEvent('validate', 'preFlush');

        // This is an owning side many to many - Only the owning side is monitored for changes
        $builder->createManyToOne('author', User::class)
                ->addJoinColumn('user_id', 'id')
                ->build();

        //$builder->createManyToMany('tags', Tag::class)->setJoinTable('post_tag')->inversedBy('posts')->build();

    }
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $post;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var \Blog\Entity\User
     */
    private $author;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $postTags;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tags;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->postTags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title.
     *
     * @param string $title
     *
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set post.
     *
     * @param string $post
     *
     * @return Post
     */
    public function setPost($post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post.
     *
     * @return string
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return Post
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated.
     *
     * @param \DateTime $updated
     *
     * @return Post
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated.
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set author.
     *
     * @param \Blog\Entity\User|null $author
     *
     * @return Post
     */
    public function setAuthor(\Blog\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author.
     *
     * @return \Blog\Entity\User|null
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Add postTag.
     *
     * @param \Blog\Entity\PostTag $postTag
     *
     * @return Post
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
     * Add tag.
     *
     * @param \Blog\Entity\Tag $tag
     *
     * @return Post
     */
    public function addTag(\Blog\Entity\Tag $tag)
    {
        $this->tags[] = $tag;
        // Could do this to the inverse side, but not needed as Post is owning side?
        //$tag->addPost($this);

        return $this;
    }

    /**
     * Remove tag.
     *
     * @param \Blog\Entity\Tag $tag
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTag(\Blog\Entity\Tag $tag)
    {
        return $this->tags->removeElement($tag);
    }

    /**
     * Get tags.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    public function getTagsAsString(string $separator = ', ')
    {
        $tags = [];
        foreach ($this->getTags() as $tag) {
            $tags[] = $tag->getTag();
        }

        return implode($separator, $tags);
    }

    public function validate()
    {
        //exit('something');
    }
    
}
