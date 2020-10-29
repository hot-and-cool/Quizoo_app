<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'quizzes';

    public function answer()
    {
        return $this->hasOne(Answer::class, 'id', 'answer_id');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function getInRandomOrder($categoryArray)
    {   
        return $this->with([
                'answer',
                'category:id,name'
            ])
            ->whereIn('quizzes.category_id', $categoryArray)
            ->inRandomOrder()
            ->limit(config('const.quizzes.PER_PAGE'))
            ->get();
    }
}
