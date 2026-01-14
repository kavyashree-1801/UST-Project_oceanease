document.addEventListener("DOMContentLoaded", () => {
    loadItems();
});

function loadItems() {
    fetch("api/stationery_items_api.php?action=list")
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById("cardsContainer");
            container.innerHTML = "";

            document.getElementById("totalItems").textContent = data.length;
            document.getElementById("inStock").textContent = 
                data.filter(item => item.quantity_in_stock > 0).length;

            if(data.length===0) {
                container.innerHTML="<p>No items available</p>";
                return;
            }

            data.forEach(item => {
                container.innerHTML += `
                    <div class="item-card">
                        <img src="${item.image}" alt="${item.item}">
                        <h3>${item.item}</h3>
                        <p><strong>Price:</strong> ₹${item.price}</p>
                        <p class="stock ${item.quantity_in_stock>0?'in':'out'}">
                            ${item.quantity_in_stock>0?'In Stock':'Out of Stock'}
                        </p>
                        <span class="qty">Qty: ${item.quantity_in_stock}</span>
                    </div>
                `;
            });
        })
        .catch(err => console.error(err));
}
