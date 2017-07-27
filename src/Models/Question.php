<?php

namespace SlimKit\PlusQuestion\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /**
     * Has topics for the question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'question_topic')
            ->using(QuestionTopic::class);
    }
}
