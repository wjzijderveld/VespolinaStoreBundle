<?php

namespace Vespolina\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CartController extends Controller
{
    public function indexAction()
    {

        $cart = $this->getCart();

        return $this->render('VespolinaStoreBundle:Cart:index.html.twig', array('cart' => $cart));
    }

    public function addToCartAction($productId)
    {
        $cartManager = $this->get('vespolina.cart_manager');
        $product = $this->getProduct($productId);

        $cart = $this->getCart();
        $cartItem = $cartManager->addItemToCart($cart, $product);

        return $this->forward('VespolinaStoreBundle:Cart:index');
    }

    protected function getCart()
    {
        $cart = null;
        $session = $this->getRequest()->getSession();

        //Find the cart in the session
        if (!$cart = $session->get('cart')) {

            $cartManager = $this->get('vespolina.cart_manager');
            //For now we assume that the cart is owned by the session which is of course not true,
            //it's owned by the user attached to the session
            $ownerId = $session->getId();

            if (!$cart = $cartManager->findOpenCartByOwner($ownerId)) {

                $cart = $cartManager->createCart();
                $cart->setOwner($ownerId);
            }

        }

        return $cart;
    }

    protected function getProduct($id)
    {

        $productManager = $this->get('vespolina.product_manager');
        $product = $productManager->findProductById($id);

        return $product;

    }
}
