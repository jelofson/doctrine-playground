<?php $this->layout('layouts::default'); ?>

<h1>TAG: <?=$this->e($tag->getTag()); ?></h1>

<h2>Posts</h2>

<?php foreach ($tag->getPosts() as $post) : ?>
<p>
    <strong><a href="<?=$router->urlFor('post', ['id'=>$post->getId()]); ?>"><?=$post->getTitle(); ?></a></strong>
    <br>
    Written on <?=$post->getCreated()->format('Y-m-d H:i:s'); ?> by <?=$post->getAuthor()->getFirstName(); ?>
</p>
<?php endforeach; ?>