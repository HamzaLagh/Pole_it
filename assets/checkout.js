import axios from "axios";
import './styles/pages/checkout.scss';


document.addEventListener("DOMContentLoaded", function () {
    const checkoutProducts = document.getElementById("checkout-products");
    const checkoutTotal = document.getElementById("checkout-total");
  
    function renderCheckoutProducts() {
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
                              <img src="${product.image}" />
                          </div>
                    </div>
                    <div class="col-4">
                    <div class="product-details">
                      
                              <div class="product-title">${product.title}</div>
                              <p class="product-description">
                                  The best dog bones of all time. Holy crap. Your dog will be begging
                                  
                              </p>
                       </div>
                    </div>
                    <div class="col">
                              <div class="product-price">${product.price.formatWithoutCurrency()}</div>
  
                    </div>
                    <div class="col">
                    <div class="product-quantity">
                    <input type="number" class="quantity-input" data-id="${ item.id}" value="${item.qty}" min="1" />
                </div>    
  
                  </div>
                  <div class="col">
                          <div class="product-removal">
                          <button class="remove-product" data-id="${item.id}"><i class="fas fa-trash-alt"></i>
                          </button>
                          </div>
  
  
                  </div>
  
                  <div class="col">
                  <div class="item-total"> <span>Item Total:</span> ${itemTotal.format()}</div>
                  </div>
  
  
                  
   
  
  
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
  
      // Display the total amount
      checkoutTotal.innerHTML = `
          <div class="totals">
              <div class="totals-item">
                  <label>Subtotal</label>
                  <div class="totals-value" id="cart-subtotal">${subtotal.formatWithoutCurrency()}</div>
              </div>
              <div class="totals-item">
                  <label>Tax (5%)</label>
                  <div class="totals-value" id="cart-tax">${tax.formatWithoutCurrency()}</div>
              </div>
              <div class="totals-item">
                  <label>Shipping</label>
                  <div class="totals-value" id="cart-shipping">${shipping.formatWithoutCurrency()}</div>
              </div>
              <div class="totals-item totals-item-total">
                  <label>Grand Total</label>
                  <div class="totals-value" id="cart-total">${grandTotal.formatWithoutCurrency()}</div>
              </div>
          </div>`;
  
      // Add event listeners for remove buttons
      const removeButtons = document.querySelectorAll(".remove-product");
      removeButtons.forEach((button) => {
        button.addEventListener("click", function () {
          const id = parseInt(this.getAttribute("data-id"));
          removeProduct(id);
        });
      });
  
      // Add event listeners for quantity inputs
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
      localStorage.setItem("online-store", JSON.stringify(cart));
    }
  
    function loadCart() {
      cart = JSON.parse(localStorage.getItem("online-store")) || [];
    }
  
    loadCart();
    renderCheckoutProducts();
  });
  
  // Helper function to format numbers as currency
  Number.prototype.format = function () {
    return this.toLocaleString("en-US", {
      style: "currency",
      currency: "USD",
    });
  };
  
  // Helper function to format numbers without currency
  Number.prototype.formatWithoutCurrency = function () {
    return this.toLocaleString("en-US", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    });
  };
  