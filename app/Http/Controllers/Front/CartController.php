<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;


class CartController extends Controller
{
    private $productService;

    public function __construct(ProductServiceInterface $productService) {
        $this->productService = $productService;
    }

    public function index() {
        $carts = Cart::content();
        $total = Cart::total();
        $subtotal = Cart::subtotal();

        return view('front.shop.cart', compact('carts', 'total', 'subtotal'));
    }

    public function show(){
        $carts = Cart::content();
        $total = Cart::total();
        $subtotal = Cart::subtotal();

        return view('front.shop.cart', compact('carts', 'total', 'subtotal'));
    }

    public function add(Request $request) {
        if($request->ajax()) {
            $products = $this->productService->find($request->productId);

            $response['cart'] = Cart::add([
                'id' => $products->id,
                'name' => $products->name,
                'qty' => 1,
                'price' => $products->discount ?? $products->price,
                'weight' => $products->weight ?? 0,
                'options' => [
                    'images' => $products->productImages,
                ],
            ]);

            $response['count'] = Cart::count();
            $response['total'] = Cart::total();

            return $response;
        }

        return back();
    }

    public function delete(Request $request) {
        if ($request->ajax()) {
            $response['cart'] = Cart::remove($request->rowId);

            $response['count']  = Cart::count();
            $response['total']  = Cart::total();
            $response['subtotal']  = Cart::subtotal();

            return $response;
        }

        return back();
    }

    public function destroy() {
        Cart::destroy();
        return back();

    }

    public function update(Request $request) {
        if ($request->ajax()) {
            $response['cart'] = Cart::update($request->rowId, $request->qty);

            $response['count']  = Cart::count();
            $response['total']  = Cart::total();
            $response['subtotal']  = Cart::subtotal();

            return $response;
        }
        return back();

    }
}
