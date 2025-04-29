<form wire:submit.prevent="addToCart" class="flex gap-4 w-full items-center">
    <input type="number" 
        wire:model="quantity"
        min="1" 
        class="w-20 p-2 border-1 border-gray-400 rounded-lg text-center focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 ease-in-out select-none" 
        placeholder="Qt.">
    
    <button type="submit"
            class="btn btn-primary w-full py-2 px-3 text-white font-semibold rounded-lg shadow-lg transform hover:scale-105 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-300 ease-in-out">
        Add to Cart
    </button>
</form>