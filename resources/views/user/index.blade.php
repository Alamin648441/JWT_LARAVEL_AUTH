<!DOCTYPE html>
<html>
<head>
    <title>Laravel AJAX Example</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Users List</h2>
    <form id="userForm">
        <input type="text" name="name" id="name" placeholder="Enter name">
        <button type="submit">Add User</button>
    </form>

    <ul id="usersList">
        @foreach($users as $user)
            <li>{{ $user->name }}</li>
        @endforeach
    </ul>

    <script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#userForm').submit(function(e) {
            e.preventDefault();
            var name = $('#name').val();

            $.ajax({
                type: 'POST',
                url: "{{ route('user.store') }}",
                data: { name: name },
                success: function(user) {
                    $('#usersList').prepend('<li>' + user.name + '</li>');
                    $('#name').val('');
                },
                error: function(err) {
                    alert('Error: ' + err.responseJSON.message);
                }
            });
        });
    });
    </script>
</body>
</html>
