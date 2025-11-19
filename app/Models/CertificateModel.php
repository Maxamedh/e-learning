<?php

namespace App\Models;

use CodeIgniter\Model;

class CertificateModel extends Model
{
    protected $table            = 'certificates';
    protected $primaryKey       = 'certificate_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'certificate_id', 'enrollment_id', 'user_id', 'course_id', 
        'certificate_url', 'certificate_number', 'issue_date', 
        'expiry_date', 'verification_hash'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'issue_date' => 'datetime',
        'expiry_date' => 'datetime',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';

    protected $validationRules = [
        'enrollment_id' => 'required',
        'user_id' => 'required',
        'course_id' => 'required',
    ];

    protected $skipValidation = false;
}
