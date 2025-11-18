<!DOCTYPE html>
<html>
<head>
    <title>Yajra DataTable</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">

</head>

<body>

<table id="users-table" class="display">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
</table>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

<script>
    $(function () {
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: "{{ route('users.data') }}",

            
            columns: [
                {data: 'id'},
                {data: 'name'},
                {data: 'email'},
                {data: 'action', orderable: true, searchable: false},
            ]
        });
    });
</script>

</body>
</html>
