// Elements
const searchInput = document.getElementById("searchInput");
const searchBtn = document.getElementById("searchBtn");
const filterBtns = document.querySelectorAll(".filter-btn");
const availableContainer = document.getElementById("availableProducts");
const unavailableContainer = document.getElementById("unavailableProducts");

const modal = document.getElementById("orderModal");
const modalName = document.getElementById("modalName");
const modalPrice = document.getElementById("modalPrice");
const modalStatus = document.getElementById("modalStatus");
const qtyInput = document.getElementById("qty");
const confirmBtn = document.getElementById("confirmOrderBtn");
const cancelBtn = document.getElementById("cancelOrderBtn");
const closeModalBtn = document.getElementById("closeModalBtn");

const menuBtn = document.getElementById("menuBtn");
const dropdownMenu = document.getElementById("dropdownMenu");

// Sample products
let products = [
  { name: "Laptop", price: 45000, stock: true },
  { name: "Printer", price: 5500, stock: true },
  { name: "Mouse", price: 350, stock: false },
  { name: "Keyboard", price: 800, stock: true },
  { name: "Monitor", price: 7500, stock: false },
];

let currentFilter = "all";
let activeProduct = null;

// Render function
function renderProducts() {
  availableContainer.innerHTML = "";
  unavailableContainer.innerHTML = "";

  const query = searchInput.value.trim().toLowerCase();

  products.forEach(p => {
    const matchSearch = p.name.toLowerCase().includes(query);

    const passesFilter =
      currentFilter === "all" ||
      (currentFilter === "in" && p.stock) ||
      (currentFilter === "out" && !p.stock);

    if (matchSearch && passesFilter) {
      const card = document.createElement("div");
      card.className = `product-card ${p.stock ? "in-stock" : "out-stock"}`;
      card.dataset.name = p.name;
      card.dataset.price = p.price;

      card.innerHTML = `
        <div class="product-image-placeholder">
          <span>Image</span>
        </div>
        <div class="product-info">
          <h3>${p.name}</h3>
          <p>₱${p.price}</p>
          <p class="status ${p.stock ? "in" : "out"}">${p.stock ? "In Stock" : "Out of Stock"}</p>
          <button class="order-btn">Order Now</button>
        </div>
      `;

      card.querySelector('.order-btn').addEventListener("click", (e) => {
        e.stopPropagation(); // Prevent any other card clicks from firing
        openModal(p);
      });

      if (p.stock) availableContainer.appendChild(card);
      else unavailableContainer.appendChild(card);
    }
  });
}

// Search and filter
searchInput.addEventListener("input", renderProducts);
searchBtn.addEventListener("click", renderProducts);

filterBtns.forEach(btn => {
  btn.addEventListener("click", () => {
    filterBtns.forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
    currentFilter = btn.dataset.filter;
    renderProducts();
  });
});

// Modal
function openModal(p) {
  activeProduct = p;
  modalName.textContent = p.name;
  modalPrice.textContent = "₱" + p.price;
  modalStatus.textContent = p.stock ? "In Stock" : "Out of Stock";
  qtyInput.value = 1;
  confirmBtn.disabled = !p.stock;
  modal.style.display = "flex";
}

function closeModal() {
  modal.style.display = "none";
  activeProduct = null;
}

confirmBtn.addEventListener("click", () => {
  // Check if user is logged in
  if (localStorage.getItem('isLoggedIn') !== 'true') {
    alert("Please log in to place an order.");
    window.location.href = "../Login/login.html";
    return;
  }

  if (!activeProduct) return;
  activeProduct.stock = false;
  alert(`Order placed for ${qtyInput.value}x ${activeProduct.name}`);
  renderProducts();
  closeModal();
});

cancelBtn.addEventListener("click", closeModal);
closeModalBtn.addEventListener("click", closeModal);

// Burger menu
menuBtn.addEventListener("click", () => {
  if (dropdownMenu) {
    dropdownMenu.classList.toggle("show");
  }
});

// Init
renderProducts();
