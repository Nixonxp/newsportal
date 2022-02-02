<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    public function getDescription()
    {
        return new HtmlString($this->description);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
