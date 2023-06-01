function addToCart(itemName, price) {
    var cartItems = [];

    // Check if the cart already exists in cookies
    var existingCart = getCookie("cartItems");
    if (existingCart) {
      cartItems = JSON.parse(existingCart);
    }

    // Check if the item already exists in the cart
    var existingItem = findItemInCart(itemName);
    if (existingItem) {
      // If the item already exists, increment the quantity
      existingItem.qty++;
    } else {
      // If the item doesn't exist, add it to the cart
      var newItem = {
        itemName: itemName,
        price: price,
        qty: 1
      };
      cartItems.push(newItem);
    }

    // Store the updated cart in cookies
    document.cookie = "cartItems=" + JSON.stringify(cartItems);
    alert("Item added to cart!");
  }

  function findItemInCart(itemName) {
    var cartItems = getCookie("cartItems");
    if (cartItems) {
      var items = JSON.parse(cartItems);
      for (var i = 0; i < items.length; i++) {
        if (items[i].itemName === itemName) {
          return items[i];
        }
      }
    }
    return null;
  }

  function getCookie(name) {
    var cookieArr = document.cookie.split("; ");
    for (var i = 0; i < cookieArr.length; i++) {
      var cookiePair = cookieArr[i].split("=");
      if (name === cookiePair[0]) {
        return decodeURIComponent(cookiePair[1]);
      }
    }
    return null;
  }

  function removeItem(itemName) {
    var cartItems = [];

    // Check if the cart exists in cookies
    var existingCart = getCookie("cartItems");
    if (existingCart) {
        cartItems = JSON.parse(existingCart);
    }

    // Find the index of the item to be removed
    var itemIndex = findItemIndexInCart(itemName);
    if (itemIndex !== -1) {
        // Remove the item from the cart
        cartItems.splice(itemIndex, 1);

        // Store the updated cart in cookies
        document.cookie = "cartItems=" + JSON.stringify(cartItems);

        // Reload the page
        location.reload();
    }
}

// Attach the removeItem() function as an event handler to the "Remove" buttons
var removeButtons = document.getElementsByClassName("btn-remove");
for (var i = 0; i < removeButtons.length; i++) {
    removeButtons[i].addEventListener("click", function () {
        var itemName = this.getAttribute("data-itemName");
        removeItem(itemName);
    });
}


function findItemIndexInCart(itemName) {
    var cartItems = [];
    var existingCart = getCookie("cartItems");
    if (existingCart) {
        cartItems = JSON.parse(existingCart);
    }
    for (var i = 0; i < cartItems.length; i++) {
        if (cartItems[i].itemName === itemName) {
            return i;
        }
    }

    return -1;
}



