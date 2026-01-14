document.addEventListener('DOMContentLoaded', () => {
    const grid = document.getElementById('menuGrid');

    fetch('api/fetch_menu.php')
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                grid.innerHTML = '<p style="color:red;">Unauthorized</p>';
                return;
            }

            if (data.length === 0) {
                grid.innerHTML = '<p>No menu items found.</p>';
                return;
            }

            grid.innerHTML = '';
            data.forEach(item => {
                grid.innerHTML += `
                    <div class="card">
                        <img src="${item.image}" alt="${item.item_name}">
                        <div class="card-content">
                            <h3>${item.item_name}</h3>
                            <p><strong>Category:</strong> ${item.category}</p>
                            <p>${item.description || ''}</p>
                            <strong>₹${item.price}</strong>
                        </div>
                    </div>
                `;
            });
        })
        .catch(err => {
            console.error(err);
            grid.innerHTML = '<p style="color:red;">Failed to load menu</p>';
        });
});
