<?php
/**
 * Simple Login Test
 * Access: http://localhost/e-learning/test_login_simple.php
 */

// Start session
session_start();

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Connect to database
    $conn = new mysqli('localhost', 'root', '', 'e-learning');
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Get user
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user && password_verify($password, $user['password_hash'])) {
        // Set session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name']
        ];
        
        // Redirect
        header('Location: http://localhost/e-learning/admin/dashboard');
        exit;
    } else {
        $error = 'Invalid email or password';
    }
    
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Simple Login Test</title>
    <style>
        body { font-family: Arial; max-width: 400px; margin: 50px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; box-sizing: border-box; }
        button { background: #1E3A8A; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>Simple Login Test</h2>
    <?php if (isset($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" value="admin@elooxacademy.com" required>
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" value="admin123" required>
        </div>
        <button type="submit">Login</button>
    </form>
    <p><a href="admin/login">Go to Admin Login</a></p>
</body>
</html>

