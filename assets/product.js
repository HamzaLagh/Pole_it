import axios from "axios";
import './styles/pages/product.scss';



document.addEventListener('DOMContentLoaded', () => {
    const cartIcon = document.querySelector('#cart-icon');
    const cart = document.querySelector('.cart');
    const closeCart = document.querySelector('#close-cart');
    
    getCartFromStorage();
   


   cartIcon.addEventListener('click', () => cart.classList.add('active'));
    closeCart.addEventListener('click', () => cart.classList.remove('active'));

    const initCartFunctions = () => {
        document.querySelectorAll('.cart-remove').forEach((button) => {
            button.addEventListener('click', removeCartItem);
        });

        document.querySelectorAll('.cart-quantity').forEach((input) => {
            input.addEventListener('change', quantityChanged);
        });

        document.querySelectorAll('.add-cart').forEach((button) => {
            button.addEventListener('click', addCartClicked);
        });

        document.querySelector('.btn-buy').addEventListener('click', buyButtonClicked);
    };

    initCartFunctions();

    // const addRemoveQuantity = document.querySelector('.cart-quantity');

    // addRemoveQuantity.addEventListener('onchange',function(e){
    //     console.log(e)
    //     console.log(e.parentElement)
    // })

    function buyButtonClicked() {
        const cartContent = document.querySelector('.cart-content');
        const cartItems = cartContent.querySelectorAll('.cart-box');
        if (cartItems.length === 0) {
            alert('Votre panier est vide');
        } else {
            alert('Order successfully placed');
            while (cartContent.firstChild) {
                cartContent.removeChild(cartContent.firstChild);
            }
            document.querySelector('.total-price').innerText = '0,00 €';
        }
    }

    function removeCartItem(event) {
       let parentElements = event.target.parentElement.querySelector(".detail-box");
       let prix = parentElements.querySelector('.cart-price').innerText;
      let splitPrice = parseInt(prix.split(" ")[0]);
      
      
       parentElements.querySelector('.id-hide').style.visibility= 'visible';
       let id = parentElements.querySelector('.id-hide').innerText;
       let qte = parentElements.querySelector('input').value;
      
     upgradeItemQuantity(id,splitPrice,qte,"removeQuantity");

        event.target.parentElement.remove();
        updateTotalPrice();
    }

    function quantityChanged(event) {
        const input = event.target;
        console.log(input)
        console.log(event)
        if (isNaN(input.value) || input.value <= 0) {
            input.value = 1;
        }
        updateTotalPrice();
    }

    let isAddingToCart = false;

    function getProductStorage(){
        return  localStorage.getItem("panier");
    }

   function upgradeStorageTotalPrice(price,panierQyte){
       let  data = localStorage.getItem("panier");
        if(data !=null){
           let result =  JSON.parse(localStorage.getItem("panier"));
           result.totalPrices = price;
           result.totalItems = panierQyte;
           localStorage.setItem("panier", JSON.stringify(result));
        }
    }

    function removeItemFromLocalStorage(price,panierQyte){
        let  data = localStorage.getItem("panier");
         if(data !=null){
            let result =  JSON.parse(localStorage.getItem("panier"));
            result.totalPrices = price;
            result.totalItems = panierQyte;
            localStorage.setItem("panier", JSON.stringify(result));
         }
     }

  

    function upgradeItemQuantity(id,prix,qte,action){
       
       let data =  JSON.parse(localStorage.getItem("panier"))
        console.log(data);
        console.log(data.cart);
        if(data !=null && data.cart.length >0){
        var result =  JSON.parse(localStorage.getItem("panier"));
        if(action =="removeQuantity"){

            let elementFilter = result.cart.filter((v) => v.id !=id);
            result.cart = elementFilter;
            if(elementFilter.length >0){
                // calcul du nouveau prix total
                let newTotalPrice = qte * prix;
                let total = result.totalPrices - newTotalPrice;
                result.totalPrices = total;

                //calcul du total des produits
                result.totalItems -= qte;
            localStorage.setItem("panier", JSON.stringify(result));

            }else{
                localStorage.clear();
            }
            
        }else if(action =="addQuantity"){
            let mapResult =   result.cart.map(v =>{
                if(v.id == id){
                    v.qte+=1;
                    return v;
                }else{
                    return v;
                }
            })
            
         localStorage.setItem("panier", JSON.stringify(mapResult));
        }

           


        }
        
    }

    function saveCardToStorage(id,title, price, productImg,qte){
       
       let data = getProductStorage();
      
        if(data !=null){
           console.log("save ici")
            let storageData = JSON.parse(data);
            let panier = storageData.cart;         
            panier.push({ id: id, title: title,price:price,productImg:productImg,qte });
           

            const userCart = {
                cart:panier,
                totalPrices:storageData.totalPrices +price,
                totalItems:storageData.totalItems+1
                
              };
    
            localStorage.setItem("panier", JSON.stringify(userCart));
            
        }else{
            let panier = [];
            panier.push({ id: id, title: title,price:price,productImg:productImg,qte });
            const userCart = {
                cart:panier,
                totalPrices:price,
                totalItems:qte
                
              };
    
            localStorage.setItem("panier", JSON.stringify(userCart));
        }
       
     

        
    }

    


    function getCartFromStorage(){
        const cartItems = document.querySelector('.cart-content');
        const cartItemNames = cartItems.querySelectorAll('.id-hide');
        const data = getProductStorage();
        if(data !=null){
        
            let cartStorage = JSON.parse(data);

            cartStorage.cart.forEach((element) =>{
                const cartShopBox = document.createElement('div');
                cartShopBox.classList.add('cart-box');
                cartShopBox.innerHTML = `
                    <img src="${element.productImg}" alt="${element.title}" class="cart-img">
                    <div class="detail-box">
                    <div class="id-hide" productId="${element.id}">${element.id}</div>
                        <div  class="cart-product-title">${element.title}</div>
                        <div class="cart-price">${element.price}</div>
                        <input type="number"  value="${element.qte}" class="cart-quantity">
                    </div>
                    <i class="bx bxs-trash-alt cart-remove"></i>`;
                cartItems.prepend(cartShopBox);
            })

            document.querySelector('.cart-qty').innerText = cartStorage.totalItems;
            document.querySelector('.total-price').innerText = `${cartStorage.totalPrices.toFixed(2).replace('.', ',')} €`;
        }
            
    }

    function addCartClicked(event) {
        isAddingToCart = true;
        const button = event.target;
        const shopProducts = button.parentElement;
        const title = shopProducts.querySelector('.product-title').innerText;
        const price = shopProducts.querySelector('.price').innerText;
        const productImg = shopProducts.querySelector('.product-img').src;
        const productId = shopProducts.getAttribute("articleId");
        console.log(productId)
        addProductToCart(productId,title, price, productImg);
        saveCardToStorage(productId,title, price, productImg,1);
        updateTotalPrice();
        setTimeout(() => (isAddingToCart = false), 100);
    }

    function addProductToCart(id,title, price, productImg) {
        const cartItems = document.querySelector('.cart-content');
        const cartItemNames = cartItems.querySelectorAll('.id-hide');

        if (Array.from(cartItemNames).some((itemName) => itemName.getAttribute("productId") === id)) {
            alert('Ce produit est déjà dans votre panier');
           cart.classList.add('active');
           return;
        }

        const cartShopBox = document.createElement('div');
        cartShopBox.classList.add('cart-box');
        cartShopBox.innerHTML = `
            <img src="${productImg}" alt="${title}" class="cart-img">
            <div class="detail-box">
            <div class="id-hide" productId="${id}">${id}</div>
                <div  class="cart-product-title">${title}</div>
                <div class="cart-price">${price}</div>
                <input type="number" value="1" class="cart-quantity">
            </div>
            <i class="bx bxs-trash-alt cart-remove"></i>`;
        cartItems.prepend(cartShopBox);
        cartShopBox.querySelector('.cart-remove').addEventListener('click', removeCartItem);
        cartShopBox.querySelector('.cart-quantity').addEventListener('change', quantityChanged);

        cart.classList.add('active');
        // saveCardToStorage(id,title, price, productImg,1);
    }

    function updateTotalPrice() {
        const cartContent = document.querySelector('.cart-content');
        const cartBoxes = cartContent.querySelectorAll('.cart-box');
        let total = 0;
        let qte = 0;
        cartBoxes.forEach((cartBox) => {
            const priceElement = cartBox.querySelector('.cart-price');
            const quantityElement = cartBox.querySelector('.cart-quantity');
            console.log(cartBox)
            let price = parseFloat(priceElement.innerText.replace('€', '').replace(',', '.'));
            let quantity = parseInt(quantityElement.value);
            qte += quantity;
            total += price * quantity;
        });
        upgradeStorageTotalPrice(total,qte);
        document.querySelector('.total-price').innerText = `${total.toFixed(2).replace('.', ',')} €`;
        document.querySelector('.cart-qty').innerText = qte;

       // upgradeItemQuantity(id,prix,qte,"addQuantity");
    }

    document.addEventListener('click', (event) => {
        if (!cart.contains(event.target) && !cartIcon.contains(event.target) && !isAddingToCart && cart.classList.contains('active')) {
            cart.classList.remove('active');
        }
    });
});
