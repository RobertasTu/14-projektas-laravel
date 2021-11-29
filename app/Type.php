<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    public function typeArticles() {
        return $this->hasMany(Article::class, 'type_id', 'id');
    }
}
