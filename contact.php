<?php
include 'config.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $msg = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO velvet_db.contacts (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $msg);
    
    if ($stmt->execute()) {
        $message = "Thank you for your message! We'll get back to you soon.";
    } else {
        $message = "There was an error submitting your message. Please try again.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us | Velvet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --soft-white: #f9f9f9;
      --light-gray: #e8e8e8;
      --medium-gray: #d1d1d1;
      --text-gray: #555555;
      --accent-color: #8a7f8d; /* Soft muted purple for accents */
    }
    
    body {
      background-color: var(--soft-white);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: var(--text-gray);
    }
    
    .contact-container {
      max-width: 650px;
      margin: 40px auto;
      background: white;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      border: 1px solid var(--light-gray);
    }
    
    h2 {
      color: var(--accent-color);
      text-align: center;
      margin-bottom: 30px;
      font-weight: 600;
      letter-spacing: 0.5px;
    }
    
    .form-control {
      background-color: var(--soft-white);
      border: 1px solid var(--medium-gray);
      border-radius: 8px;
      padding: 12px 15px;
      color: var(--text-gray);
    }
    
    .form-control:focus {
      box-shadow: 0 0 0 0.25rem rgba(138, 127, 141, 0.25);
      border-color: var(--accent-color);
    }
    
    .btn-primary {
      background-color: var(--accent-color);
      border: none;
      padding: 12px;
      border-radius: 8px;
      font-weight: 500;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
      background-color: #7a6f7d;
      transform: translateY(-2px);
    }
    
    .form-label {
      font-weight: 500;
      margin-bottom: 8px;
      color: var(--text-gray);
    }
    
    .alert {
      border-radius: 8px;
      text-align: center;
    }
    
    .contact-icon {
      text-align: center;
      margin-bottom: 20px;
      color: var(--accent-color);
      font-size: 2.5rem;
    }
  </style>
</head>
<body>

<div class="container contact-container">
  <div class="contact-icon">
    <i class="fas fa-envelope-open-text"></i>
  </div>
  <h2>Get In Touch</h2>

  <?php if ($message): ?>
    <div class="alert <?= strpos($message, 'error') !== false ? 'alert-danger' : 'alert-success' ?>">
      <?= $message ?>
    </div>
  <?php endif; ?>

  <form method="post" novalidate>
    <div class="mb-4">
      <label for="name" class="form-label">Full Name</label>
      <input type="text" name="name" id="name" required class="form-control" placeholder="Enter your name">
      <div class="form-text">We'd love to know who you are</div>
    </div>

    <div class="mb-4">
      <label for="email" class="form-label">Email Address</label>
      <input type="email" name="email" id="email" required class="form-control" placeholder="your.email@example.com">
      <div class="form-text">We'll never share your email</div>
    </div>

    <div class="mb-4">
      <label for="message" class="form-label">Your Message</label>
      <textarea name="message" id="message" rows="5" required class="form-control" placeholder="How can we help you?"></textarea>
      <div class="form-text">Tell us what's on your mind</div>
    </div>

    <div class="d-grid mt-4">
      <button type="submit" class="btn btn-primary btn-lg">
        <i class="fas fa-paper-plane me-2"></i> Send Message
      </button>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

