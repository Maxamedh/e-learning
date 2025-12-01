<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class SetupDatabase extends BaseController
{
    public function index()
    {
        // Security: Only allow in development
        if (ENVIRONMENT === 'production') {
            return $this->response->setJSON([
                'error' => 'Database setup is disabled in production'
            ])->setStatusCode(403);
        }

        // Get database configuration
        $dbConfig = config('Database');
        $defaultGroup = $dbConfig->defaultGroup;
        $defaultConfig = $dbConfig->{$defaultGroup};
        
        $dbName = $defaultConfig['database'] ?? 'e-learning';
        $hostname = $defaultConfig['hostname'] ?? 'localhost';
        $username = $defaultConfig['username'] ?? 'root';
        $password = $defaultConfig['password'] ?? '';
        
        $results = [
            'database' => $dbName,
            'tables_created' => [],
            'errors' => [],
            'admin_created' => false
        ];

        try {
            // Step 1: Connect to MySQL server (without selecting database)
            $mysqli = new \mysqli($hostname, $username, $password);
            
            if ($mysqli->connect_error) {
                throw new \Exception("Connection failed: " . $mysqli->connect_error);
            }
            
            // Step 2: Create database if not exists
            $createDbSql = "CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
            if (!$mysqli->query($createDbSql)) {
                throw new \Exception("Error creating database: " . $mysqli->error);
            }
            
            // Step 3: Select the database
            $mysqli->select_db($dbName);
            
            // Step 4: Read SQL file
            $sqlFile = ROOTPATH . 'database_schema.sql';
            
            if (!file_exists($sqlFile)) {
                throw new \Exception("SQL file not found: $sqlFile");
            }

            $sql = file_get_contents($sqlFile);
            
            // Remove comments
            $sql = preg_replace('/--.*$/m', '', $sql);
            $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
            
            // Split into statements
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (empty($statement) || strlen($statement) < 10) {
                    continue;
                }
                
                // Skip CREATE DATABASE statements
                if (stripos($statement, 'CREATE DATABASE') !== false) {
                    continue;
                }
                
                try {
                    if ($mysqli->query($statement)) {
                        // Extract table name
                        if (preg_match('/CREATE TABLE.*?IF NOT EXISTS.*?`?(\w+)`?/i', $statement, $matches)) {
                            $results['tables_created'][] = $matches[1];
                        } elseif (preg_match('/CREATE TABLE.*?`?(\w+)`?/i', $statement, $matches)) {
                            $results['tables_created'][] = $matches[1];
                        }
                    } else {
                        // Ignore "already exists" errors
                        if (strpos($mysqli->error, 'already exists') === false && 
                            strpos($mysqli->error, 'Duplicate') === false) {
                            $results['errors'][] = $mysqli->error . " (Statement: " . substr($statement, 0, 50) . "...)";
                        }
                    }
                } catch (\Exception $e) {
                    // Ignore "already exists" errors
                    if (strpos($e->getMessage(), 'already exists') === false) {
                        $results['errors'][] = $e->getMessage();
                    }
                }
            }
            
            // Step 5: Create admin user using direct query
            $checkAdmin = $mysqli->query("SELECT id FROM users WHERE email = 'admin@elooxacademy.com'");
            
            if (!$checkAdmin || $checkAdmin->num_rows == 0) {
                helper('uuid');
                $uuid = generate_uuid();
                $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
                
                $insertAdmin = "INSERT INTO users (uuid, email, password_hash, role, first_name, last_name, is_active, email_verified) 
                                VALUES ('$uuid', 'admin@elooxacademy.com', '$adminPassword', 'admin', 'Admin', 'User', TRUE, TRUE)";
                
                if ($mysqli->query($insertAdmin)) {
                    $results['admin_created'] = true;
                    $results['admin_email'] = 'admin@elooxacademy.com';
                    $results['admin_password'] = 'admin123';
                } else {
                    $results['errors'][] = "Failed to create admin user: " . $mysqli->error;
                }
            } else {
                $results['admin_exists'] = true;
            }
            
            $mysqli->close();
            
            return view('setup_database_result', $results);
            
        } catch (\Exception $e) {
            return view('setup_database_result', [
                'error' => $e->getMessage(),
                'database' => $dbName,
                'hostname' => $hostname,
                'username' => $username
            ]);
        }
    }
}

