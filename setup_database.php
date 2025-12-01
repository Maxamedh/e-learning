<?php
/**
 * E-LOOX Academy Database Setup Script
 * Run this file directly: php setup_database.php
 * Or access via browser: http://localhost/e-learning/setup_database.php
 */

// Check if running from command line or web
$isCli = php_sapi_name() === 'cli';

if (!$isCli) {
    // Web interface
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Database Setup - E-LOOX Academy</title>
        <style>
            body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
            .success { color: green; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; margin: 10px 0; }
            .error { color: red; padding: 10px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px; margin: 10px 0; }
            .info { color: #0c5460; padding: 10px; background: #d1ecf1; border: 1px solid #bee5eb; border-radius: 5px; margin: 10px 0; }
            button { padding: 10px 20px; background: #1E3A8A; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
            button:hover { background: #3B82F6; }
            pre { background: #f4f4f4; padding: 15px; border-radius: 5px; overflow-x: auto; }
        </style>
    </head>
    <body>
        <h1>E-LOOX Academy - Database Setup</h1>
    <?php
}

// Database configuration
$config = [
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'e-learning',
    'charset' => 'utf8mb4'
];

// Try to connect
try {
    $conn = new mysqli($config['hostname'], $config['username'], $config['password']);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    if (!$isCli) {
        echo "<div class='success'>✓ Connected to MySQL server</div>";
    } else {
        echo "✓ Connected to MySQL server\n";
    }
    
    // Create database if not exists
    $sql = "CREATE DATABASE IF NOT EXISTS `{$config['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
    if ($conn->query($sql)) {
        if (!$isCli) {
            echo "<div class='success'>✓ Database '{$config['database']}' created or already exists</div>";
        } else {
            echo "✓ Database '{$config['database']}' created or already exists\n";
        }
    } else {
        throw new Exception("Error creating database: " . $conn->error);
    }
    
    // Select database
    $conn->select_db($config['database']);
    
    // Read and execute SQL file
    $sqlFile = __DIR__ . '/database_schema.sql';
    
    if (!file_exists($sqlFile)) {
        throw new Exception("SQL file not found: $sqlFile");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Remove comments and split by semicolon
    $sql = preg_replace('/--.*$/m', '', $sql);
    $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
    
    // Split into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    $successCount = 0;
    $errorCount = 0;
    $errors = [];
    
    foreach ($statements as $statement) {
        if (empty($statement) || strlen(trim($statement)) < 10) {
            continue;
        }
        
        // Skip IF NOT EXISTS for some statements
        $statement = trim($statement);
        
        if ($conn->query($statement)) {
            $successCount++;
            // Extract table name for feedback
            if (preg_match('/CREATE TABLE.*?`?(\w+)`?/i', $statement, $matches)) {
                $tableName = $matches[1];
                if (!$isCli) {
                    echo "<div class='info'>✓ Created table: $tableName</div>";
                } else {
                    echo "✓ Created table: $tableName\n";
                }
            }
        } else {
            $errorCount++;
            $errorMsg = $conn->error;
            // Ignore "already exists" errors
            if (strpos($errorMsg, 'already exists') === false && strpos($errorMsg, 'Duplicate') === false) {
                $errors[] = $errorMsg;
                if (!$isCli) {
                    echo "<div class='error'>✗ Error: $errorMsg</div>";
                } else {
                    echo "✗ Error: $errorMsg\n";
                }
            }
        }
    }
    
    // Create admin user
    $adminEmail = 'admin@elooxacademy.com';
    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
    
    // Check if admin exists
    $checkAdmin = $conn->query("SELECT id FROM users WHERE email = '$adminEmail'");
    
    if ($checkAdmin && $checkAdmin->num_rows == 0) {
        $uuid = bin2hex(random_bytes(16));
        $uuid = substr($uuid, 0, 8) . '-' . substr($uuid, 8, 4) . '-' . substr($uuid, 12, 4) . '-' . substr($uuid, 16, 4) . '-' . substr($uuid, 20, 12);
        
        $insertAdmin = "INSERT INTO users (uuid, email, password_hash, role, first_name, last_name, is_active, email_verified) 
                        VALUES ('$uuid', '$adminEmail', '$adminPassword', 'admin', 'Admin', 'User', TRUE, TRUE)";
        
        if ($conn->query($insertAdmin)) {
            if (!$isCli) {
                echo "<div class='success'>✓ Admin user created successfully!</div>";
                echo "<div class='info'><strong>Admin Credentials:</strong><br>";
                echo "Email: <strong>$adminEmail</strong><br>";
                echo "Password: <strong>admin123</strong></div>";
            } else {
                echo "✓ Admin user created successfully!\n";
                echo "Admin Email: $adminEmail\n";
                echo "Admin Password: admin123\n";
            }
        } else {
            if (!$isCli) {
                echo "<div class='error'>✗ Error creating admin user: " . $conn->error . "</div>";
            } else {
                echo "✗ Error creating admin user: " . $conn->error . "\n";
            }
        }
    } else {
        if (!$isCli) {
            echo "<div class='info'>ℹ Admin user already exists</div>";
        } else {
            echo "ℹ Admin user already exists\n";
        }
    }
    
    if (!$isCli) {
        echo "<div class='success'><h2>✓ Database setup completed!</h2></div>";
        echo "<p><a href='" . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/admin/login'><button>Go to Admin Login</button></a></p>";
    } else {
        echo "\n✓ Database setup completed!\n";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    if (!$isCli) {
        echo "<div class='error'><strong>Error:</strong> " . $e->getMessage() . "</div>";
        echo "<p>Please check your database configuration in this file.</p>";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
    exit(1);
}

if (!$isCli) {
    ?>
    </body>
    </html>
    <?php
}

