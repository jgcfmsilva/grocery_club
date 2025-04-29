<div class="gshop-header-cart relative">
    <button type="button" class="header-icon theme-icon">
        <svg width="19" height="23" viewBox="0 0 22 25" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M21.1704 23.9559L19.6264 7.01422C19.5843 6.55156 19.1908 6.19718 18.7194 6.19718H15.5355V4.78227C15.5355 2.14533 13.3583 0 10.6823 0C8.00628 0 5.82937 2.14533 5.82937 4.78227V6.19718H2.6433C2.17192 6.19718 1.77839 6.55156 1.73625 7.01422L0.186259 24.0225C0.163431 24.2735 0.248671 24.5223 0.421216 24.7082C0.593761 24.8941 0.837705 25 1.0933 25H20.2695C20.2702 25 20.2712 25 20.2719 25C20.775 25 21.1826 24.5982 21.1826 24.1027C21.1825 24.0528 21.1784 24.0036 21.1704 23.9559ZM7.65075 4.78227C7.65075 3.1349 9.01071 1.79465 10.6824 1.79465C12.3542 1.79465 13.7142 3.1349 13.7142 4.78227V6.19718H7.65075V4.78227ZM2.08948 23.2055L3.47591 7.99183H5.82937V9.59649C5.82937 10.0921 6.237 10.4938 6.74006 10.4938C7.24313 10.4938 7.65075 10.0921 7.65075 9.59649V7.99183H13.7142V9.59649C13.7142 10.0921 14.1219 10.4938 14.6249 10.4938C15.128 10.4938 15.5356 10.0921 15.5356 9.59649V7.99183H17.8869L19.2733 23.2055H2.08948Z"
                fill="#5D6374" />
        </svg>
        <span class="cart-counter badge bg-primary rounded-circle p-0 {{ count($cartItems) > 0 ? '' : 'hidden' }}">
            x{{ count($cartItems) }}
        </span>
    </button>
    <div class="cart-box-wrapper">
        <div class="apt_cart_box theme-scrollbar">
            <ul class="cart-navbar-wrapper overflow-auto">
                @forelse ($cartItems as $item)
                    <li class="d-flex align-items-center pb-3 @if (!$loop->first) pt-3 @endif">
                        <div class="thumb-wrapper">
                            <a href="{{ route('products.show', $item['id']) }}"><img
                                    src="{{ asset('storage/products/' . $item['photo']) }}" alt="products"
                                    class="img-fluid rounded-circle"></a>
                        </div>
                        <div class="items-content ms-3">
                            <a href="{{ route('products.show', $item['id']) }}">
                                <h6 class="mb-0">{{ $item['name'] }}</h6>
                            </a>

                            <div class="products_meta mt-1 d-flex align-items-center">
                                <div>
                                    <span
                                        class="price text-primary fw-semibold">{{ number_format(calculate_discounted_price($item['price'], $item['discount'], $item['quantity'], $item['discount_min_qty']), 2, ',', '.')  }}€</span>
                                    <span class="count fs-semibold">x {{ $item['quantity'] }}</span>
                                </div>
                                <button class="remove_cart_btn ms-2" wire:click="removeFromCart({{ $item['id'] }})">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                @empty
                    <p>Your cart is empty.</p>
                @endforelse
            </ul>
            <div class="flex items-center justify-between mt-3">
                <h6 class="mb-0">Subtotal:</h6>
                <span
                    class="fw-semibold text-secondary sub-total-price">{{ number_format($subtotal, 2) }}€</span>
            </div>
            <div class="row items-center justify-between">
                <div class="col-6">
                    <a href="{{ route('cart') }}"
                        class="btn btn-secondary btn-md mt-4 w-100"><span
                            class="me-2"><i
                                class="fa-solid fa-shopping-bag"></i></span>View Cart</a>
                </div>
                <div class="col-6">
                    <a href="{{ route('checkout') }}"
                        class="btn btn-primary btn-md mt-4 w-full"><span class="me-2"><i
                                class="fa-solid fa-credit-card"></i></span>Checkout</a>
                </div>
            </div>
        </div>
    </div>
</div>