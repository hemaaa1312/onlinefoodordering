<div class="search-filter">
    <input type="text" id="search-input" placeholder="Search restaurants...">
    <select id="city-filter">
        <option value="">All Cities</option>
        <option value="Mumbai">Mumbai</option>
        <option value="Nagpur">Nagpur</option>
        <option value="Gondia">Gondia</option>
    </select>
</div>

<script>
document.getElementById('search-input').addEventListener('input', filterRestaurants);
document.getElementById('city-filter').addEventListener('change', filterRestaurants);

function filterRestaurants() {
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    const cityFilter = document.getElementById('city-filter').value;
    
    document.querySelectorAll('.restaurant-card').forEach(card => {
        const name = card.querySelector('h3').textContent.toLowerCase();
        const location = card.querySelector('p').textContent.toLowerCase();
        const city = card.querySelector('p').textContent.includes('Mumbai') ? 'Mumbai' : 
                    card.querySelector('p').textContent.includes('Nagpur') ? 'Nagpur' : 'Gondia';
        
        const matchesSearch = name.includes(searchTerm) || location.includes(searchTerm);
        const matchesCity = cityFilter === '' || city === cityFilter;
        
        card.style.display = (matchesSearch && matchesCity) ? 'block' : 'none';
    });
}
</script>