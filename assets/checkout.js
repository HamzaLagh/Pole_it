import './styles/pages/checkout.scss';

document.addEventListener("DOMContentLoaded", function () {
  let cart = [];
  var products = $('#js-data').data('produits');
  var url = $('#js-data').data('url');
  
  const checkoutProducts = document.getElementById("checkout-products");
  const checkoutTotal = document.getElementById("checkout-total");
  

  function renderCheckoutProducts() {
    cart = JSON.parse(localStorage.getItem("store-astro")) || [];
    // products = cart;
    checkoutProducts.innerHTML = "";
    let subtotal = 0;

    cart.forEach((item, index) => {
      const product = products.find((product) => product.id === item.id);

      if (product) {
        const productElement = document.createElement("div");
        productElement.classList.add(
          "checkout-product",
          "checkout-product-item"
        );

        const itemTotal = product.price * item.qty;
        subtotal += itemTotal;

        productElement.innerHTML = `
                <div class="container mt-2">
                <div class="row">
                
                  <div class="col">
                          <div class="product-image">
                            <img src="${url}/${product.image}" />
                          </div>
                  </div>

                  <div class="col">
                          <div class="product-details">
                                    <div class="product-title">${product.title}</div>
                                    <p class="product-description">${product.description}</p>
                            </div>
                  </div>

                  <div class="col">
                            <div class="product-price">${product.price.format()}</div>
                  </div>


                  <div class="col">
                            <div class="product-quantity">
                              <div class="cart-item-amount w-50">
                                  <input type="number" class="quantity-input w-100" data-id="${   item.id}" value="${item.qty}" min="1" />
                           </div>    
                           </div>    


                  </div>


                <div class="col">
                        <div class="product-removal">
                        <div class="remove-product" data-id="${item.id}"><i style='color:red' class="fas fa-trash-alt"></i></div>
                          
                        </div>
                </div>

                <div class="col">
                        <div class="item-total"> <span>Total:</span> ${itemTotal.format()}</div>
                </div>



      </div>
    </div>
                



         
                `;

        checkoutProducts.appendChild(productElement);
      }
    });

    const taxRate = 0.05;
    const shipping = 15.0;
    const tax = subtotal * taxRate;
    const grandTotal = subtotal + tax + shipping;

    checkoutTotal.innerHTML = `
        <div class="totals">
            <div class="totals-item">
                <label>Sous-total</label>
                <div class="totals-value" id="cart-subtotal">${subtotal.format()}</div>
            </div>
            <div class="totals-item">
                <label>Taxes (5%)</label>
                <div class="totals-value" id="cart-tax">${tax.format()}</div>
            </div>
            <div class="totals-item">
                <label>Frais de port</label>
                <div class="totals-value" id="cart-shipping">${shipping.format()}</div>
            </div>
            <div class="totals-item totals-item-total">
                <label>Total</label>
                <div class="totals-value" id="cart-total">${grandTotal.format()}</div>
            </div>
        </div>`;

    const removeButtons = document.querySelectorAll(".remove-product");
    removeButtons.forEach((button) => {
      button.addEventListener("click", function () {
        const id = parseInt(this.getAttribute("data-id"));
        removeProduct(id);
      });
    });

    const quantityInputs = document.querySelectorAll(".quantity-input");
    quantityInputs.forEach((input) => {
      input.addEventListener("change", function () {
        const id = parseInt(this.getAttribute("data-id"));
        const newQty = parseInt(this.value);
        updateQuantity(id, newQty);
      });
    });
  }

  function removeProduct(id) {
    cart = cart.filter((item) => item.id !== id);
    saveCart();
    renderCheckoutProducts();
    renderCart();
  }

  function updateQuantity(id, newQty) {
    const item = cart.find((item) => item.id === id);
    if (item && newQty > 0) {
      item.qty = newQty;
      saveCart();
      renderCheckoutProducts();
      renderCart();
    }
  }

  function saveCart() {
    localStorage.setItem("store-astro", JSON.stringify(cart));
  }

  function loadCart() {
    cart = JSON.parse(localStorage.getItem("store-astro")) || [];
  }

  loadCart();
  renderCheckoutProducts();
});


Number.prototype.format = function () {
  return this.toLocaleString("fr-FR", {
    style: "currency",
    currency: "EUR",
  });
};


Number.prototype.formatWithoutCurrency = function () {
  return this.toLocaleString("fr-FR", {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  });
};

