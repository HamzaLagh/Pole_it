import axios from "axios";
import './styles/pages/payment.scss';



document.addEventListener("DOMContentLoaded", function() {
    
    const checkoutProducts = document.getElementById("checkout-products");
    const checkoutTotal = document.getElementById("checkout-total");
  
    function renderCheckoutProducts() {
        checkoutProducts.innerHTML = "";
        let subtotal = 0;
  
        cart.forEach((item, index) => {
            const product = products.find((product) => product.id === item.id);
  
            if (product) {
                const productElement = document.createElement("div");
                productElement.classList.add("checkout-product", "checkout-product-item");
  
                const itemTotal = product.price * item.qty;
                subtotal += itemTotal;
  
                productElement.innerHTML = `
  
                <li>
                  <img src='${product.image}'>
                  <h4 class="truncate">${product.title}</h4>
                  <h5>${product.price.format()}</h5>
                  <h5>${item.qty} pcs</h5>
                </li>
  
  
  
                `;
  
                checkoutProducts.appendChild(productElement);
            }
        });
  
        const taxRate = 0.05;
        const shipping = 15.00;
        const tax = subtotal * taxRate;
        const grandTotal = subtotal + tax + shipping;
  
        // Display the total amount
        checkoutTotal.innerHTML = `
              <h5>Shipping</h5><h4>${shipping.format()}</h4>
              <h5 class='total'>Total</h5><h1>${grandTotal.format()}</h1>
                
                `;
                
  
        // Add event listeners for remove buttons
        const removeButtons = document.querySelectorAll(".remove-product");
        removeButtons.forEach(button => {
            button.addEventListener("click", function() {
                const id = parseInt(this.getAttribute("data-id"));
                removeProduct(id);
            });
        });
  
        // Add event listeners for quantity inputs
        const quantityInputs = document.querySelectorAll(".quantity-input");
        quantityInputs.forEach(input => {
            input.addEventListener("change", function() {
                const id = parseInt(this.getAttribute("data-id"));
                const newQty = parseInt(this.value);
                updateQuantity(id, newQty);
            });
        });
    }
  
    function removeProduct(id) {
        cart = cart.filter(item => item.id !== id);
        saveCart();
        renderCheckoutProducts();
        renderCart();
    }
  
    function updateQuantity(id, newQty) {
        const item = cart.find(item => item.id === id);
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
  Number.prototype.format = function() {
    return this.toLocaleString("en-US", {
        style: "currency",
        currency: "USD",
    });
  };
  
  // Helper function to format numbers without currency
  Number.prototype.formatWithoutCurrency = function() {
    return this.toLocaleString("en-US", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
  };
  
  
  
  
  
  
  
  
  