<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/{username}/comment", name="comment")
     *
     * @param string $username
     * @return Response
     */
    public function commentAction($username)
    {
        $github = $this->get('app.github');
        if (!$github->isOnGithub($username)) {
            $this->redirect('homepage');
        }

        /** @var Request $request */
        $request = $this->get('request_stack')->getCurrentRequest();
        $em = $this->getDoctrine()->getManager();

        if ($request->isMethod('POST')) {
            // create comment
            $repository = $request->request->get('repository');

            $user = strstr($repository, '/', true);
            if ($user == $username) {
                $content = $request->request->get('comment');

                $comment = new Comment();
                $comment->setAuthor($this->getUser());
                $comment->setContent($content);
                $comment->setRepository($repository);

                $em->persist($comment);
                $em->flush();
            }
        }

        $repositories = $github->getUserRepositories($username);
        $comments = $em->getRepository('AppBundle:Comment')->findByGithubUsername($username);

        return $this->render('default/comment.html.twig', [
            'username' => $username,
            'repositories' => $repositories,
            'comments' => $comments
        ]);
    }
}
