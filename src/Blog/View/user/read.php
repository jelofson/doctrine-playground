<?php $this->layout('layouts::default'); ?>

<h1><?=$this->e($author->getFullName()); ?></h1>

<h2>Posts</h2>

<?php foreach ($author->getPosts() as $post) : ?>
<p>
    <strong><a href="<?=$router->urlFor('post', ['id'=>$post->getId()]); ?>"><?=$post->getTitle(); ?></a></strong>
    <br>
    Written on <?=$post->getCreated()->format('Y-m-d H:i:s'); ?> by <a href="<?=$router->urlFor('author', ['id'=>$post->getAuthor()->getId()]); ?>"><?=$post->getAuthor()->getFirstName(); ?></a>
</p>
<?php endforeach; ?>