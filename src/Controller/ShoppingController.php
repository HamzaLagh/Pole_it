<?php

namespace App\Controller;

use App\Api\Api;
use App\Entity\Articles;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Form\ProductType;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class ShoppingController extends AbstractController
{

    public function __construct(private EntityManagerInterface $em, private ArticlesRepository $articlesRepository, private SerializerInterface $serializerInterface,)
    {
    }





    #[Route('/shop/add', name: 'app_shop_add')]
    public function index(Request $request)
    {
        $session = $request->getSession();
        $session->set('menu', 'publication');

        $post = new Articles();

        $form = $this->createForm(ProductType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $post = $form->getData();


            $post->setCreatedAt(new \DateTimeImmutable());
            $post->setTitle($form->get('title')->getData());
            $post->setDescription($form->get('description')->getData());
            $post->setPrice($form->get('price')->getData());
            $post->setQuantite($form->get('quantite')->getData());
            $post->setInStock(true);
            $imageFile = $form->get('image')->getData();
            $targetDirectory = $this->getParameter('images_shopping_directory');
            $fichier = random_int(4728, 69580873) . uniqid(rand(), true) . $imageFile->getClientOriginalName();
            $imageFile->move(
                $targetDirectory,
                $fichier
            );

            $post->setImage($fichier);
            $this->em->persist($post);
            $this->em->flush();

            // $this->addFlash('success', "Produit ajouté avec success");
            return $this->redirectToRoute('app_shop');
        }




        $response = new Response(null, $form->isSubmitted() ? 422 : 200);

        return $this->render('boutique/add-product.html.twig', [
            'form' => $form,
            'update' => $post->getId() !== null
        ], $response);
    }


    #[Route('/shop', name: 'app_shop')]
    public function shopList(Request $request)
    {
        $api = new Api();
        $url = $api->apiUrl() . "uploads/shopping";
        $articles =  $this->articlesRepository->findAll();
        $articles_json = $this->serializerInterface->serialize($articles, 'json');

        return $this->render('shopping/index.html.twig', [
            "produits" => $articles_json,
            "url" => $url
        ]);
    }

    #[Route('/shop/cart', name: 'app_shop_cart')]
    public function cart(Request $request)
    {
        $api = new Api();
        $url = $api->apiUrl() . "uploads/shopping";
        $articles =  $this->articlesRepository->findAll();
        $articles_json = $this->serializerInterface->serialize($articles, 'json');

        return $this->render('boutique/cart.html.twig', [
            "produits" => $articles_json,
            "url" => $url
        ]);
    }


    #[Route('/shop/checkout', name: 'app_shop_checkout')]
    public function checkout(Request $request)
    {
        $api = new Api();
        $url = $api->apiUrl() . "uploads/shopping";
        $articles =  $this->articlesRepository->findAll();
        $articles_json = $this->serializerInterface->serialize($articles, 'json');

        return $this->render('shopping/checkout.html.twig', [
            "produits" => $articles_json,
            "url" => $url
        ]);
    }


    #[Route('/shop/confirmation', name: 'app_shop_confirmation')]
    public function confirmation(Request $request)
    {

        $api = new Api();
        $url = $api->apiUrl() . "uploads/shopping";
        $articles =  $this->articlesRepository->findAll();
        $articles_json = $this->serializerInterface->serialize($articles, 'json');
        return $this->render('shopping/confirmation.html.twig', [
            "produits" => $articles_json,
            "url" => $url
        ]);
    }

    #[Route('/shop/payment', name: 'app_shop_payment')]
    public function payment(Request $request)
    {
        $api = new Api();
        $url = $api->apiUrl() . "uploads/shopping";
        $articles =  $this->articlesRepository->findAll();
        $articles_json = $this->serializerInterface->serialize($articles, 'json');

        return $this->render('shopping/paiement.html.twig', [
            "produits" => $articles_json,
            "url" => $url
        ]);
    }
}
