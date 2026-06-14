<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Generator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
</head>

<body>
    <div class="container mt-4">
        <h3>Form Generator</h3>

        <div class="mb-3">
            <label for="template_select" class="form-label">
                Select Form Template
            </label>

            <div class="d-flex">
                <select id="template_select" class="form-control me-2">
                    <option value="">-- Select Template --</option>

                    @foreach($templates as $template)
                        <option value="{{ $template->id }}">
                            {{ $template->name }}
                        </option>
                    @endforeach
                </select>

                <div class="d-flex gap-2">
                    <a href="{{ route('form-generator.template.create') }}" class="btn btn-success">
                        + Add Template
                    </a>

                    <a href="{{ route('form-generator.template') }}" class="btn btn-secondary">
                        Manage Templates
                    </a>
                </div>
            </div>
        </div>

        <form id="form-builder">
            <div id="form-fields"></div>

            <button type="button" class="btn btn-success mt-2" onclick="addField()">+ Add Field</button>
            <button type="button" class="btn btn-primary mt-2" onclick="previewForm()">Live Preview</button>
            <button type="button" class="btn btn-info mt-2" onclick="showGeneratedCode()">Show Code</button>
        </form>
    </div>

    <!-- Preview Modal -->
    @include('form-generator::preview')

    <!-- Show Code Modal -->
    @include('form-generator::show-code')

    <script>
        let availableFields = [];
        let templateStub = '';

        const templates = @json(
            $templates->mapWithKeys(function ($template) {
                return [
                    $template->id => [
                        'stub' => $template->stub,
                    ]
                ];
            })
        );

        $('#template_select').change(function () {

            let templateId = $(this).val();

            templateStub = templates[templateId]
                ? templates[templateId].stub
                : '';

            if (templateId) {
                $.get(`{{ url('form-fields') }}/${templateId}`, function (data) {
                    availableFields = data;
                });
            } else {
                availableFields = [];
            }
        });

        function addField() {
            if (availableFields.length === 0) {
                alert('Select a template to add fields.');
                return;
            }

            let fieldOptions = availableFields.map(field =>
                `<option value="${field.type}" data-stub='${field.stub}'>${field.type} - ${field.name}</option>`
            ).join('');

            $('#form-fields').append(`
            <div class="mb-3 field-group d-flex align-items-center">
                <select class="form-control field-type me-2" required onchange="updateStub(this)">
                    <option value="">Select Field Type</option>
                    ${fieldOptions}
                </select>
                <input type="text" class="form-control field-name me-2" placeholder="Field Name" required>
                <input type="text" class="form-control field-id me-2" placeholder="Field ID">
                <input type="text" class="form-control field-class me-2" placeholder="CSS Class">
                <div class="form-check me-2">
                    <input class="form-check-input field-required" type="checkbox">
                    <label class="form-check-label">
                        Required
                    </label>
                </div>
                <button type="button" class="btn btn-danger" onclick="removeField(this)">Remove</button>
            </div>
        `);
        }

        function updateStub(selectBox) {
            let selectedOption = $(selectBox).find(":selected");
            let stubTemplate = selectedOption.data('stub') || '';

            // Store stub template for later preview use
            $(selectBox).closest('.field-group').data('stub', stubTemplate);
        }

        function removeField(btn) {
            $(btn).parent().remove();
        }

        function previewForm() {
            let fields = [];
            $('.field-group').each(function () {
                let stubTemplate = $(this).data('stub') || '';
                let name = $(this).find('.field-name').val();
                let id = $(this).find('.field-id').val();
                let className = $(this).find('.field-class').val();
                let isRequired = $(this).find('.field-required').is(':checked');

                // Replace placeholders in the stub
                let fieldHtml = stubTemplate
                    .replace(/@{{\s*\$name\s*}}/g, name)
                    .replace(/@{{\s*\$id\s*}}/g, id)
                    .replace(/@{{\s*\$class\s*}}/g, className)
                    .replace(/@{{\s*\$required\s*}}/g, isRequired ? 'required' : '');

                fields.push(fieldHtml);
            });

            // $('#preview-body').html(fields.join("<br>"));
            let generatedForm = templateStub;
            if (generatedForm) {
                generatedForm = generatedForm.replace('[[fields]]', fields.join("\n"));
            } else {
                generatedForm = fields.join("<br>");
            }
            $('#preview-body').html(generatedForm);
            $('#previewModal').modal('show');
        }

        function showGeneratedCode() {
            let fields = [];
            $('.field-group').each(function () {
                let stubTemplate = $(this).data('stub') || '';
                let name = $(this).find('.field-name').val();
                let id = $(this).find('.field-id').val();
                let className = $(this).find('.field-class').val();
                let isRequired = $(this).find('.field-required').is(':checked');

                let fieldHtml = stubTemplate
                    .replace(/@{{\s*\$name\s*}}/g, name)
                    .replace(/@{{\s*\$id\s*}}/g, id)
                    .replace(/@{{\s*\$class\s*}}/g, className)
                    .replace(/@{{\s*\$required\s*}}/g, isRequired ? 'required' : '');

                fields.push(fieldHtml);
            });

            // let generatedCode = `<form>\n${fields.join("\n")}\n</form>`;
            let generatedCode = templateStub;
            if (generatedCode) {
                generatedCode = generatedCode.replace(
                    '[[fields]]',
                    fields.join("\n")
                );
            } else {
                generatedCode = `<form>\n${fields.join("\n")}\n</form>`;
            }

            $("#generatedCodeBlock").text(generatedCode);
            $("#codeModal").modal("show");
        }

        function copyGeneratedCode() {
            let codeText = document.getElementById("generatedCodeBlock").innerText;
            navigator.clipboard.writeText(codeText).then(() => {
                alert("Copied to clipboard!");
            }).catch(err => {
                console.error("Error copying text: ", err);
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>