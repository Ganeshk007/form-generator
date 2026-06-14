<?php

namespace Ganesh\FormGenerator\Controllers;

use Ganesh\FormGenerator\Models\FormTemplate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ganesh\FormGenerator\Models\FormTemplateField;
use Illuminate\Support\Str;

class FormTemplateController extends Controller
{
    public function index()
    {
        $templates = FormTemplate::with('fields')->get();
        return view('form-generator::form-template.index', compact('templates'));
    }

    public function create()
    {
        return view('form-generator::form-template.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'template_name' => 'required|string',
            'template_stub' => 'nullable|string',
            'fields' => 'required|array',
            'fields.*.type' => 'required|string',
            'fields.*.stub' => 'required|string',
        ]);

        // Save Form Template
        $template = FormTemplate::create([
            'name' => $data['template_name'],
            'slug' => Str::slug($data['template_name']),
            'stub' => $data['template_stub'] ?? ''
        ]);

        // Save Form Fields
        foreach ($data['fields'] as $field) {
            FormTemplateField::create([
                'form_template_id' => $template->id,
                'type' => $field['type'],
                'name' => $field['type'],
                'stub' => $field['stub']
            ]);
        }

        return response()->json(['message' => 'Template saved successfully']);
    }

    public function edit($id)
    {
        $template = FormTemplate::with('fields')->findOrFail($id);
        $fieldNames = config('form-generator.input_types');

        return view('form-generator::form-template.edit', compact('template', 'fieldNames'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'template_name' => 'required|string',
            'template_stub' => 'required|string',
            'fields' => 'required|array',
            'fields.*.type' => 'required|string',
            'fields.*.stub' => 'required|string'
        ]);

        $template = FormTemplate::findOrFail($id);
        $template->update([
            'name' => $request->template_name,
            'slug' => Str::slug($request->template_name),
            'stub' => $request->template_stub ?? '',
        ]);

        FormTemplateField::where('form_template_id', $id)->delete();
        foreach ($request->fields as $field) {
            FormTemplateField::create([
                'form_template_id' => $id,
                'type' => $field['type'],
                'name' => $field['type'],
                'stub' => $field['stub']
            ]);
        }

        return response()->json(['message' => 'Template updated successfully']);
    }

    public function destroy($id)
    {
        FormTemplate::findOrFail($id)->delete();
        FormTemplateField::where('form_template_id', $id)->delete();
        return redirect()->route('form.templates')->with('success', 'Template deleted!');
    }
}
