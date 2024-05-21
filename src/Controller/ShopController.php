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

class ShopController extends AbstractController
{

    public function __construct(private EntityManagerInterface $em, private ArticlesRepository $articlesRepository)
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
        $url = $api->apiUrl() . "uploads/shopping/";
        $articles =  $this->articlesRepository->findAll();

        return $this->render('boutique/products.html.twig', [
            "articles" => $articles,
            "url" => $url
        ]);
    }

    #[Route('/shop/cart', name: 'app_shop_cart')]
    public function cart(Request $request)
    {

        return $this->render('boutique/cart.html.twig');
    }
}
