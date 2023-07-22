<?php

namespace App\Controller\Comment;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreateCommentController extends AbstractController
{

    public function __invoke(Comment $comment): Comment {
        $user = $this->getUser();
        $comment->setAuthor($user);
        return $comment;
    }
}