<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Velvet Vogue - Formal & Casual Wear</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <style>
        :root {
            --primary: #0c0668;
            --secondary: #1e3a8a;
            --accent: #2563eb;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --white: #ffffff;
            --black: #000000;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
        }
        
        /* Header Styles */
        .announcement-bar {
            background-color: var(--primary);
            color: var(--white);
            padding: 8px 0;
            text-align: center;
            font-size: 14px;
            font-weight: 500;
        }
        
        .header {
            background-color: var(--white);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 15px 0;
        }
        
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .logo img {
            height: 50px;
            margin-right: 10px;
        }
        
        /* Category Navigation */
        .category-nav {
            background-color: var(--white);
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }
        
        .category-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        
        .category-tab {
            padding: 10px 20px;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .category-tab.active {
            background-color: var(--primary);
            color: var(--white);
        }
        
        /* Product Grid */
        .products-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .section-title h1 {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .section-title p {
            font-size: 1.1rem;
            color: var(--gray);
            max-width: 700px;
            margin: 0 auto;
        }
        
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }
        
        .product-card {
            background: var(--white);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
        }
        
        .product-image {
            width: 100%;
            height: 350px;
            object-fit: cover;
        }
        
        .product-info {
            padding: 20px;
        }
        
        .product-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--dark);
        }
        
        .product-category {
            font-size: 0.9rem;
            color: var(--gray);
            margin-bottom: 10px;
            display: inline-block;
            padding: 3px 8px;
            background-color: #f0f0f0;
            border-radius: 4px;
        }
        
        .product-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary);
            margin: 10px 0;
        }
        
        .product-description {
            font-size: 0.95rem;
            color: var(--gray);
            margin-bottom: 15px;
            line-height: 1.5;
        }
        
        .product-sizes {
            display: flex;
            gap: 8px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        
        .size-option {
            padding: 5px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .size-option:hover, .size-option.selected {
            background-color: var(--primary);
            color: var(--white);
            border-color: var(--primary);
        }
        
        .add-to-cart {
            width: 100%;
            padding: 12px;
            background-color: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .add-to-cart:hover {
            background-color: var(--secondary);
        }
        
        /* Footer */
        .site-footer {
            background-color: var(--dark);
            color: var(--white);
            padding: 60px 0 20px;
            margin-top: 60px;
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            padding: 0 20px;
        }
        
        .footer-column h3 {
            font-size: 1.3rem;
            margin-bottom: 20px;
            color: var(--white);
        }
        
        .footer-column a {
            color: #cccccc;
            margin-bottom: 10px;
            display: block;
            text-decoration: none;
        }
        
        .footer-column a:hover {
            color: var(--white);
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            margin-top: 30px;
            border-top: 1px solid #333;
            color: #999;
            font-size: 0.9rem;
        }
        
        /* Responsive Styles */
        @media (max-width: 768px) {
            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
            
            .product-image {
                height: 300px;
            }
        }
        
        @media (max-width: 480px) {
            .section-title h1 {
                font-size: 2rem;
            }
            
            .category-container {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <!-- Announcement Bar -->
    <div class="announcement-bar">
        Island Wide Delivery Within 3 Days* - Buy 1 & Get 1 Free - <a href="#" style="color: white; text-decoration: underline;">SHOP NOW</a>
    </div>
    
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <a href="home.php" class="logo">
                <img src="07Oct24_Anis_Free_Upload_-removebg-preview (1).png" alt="Velvet Vogue">
                VELVET VOGUE
            </a>
            
            <div class="header-right">
                <a href="cart.php" style="color: var(--dark); text-decoration: none;">
                    <i class="fas fa-shopping-cart"></i> Cart
                </a>
            </div>
        </div>
    </header>
    
    <!-- Category Navigation -->
    <nav class="category-nav">
        <div class="category-container">
            <div class="category-tab active" onclick="filterProducts('all')">All</div>
            <div class="category-tab" onclick="filterProducts('women-casual')">Women's Casual</div>
            <div class="category-tab" onclick="filterProducts('women-formal')">Women's Formal</div>
            <div class="category-tab" onclick="filterProducts('men-casual')">Men's Casual</div>
            <div class="category-tab" onclick="filterProducts('men-formal')">Men's Formal</div>
        </div>
    </nav>
    
    <!-- Products Section -->
    <div class="products-container">
        <div class="section-title">
            <h1>Premium Fashion Collection</h1>
            <p>Discover our curated selection of high-quality formal and casual wear for men and women</p>
        </div>
        
        <div class="product-grid">
            <!-- Women's Casual Wear -->
            <div class="product-card" data-category="women-casual">
                <img src="https://images.unsplash.com/photo-1551232864-3f0890e580d9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="Women's Casual Dress" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Floral Summer Dress</h3>
                    <span class="product-category">Women's Casual</span>
                    <div class="product-price">Rs 2,490.00</div>
                    <p class="product-description">Lightweight and breathable floral dress perfect for summer outings. Made from 100% organic cotton.</p>
                    <div class="product-sizes">
                        <span class="size-option">S</span>
                        <span class="size-option selected">M</span>
                        <span class="size-option">L</span>
                        <span class="size-option">XL</span>
                    </div>
                    <button class="add-to-cart" onclick="addToCart('Floral Summer Dress', 2490, 'women-casual')">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                </div>
            </div>
            
            <!-- Women's Formal Wear -->
            <div class="product-card" data-category="women-formal">
                <img src="https://images.unsplash.com/photo-1539533018447-63fcce2678e4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="Women's Formal Dress" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Elegant Evening Gown</h3>
                    <span class="product-category">Women's Formal</span>
                    <div class="product-price">Rs 5,990.00</div>
                    <p class="product-description">Stunning floor-length gown with delicate lace details. Perfect for formal events and special occasions.</p>
                    <div class="product-sizes">
                        <span class="size-option">S</span>
                        <span class="size-option selected">M</span>
                        <span class="size-option">L</span>
                    </div>
                    <button class="add-to-cart" onclick="addToCart('Elegant Evening Gown', 5990, 'women-formal')">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                </div>
            </div>
            
            <!-- Men's Casual Wear -->
            <div class="product-card" data-category="men-casual">
                <img src="https://images.unsplash.com/photo-1529374255404-311a2a4f1fd9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=725&q=80" alt="Men's Casual Outfit" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Classic Denim Jacket</h3>
                    <span class="product-category">Men's Casual</span>
                    <div class="product-price">Rs 3,290.00</div>
                    <p class="product-description">Versatile denim jacket with a modern fit. Features multiple pockets and premium stitching.</p>
                    <div class="product-sizes">
                        <span class="size-option">S</span>
                        <span class="size-option selected">M</span>
                        <span class="size-option">L</span>
                        <span class="size-option">XL</span>
                    </div>
                    <button class="add-to-cart" onclick="addToCart('Classic Denim Jacket', 3290, 'men-casual')">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                </div>
            </div>
            
            <!-- Men's Formal Wear -->
            <div class="product-card" data-category="men-formal">
                <img src="https://images.unsplash.com/photo-1598033129183-c4f50c736f10?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1025&q=80" alt="Men's Formal Suit" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Premium Business Suit</h3>
                    <span class="product-category">Men's Formal</span>
                    <div class="product-price">Rs 8,990.00</div>
                    <p class="product-description">Tailored suit with premium wool blend fabric. Includes jacket and trousers with perfect fit.</p>
                    <div class="product-sizes">
                        <span class="size-option">S</span>
                        <span class="size-option">M</span>
                        <span class="size-option selected">L</span>
                        <span class="size-option">XL</span>
                    </div>
                    <button class="add-to-cart" onclick="addToCart('Premium Business Suit', 8990, 'men-formal')">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                </div>
            </div>
            
            <!-- Additional Women's Casual -->
            <div class="product-card" data-category="women-casual">
                <img src="https://images.unsplash.com/photo-1585487000160-6ebcfceb0d03?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=734&q=80" alt="Casual Top" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Casual Striped Top</h3>
                    <span class="product-category">Women's Casual</span>
                    <div class="product-price">Rs 1,590.00</div>
                    <p class="product-description">Comfortable and stylish striped top made from soft cotton blend. Perfect for everyday wear.</p>
                    <div class="product-sizes">
                        <span class="size-option selected">S</span>
                        <span class="size-option">M</span>
                        <span class="size-option">L</span>
                    </div>
                    <button class="add-to-cart" onclick="addToCart('Casual Striped Top', 1590, 'women-casual')">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                </div>
            </div>
            
            <!-- Additional Women's Formal -->
            <div class="product-card" data-category="women-formal">
                <img src="https://images.unsplash.com/photo-1551232864-3f0890e580d9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="Formal Blazer" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Tailored Blazer</h3>
                    <span class="product-category">Women's Formal</span>
                    <div class="product-price">Rs 4,290.00</div>
                    <p class="product-description">Sophisticated blazer with structured shoulders and premium fabric. Ideal for business or formal events.</p>
                    <div class="product-sizes">
                        <span class="size-option">S</span>
                        <span class="size-option selected">M</span>
                        <span class="size-option">L</span>
                    </div>
                    <button class="add-to-cart" onclick="addToCart('Tailored Blazer', 4290, 'women-formal')">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                </div>
            </div>
            
            <!-- Additional Men's Casual -->
            <div class="product-card" data-category="men-casual">
                <img src="https://images.unsplash.com/photo-1527719327859-c6ce80353573?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=764&q=80" alt="Casual Shirt" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Linen Casual Shirt</h3>
                    <span class="product-category">Men's Casual</span>
                    <div class="product-price">Rs 2,190.00</div>
                    <p class="product-description">Breathable linen shirt with relaxed fit. Perfect for warm weather and casual outings.</p>
                    <div class="product-sizes">
                        <span class="size-option">S</span>
                        <span class="size-option selected">M</span>
                        <span class="size-option">L</span>
                        <span class="size-option">XL</span>
                    </div>
                    <button class="add-to-cart" onclick="addToCart('Linen Casual Shirt', 2190, 'men-casual')">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                </div>
            </div>
            
            <!-- Additional Men's Formal -->
            <div class="product-card" data-category="men-formal">
                <img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=736&q=80" alt="Formal Shirt" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Classic Dress Shirt</h3>
                    <span class="product-category">Men's Formal</span>
                    <div class="product-price">Rs 2,890.00</div>
                    <p class="product-description">Premium cotton dress shirt with French cuffs. Perfect for business or formal occasions.</p>
                    <div class="product-sizes">
                        <span class="size-option">S</span>
                        <span class="size-option">M</span>
                        <span class="size-option selected">L</span>
                        <span class="size-option">XL</span>
                    </div>
                    <button class="add-to-cart" onclick="addToCart('Classic Dress Shirt', 2890, 'men-formal')">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-column">
                <h3>About Velvet Vogue</h3>
                <p>Your premier destination for fashion that blends timeless elegance with contemporary style.</p>
            </div>
            
            <div class="footer-column">
                <h3>Shop</h3>
                <a href="#">Women's</a>
                <a href="#">Men's</a>
                <a href="#">Accessories</a>
                <a href="#">New Arrivals</a>
            </div>
            
            <div class="footer-column">
                <h3>Help</h3>
                <a href="#">Customer Service</a>
                <a href="#">Track Order</a>
                <a href="#">Returns & Exchanges</a>
                <a href="#">Shipping Info</a>
            </div>
            
            <div class="footer-column">
                <h3>Contact Us</h3>
                <p><i class="fas fa-envelope"></i> support@velvetvogue.com</p>
                <p><i class="fas fa-phone"></i> +123 456 7890</p>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date("Y"); ?> Velvet Vogue. All rights reserved.</p>
        </div>
    </footer>
    
    <script>
        // Filter products by category
        function filterProducts(category) {
            const tabs = document.querySelectorAll('.category-tab');
            tabs.forEach(tab => tab.classList.remove('active'));
            event.currentTarget.classList.add('active');
            
            const products = document.querySelectorAll('.product-card');
            products.forEach(product => {
                if (category === 'all') {
                    product.style.display = 'block';
                } else {
                    if (product.dataset.category === category) {
                        product.style.display = 'block';
                    } else {
                        product.style.display = 'none';
                    }
                }
            });
        }
        
        // Size selection
        document.querySelectorAll('.size-option').forEach(option => {
            option.addEventListener('click', function() {
                this.parentElement.querySelectorAll('.size-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                this.classList.add('selected');
            });
        });
        
        // Add to cart functionality
        function addToCart(name, price, category) {
            const selectedSize = event.target.closest('.product-info').querySelector('.size-option.selected').textContent;
            
            // In a real implementation, you would add to cart here
            alert(`Added to cart: ${name} (Size: ${selectedSize}) - Rs ${price.toFixed(2)}`);
            
            // Example: You might use sessionStorage or make an AJAX call to your backend
            /*
            let cart = JSON.parse(sessionStorage.getItem('cart')) || [];
            cart.push({
                name: name,
                price: price,
                size: selectedSize,
                category: category
            });
            sessionStorage.setItem('cart', JSON.stringify(cart));
            */
        }
    </script>
</body>
</html>