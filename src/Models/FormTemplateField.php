<?php

namespace Ganesh\FormGenerator\Models;

use Ganesh\FormGenerator\Models\FormTemplate;
use Illuminate\Database\Eloquent\Model;

class FormTemplateField extends Model
{
    protected $table = 'form_template_fields';

    protected $fillable = ['form_template_id', 'type', 'name', 'stub'];

    public function template()
    {
        return $this->belongsTo(FormTemplate::class);
    }
}
