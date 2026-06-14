<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Form Template</title>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }

        .container {
            background: #fff;
            padding: 20px;
            max-width: 90%;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }

        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            height: 60px;
            width: 99%;
        }

        .fields-container {
            margin-top: 20px;
        }

        .field-group {
            background: #f9f9f9;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .remove-btn {
            background: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
            display: inline-block;
            margin-top: 5px;
        }

        .remove-btn:hover {
            background: darkred;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            border: none;
            color: white;
            background: #007BFF;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
            text-align: center;
        }

        .btn:hover {
            background: #0056b3;
        }

        .btn.add-field {
            background: #28a745;
        }

        .btn.add-field:hover {
            background: #218838;
        }

        .error-message {
            color: red;
            font-size: 12px;
        }

        .back-btn {
            background: #6c757d;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            float: inline-end;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2 class="mb-0">Create Form Template
            <button type="button" onclick="window.location='{{ route('form-generator.template') }}'" class="back-btn">
                Back
            </button>
        </h2>


        <!-- Form Template Name -->
        <label for="template_name">Template Name:</label>
        <input type="text" id="template_name" name="template_name" required>

        <!-- Stub for the template -->
        <label for="template_stub">Template Stub:</label>
        <textarea id="template_stub" name="template_stub" rows="4"></textarea>

        <h3>Fields</h3>
        <p class="error-message" id="duplicate-error" style="display: none;">Duplicate field name is not allowed!</p>

        <!-- Fields Container -->
        <div id="fields-container" class="fields-container"></div>

        <!-- Add Field Button -->
        <button type="button" class="btn add-field" onclick="addField()">Add Field</button>

        <br><br>

        <!-- Save Button -->
        <button type="button" class="btn" onclick="saveTemplate()">Save Template</button>
    </div>

    <script>
        const fieldNames = @json(config('form-generator.input_types'));

        function addField() {
            let options = fieldNames.map(field => `<option value="${field.type}">${field.name}</option>`).join('');

            $('#fields-container').append(`
                <div class="field-group">
                    <label>Field Name:</label>
                    <select class="field-name" onchange="checkDuplicate(this)">${options}</select>

                    <label>Field Stub:</label>
                    <textarea class="field-stub" placeholder="Enter stub"></textarea>

                    <button type="button" class="remove-btn" onclick="removeField(this)">Remove</button>
                </div>
            `);
        }

        function removeField(button) {
            $(button).parent().remove();
            $("#duplicate-error").hide();
        }

        function checkDuplicate(selectElement) {
            let selectedName = $(selectElement).find("option:selected").text();
            let selectedNames = [];

            $(".field-name option:selected").each(function () {
                selectedNames.push($(this).text());
            });

            let duplicates = selectedNames.filter(name => name === selectedName).length;
            if (duplicates > 1) {
                $(selectElement).val('');
                $("#duplicate-error").show();
            } else {
                $("#duplicate-error").hide();
            }
        }

        function saveTemplate() {
            let templateName = $('#template_name').val();
            let templateStub = $('#template_stub').val();
            let fields = [];

            $('.field-group').each(function () {
                let fieldType = $(this).find('.field-name').val();
                let fieldStub = $(this).find('.field-stub').val();
                if (fieldType && fieldStub) {
                    fields.push({
                        type: fieldType,
                        stub: fieldStub
                    });
                }
            });

            if (!templateName || fields.length === 0) {
                alert("Template name and at least one field are required.");
                return;
            }

            let formData = {
                template_name: templateName,
                template_stub: templateStub,
                fields: fields
            };

            $.ajax({
                url: "{{ route('form-generator.template.store') }}",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify(formData),
                success: function (response) {
                    alert("Template saved successfully!");
                    location.reload();
                },
                error: function (err) {
                    console.error("Error saving template:", err);
                    alert("Error saving template.");
                }
            });
        }
    </script>

</body>

</html>