<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    private $quiz;

    public function __construct(Quiz $quiz)
    {
        $this->quiz = $quiz;
    }
    public function index(Request $request)
    {
        $category = $request->input('categories');
        if (empty($category)) {
            return [];
        }
        $quizzes = $this->quiz->getInRandomOrder(explode(',', $category));

        return $quizzes;

    }
}
