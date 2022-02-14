<?php $this->layout('layouts::default'); ?>

<h1><?=$post->getTitle(); ?></h1>

<p>
    <?=$post->getCreated()->format('Y-m-d H:i:s'); ?> by 
    <a href="<?=$router->urlFor('author', ['id'=>$post->getAuthor()->getId()]); ?>"><?=$post->getAuthor()->getFirstName(); ?></a>
</p>
<p>  
    <?php foreach ($post->getTags() as $tag) : ?>
        <a href="<?=$router->urlFor('tag', ['id'=>$tag->getId()]); ?>" class="btn btn-sm btn-primary position-relative me-2"><?=$tag->getTag(); ?> 
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?=count($tag->getPosts()); ?></span></a>
    <?php endforeach; ?>
</p>



<p><?=nl2br($post->getPost()); ?></p>
<p>
    <a href="<?=$router->urlFor('post-edit', ['id'=>$post->getId()]); ?>" class="btn btn-secondary">Edit</a>
    <a href="<?=$router->urlFor('post-delete', ['id'=>$post->getId()]); ?>" class="btn btn-danger">Delete</a>

</p>
<hr>
<p><a href="<?=$router->urlFor('posts'); ?>">Posts</a></p>