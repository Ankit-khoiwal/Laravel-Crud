<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <form id="userForm" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <div class="form-group mb-3">
                <label>Role</label>
                <select name="role_id" class="form-control">
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-3">
                <label>Profile Image</label>
                <input type="file" name="profile_image" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <table class="table table-striped mt-4" id="usersTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Description</th>
                    <th>Role</th>
                    <th>Profile Image</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function fetchUsers() {
                $.ajax({
                    url: "/api/users",
                    method: "GET",
                    success: function(response) {
                        let usersTable = $('#usersTable tbody');
                        usersTable.empty();
                        $.each(response.data, function(index, user) {
                            usersTable.append(`
                                <tr>
                                    <td>${user.name}</td>
                                    <td>${user.email}</td>
                                    <td>${user.phone}</td>
                                    <td>${user.description}</td>
                                    <td>${user.role.role_name}</td>
                                    <td>${user.profile_image ? '<img src="/storage/' + user.profile_image + '" width="50">' : ''}</td>
                                </tr>
                            `);
                        });
                    }
                });
            }

            $('#userForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "/api/users",
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        fetchUsers();
                        $('#userForm')[0].reset();
                        alert(response.message);
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            alert(value[0]);
                        });
                    }
                });
            });

            fetchUsers();
        });
    </script>
</body>
</html>
