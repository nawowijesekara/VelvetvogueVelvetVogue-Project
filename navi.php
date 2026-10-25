 <div class="sidebar">
    <div class="sidebar-brand">
      <!-- <img src="07Oct24 Anis Free Upload .png" alt="Velvet Vogue Logo"> -->
      <h4>VELVET VOGUE</h4>
      <div class="tagline">Luxury Fashion Admin</div>
    </div>
    
    <nav class="nav flex-column mt-4">
      <li class="nav-item">
        <a class="nav-link active" href="#">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="addproduct.php">
          <i class="fas fa-tshirt"></i>
          <span>Products</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="vieworders.php">
          <i class="fas fa-shopping-bag"></i>
          <span>Orders</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="manageusers.php">
          <i class="fas fa-users"></i>
          <span>Customers</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="feedback2.php">
          <i class="fas fa-star"></i>
          <span>Feedback</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="viewinqueries.php">
          <i class="fas fa-envelope"></i>
          <span>Inquiries</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="payment.php">
          <i class="fas fa-credit-card"></i>
          <span>Payments</span>
        </a>
      </li>
      <li class="nav-item mt-4">
        <a class="nav-link" href="logout2.php">
          <i class="fas fa-sign-out-alt"></i>
          <span>Logout</span>
        </a>
      </li>
    </nav>
  </div>
<style>
    

  /* Luxury Dashboard Styles */
  .luxury-dashboard {
    font-family: 'Montserrat', sans-serif;
    background-color: #f9f9f9;
    color: #333;
    padding: 20px;
  }
  
  .luxury-header {
    background-color: #1a1a2e;
    color: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 30px;
  }
  
  .header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  
  .brand-name {
    font-size: 24px;
    font-weight: 700;
    margin: 0;
    letter-spacing: 1px;
  }
  
  .brand-subtitle {
    font-size: 14px;
    opacity: 0.8;
    margin: 5px 0 0;
  }
  
  .header-actions {
    display: flex;
    align-items: center;
    gap: 30px;
  }
  
  .date-display {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
  }
  
  .user-profile {
    display: flex;
    align-items: center;
    gap: 15px;
  }
  
  .profile-info {
    display: flex;
    flex-direction: column;
    text-align: right;
  }
  
  .user-name {
    font-weight: 600;
  }
  
  .user-role {
    font-size: 12px;
    opacity: 0.7;
  }
  
  .profile-image {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(255,255,255,0.3);
  }
  
  /* Metrics Section */
  .metrics-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
  }
  
  .metric-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
  }
  
  .metric-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
  }
  
  .metric-details h3 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
  }
  
  .metric-details p {
    margin: 5px 0;
    font-size: 13px;
    color: #666;
  }
  
  .metric-trend {
    font-size: 12px;
    font-weight: 600;
  }
  
  .metric-trend.up {
    color: #4CAF50;
  }
  
  /* Orders Section */
  .orders-section {
    background: white;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
  }
  
  .section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
  }
  
  .section-header h2 {
    margin: 0;
    font-size: 20px;
  }
  
  .view-all {
    color: #666;
    text-decoration: none;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: color 0.3s;
  }
  
  .view-all:hover {
    color: #1a1a2e;
  }
  
  .orders-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
  }
  
  .order-card {
    border: 1px solid #eee;
    border-radius: 10px;
    padding: 15px;
    transition: transform 0.3s, box-shadow 0.3s;
  }
  
  .order-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
  }
  
  .order-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
  }
  
  .order-id {
    font-size: 13px;
    color: #666;
  }
  
  .order-status {
    font-size: 12px;
    padding: 3px 10px;
    border-radius: 20px;
    font-weight: 600;
  }
  
  .order-status.delivered {
    background-color: #e8f5e9;
    color: #4CAF50;
  }
  
  .order-status.processing {
    background-color: #fff8e1;
    color: #FFA000;
  }
  
  .order-status.shipped {
    background-color: #e3f2fd;
    color: #2196F3;
  }
  
  .order-status.returned {
    background-color: #ffebee;
    color: #F44336;
  }
  
  .order-body h4 {
    margin: 0 0 5px;
    font-size: 16px;
  }
  
  .order-date {
    font-size: 13px;
    color: #666;
    margin: 0 0 10px;
  }
  
  .order-amount {
    font-size: 18px;
    font-weight: 700;
    margin: 0;
  }
  
  .order-footer {
    margin-top: 15px;
  }
  
  .action-button {
    background: none;
    border: 1px solid #ddd;
    padding: 8px 15px;
    border-radius: 5px;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.3s;
  }
  
  .action-button:hover {
    background: #1a1a2e;
    color: white;
    border-color: #1a1a2e;
  }
</style>
