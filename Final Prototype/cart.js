document.addEventListener('DOMContentLoaded', () => {
    // --- CART STATE & DOM ELEMENTS ---
    let cartItems = JSON.parse(sessionStorage.getItem('shoppingCart')) || [];
    let isLoggedIn = false; // Simulated login state

    const cartSidebar = document.getElementById('cart-sidebar');
    const cartOverlay = document.getElementById('cart-overlay');
    const cartIcon = document.getElementById('cart-btn');
    const closeCartBtn = document.getElementById('close-cart-btn');
    const cartContent = document.getElementById('cart-content');
    const cartTotalEl = document.getElementById('cart-total');
    const cartCountEl = document.getElementById('cart-count');
    const confirmOrderBtn = document.getElementById('confirm-order-btn');
    const optionsModal = document.getElementById('options-modal');
    const optionsModalPanel = document.getElementById('options-modal-panel');
    const optionsProductName = document.getElementById('options-product-name');
    const optionsContainer = document.getElementById('options-container');
    const optionsAddToCartBtn = document.getElementById('options-add-to-cart-btn');
    const optionsInfo = document.getElementById('options-info');

    // --- CART FUNCTIONS ---

    /**
     * Saves the current cart state to sessionStorage.
     */
    function saveCart() {
        sessionStorage.setItem('shoppingCart', JSON.stringify(cartItems));
    }

    /**
     * Toggles the visibility of the shopping cart sidebar.
     */
    function toggleCart() {
        cartSidebar.classList.toggle('translate-x-full');
        cartOverlay.classList.toggle('hidden');
    }

    /**
     * Renders the current items in the cart to the sidebar.
     */
    function renderCart() {
        // Clear previous content
        cartContent.innerHTML = '';

        if (cartItems.length === 0) {
            cartContent.innerHTML = '<p class="text-center text-slate-500 py-10">Your cart is empty.</p>';
        } else {
            cartItems.forEach((item, index) => {
                // Display selected options if they exist
                const optionsText = item.options.size ? `Size: ${item.options.size} (${item.options.yards} yards)` : item.options.yards ? `Yards: ${item.options.yards}` : '';

                const cartItemEl = `
                    <div class="flex justify-between items-center py-3 border-b border-slate-100">
                        <div>
                            <h4 class="font-bold text-slate-800">${item.name}</h4>
                            <p class="text-sm text-slate-500">${optionsText}</p>
                            <p class="text-xs text-slate-400 mt-1">Unit Price: ₱${item.price.toFixed(2)}</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="font-bold text-lg text-slate-900 whitespace-nowrap">₱${(item.price * item.quantity).toFixed(2)}</span>
                            <button class="remove-from-cart-btn text-slate-400 hover:text-red-500" data-index="${index}">
                                <i data-lucide="minus-circle" class="w-5 h-5 pointer-events-none"></i>
                            </button>
                        </div>
                    </div>
                `;
                cartContent.innerHTML += cartItemEl;
            });
        }

        // Calculate and render total
        const total = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        cartTotalEl.textContent = `₱${total.toFixed(2)}`;

        // Update cart count bubble
        cartCountEl.textContent = cartItems.length;
        if (cartItems.length > 0) {
            cartCountEl.classList.remove('hidden');
        } else {
            cartCountEl.classList.add('hidden');
        }

        // Re-initialize icons for the newly added remove buttons
        lucide.createIcons();
    }

    /**
     * Adds a product to the shopping cart.
     * @param {HTMLElement} productCard - The product card element.
     */
    function addToCart(product) {
        if (product.name && !isNaN(product.price)) {
            // Check if item with same name and options already exists
            const existingItemIndex = cartItems.findIndex(item => 
                item.name === product.name &&
                item.options.size === product.options.size &&
                item.options.yards === product.options.yards
            );

            if (existingItemIndex > -1) {
                // Increment quantity
                cartItems[existingItemIndex].quantity++;
            } else {
                // Add new item
                cartItems.push({ ...product, quantity: 1 });
            }

            renderCart();
            saveCart();
            
            // Show a quick confirmation
            const cartBtn = document.getElementById('cart-btn');
            cartBtn.classList.add('animate-bounce');
            setTimeout(() => cartBtn.classList.remove('animate-bounce'), 1000);
        }
    }

    /**
     * Removes an item from the cart by its index.
     * @param {number} itemIndex - The index of the item to remove.
     */
    function removeFromCart(itemIndex) {
        cartItems.splice(itemIndex, 1);
        saveCart();
        renderCart();
    }

    /**
     * Opens the product options modal and populates it.
     * @param {HTMLElement} productCard 
     */
    function openProductOptions(productCard) {
        const name = productCard.dataset.name;
        const type = productCard.dataset.type;
        const optionsString = productCard.dataset.options;

        optionsProductName.textContent = name;
        optionsContainer.innerHTML = ''; // Clear previous options
        optionsInfo.classList.add('hidden');

        if (type === 'sized' && optionsString) {
            const optionsArray = optionsString.split(';').map(opt => {
                const [size, yards, price] = opt.split(':');
                return { size, yards: parseFloat(yards), price: parseFloat(price) };
            });

            let sizeOptionsHTML = '<label for="size-select" class="block text-sm font-medium text-gray-700">Select Size:</label>';
            sizeOptionsHTML += '<select id="size-select" class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-[#b91c1c] focus:outline-none focus:ring-[#b91c1c] sm:text-sm">';
            optionsArray.forEach(opt => {
                sizeOptionsHTML += `<option value="${opt.size}" data-yards="${opt.yards}" data-price="${opt.price}">${opt.size}</option>`;
            });
            sizeOptionsHTML += '</select>';
            optionsContainer.innerHTML = sizeOptionsHTML;

            const sizeSelect = document.getElementById('size-select');
            
            // Function to update info display
            const updateInfo = () => {
                const selectedOption = sizeSelect.options[sizeSelect.selectedIndex];
                const yards = selectedOption.dataset.yards;
                const price = parseFloat(selectedOption.dataset.price).toFixed(2);
                optionsInfo.innerHTML = `Requires: <span class="font-bold">${yards} yards</span><br>Price: <span class="font-bold">₱${price}</span>`;
                optionsInfo.classList.remove('hidden');
            };

            // Add event listener and update immediately
            sizeSelect.addEventListener('change', updateInfo);
            updateInfo();

        } else if (type === 'textile') {
            const price = parseFloat(productCard.dataset.price);
            let yardOptionsHTML = '<label for="yards-input" class="block text-sm font-medium text-gray-700">Enter Yards:</label>';
            yardOptionsHTML += '<input type="number" id="yards-input" value="1" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#b91c1c] focus:ring-[#b91c1c] sm:text-sm">';
            optionsContainer.innerHTML = yardOptionsHTML;
            
            const updateYardInfo = () => {
                const yards = document.getElementById('yards-input').value;
                const total = (price * yards).toFixed(2);
                optionsInfo.innerHTML = `Total Price: <span class="font-bold">₱${total}</span>`;
                optionsInfo.classList.remove('hidden');
            };
            document.getElementById('yards-input').addEventListener('input', updateYardInfo);
            updateYardInfo();
        }

        // Temporarily store product data on the modal's "Add to Cart" button
        optionsAddToCartBtn.dataset.name = name; // Base name
        optionsAddToCartBtn.dataset.basePrice = productCard.dataset.price; // For textiles
        optionsAddToCartBtn.dataset.type = type;

        optionsModal.classList.remove('hidden');
    }

    // Function to close the options modal
    window.closeOptionsModal = function() {
        optionsModal.classList.add('hidden');
    }

    // Event listener for the "Add to Cart" button inside the options modal
    optionsAddToCartBtn.addEventListener('click', () => {
        const name = optionsAddToCartBtn.dataset.name;
        const type = optionsAddToCartBtn.dataset.type;
        
        let productWithOptions = { name, options: {} };

        if (type === 'sized') {
            const selectedOptionEl = document.getElementById('size-select').options[document.getElementById('size-select').selectedIndex];
            productWithOptions.price = parseFloat(selectedOptionEl.dataset.price);
            productWithOptions.options.size = selectedOptionEl.value;
            productWithOptions.options.yards = selectedOptionEl.dataset.yards;
            productWithOptions.quantity = 1; // Default quantity is 1 for sized items

        } else if (type === 'textile') {
            const price = parseFloat(optionsAddToCartBtn.dataset.basePrice);
            const yards = parseInt(document.getElementById('yards-input').value, 10);
            if (yards > 0) {
                productWithOptions.options.yards = yards;
                // For textiles, we treat quantity as yards and price per yard
                productWithOptions.quantity = yards;
                productWithOptions.price = price; // price is per yard
                 // We'll handle total calculation in renderCart
            } else {
                alert("Please enter a valid number of yards.");
                return;
            }
        }
        addToCart(productWithOptions);
        saveCart();
        closeOptionsModal();
    });

    /**
     * Handles the confirm order process.
     */
    function handleConfirmOrder() {
        if (!isLoggedIn) {
            const prompt = document.getElementById('cart-login-prompt');
            const confirmBtn = document.getElementById('confirm-order-btn');

            // Highlight the prompt and shake the button
            prompt.classList.remove('text-slate-400');
            prompt.classList.add('text-red-500', 'font-bold', 'scale-110');
            confirmBtn.style.animation = 'shake 0.5s ease-in-out';

            // After the animation, open the login modal and reset the styles
            setTimeout(() => {
                prompt.classList.add('text-slate-400');
                prompt.classList.remove('text-red-500', 'font-bold', 'scale-110');
                confirmBtn.style.animation = '';
                
                // Now open the login modal
                openLogin();
            }, 1000); // Wait for 1 second
        } else {
            // This would be the actual order submission logic
            alert('Order confirmed! Thank you for your purchase.');
            cartItems = []; // Clear the cart
            saveCart();
            renderCart();
            toggleCart();
        }
    }

    // --- EVENT LISTENERS ---

    // Open/close cart
    cartIcon.addEventListener('click', toggleCart);
    closeCartBtn.addEventListener('click', toggleCart);
    cartOverlay.addEventListener('click', toggleCart);

    // Event delegation for removing items from the cart
    cartContent.addEventListener('click', (e) => {
        if (e.target.closest('.remove-from-cart-btn')) {
            const itemIndex = e.target.closest('.remove-from-cart-btn').dataset.index;
            removeFromCart(itemIndex);
        }
    });

    // Add to cart buttons
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            // Use e.currentTarget to ensure we're referencing the button itself
            const productCard = e.currentTarget.closest('.product-card');
            const productType = productCard.dataset.type;

            if (productType === 'sized' || productType === 'textile') {
                openProductOptions(productCard);
            } else {
                // Simple product, add directly
                const name = productCard.dataset.name;
                const price = parseFloat(productCard.dataset.price);
                if (name && !isNaN(price)) {
                    addToCart({ name, price, quantity: 1, options: {} });
                }
            }
        });
    });

    // Confirm order button
    confirmOrderBtn.addEventListener('click', handleConfirmOrder);

    // Initial render
    renderCart();
});