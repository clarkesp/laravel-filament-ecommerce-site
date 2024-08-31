<?php

namespace App\Helpers;

use App\Models\Store\Product;
use Illuminate\Support\Facades\Cookie;

class CartManagement {

    // Add item to cart
    static public function addItemsToCart(int $product_id): int {
        $cart_items = self::getCartItemsFromCookie();
        $existing_item = null;

        // Check if the product already exists in the cart
        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id) {
                $existing_item = $key;
                break;
            }
        }

        // Update quantity if item exists, else add new item
        if ($existing_item !== null) {
            $cart_items[$existing_item]['quantity']++;
            $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] *
                $cart_items[$existing_item]['unit_amount'];
        } else {
            // Fetch product details and add to cart
            $product = Product::find($product_id); // Fetch product using the Eloquent model
            if ($product) {
                $cart_items[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->images[0], // Assuming images is an array
                    'quantity' => 1,
                    'unit_amount' => $product->price,
                    'total_amount' => $product->price,
                ];
            }
        }

        // Store updated cart in the cookie
        self::addCartItemsToCookie($cart_items);
        return count($cart_items);
    }

    // Remove item from cart
    static public function removeCartItem(int $product_id): void {
        $cart_items = self::getCartItemsFromCookie();

        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id) {
                unset($cart_items[$key]);
                break;
            }
        }

    // add item to cookie
    static public function addCartItemsToCookie($cart_items): void {
    Cookie::queue('cart_items', json_encode($cart_items), 60 * 24 * 30);
    }

    // clear items from cookie
    static public function clearCartItemsFromCookie(): void {
        Cookie::queue('cart_items', null, -60 * 24 * 30);
    }

    // get all cart items from cookie
    static public function getCartItemsFromCookie(): void {
        // Clear the 'cart_items' cookie
        $cart_items = json_decode(Cookie::get('cart_items'), true);
    }

    // increment item quantity


    // decrement item quantity


    // calculate grand total
    private static function getcartitemsfromCookie()
    {
    }

}
