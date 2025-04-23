@forelse ($carts as $cart)

    <li class="d-flex align-items-center pb-3 @if (!$loop->first) pt-3 @endif">
        <div class="thumb-wrapper">
            <a href="{{ route('products.show', $cart->product_variation->product->slug) }}">
                <img src="{{ uploadedAsset($cart->product_variation->product->thumbnail_image) }}" alt="products" class="img-fluid rounded-circle">
            </a>
        </div>
        <div class="items-content ms-3">
            <a href="{{ route('products.show', $cart->product_variation->product->slug) }}">
                <h6 class="mb-0">Nome do Produto</h6>
            </a>

            <div class="products_meta mt-1 d-flex align-items-center">
                <div>
                    <span
                        class="price text-primary fw-semibold">10,99€</span>
                    <span class="count fs-semibold">x 1</span>
                </div>
                <button class="remove_cart_btn ms-2" onclick="">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>
        </div>
    </li>
@empty
    <li>
        <img src="" alt="" srcset=""
            class="img-fluid">
    </li>
@endforelse
