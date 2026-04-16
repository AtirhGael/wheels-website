/* Elite BBS Rims - Cart JavaScript */

// Absolute URL for cart AJAX — pages in subdirectories can't use relative paths
const cartAjaxUrl = (typeof siteUrl !== 'undefined' ? siteUrl : '') + '/includes/cart_ajax.php';

// Initialize cart display
document.addEventListener('DOMContentLoaded', function() {
    updateCartDisplay();
});

// Update cart display (count and total)
function updateCartDisplay() {
    fetch(cartAjaxUrl + '?action=get')
        .then(response => response.json())
        .then(data => {
            // Update all cart count elements
            document.querySelectorAll('#cart-count, #cart-count-mobile').forEach(el => {
                el.textContent = data.count;
            });
            
            // Update cart total if element exists
            const totalEl = document.getElementById('cart-total');
            if (totalEl) {
                totalEl.textContent = data.total.toFixed(2);
            }
        })
        .catch(err => console.log('Cart fetch error:', err));
}

// Add to cart
function addToCart(productId, name, _price, _image, qty = 1) {
    const formData = new FormData();
    formData.append('action', 'add');
    formData.append('product_id', productId);
    formData.append('quantity', qty);
    
    fetch(cartAjaxUrl, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartDisplay();
            // Show success message
            showNotification(name + ' added to cart!', 'success');
        } else {
            showNotification(data.message || 'Error adding to cart', 'error');
        }
    })
    .catch(err => {
        console.error('Add to cart error:', err);
        showNotification('Error adding to cart', 'error');
    });
}

// Remove from cart
function removeFromCart(productId) {
    if (!confirm('Remove this item from cart?')) return;
    
    const formData = new FormData();
    formData.append('action', 'remove');
    formData.append('product_id', productId);
    
    fetch(cartAjaxUrl, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartDisplay();
            // Reload cart page to update UI
            location.reload();
        }
    })
    .catch(err => console.error('Remove error:', err));
}

// Update cart quantity
function updateCartQty(productId, newQty) {
    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('product_id', productId);
    formData.append('quantity', newQty);
    
    fetch(cartAjaxUrl, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartDisplay();
            // Optionally reload to update totals
            const totalEl = document.getElementById('cart-grand-total');
            if (totalEl) {
                totalEl.textContent = '$' + data.total.toFixed(2);
            }
        }
    })
    .catch(err => console.error('Update error:', err));
}

// Clear cart
function clearCart() {
    if (!confirm('Clear all items from cart?')) return;
    
    const formData = new FormData();
    formData.append('action', 'clear');
    
    fetch(cartAjaxUrl, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartDisplay();
            location.reload();
        }
    })
    .catch(err => console.error('Clear error:', err));
}

// Add to cart from product page (triggered by button)
function addToCartFromPage() {
    const productId = document.getElementById('product-id').value;
    const name = document.getElementById('product-name').value;
    const price = document.getElementById('product-price').value;
    const image = document.getElementById('product-image').value;
    const qty = parseInt(document.getElementById('product-qty').value) || 1;
    
    addToCart(productId, name, price, image, qty);
}

// Show notification
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'notification notification-' + type;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 5px;
        z-index: 9999;
        animation: slideIn 0.3s ease;
        ${type === 'success' ? 'background: #d4edda; color: #155724; border: 1px solid #c3e6cb;' : ''}
        ${type === 'error' ? 'background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;' : ''}
    `;
    
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Add animation keyframes
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
`;
document.head.appendChild(style);

