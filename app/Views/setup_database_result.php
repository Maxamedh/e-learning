<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Database Setup - E-LOOX Academy</title>
    <link href="<?=base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .success { 
            color: #155724; 
            padding: 15px; 
            background: #d4edda; 
            border: 1px solid #c3e6cb; 
            border-radius: 5px; 
            margin: 10px 0; 
        }
        .error { 
            color: #721c24; 
            padding: 15px; 
            background: #f8d7da; 
            border: 1px solid #f5c6cb; 
            border-radius: 5px; 
            margin: 10px 0; 
        }
        .info { 
            color: #0c5460; 
            padding: 15px; 
            background: #d1ecf1; 
            border: 1px solid #bee5eb; 
            border-radius: 5px; 
            margin: 10px 0; 
        }
        .btn-primary {
            background-color: #1E3A8A;
            border-color: #1E3A8A;
            padding: 12px 30px;
            font-size: 16px;
            margin-top: 20px;
        }
        .btn-primary:hover {
            background-color: #3B82F6;
            border-color: #3B82F6;
        }
        ul {
            list-style: none;
            padding-left: 0;
        }
        ul li:before {
            content: "✓ ";
            color: green;
            font-weight: bold;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 style="color: #1E3A8A;">E-LOOX Academy - Database Setup</h1>
        
        <?php if (isset($error)): ?>
            <div class="error">
                <h4>✗ Setup Failed</h4>
                <p><strong>Error:</strong> <?= esc($error) ?></p>
                <?php if (isset($hostname)): ?>
                    <p><strong>Hostname:</strong> <?= esc($hostname) ?></p>
                    <p><strong>Username:</strong> <?= esc($username ?? 'root') ?></p>
                    <p><strong>Database:</strong> <?= esc($database ?? 'e-learning') ?></p>
                <?php endif; ?>
                <p><strong>Solution:</strong> Make sure MySQL is running and check your database configuration in <code>app/Config/Database.php</code></p>
            </div>
        <?php else: ?>
            <div class="success">
                <h4>✓ Database Setup Completed Successfully!</h4>
                <p><strong>Database:</strong> <?= esc($database ?? 'e-learning') ?></p>
            </div>
            
            <?php if (!empty($tables_created)): ?>
                <div class="info">
                    <h5>Tables Created:</h5>
                    <ul>
                        <?php foreach ($tables_created as $table): ?>
                            <li><?= esc($table) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if ($admin_created ?? false): ?>
                <div class="success">
                    <h5>✓ Admin User Created!</h5>
                    <p><strong>Email:</strong> <?= esc($admin_email) ?></p>
                    <p><strong>Password:</strong> <?= esc($admin_password) ?></p>
                    <p class="text-muted"><small>⚠️ Please change the password after first login!</small></p>
                </div>
            <?php else: ?>
                <div class="info">
                    <p>ℹ Admin user already exists or could not be created.</p>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($errors)): ?>
                <div class="error">
                    <h5>Errors Encountered:</h5>
                    <ul>
                        <?php foreach ($errors as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div class="text-center">
                <a href="<?= base_url('admin/login') ?>" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Go to Admin Login
                </a>
                <a href="<?= base_url('admin/setup') ?>" class="btn btn-outline-primary" style="margin-left: 10px;">
                    <i class="fas fa-user-plus"></i> Create Another Admin
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

