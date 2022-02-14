<?php

namespace Blog\Controller;

use Blog\Entity\User as UserEntity;

class User extends Controller
{
    public function read($id)
    {
        $author = $this->entityManager->find(UserEntity::class, $id);

        if (! $author) {
            return $this->notFound();
        }

        $content = $this->view->render('user/read', [
            'author'=>$author
        ]);

        return $this->asHtml($content);
    }
}