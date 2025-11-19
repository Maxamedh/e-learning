<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;
use App\Models\QuizModel;
use App\Models\QuestionModel;
use App\Models\QuestionOptionModel;

class QuizApiController extends BaseApiController
{
    protected $quizModel;
    protected $questionModel;
    protected $optionModel;

    public function __construct()
    {
        $this->quizModel = new QuizModel();
        $this->questionModel = new \App\Models\QuestionModel();
        $this->optionModel = new \App\Models\QuestionOptionModel();
    }

    public function index()
    {
        $this->authenticate();
        $lecture_id = $this->request->getGet('lecture_id');
        if (!$lecture_id) return $this->fail('lecture_id required', 400);

        $quizzes = $this->quizModel->where('lecture_id', $lecture_id)->findAll();
        return $this->respond(['status' => 'success', 'data' => $quizzes]);
    }

    public function show($id = null)
    {
        $this->authenticate();
        $quiz = $this->quizModel->find($id);
        if (!$quiz) return $this->failNotFound('Quiz not found');

        // Get questions with options
        $questions = $this->questionModel->where('quiz_id', $id)->orderBy('sort_order', 'ASC')->findAll();
        foreach ($questions as &$question) {
            $question['options'] = $this->optionModel->where('question_id', $question['question_id'])->orderBy('sort_order', 'ASC')->findAll();
        }
        $quiz['questions'] = $questions;

        return $this->respond(['status' => 'success', 'data' => $quiz]);
    }

    public function create()
    {
        $this->authenticate();
        if (!$this->requirePermission(['instructor', 'admin'])) return;

        $data = $this->request->getJSON(true);
        helper('uuid');
        $data['quiz_id'] = generate_uuid();

        if ($this->quizModel->insert($data)) {
            // Create questions if provided
            if (isset($data['questions']) && is_array($data['questions'])) {
                foreach ($data['questions'] as $questionData) {
                    $questionData['quiz_id'] = $data['quiz_id'];
                    $questionData['question_id'] = generate_uuid();
                    $this->questionModel->insert($questionData);

                    // Create options
                    if (isset($questionData['options']) && is_array($questionData['options'])) {
                        foreach ($questionData['options'] as $optionData) {
                            $optionData['question_id'] = $questionData['question_id'];
                            $this->optionModel->insert($optionData);
                        }
                    }
                }
            }

            return $this->respondCreated(['status' => 'success', 'message' => 'Quiz created', 'data' => $this->quizModel->find($data['quiz_id'])]);
        }
        return $this->failValidationErrors($this->quizModel->errors());
    }

    public function update($id = null)
    {
        $this->authenticate();
        if (!$this->requirePermission(['instructor', 'admin'])) return;

        $data = $this->request->getJSON(true);
        if ($this->quizModel->update($id, $data)) {
            return $this->respond(['status' => 'success', 'message' => 'Quiz updated', 'data' => $this->quizModel->find($id)]);
        }
        return $this->failValidationErrors($this->quizModel->errors());
    }

    public function delete($id = null)
    {
        $this->authenticate();
        if (!$this->requirePermission(['instructor', 'admin'])) return;

        if ($this->quizModel->delete($id)) {
            return $this->respondDeleted(['status' => 'success', 'message' => 'Quiz deleted']);
        }
        return $this->fail('Failed to delete quiz');
    }
}

