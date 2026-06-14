<?php

namespace Ganesh\FormGenerator\Controllers;

use Ganesh\FormGenerator\Models\FormTemplate;
use Ganesh\FormGenerator\Models\FormTemplateField;
use Illuminate\Routing\Controller;

class FormGeneratorController extends Controller
{
    public function index()
    {
        $templates = FormTemplate::all();
        return view('form-generator::form-builder', compact('templates'));
    }

    public function getFields($templateId)
    {
        $fields = FormTemplateField::where('form_template_id', $templateId)
            ->select('type', 'name', 'stub')
            ->get();

        return response()->json($fields);
    }
}
