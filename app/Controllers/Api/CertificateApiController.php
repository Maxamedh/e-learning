<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;
use App\Models\CertificateModel;
use App\Models\EnrollmentModel;

class CertificateApiController extends BaseApiController
{
    protected $certificateModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->certificateModel = new CertificateModel();
        $this->enrollmentModel = new EnrollmentModel();
    }

    public function index()
    {
        $this->authenticate();
        $user_id = $this->request->getGet('user_id') ?? $this->currentUser['user_id'];
        
        if ($user_id !== $this->currentUser['user_id'] && !$this->hasPermission('admin')) {
            return $this->failForbidden('Access denied');
        }

        $certificates = $this->certificateModel->select('certificates.*, courses.title as course_title')
            ->join('courses', 'courses.course_id = certificates.course_id')
            ->where('certificates.user_id', $user_id)
            ->orderBy('certificates.issue_date', 'DESC')
            ->findAll();

        return $this->respond(['status' => 'success', 'data' => $certificates]);
    }

    public function show($id = null)
    {
        $this->authenticate();
        $certificate = $this->certificateModel->select('certificates.*, courses.title as course_title, users.first_name, users.last_name')
            ->join('courses', 'courses.course_id = certificates.course_id')
            ->join('users', 'users.user_id = certificates.user_id')
            ->find($id);
        if (!$certificate) return $this->failNotFound('Certificate not found');
        return $this->respond(['status' => 'success', 'data' => $certificate]);
    }

    public function create()
    {
        $this->authenticate();
        if (!$this->requirePermission(['admin'])) return;

        $data = $this->request->getJSON(true);
        
        // Verify enrollment is completed
        $enrollment = $this->enrollmentModel->where('enrollment_id', $data['enrollment_id'])
            ->where('completion_status', 'completed')
            ->first();
        
        if (!$enrollment) {
            return $this->fail('Enrollment not found or not completed', 400);
        }

        helper('uuid');
        $data['certificate_id'] = generate_uuid();
        $data['certificate_number'] = 'CERT-' . strtoupper(substr(generate_uuid(), 0, 8));
        $data['verification_hash'] = hash('sha256', $data['certificate_id'] . time());

        if ($this->certificateModel->insert($data)) {
            // Update enrollment
            $this->enrollmentModel->update($data['enrollment_id'], [
                'certificate_issued' => true,
                'certificate_issued_at' => date('Y-m-d H:i:s')
            ]);

            return $this->respondCreated(['status' => 'success', 'message' => 'Certificate created', 'data' => $this->certificateModel->find($data['certificate_id'])]);
        }
        return $this->failValidationErrors($this->certificateModel->errors());
    }
}

