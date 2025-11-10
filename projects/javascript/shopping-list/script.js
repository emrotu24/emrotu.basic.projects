

document.addEventListener('DOMContentLoaded', () => {
  const itemForm = document.getElementById('item-form');
  const itemInput = document.getElementById('item-input');
  const itemQty = document.getElementById('item-qty');
  const itemList = document.getElementById('item-list');
  const cartList = document.getElementById('cart-list');
  const toggleCartBtn = document.getElementById('toggle-cart');
  const template = document.querySelector('.item-template');

  // Aggiungi nuovo elemento
  itemForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const text = itemInput.value.trim();
    const qty = itemQty.value.trim();

    if (text == ''){
      itemInput.classList = 'form-control form-control-lg border-danger'
    }else if(qty == ''){
      itemQty.classList = 'form-control form-control-lg border-danger'
    }

    if (text !== '' && qty !== '') {
      const newItem = template.cloneNode(true);
      newItem.style.display = 'block';
      newItem.classList.remove('item-template');
      newItem.querySelector('.item-text').textContent = text;
      newItem.querySelector('.item-quantity').textContent = `x${qty}`;
      itemList.appendChild(newItem);

      itemInput.value = '';
      itemQty.value = '';
    }
  });

  // Gestione click su lista principale
  itemList.addEventListener('click', (e) => {
    const itemCard = e.target.closest('.col-12, .col-sm-6, .col-md-4, .col-lg-3');
    if (!itemCard) return;

    const itemText = itemCard.querySelector('.item-text').textContent;
    const itemQty = itemCard.querySelector('.item-quantity').textContent;

    if (e.target.closest('.remove-item')) {
      itemCard.remove();
      removeFromCart(`${itemText} ${itemQty}`);
    } else if (e.target.closest('.mark-item')) {
      itemCard.classList.toggle('border-success');
      itemCard.classList.toggle('bg-light');
      addToCart(`${itemText} ${itemQty}`);
    }
  });

  // Mostra/Nascondi carrello
  toggleCartBtn.addEventListener('click', () => {
    cartList.style.display = cartList.style.display === 'none' ? 'block' : 'none';
  });

  // Aggiungi al carrello
  function addToCart(text) {
    const exists = [...cartList.querySelectorAll('li')].some(
      (li) => li.textContent === text
    );
    if (!exists) {
      const li = document.createElement('li');
      li.className = 'list-group-item';
      li.textContent = text;
      cartList.appendChild(li);
    }
  }

  // Rimuovi dal carrello
  function removeFromCart(text) {
    const items = cartList.querySelectorAll('li');
    items.forEach((li) => {
      if (li.textContent === text) {
        li.remove();
      }
    });
  }
});



