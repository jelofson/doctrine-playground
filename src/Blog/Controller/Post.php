<?php
namespace Blog\Controller;

use Blog\Entity\EntityInterface;
use Blog\Entity\Post as PostEntity;
use Blog\Entity\User as UserEntity;
use Blog\Entity\Tag as TagEntity;

// Type hints for some methods available via __get (in the container)
/**
 * @property \Doctrine\ORM\EntityManager $entityManager
 */

/**
 * @property \League\Plates\Engine $view
 */

 /**
 * @property \Vespula\Form\Form $form
 */

class Post extends Controller
{
    public function browse()
    {
        $entityManager = $this->entityManager;
        $postRepository = $entityManager->getRepository(PostEntity::class);
        $allposts = $postRepository->findAll();

        $lastFive = $postRepository->findBy([], ['created'=>'DESC'], 5);

        

        $content = $this->view->render('post/browse', [
            'posts'=>$lastFive
        ]);

        return $this->asHtml($content);
    }

    public function read($id)
    {
        $post = $this->entityManager->find(PostEntity::class, $id);

        if (! $post) {
            return $this->notFound();
        }


        // will be (int) 1 or UnitOfWork::STATE_MANAGED (so no persist needed as it is already managed)
        //var_dump($this->entityManager->getUnitOfWork()->getEntityState($post));

        /*
        $post->getTags()->clear();
        $this->entityManager->persist($post);
        $this->entityManager->remove($post);
        $this->entityManager->flush();
        return $this->redirect('/');
        */
        $content = $this->view->render('post/read', [
            'post'=>$post
        ]);

        return $this->asHtml($content);

    }

    public function add()
    {
        $post = new PostEntity();

        // will be (int) 2 or UnitOfWork::STATE_NEW (so persist is needed to make it managed)
        //var_dump($this->entityManager->getUnitOfWork()->getEntityState($post));

        
        $faker = \Faker\Factory::create();
        $post->setPost($faker->paragraphs(2, true));
        $content = $this->buildForm($post, self::ACTION_ADD);

        return $this->asHtml($content);
    }
    public function doAdd()
    {
        $post = new PostEntity();
        $data = $this->sanitizePost();

        $author = $this->entityManager->find(UserEntity::class, $data['user_id']);

        $post->setCreated(new \DateTime());
        $post->setUpdated(new \DateTime());
        $post->setTitle($data['title']);
        $post->setPost($data['post']);
        $post->setAuthor($author);

        $this->manageTags($post, $data['tags']);

        try {
            // Must persist to get the new auto ID for the record. (managed) 
            $this->entityManager->persist($post); 
            //var_dump($this->entityManager->getUnitOfWork()->getEntityState($post)); exit; (MANAGED now!)
            $this->entityManager->flush();
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
        

        $href = $this->router->urlFor('post', ['id'=>$post->getId()]);
        return $this->redirect($href);
        
    }

    public function edit($id)
    {
        $post = $this->entityManager->find(PostEntity::class, $id);

        if (! $post) {
            return $this->notFound();
        }

        $content = $this->buildForm($post, self::ACTION_EDIT);

        return $this->asHtml($content);
    }
    public function doEdit($id)
    {
        $post = $this->entityManager->find(PostEntity::class, $id);

        if (! $post) {
            return $this->notFound();
        }
        $data = $this->sanitizePost();

        $author = $this->entityManager->find(UserEntity::class, $data['user_id']);

        $post->setUpdated(new \DateTime());
        $post->setTitle($data['title']);
        $post->setPost($data['post']);
        $post->setAuthor($author);

        $this->manageTags($post, $data['tags']);

        
        try {
            // $this->entityManager->persist($post);
            // Persist not technically required as the ID won't be new and we aren't doing anthing after
            $this->entityManager->flush();
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
        

        $href = $this->router->urlFor('post', ['id'=>$post->getId()]);
        return $this->redirect($href);
        
    }

    public function delete($id)
    {

        $post = $this->entityManager->find(PostEntity::class, $id);

        if (! $post) {
            return $this->notFound();
        }
        
        $post->getTags()->clear();

        $this->entityManager->remove($post);
        $this->entityManager->flush();
        return $this->redirect($this->router->urlFor('posts'));

    }

    protected function buildForm(PostEntity $post, string $action): string
    {
        $metadata = $this->entityManager->getClassMetadata(get_class($post));
        $data = $this->toArray($metadata, $post);

        $author = $post->getAuthor();
        if ($author) {
            $data['user_id'] = $author->getId();
        }
        

        // Just to show how you can get metadata about the entity
        $table_name = $metadata->getTableName();

        $authorRepository = $this->entityManager->getRepository(UserEntity::class);
        $authors = $authorRepository->findBy([], ['lastName'=>'ASC']);
        $author_opts = [''=>'------'];
        
        foreach ($authors as $author) {
            $author_opts[$author->getId()] = $author->getFullName();
        }

        $builder = $this->form->getBuilder();
        $builder->setColumnOptions([
            'user_id'=>[
                'type'=>'select',
                'callback'=>function ($element) use ($author_opts) {
                    $element->options($author_opts);
                }
            ]
        ]);

        $this->form->build($table_name, $data);
        //$csrf = $this->form->hidden()->idName('csrf_token')->value($this->session->getCsrfToken()->getValue());
        //$this->form->addElement('csrf', $csrf);

        switch ($action) {
            case self::ACTION_ADD :
                $page_title = "Add Post";
                $cancel_href = $this->router->urlFor('posts');
            break;
            case self::ACTION_EDIT :
                $page_title = "Add Post";
                $cancel_href = $this->router->urlFor('post', ['id'=>$post->getId()]);
            break;
        }
        

        $content = $this->view->render('post/form', [
            'form'=>$this->form,
            'post'=>$post,
            'action'=>$action,
            'page_title'=>$page_title,
            'cancel_href'=>$cancel_href
        ]);

        return $content;
    }

    protected function manageTags(PostEntity $post, string $tags)
    {
        $tags = trim($tags);
        
        $post->getTags()->clear(); // does not actually delete (marked removed in UoW)
        /*
        foreach ($post->getTags() as $tag) {
            $post->removeTag($tag);
        }
        */

        $tags_array = explode(',', $tags);
        foreach ($tags_array as $tag_string) {
            $tag_string = trim($tag_string);
            $tag = $this->entityManager->getRepository(TagEntity::class)->findOneBy(['tag'=>$tag_string]);
            if (! $tag) {
                $tag = new TagEntity();
                $tag->setTag($tag_string);
                
            }
            $post->addTag($tag); // will not add new rows in the join table if the tag was already used
                                 // - smart enough to re-use if they exist
            $this->entityManager->persist($tag);
        }
    }
    
}