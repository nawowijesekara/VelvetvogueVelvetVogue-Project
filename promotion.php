<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Offers | Velvet Vogue</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #8a63ff;
            --primary-dark: #6a4bcc;
            --accent-color: #ff7e9d;
            --text-color: #2d3748;
            --light-gray: #f7fafc;
            --gray: #e2e8f0;
            --dark-gray: #a0aec0;
            --error-color: #e53e3e;
            --success-color: #38a169;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-gray);
            color: var(--text-color);
            background-image: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
        }
        
        .promotion-header {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            padding: 60px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .promotion-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://example.com/pattern.png') center/cover;
            opacity: 0.1;
        }
        
        .promotion-header h1 {
            font-weight: 700;
            font-size: 42px;
            margin-bottom: 15px;
            position: relative;
        }
        
        .promotion-header p {
            font-size: 18px;
            max-width: 700px;
            margin: 0 auto;
            opacity: 0.9;
            position: relative;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .promo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .promo-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .promo-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }
        
        .promo-image {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .promo-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--accent-color);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .promo-content {
            padding: 25px;
        }
        
        .promo-content h3 {
            font-size: 22px;
            margin-bottom: 10px;
            color: var(--primary-dark);
        }
        
        .promo-content p {
            color: var(--dark-gray);
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .promo-price {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .current-price {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .original-price {
            font-size: 16px;
            color: var(--dark-gray);
            text-decoration: line-through;
            margin-left: 10px;
        }
        
        .promo-btn {
            display: inline-block;
            padding: 12px 25px;
            background: var(--primary-color);
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s ease;
        }
        
        .promo-btn:hover {
            background: var(--primary-dark);
        }
        
        .countdown {
            background: white;
            border-radius: 16px;
            padding: 25px;
            margin: 40px auto;
            max-width: 800px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
        
        .countdown h2 {
            color: var(--primary-dark);
            margin-bottom: 20px;
        }
        
        .timer {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        
        .timer-unit {
            background: var(--light-gray);
            padding: 15px;
            border-radius: 8px;
            min-width: 80px;
        }
        
        .timer-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .timer-label {
            font-size: 12px;
            color: var(--dark-gray);
            text-transform: uppercase;
            margin-top: 5px;
        }
        
        @media (max-width: 768px) {
            .promo-grid {
                grid-template-columns: 1fr;
            }
            
            .promotion-header h1 {
                font-size: 32px;
            }
            
            .timer {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <header class="promotion-header">
        <h1>Exclusive Promotions</h1>
        <p>Discover our limited-time offers and elevate your style with Velvet Vogue's premium collections</p>
    </header>
    
    <div class="container">
        <div class="countdown">
            <h2>Hurry! These offers end in:</h2>
            <div class="timer">
                <div class="timer-unit">
                    <div class="timer-value" id="days">00</div>
                    <div class="timer-label">Days</div>
                </div>
                <div class="timer-unit">
                    <div class="timer-value" id="hours">00</div>
                    <div class="timer-label">Hours</div>
                </div>
                <div class="timer-unit">
                    <div class="timer-value" id="minutes">00</div>
                    <div class="timer-label">Minutes</div>
                </div>
                <div class="timer-unit">
                    <div class="timer-value" id="seconds">00</div>
                    <div class="timer-label">Seconds</div>
                </div>
            </div>
        </div>
        
        <div class="promo-grid">
            <!-- Promotion 1 -->
            <div class="promo-card">
                <div class="promo-image" style="background-image: url('https://images.unsplash.com/photo-1551232864-3f0890e580d9?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80');">
                    <span class="promo-badge">Limited Time</span>
                </div>
                <div class="promo-content">
                    <h3>Summer Collection</h3>
                    <p>Refresh your wardrobe with our breezy summer styles. Light fabrics and vibrant colors perfect for the season.</p>
                    <div class="promo-price">
                        <span class="current-price">Rs8900.99</span>
                        <span class="original-price">Rs9290.99</span>
                    </div>
                    <a href="#" class="promo-btn">Shop Now</a>
                </div>
            </div>
            
            <!-- Promotion 2 -->
            <div class="promo-card">
                <div class="promo-image" style="background-image: url('https://images.unsplash.com/photo-1520367445093-50dc08a59d9d?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80');">
                    <span class="promo-badge">30% Off</span>
                </div>
                <div class="promo-content">
                    <h3>Luxury Accessories</h3>
                    <p>Complete your look with our premium accessories. Handcrafted pieces that add elegance to any outfit.</p>
                    <div class="promo-price">
                        <span class="current-price">Rs8900.99</span>
                        <span class="original-price">Rs9900.99</span>
                    </div>
                    <a href="#" class="promo-btn">View Collection</a>
                </div>
            </div>
            
            <!-- Promotion 3 -->
            <div class="promo-card">
                <div class="promo-image" style="background-image: url('https://images.unsplash.com/photo-1558769132-cb1aea458c5e?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80');">
                    <span class="promo-badge">New Arrival</span>
                </div>
                <div class="promo-content">
                    <h3>Evening Wear</h3>
                    <p>Make a statement at your next event with our stunning evening collection. Elegant designs for unforgettable nights.</p>
                    <div class="promo-price">
                        <span class="current-price">Rs4749.99</span>
                        <span class="original-price">Rs5199.99</span>
                    </div>
                    <a href="#" class="promo-btn">Explore</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Countdown timer (3 days from now)
        function updateCountdown() {
            const now = new Date();
            const endDate = new Date();
            endDate.setDate(now.getDate() + 3);
            
            const diff = endDate - now;
            
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);
            
            document.getElementById('days').textContent = days.toString().padStart(2, '0');
            document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
        }
        
        // Update every second
        updateCountdown();
        setInterval(updateCountdown, 1000);
        
        // Add to cart animation
        document.querySelectorAll('.promo-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const card = this.closest('.promo-card');
                card.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    card.style.transform = 'scale(1)';
                }, 200);
                
                // In a real app, you would add the item to cart here
                alert('Item added to your cart!');
            });
        });
    </script>
</body>
</html>