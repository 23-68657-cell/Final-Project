let currentCategory = 'all'; // Keep track of the current category

// Combined Search and Filter Logic
function searchAndFilterProducts() {
    const searchTerm = document.getElementById('product-search')?.value.toLowerCase() || '';
    const productCards = document.querySelectorAll('.product-card');

    productCards.forEach(card => {
        const cardCategory = card.dataset.category;
        const cardName = card.dataset.name.toLowerCase();

        const categoryMatch = currentCategory === 'all' || cardCategory === currentCategory;
        const searchMatch = cardName.includes(searchTerm);

        if (categoryMatch && searchMatch) {
            card.style.display = 'flex';
            card.style.animation = 'fadeIn 0.5s ease-out forwards';
        } else {
            card.style.display = 'none';
        }
    });
}

// Updated filterProducts function
function filterProducts(category) {
    currentCategory = category;

    // Update button states
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('bg-black', 'text-white', 'border-black');
        btn.classList.add('bg-white', 'text-slate-600', 'border-slate-200');
    });

    const activeBtn = document.getElementById(`btn-${category}`);
    if (activeBtn) {
        activeBtn.classList.add('bg-black', 'text-white', 'border-black');
        activeBtn.classList.remove('bg-white', 'text-slate-600', 'border-slate-200');
    }

    // Trigger the combined filter and search
    searchAndFilterProducts();
}

// Modal Logic
// Menu Toggle Logic
const menuBtn = document.getElementById('menu-btn');
const dropdownMenu = document.getElementById('dropdown-menu');

menuBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    dropdownMenu.classList.toggle('hidden');
});

// Close menu when clicking outside
document.addEventListener('click', (e) => {
    if (!menuBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
        dropdownMenu.classList.add('hidden');
    }
});

// Sticky Navbar visual update
window.addEventListener('scroll', () => {
    const navbar = document.getElementById('navbar');
    navbar.classList.toggle('shadow-md', window.scrollY > 10);
});

// Forgot Password Form Logic
const forgotForm = document.getElementById('forgot-form');
if (forgotForm) {
    forgotForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const emailInput = document.getElementById('forgot-email');
        const errorMsg = document.getElementById('forgot-error');
        const successMsg = document.getElementById('reset-success');
        const emailValue = emailInput.value;

        // G-Suite email validation using regex
        const gsuiteRegex = /^[a-zA-Z0-9._%+-]+@g\.batstate-u\.edu\.ph$/;

        if (gsuiteRegex.test(emailValue)) {
            errorMsg.classList.add('hidden');
            successMsg.classList.remove('hidden');
            // Here you would typically handle the form submission (e.g., via API call)
        } else {
            successMsg.classList.add('hidden');
            errorMsg.classList.remove('hidden');
        }
    });
}

// Event listener for the search bar on the catalog page
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('product-search');
    if (searchInput) {
        searchInput.addEventListener('input', searchAndFilterProducts);
    }
});