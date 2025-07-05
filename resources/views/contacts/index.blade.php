<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
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
        .header-actions {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 20px;
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }
        .btn-warning:hover {
            background-color: #e0a800;
        }
        .contacts-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .contacts-table th,
        .contacts-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .contacts-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        .contacts-table tr:hover {
            background-color: #f8f9fa;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
        }
        .pagination a,
        .pagination span {
            padding: 8px 12px;
            text-decoration: none;
            color: #007bff;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            transition: all 0.3s;
        }
        .pagination a:hover {
            background-color: #e9ecef;
            color: #0056b3;
        }
        .pagination .active span {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        .pagination .disabled span {
            color: #6c757d;
            background-color: #fff;
            border-color: #dee2e6;
        }
        .no-contacts {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 40px;
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
        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .contact-name {
            font-weight: bold;
            color: #333;
        }
        .contact-email {
            color: #666;
            font-size: 14px;
        }
        .contact-phone {
            color: #666;
            font-size: 14px;
        }
        .total-info {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Contacts List</h1>
        
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

        <div class="header-actions">
            <a href="{{ route('contacts.create') }}" class="btn">Add New Contact</a>
            <div class="total-info">
                Showing {{ $contacts->count() }} of {{ $contacts->total() }} contacts
            </div>
        </div>

        @if($contacts->count() > 0)
            <table class="contacts-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Contact Information</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contacts as $contact)
                        <tr>
                            <td>{{ $contact->id }}</td>
                            <td>
                                <div class="contact-info">
                                    <div class="contact-name">{{ $contact->name }}</div>
                                    <div class="contact-email">{{ $contact->email }}</div>
                                    <div class="contact-phone">{{ $contact->phone }}</div>
                                </div>
                            </td>
                            <td>{{ $contact->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('contacts.edit', $contact->id) }}" class="btn btn-warning">Edit</a>
                                    <form method="POST" action="{{ route('contacts.destroy', $contact->id) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this contact?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination Links -->
            <div class="pagination">
                {{ $contacts->links() }}
            </div>
        @else
            <div class="no-contacts">
                <p>No contacts found.</p>
                <a href="{{ route('contacts.create') }}" class="btn">Create your first contact</a>
            </div>
        @endif
    </div>
</body>
</html>
