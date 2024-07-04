import './styles/pages/shopping.scss';


document.addEventListener("DOMContentLoaded", function(){

 
var products = $('#js-data').data('produits');
  var url = $('#js-data').data('url');
  
  


let cart = [];

//* selectors
const selectors = {
products: document.querySelector(".products"),
cartBtn: document.querySelector(".cart-btn"),
cartQty: document.querySelector(".cart-qty"),
cartClose: document.querySelector(".cart-close"),
cart: document.querySelector(".cart"),
cartOverlay: document.querySelector(".cart-overlay"),
cartClear: document.querySelector(".cart-clear"),
cartBody: document.querySelector(".cart-body"),
cartTotal: document.querySelector(".cart-total"),
checkout: document.querySelector(".checkout"),
confirmModal: document.getElementById("confirmModal"),
closeModal: document.getElementById("closeModal"),
confirmAddToCart: document.getElementById("confirmAddToCart"),
cancelAddToCart: document.getElementById("cancelAddToCart")
};

let productToAddId = null;

const setupListeners = () => {
// document.addEventListener("DOMContentLoaded", initStore);

loadCart();
renderProducts();
renderCart();


const cartState = localStorage.getItem('cart-state');
if (cartState === 'open') {
  showCart();
}


selectors.products.addEventListener("click", openAddToCartModal);

selectors.cartBtn.addEventListener("click", showCart);
selectors.cartOverlay.addEventListener("click", hideCart);
selectors.cartClose.addEventListener("click", function () {
  hideCart();
});
selectors.cartBody.addEventListener("click", updateCart);
selectors.cartClear.addEventListener("click", clearCart);
selectors.checkout.addEventListener("click", function () {
  hideCart();
});


selectors.closeModal.addEventListener("click", closeModal);
selectors.cancelAddToCart.addEventListener("click", closeModal);
selectors.confirmAddToCart.addEventListener("click", function (e) {
  
  confirmAddToCart();
  // location.reload();
  // showCart();
});
};


function openAddToCartModal(e) {
if (e.target.hasAttribute("data-id")) {
  productToAddId = parseInt(e.target.dataset.id);
  selectors.confirmModal.style.display = "block";
}
}


function closeModal() {
selectors.confirmModal.style.display = "none";
productToAddId = null;
}

function confirmAddToCart() {
if (productToAddId !== null) {
  addToCart(productToAddId);
  closeModal();
  location.reload(); 
}
}


// const initStore = () => {
//   console.log("bonjour shopping")
// loadCart();
// renderProducts();
// renderCart();


// const cartState = localStorage.getItem('cart-state');
// if (cartState === 'open') {
//   showCart();
// }
// };

const showCart = () => {
selectors.cart.classList.add("show");
selectors.cartOverlay.classList.add("show");
localStorage.setItem('cart-state', 'open'); 
};

const hideCart = () => {
selectors.cart.classList.remove("show");
selectors.cartOverlay.classList.remove("show");
localStorage.setItem('cart-state', 'closed'); 
};

const clearCart = () => {
cart = [];
saveCart();
renderCart();
renderProducts();
setTimeout(hideCart, 500);
};

const addToCart = (id) => {
const inCartIndex = cart.findIndex((item) => item.id === id);

if (inCartIndex !== -1) {
  openModal();
  return;
}

const product = products.find((product) => product.id === id);
if (product) {
  cart.push({ id: product.id, qty: 1 });
  saveCart();
  renderCart();
  localStorage.setItem('cart-state', 'open'); 
  //showCart();
} else {
  console.error("Product not found!");
}
};

const removeFromCart = (id) => {
cart = cart.filter((x) => x.id !== id);
cart.length === 0 && setTimeout(hideCart, 500);
renderProducts();
};

const increaseQty = (id) => {
const item = cart.find((x) => x.id === id);
if (!item) return;

const product = products.find((p) => p.id === id);

if (item.qty < product.quantite) {
  item.qty++;
} else {
  alert("La quantité demandée n'est pas disponible");
}
};

const decreaseQty = (id) => {
const item = cart.find((x) => x.id === id);
if (!item) return;

item.qty--;

if (item.qty === 0) removeFromCart(id);
};

const updateCart = (e) => {
if (e.target.hasAttribute("data-btn")) {
  const cartItem = e.target.closest(".cart-item");
  const id = parseInt(cartItem.dataset.id);
  const btn = e.target.dataset.btn;

  btn === "incr" && increaseQty(id);
  btn === "decr" && decreaseQty(id);

  saveCart();
  renderCart();
}
};

const saveCart = () => {
localStorage.setItem("store-astro", JSON.stringify(cart));
};

const loadCart = () => {
cart = JSON.parse(localStorage.getItem("store-astro")) || [];
};

//* render functions
const renderCart = () => {
const cartQty = cart.reduce((sum, item) => sum + item.qty, 0);
selectors.cartQty.textContent = cartQty;
selectors.cartQty.classList.toggle("visible", cartQty);

selectors.cartTotal.textContent = calculateTotal().format();

if (cart.length === 0) {
  selectors.cartBody.innerHTML = '<div class="cart-empty">Votre panier est vide</div>';
  return;
}

selectors.cartBody.innerHTML = cart
  .map(({ id, qty }) => {
      const product = products.find((x) => x.id === id);
      const { title, image, price } = product;
      const amount = price * qty;

      return `
      <div class="cart-item" data-id="${id}">
          <img src="${url}/${image}" alt="${title}" />      
          <div class="cart-item-detail">
              <div cart-item-price>
              </div>
              <div class="cart-item-amount">
              <h3>${title}</h3>

              <h5>${price.format()}</h5>

              <i class="bi bi-dash-lg" data-btn="decr"></i>
              <span class="qty">${qty}</span>
              <i class="bi bi-plus-lg" data-btn="incr"></i>
              <span class="cart-item-price">${amount.format()}</span>
              </div>
              <p class="error-message" id="error-${id}" style="color:red;"></p>
          </div>
      </div>`;
  })
  .join("");
};

const renderProducts = () => {
selectors.products.innerHTML = products
  .map((product) => {
      const { id, title, image, price, description } = product;
      const inCart = cart.find((x) => x.id === id);
      const disabled = inCart ? "disabled" : "";
      const text = inCart ? " " : "";

      return `
      <div class="product">
      <div class="row">
      <div class="col-6" style="background-color:white;">
          <img src="${url}/${image}" alt="${title}" />
      </div>
      <div class="col-5 bg-light">
          <h3>${title}</h3>
          <P>${description}</P>
          <h5>${price.format()}</h5>
          <p> ${text}</p>
          <button class="greg" ${disabled} data-id=${id}></button>
      </div>
      </div>
      </div>
      `;
  })
  .join("");
};

const calculateTotal = () => {
return cart.reduce((sum, item) => {
  const product = products.find((x) => x.id === item.id);
  return sum + product.price * item.qty;
}, 0);
};

Number.prototype.format = function () {
return this.toLocaleString("en-US", {
style: "currency",
currency: "EUR",
});
};

//* init app
setupListeners();

})
