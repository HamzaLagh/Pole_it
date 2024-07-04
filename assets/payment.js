import './styles/pages/payment.scss';

document.addEventListener("DOMContentLoaded", function() {
    let cart = [];

    var products = $('#js-data').data('produits');

  var url = $('#js-data').data('url');
  
      
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
                  <img src='${url}/${product.image}'>
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
  
        checkoutTotal.innerHTML = `
              <h5>Shipping</h5><h4>${shipping.format()}</h4>
              <h5 class='total'>Total</h5><h1>${grandTotal.format()}</h1>
                
                `;
                
  
        const removeButtons = document.querySelectorAll(".remove-product");
        removeButtons.forEach(button => {
            button.addEventListener("click", function() {
                const id = parseInt(this.getAttribute("data-id"));
                removeProduct(id);
            });
        });
  
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
    
  
  // -------ecrirez
    function updateCardNumber() {
      const inputField = document.getElementById("inputCardNumber").value;
      let defaultText = "0000 0000 0000 0000";
      let updatedText = defaultText.split('');
  
      for (let i = 0; i < inputField.length; i++) {
          
          if (defaultText[i] === ' ') {
              updatedText[i] = ' ';
          } else {
              updatedText[i] = inputField[i] || '0';
          }
      }
  
      document.getElementById("label-cardnumber").innerText = updatedText.join('');
  }
  
  function updateExpiration() {
      const inputField = document.getElementById("inputExpiration").value;
      let defaultText = "00/0000";
      let updatedText = defaultText.split('');
  
      for (let i = 0; i < inputField.length; i++) {
         
          if (defaultText[i] === ' ' || defaultText[i] === '/') {
              updatedText[i] = defaultText[i];
          } else {
              updatedText[i] = inputField[i] || '0';
          }
      }
  
      document.getElementById("label-cardexpiration").innerText = updatedText.join('');
  }
  
  function updateCVC() {
      const inputField = document.getElementById("inputCVC").value;
      let defaultText = "000";
      let updatedText = defaultText.split('');
  
      for (let i = 0; i < inputField.length; i++) {
          updatedText[i] = inputField[i] || '0';
      }
  
      document.getElementById("label-cvc").innerText = updatedText.join('');
  }
  





function validateInput() {
    var cardNumber = document.getElementById("inputCardNumber").value;
    var expiration = document.getElementById("inputExpiration").value;
    var cvc = document.getElementById("inputCVC").value;

    
    if (!isValidCardNumber(cardNumber)) {
        alert("Please enter a valid card number.");
        return false;
    }

  
    if (!isValidExpiration(expiration)) {
        alert("Please enter a valid expiration date in MM / YYYY format.");
        return false;
    }

 
    if (!isValidCVC(cvc)) {
        alert("Please enter a valid CVC code.");
        return false;
    }

    return true;
}

function isValidCardNumber(cardNumber) {
    
    return cardNumber.length === 19;
}

function isValidExpiration(expiration) {
 
    return /^\d{2}\/\d{4}$/.test(expiration);
}

function isValidCVC(cvc) {
 
    return /^\d{3}$/.test(cvc);
}