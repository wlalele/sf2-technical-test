<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    const APP_USER_AGENT = 'stadline';
    const GITHUB_API_URL = 'https://api.github.com/';
    const GITHUB_API_SEARCH_USER = 'search/users?q=%s';
    const GITHUB_API_LIST_USER_REPOS = 'users/%s/repos';

    private function curlRequest($url)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_USERAGENT, self::APP_USER_AGENT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));

        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    private function isOnGithub($username)
    {
        $url = self::GITHUB_API_URL.sprintf(self::GITHUB_API_SEARCH_USER, $username);
        $output = $this->curlRequest($url);
        $decoded = json_decode($output);
        return ($decoded->total_count > 0);
    }

    private function getUserRepositories($username)
    {
        $url = self::GITHUB_API_URL.sprintf(self::GITHUB_API_LIST_USER_REPOS, $username);
        $output = $this->curlRequest($url);
        return json_decode($output);
    }

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
        if (!$this->isOnGithub($username)) {
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

        $repositories = $this->getUserRepositories($username);
        $comments = $em->getRepository('AppBundle:Comment')->findByGithubUsername($username);

        return $this->render('default/comment.html.twig', [
            'username' => $username,
            'repositories' => $repositories,
            'comments' => $comments
        ]);
    }
}
