<?php $this->layout('layouts::default'); ?>

<h1>Posts</h1>


<?php foreach ($posts as $post) : ?>
<h4><a href="<?=$router->urlFor('post', ['id'=>$post->getId()]); ?>"><?=$post->getTitle(); ?></a></h5>
<p><?=$post->getCreated()->format('Y-m-d H:i:s'); ?> by <a href="<?=$router->urlFor('author', ['id'=>$post->getAuthor()->getId()]); ?>"><?=$post->getAuthor()->getFirstName(); ?></a></p>
<p>
<?php foreach ($post->getTags() as $tag) : ?>
        <a href="<?=$router->urlFor('tag', ['id'=>$tag->getId()]); ?>" class="btn btn-sm btn-primary position-relative me-2"><?=$tag->getTag(); ?> 
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?=count($tag->getPosts()); ?></span></a>
    <?php endforeach; ?>
</p>
<hr>
<?php endforeach; ?>