// static/js/cart.js

document.addEventListener("DOMContentLoaded", function () {
  const quantityControls = document.querySelectorAll(".quantity-control");

  quantityControls.forEach(control => {
    const minusBtn = control.querySelector(".decrement");
    const plusBtn = control.querySelector(".increment");
    const quantityDisplay = control.querySelector(".quantity");
    const productId = parseInt(control.dataset.productId);

    minusBtn.addEventListener("click", () => {
      let quantity = parseInt(quantityDisplay.textContent);
      if (quantity > 1) {
        quantity--;
        quantityDisplay.textContent = quantity;
        updateTotal(control, quantity);
        syncQuantityToServer(productId, quantity);
      }
    });

    plusBtn.addEventListener("click", () => {
      let quantity = parseInt(quantityDisplay.textContent);
      quantity++;
      quantityDisplay.textContent = quantity;
      updateTotal(control, quantity);
      syncQuantityToServer(productId, quantity);
    });
  });

  function updateTotal(control, quantity) {
    const row = control.closest("tr");
    const priceCell = row.querySelector(".price");
    const totalCell = row.querySelector(".total");

    const price = parseInt(priceCell.dataset.price);
    const total = price * quantity;

    totalCell.textContent = formatRupiah(total);
    recalculateBill();
  }

  function recalculateBill() {
    let subtotal = 0;
    document.querySelectorAll(".total").forEach(cell => {
      const value = parseInt(cell.textContent.replace(/\D/g, ""));
      if (!isNaN(value)) {
        subtotal += value;
      }
    });

    const shippingText = document.querySelector(".shipping-value").textContent;
    const shipping = parseInt(shippingText.replace(/\D/g, "")) || 0;

    const totalAmount = subtotal + shipping;

    document.querySelector(".subtotal-value").textContent = formatRupiah(subtotal);
    document.querySelector(".total-value").textContent = formatRupiah(totalAmount);
  }

  function syncQuantityToServer(productId, quantity) {
    fetch("/update_cart_quantity", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        product_id: productId,
        quantity: quantity,
      }),
    })
    .then((response) => response.json())
    .then((data) => {
      if (data.status !== "success") {
        console.error("Gagal update:", data.message);
      }
    })
    .catch((error) => console.error("Error:", error));
  }

  function formatRupiah(number) {
    return "Rp" + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }
});
