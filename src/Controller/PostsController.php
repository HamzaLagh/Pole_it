<?php

namespace App\Controller;


use App\Api\Api;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Post;
use App\Form\PostsType;
use Symfony\Component\EventDispatcher\EventDispatcher;
use App\Entity\User;

use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;



class PostsController extends AbstractController
{


    public function __construct(private PostRepository $postsRepository, private EntityManagerInterface $em,)
    {
    }




    #[Route('/publication/detail/{id}', name: 'app_post_get_detail', requirements: ['id' => '\d+'])]
    public function detailPublication(Post $post = null, Request $request)
    {


        if ($post == null) {

            throw $this->createNotFoundException("ce produit n'éxiste pas");
        }

        $session = $request->getSession();
        $session->set('menu', 'discussions');

        $api = new Api();
        $urlProfil = $api->apiUrl() . "images/profile/";
        $urlPublication = $api->apiUrl() . "uploads/posts/";


        return $this->render("posts/detail-post.html.twig", [
            "post" => $post,
            "urlProfil" =>  $urlProfil,
            "urlPublication" => $urlPublication



        ]);
    }







    #[Route('/publication/list/page/{page<[1-9]\d*>}', defaults: ["page" => "1"], methods: ["GET"], name: 'publication_list')]
    public function displayPublications($page, Request $request)
    {
        $session = $request->getSession();
        $session->set('menu', 'discussions');
        $forum = $this->postsRepository->findLatest($page);
        $api = new Api();
        $urlPublication = $api->apiUrl() . "uploads/posts/";
        return $this->render("posts/forum-list.html.twig", ["forum" => $forum, "url" =>  $urlPublication]);
    }







    #[Route('/find/publications', methods: ["POST"], name: 'app_find_publications')]
    public function rechercheArticles(Request $request)
    {

        $data = json_decode($request->getContent(), true);
        $recherche = $data['recherche'];
        $output = '';
        $reponse = $this->postsRepository->findPosts($recherche);
        $output = '<ul class="post-recherche">';

        if (count($reponse) > 0) {
            foreach ($reponse as $element) {


                $output .= '<li class="post-reponse" post=' . $element->getId() . '>' . $element->getTitle() . '</li>';
            }
        } else {
            $output .= '<li>Aucune publication ne correspond à votre recherche</li>';
        }
        $output .= '</ul>';

        return $this->json([
            "data" => $output

        ], 200);
    }
}
