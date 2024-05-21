<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Api\Api;
use App\Entity\ImagesPost;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Post;
use App\Form\PostsType;
use Symfony\Component\EventDispatcher\EventDispatcher;
use App\Entity\User;
use App\Entity\PostLike;
use App\Entity\VuePosts;
use App\Repository\PostLikeRepository;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;



class PostsController extends AbstractController
{


    public function __construct(private PostRepository $postsRepository, private EntityManagerInterface $em, private PostLikeRepository $postLikeRepository)
    {
    }

    #[Route('/publication', name: 'app_post')]
    #[Route('/publication/edit/{id}', name: 'app_post_edit')]
    public function index(Post $post = null, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $session = $request->getSession();
        $session->set('menu', 'publication');
        if ($this->getUser()->isIsVerified() == false) {
            $this->addFlash('danger', "Vous devez activer votre compte dans votre boite mail pour pouvoir publier");
            return $this->redirectToRoute('app_post_forum');
        }
        if (!$post) {
            $post = new Post();
        }

        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $post = $form->getData();
            if (!$post->getId()) {
                $post->setCreatedAt(new \DateTimeImmutable());
            }

            $post->setUsers($this->getUser());
            $post->setTitle($form->get('title')->getData());
            $post->setContent($form->get('content')->getData());

            $brochureFile = $form->get('image')->getData();
            $targetDirectory = $this->getParameter('images_posts_directory');

            if (isset($brochureFile) && $brochureFile != null) {
                foreach ($brochureFile as $key => $image) {

                    $fichier = random_int(4728, 69580873) . uniqid(rand(), true) . $image->getClientOriginalName();
                    $image->move(
                        $targetDirectory,
                        $fichier
                    );

                    $images = new ImagesPost();

                    $images->setNom($fichier);

                    $post->addImagesPost($images);
                }
            }


            $this->em->persist($post);
            $this->em->flush();
            $this->addFlash('success', "Publication crée avec success");
            return $this->redirectToRoute('app_post_get_detail', [
                "id" => $post->getId()

            ]);
        }
        $response = new Response(null, $form->isSubmitted() ? 422 : 200);
        return $this->render('posts/post.html.twig', [
            'form' => $form,
            'update' => $post->getId() !== null
        ], $response);
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
        $urlPosts = $api->apiUrl() . "uploads/comments/";
        $urlProfil = $api->apiUrl() . "images/profile/";
        $urlPublication = $api->apiUrl() . "uploads/posts/";


        return $this->render("posts/detail-post.html.twig", [
            "post" => $post,
            "urlposts" => $urlPosts,
            "urlProfil" =>  $urlProfil,
            "urlPublication" => $urlPublication



        ]);
    }



    #[Route('/publication/comment/{id}', name: 'app_post_comment', requirements: ['id' => '\d+'])]
    public function commentPublication(Post $post, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $session = $request->getSession();
        $session->set('menu', 'publication');

        $image = $request->files->get('image');
        $content = $request->request->get('content');
        if ($content == null && $image == null) {
            return $this->json(["message" => "success"], 404);
        }
        $comment = new Comment();
        if (isset($image) && $image != null) {
            $imageFile = $image;
            $targetDirectory = $this->getParameter('images_comment_directory');
            $fichier = random_int(170, 6958931) . uniqid(rand(), true) . $imageFile->getClientOriginalName();
            $imageFile->move(
                $targetDirectory,
                $fichier
            );
            $comment->setImage($fichier);
        }

        if ($content != null) {
            $comment->setContent($content);
        }
        $comment->setCreatedAt(new \DateTimeImmutable());
        $comment->setUsers($this->getUser());
        $comment->setPosts($post);
        $this->em->persist($comment);
        $this->em->flush();
        $post->addComment($comment);


        $this->em->flush();
        return $this->json(["message" => "success"], 201);
    }



    #[Route('/publication/delete/{id}', name: 'app_post_delete_topic', requirements: ['id' => '\d+'])]
    public function deletePublication(Post $post, Request $request)
    {


        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        if ($post == null) {

            throw $this->createNotFoundException("ce produit n'éxiste pas");
        }


        $this->em->flush();
        //$this->postsRepository->remove($post, true);



        $forum = $this->postsRepository->findLatest(1);


        return $this->json([
            "message" => "suppréssion réussie",
            "route" => $this->generateUrl("app_post_forum", [
                "forum" => $forum

            ])
        ], 200);
    }




    #[Route('/forum/list/page/{page<[1-9]\d*>}', defaults: ["page" => "1"], methods: ["GET"], name: 'app_post_forum')]
    public function displayPublications($page, Request $request)
    {
        $session = $request->getSession();
        $session->set('menu', 'discussions');
        $forum = $this->postsRepository->findLatest($page);
        $api = new Api();
        $urlProfil = $api->apiUrl() . "images/profile/";
        return $this->render("posts/forum-list.html.twig", ["forum" => $forum, "urlProfil" =>  $urlProfil]);
    }



    #[Route('/annonces/vue/{id}', methods: ["GET"], name: 'app_post_vues', requirements: ['id' => '\d+'])]
    public function postVue(Post $post)
    {
        $vuePosts = new VuePosts();
        $vuePosts->setCreatedAt(new \DateTimeImmutable());
        $vuePosts->setPosts($post);
        $this->em->persist($vuePosts);
        $this->em->flush();
        return $this->json([
            "message" => "success",
            "vueId" => $vuePosts->getId()
        ], 200);
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




    #[Route('/post/{id}/like', name: 'like_post')]
    /**
     * like
     *
     * Cette fonction permet d'aimer ou ne plus aimer un post
     * @param  mixed $post
     * @return void
     */
    public function like(Request $request, Post $post)
    {
        $user = $this->getUser();
        if (!$user)
            return $this->json(["message" => "il faut être connecté"], 403);

        if ($post->isLikedByUser($user)) {
            $like = $this->postLikeRepository->findOneBy([
                'post' => $post,
                'users' => $user

            ]);

            $this->em->remove($like);
            $this->em->flush();
            return $this->json(["message" => "success", "likes" => $this->postLikeRepository->count(['post' => $post])], 200);
        } else {

            $like = new PostLike();
            $like->setPost($post);
            $like->setUsers($user);
            $this->em->persist($like);
            $this->em->flush();
            return $this->json(["message" => "success", "likes" => $this->postLikeRepository->count(['post' => $post])], 200);
        }
    }
}
