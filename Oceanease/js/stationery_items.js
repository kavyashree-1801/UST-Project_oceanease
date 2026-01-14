document.addEventListener("DOMContentLoaded", () => {
    loadUser();
    loadItems();

    document.getElementById("itemForm").addEventListener("submit", saveItem);
});

function loadUser() {
    fetch("api/stationery_api.php?action=user")
        .then(r => r.json())
        .then(u => document.getElementById("userName").innerText = "Hello, " + u.name);
}

function loadItems() {
    fetch("api/stationery_api.php?action=list")
        .then(r => r.json())
        .then(data => {
            const tbody = document.getElementById("itemsTable");
            tbody.innerHTML = "";
            data.forEach((i, index) => {
                tbody.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${i.item}</td>
                    <td>${i.quantity_in_stock}</td>
                    <td>${i.price}</td>
                    <td>
                        <button class="btn edit" onclick='editItem(${JSON.stringify(i)})'>Edit</button>
                        <button class="btn delete" onclick="deleteItem(${i.id})">Delete</button>
                    </td>
                </tr>`;
            });
        });
}

function saveItem(e) {
    e.preventDefault();
    const fd = new FormData();
    fd.append("id", item_id.value);
    fd.append("item", item.value);
    fd.append("quantity", quantity.value);
    fd.append("price", price.value);

    fetch("api/stationery_api.php?action=save", { method:"POST", body:fd })
        .then(() => { resetForm(); loadItems(); });
}

function editItem(i) {
    item_id.value = i.id;
    item.value = i.item;
    quantity.value = i.quantity_in_stock;
    price.value = i.price;
    document.getElementById("formTitle").innerText = "Edit Item";
}

function deleteItem(id) {
    if (!confirm("Delete item?")) return;
    const fd = new FormData();
    fd.append("id", id);
    fetch("api/stationery_api.php?action=delete", { method:"POST", body:fd })
        .then(() => loadItems());
}

function resetForm() {
    item_id.value = "";
    document.getElementById("itemForm").reset();
    document.getElementById("formTitle").innerText = "Add New Item";
}
