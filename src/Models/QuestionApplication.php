<?php

namespace SlimKit\PlusQuestion\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionApplication extends Model
{
    protected $table = 'question_application';

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
