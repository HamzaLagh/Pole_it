<?php

namespace App\Controller;

use App\Api\Api;
use App\Repository\ArticlesRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{

    public function __construct(private PostRepository $postRepository)
    {
    }

    #[Route('/', methods: ["GET"], name: "app_home")]
    public function index(Request $request)
    {
        $session = $request->getSession();
        $session->set('menu', 'home');
        $articles = $this->postRepository->findArticles();
        $api = new Api();

        $urlPublication = $api->apiUrl() . "uploads/posts/";


        return $this->render("posts/index.html.twig", ["articles" => $articles, "url" => $urlPublication]);
    }



    #[Route('/about', methods: ["GET"], name: "app_about")]
    public function about(Request $request)
    {
        $session = $request->getSession();
        $session->set('menu', 'about');

        return $this->render("about/about.html.twig");
    }
}
