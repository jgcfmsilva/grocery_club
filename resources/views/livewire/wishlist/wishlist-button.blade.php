<div>
    @if (Auth::check())
        <button wire:click="toggleWishlist" type="button" class="focus:outline-none {{ $inWishlist ? 'text-red-500 hover:text-red-400' : 'text-gray-500 hover:text-red-500' }}">
            <i class="{{ $inWishlist ? 'fas' : 'far' }} fa-heart fa-lg"></i>
        </button>
    @else
        <button wire:click="redirectToLogin" type="button" class="text-gray-500 hover:text-red-500 focus:outline-none">
            <i class="far fa-heart fa-lg"></i>
        </button>
    @endif
</div>
