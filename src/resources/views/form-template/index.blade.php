<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Templates</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }

        .container {
            background: #fff;
            padding: 20px;
            max-width: 700px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #007BFF;
            color: white;
        }

        .btn {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            text-align: center;
        }

        .btn.edit {
            background: #28a745;
            color: white;
        }

        .btn.delete {
            background: red;
            color: white;
        }

        .btn.add {
            background: #007BFF;
            color: white;
            text-decoration: none;
            margin-right: 10px;
        }

        .btn.generate {
            background: #6f42c1;
            color: white;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Manage Form Templates
            <div style="float: right; font-size: medium;">
                <a href="{{ route('form-generator.template.create') }}" class="btn add">
                    + Add Template
                </a>

                <a href="{{ route('form-builder') }}" class="btn generate">
                    Generate Form
                </a>
            </div>
        </h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Fields</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($templates as $template)
                    <tr>
                        <td>{{ $template->name }}</td>
                        <td>{{ count($template->fields) }} fields</td>
                        <td>
                            <a href="{{ route('form-generator.template.edit', $template->id) }}" class="btn edit">Edit</a>
                            <form action="{{ route('form-generator.template.delete', $template->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                <button type="submit" class="btn delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>