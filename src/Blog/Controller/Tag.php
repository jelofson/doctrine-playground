<?php

namespace Blog\Controller;

use Blog\Entity\Tag as TagEntity;
use Blog\Entity\Post as PostEntity;

class Tag extends Controller
{
    public function read($id)
    {
        $tag = $this->entityManager->find(TagEntity::class, $id);

        if (! $tag) {
            return $this->notFound();
        }

        $content = $this->view->render('tag/read', [
            'tag'=>$tag
        ]);

        return $this->asHtml($content);
    }
}