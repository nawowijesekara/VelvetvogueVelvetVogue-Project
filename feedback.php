
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Feedback</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link 
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
    rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .feedback-card {
      background-color: #ffffff;
      border-radius: 12px;
      border: none;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    .form-control, .form-select {
      background-color: #f8f9fa;
      border: 1px solid #e9ecef;
      padding: 10px 15px;
    }
    .form-control:focus {
      background-color: #ffffff;
      box-shadow: 0 0 0 0.25rem rgba(211, 211, 211, 0.25);
    }
    .btn-primary {
      background-color: #6c757d;
      border-color: #6c757d;
      padding: 10px 20px;
    }
    .btn-primary:hover {
      background-color: #5a6268;
      border-color: #545b62;
    }
    .star-rating {
      display: flex;
      flex-direction: row-reverse;
      justify-content: flex-end;
    }
    .star-rating input[type="radio"] {
      display: none;
    }
    .star-rating label {
      font-size: 1.8rem;
      color: #e0e0e0;
      cursor: pointer;
      padding: 0 0.2rem;
      transition: color 0.2s;
    }
    .star-rating input[type="radio"]:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
      color: #ffc107;
    }
    .page-title {
      color: #495057;
      font-weight: 600;
      margin-bottom: 1.5rem;
    }
    .form-label {
      color: #495057;
      font-weight: 500;
      margin-bottom: 0.5rem;
    }
  </style>
</head>
<body class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <h2 class="page-title">✍ Customer Feedback</h2>
      
      <form action="submit_feedback.php" method="POST" class="card feedback-card p-4">
        <div class="mb-4">
          <label for="name" class="form-label">Your Name</label>
          <input type="text" class="form-control" name="name" id="name" required>
        </div>

        <div class="mb-4">
          <label for="email" class="form-label">Your Email</label>
          <input type="email" class="form-control" name="email" id="email" required>
        </div>

        <div class="mb-4">
          <label class="form-label">Your Rating</label>
          <div class="star-rating">
            <input type="radio" id="star5" name="rating" value="5" required><label for="star5">★</label>
            <input type="radio" id="star4" name="rating" value="4"><label for="star4">★</label>
            <input type="radio" id="star3" name="rating" value="3"><label for="star3">★</label>
            <input type="radio" id="star2" name="rating" value="2"><label for="star2">★</label>
            <input type="radio" id="star1" name="rating" value="1"><label for="star1">★</label>
          </div>
        </div>

        <div class="mb-4">
          <label for="comment" class="form-label">Your Feedback</label>
          <textarea class="form-control" name="comment" id="comment" rows="5" required></textarea>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-primary btn-lg">Submit Feedback</button>
        </div>
      </form>
    </div>
  </div>
  
  <!-- Bootstrap JS (optional) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <?php
  $host = "localhost";
  $user = "root";
  $pass = "";
  $db = "velvet";

  $conn = new mysqli($host, $user, $pass, $db);

  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
  ?>

</body>
</html>