// Vehicle fitment data (kept from main.js)
var vehicleData = {
    'Toyota': ['Camry','Corolla','RAV4','Hilux','Land Cruiser','Yaris','Prius','HiAce','Supra','86','GR86','Tundra','4Runner','Sequoia'],
    'Honda': ['Civic','Accord','CR-V','HR-V','Jazz','City','Pilot','Odyssey','S2000','NSX','Type R','Fit','Passport'],
    'Ford': ['F-150','F-250','F-350','Mustang','Mustang GT500','Explorer','Ranger','Escape','Edge','Bronco','Transit','GT'],
    'Chevrolet': ['Silverado','Malibu','Equinox','Tahoe','Suburban','Camaro','Camaro ZL1','Traverse','Corvette','Colorado','Blazer'],
    'BMW': ['2 Series','3 Series','4 Series','5 Series','7 Series','8 Series','X3','X5','X6','X7','M2','M3','M4','M5','M8','i4','iX'],
    'Mercedes-Benz': ['C-Class','E-Class','S-Class','GLE','GLC','GLS','A-Class','AMG GT','AMG C63','AMG E63','AMG G63','G-Class','SL','CLA'],
    'Nissan': ['Altima','Sentra','Rogue','Pathfinder','Frontier','Titan','Murano','370Z','GT-R','Skyline','Maxima','Armada'],
    'Hyundai': ['Elantra','Sonata','Tucson','Santa Fe','Palisade','Kona','Ioniq','Ioniq 5','Ioniq 6','Veloster N','i30 N'],
    'Kia': ['Optima','Sportage','Sorento','Telluride','Soul','Stinger','EV6','Carnival','K5'],
    'Volkswagen': ['Golf','Golf R','Passat','Tiguan','Jetta','Atlas','Polo','Arteon','GTI','R32'],
    'Audi': ['A3','A4','A6','A8','Q3','Q5','Q7','Q8','TT','TTS','R8','RS3','RS4','RS6','RS7','e-tron','e-tron GT'],
    'Dodge': ['Charger','Charger Hellcat','Challenger','Challenger Hellcat','Durango','Ram 1500','Journey','Viper'],
    'Jeep': ['Wrangler','Cherokee','Grand Cherokee','Grand Cherokee L','Compass','Renegade','Gladiator'],
    'Subaru': ['Outback','Forester','Impreza','Legacy','Crosstrek','WRX','WRX STI','BRZ'],
    'Mazda': ['Mazda3','Mazda6','CX-5','CX-9','MX-5 Miata','CX-30','CX-50','RX-7','RX-8'],
    'Porsche': ['911','911 GT3','911 Turbo','Cayenne','Macan','Panamera','Taycan','718 Cayman','718 Boxster'],
    'Ferrari': ['488','F8 Tributo','SF90','Roma','Portofino','296 GTB','812 Superfast','F40','F50','Enzo','LaFerrari'],
    'Lamborghini': ['Huracan','Huracan EVO','Urus','Aventador','Aventador SVJ','Gallardo','Murcielago'],
    'Bentley': ['Continental GT','Continental GTC','Flying Spur','Bentayga','Mulsanne'],
    'Rolls-Royce': ['Ghost','Phantom','Wraith','Dawn','Cullinan','Spectre'],
    'Maserati': ['Ghibli','Quattroporte','GranTurismo','GranCabrio','Levante','MC20'],
    'Alfa Romeo': ['Giulia','Stelvio','4C','Giulia Quadrifoglio','Tonale'],
    'Aston Martin': ['DB11','Vantage','DBS','DBX','Valkyrie'],
    'Lexus': ['IS','ES','GS','LS','RC','RC F','LC','LC 500','NX','RX','LX','GX','UX'],
    'Infiniti': ['Q50','Q60','Q70','QX50','QX55','QX60','QX80','G35','G37'],
    'Acura': ['ILX','TLX','RLX','MDX','RDX','NSX','Integra','RSX'],
    'Mitsubishi': ['Lancer','Lancer Evolution','Eclipse','Eclipse Cross','Outlander','Galant','3000GT'],
    'Cadillac': ['CT4','CT5','CT6','XT4','XT5','XT6','Escalade','CTS-V','ATS-V'],
    'Lincoln': ['Navigator','Aviator','Corsair','Nautilus','Continental','MKZ'],
    'Buick': ['Enclave','Envision','Encore','LaCrosse','Regal GS'],
    'GMC': ['Sierra','Yukon','Yukon XL','Canyon','Terrain','Acadia','Envoy'],
    'Pontiac': ['GTO','Firebird','Trans Am','G8','Solstice'],
    'Chrysler': ['300','300C','300 SRT8','Pacifica','Sebring'],
    'Genesis': ['G70','G80','G90','GV70','GV80','GV60'],
    'Scion': ['FR-S','tC','xB','iQ'],
    'Suzuki': ['Swift','Jimny','Vitara','Grand Vitara','SX4']
};

// Make/Model dropdown handling
document.addEventListener('DOMContentLoaded', function() {
    var makeEl = document.getElementById('vf2-make');
    var modelEl = document.getElementById('vf2-model');
    
    if (makeEl && modelEl) {
        makeEl.addEventListener('change', function() {
            var models = vehicleData[this.value] || [];
            modelEl.innerHTML = '<option value="">Select Model</option>';
            models.forEach(function(m) {
                var opt = document.createElement('option');
                opt.value = opt.textContent = m;
                modelEl.appendChild(opt);
            });
        });
    }
});

// Search function
function doSearch() {
    var make = document.getElementById('vf2-make').value;
    var model = document.getElementById('vf2-model').value;
    var part = document.getElementById('vf2-part').value;
    
    if (!make || !model) {
        alert('Please select Make and Model before searching.');
        return;
    }
    
    var base = 'shop?search=';
    var query = encodeURIComponent(make + ' ' + model + (part ? ' ' + part : ''));
    window.location.href = base + query;
}

// Format price helper
function formatPrice(price) {
    return '$' + parseFloat(price).toFixed(2);
}