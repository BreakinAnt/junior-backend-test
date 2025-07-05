<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Contact</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"],
        input[type="email"],
        input[type="tel"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0,123,255,0.3);
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
            margin-bottom: 10px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .error {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        .form-actions {
            display: flex;
            gap: 10px;
        }
        .form-actions .btn {
            flex: 1;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Edit Contact</h1>
        
        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('contacts.update', $contact->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $contact->name) }}" placeholder="Enter full name" required>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email', $contact->email) }}" placeholder="Enter email address" required>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone', $contact->phone) }}" placeholder="(11) 99999-9999" required>
                @error('phone')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Update Contact</button>
                <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        // Phone number formatting
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = '(' + value.substring(0, 2) + ') ' + value.substring(2);
            }
            if (value.length >= 10) {
                value = value.substring(0, 10) + '-' + value.substring(10, 14);
            }
            e.target.value = value;
        });

        // Format phone number on page load
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.getElementById('phone');
            const phoneValue = phoneInput.value.replace(/\D/g, '');
            
            if (phoneValue.length === 11) {
                phoneInput.value = '(' + phoneValue.substring(0, 2) + ') ' + phoneValue.substring(2, 7) + '-' + phoneValue.substring(7);
            } else if (phoneValue.length === 10) {
                phoneInput.value = '(' + phoneValue.substring(0, 2) + ') ' + phoneValue.substring(2, 6) + '-' + phoneValue.substring(6);
            }
        });
    </script>
</body>
</html>
