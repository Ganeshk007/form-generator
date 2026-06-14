<?php

namespace Ganesh\FormGenerator\Models;

use Illuminate\Database\Eloquent\Model;

class FormTemplate extends Model
{
    protected $fillable = ['name', 'slug', 'stub'];

    public function fields()
    {
        return $this->hasMany(FormTemplateField::class);
    }
}
