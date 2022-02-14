<?php $this->layout('layouts::default'); ?>

<h1><?=$page_title; ?></h1>

<?=$form->begin(); ?>
<div class="mb-3">
    <?=$form->getElement('label_title')->class('form-label'); ?>
    <?=$form->getElement('title'); ?>
</div>

<div class="mb-3">
    <?=$form->getElement('label_post')->class('form-label'); ?>
    <?=$form->getElement('post')->cols(50); ?>
</div>

<div class="mb-3">
    <?=$form->getElement('label_user_id')->text('Author')->class('form-label'); ?>
    <?=$form->getElement('user_id'); ?>
</div>

<div class="mb-3">
    <?=$form->label('Tags')->for('tags')->class('form-label'); ?>
    <?=$form->text()->idName('tags')->value($post->getTagsAsString())->class('form-control'); ?>

<div class="mb-3 mt-3">
    <?=$form->submit('Save')->class('btn btn-primary'); ?>
    <a href="<?=$cancel_href; ?>" class="btn btn-secondary">Cancel</a>
</div>
<?=$form->end(); ?>