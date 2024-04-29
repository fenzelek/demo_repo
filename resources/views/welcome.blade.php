<!DOCTYPE html>
<html>
<head>
    <title>Upload File for Parsing Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
        }

        #container {
            width: 400px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        #uploadForm {
            display: flex;
            flex-direction: column;
        }

        #uploadForm input,
        #uploadForm select,
        #uploadForm button {
            margin-bottom: 10px;
            width: calc(100% - 20px);
        }

        #uploadForm button {
            margin-bottom: 0;
        }

        #response {
            margin-top: 20px;
            padding: 10px;
            background-color: #f0f0f0;
            border-radius: 4px;
        }

        input[type="file"] {
            margin-bottom: 10px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            color: #dc3545;
            font-size: 14px;
        }

        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 2s linear infinite;
            display: inline-block;
            margin-left: 10px;
            vertical-align: middle;
            visibility: hidden;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>

<div id="container">
    <h2>Upload File for Parsing Test</h2>

    <form id="uploadForm" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" id="file">
        <div class="form-group">
            <label for="sourceType">Select Source Type:</label>
            <select class="form-control" id="sourceType" name="source_type">
                <option value="CCNX">CCNX</option>
                <option value="SomeOther">SomeOther</option>
            </select>
        </div>
        <button type="submit">Upload File</button>
        <div class="loader" id="loader"></div>
    </form>

    <div id="response"></div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $('#uploadForm').submit(function (e) {
            e.preventDefault();

            $('#loader').css('visibility', 'visible');

            var formData = new FormData(this);

            $.ajax({
                url: "{{ route('parse-file') }}",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#response').html(data);
                    $('#loader').css('visibility', 'hidden');
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    $('#response').html('<div class="error">Error occurred while uploading file.</div>');
                    $('#loader').css('visibility', 'hidden');
                }
            });
        });
    });
</script>

</body>
</html>
