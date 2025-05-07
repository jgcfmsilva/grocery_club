<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Cart\AddToCartRequest; // Import the Form Request class

class ProductPage extends Component
{
    public $product ;
    public $quantity = 1;
    public $inWishList = false;

    public function mount(Product $product )
    {
        $this->product = $product ;

        if(Auth::check()):
            $this->inWishList = in_array($this->product->id, authUser()->custom['wishlist'] ?? []);
        endif;
    }

    public function addToCart(AddToCartRequest $request)
    {
        $validated = $request->validated();

        if ($this->product->stock < $validated['quantity']) {
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->warning("Only {$this->product->stock} x {$this->product->name} available in stock. You can still place the order.");
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$this->product->id])) {
            $cart[$this->product->id]['quantity'] += $validated['quantity'];
        } else {
            $cart[$this->product->id] = [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
                'discount_min_qty' => $this->product->discount_min_qty,
                'discount' => $this->product->discount,
                'quantity' => $validated['quantity'],
                'photo' => $this->product->photo,
                'category_name' => $this->product->category?->name ?? 'Uncategorized',
                'stock' => $this->product->stock,
            ];
        }

        session()->put('cart', $cart);
        $this->dispatch('pageProductCartUpdated');

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success("{$validated['quantity']} x {$this->product->name} added to cart!");
    }

    public function editWishList(){
        if (!Auth::check()) {
            abort(403, 'You need to be logged in to manage your wishlist!');
        }

        $user = authUser();
        $custom = $user->custom ?? [];
        $custom['wishlist'] = $custom['wishlist'] ?? [];
        $wishlist = $custom['wishlist'] ?? [];

        if (in_array($this->product -> id, $wishlist)) {
            $wishlist = array_filter($wishlist, fn($id) => $id != $this->product -> id);
        } else {
            $wishlist[] = $this->product -> id;
        }

        $custom['wishlist'] = array_values($wishlist);
        $user->custom = $custom;
        $user->save();

        $this->inWishList = !$this->inWishList;
        $this->dispatch('wishlistUpdatedProductPgae');    }

    public function redirectToLogin(){
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.products.product-page', [
            'product' => $this->product
        ]);
    }
}
