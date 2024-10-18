<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            z-index: 9999;
            display: none;
            justify-content: center;
            align-items: center;
        }
        .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: 0.3rem;
        }
        .has-error .form-control {
            border-color: #dc3545;
        }
        .error-text {
            color: #dc3545;
            font-size: 0.875em;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
            <a href="{{ url('/') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
                <span class="fs-4">User CRUD</span>
            </a>
            <ul class="nav nav-pills">
                <li class="nav-item"><a href="{{ url('/') }}" class="nav-link active" aria-current="page">Home</a></li>
            </ul>
        </header>

        <!-- Form Section -->
        <div class="mt-5">
            <form id="userForm" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                    <label>Name</label>
                    <input type="text" name="name" placeholder="Enter Name here" class="form-control">
                    <span class="error-text name_error"></span>
                </div>
                <div class="form-group mb-3">
                    <label>Email</label>
                    <input type="text" name="email" placeholder="Enter Email here" class="form-control">
                    <span class="error-text email_error"></span>
                </div>
                <div class="form-group mb-3">
                    <label>Phone</label>
                    <input type="text" name="phone" placeholder="Enter Phone here" class="form-control">
                    <span class="error-text phone_error"></span>
                </div>
                <div class="form-group mb-3">
                    <label>Description</label>
                    <textarea name="description" placeholder="Enter Description here" class="form-control"></textarea>
                    <span class="error-text description_error"></span>
                </div>
                <div class="form-group mb-3">
                    <label>Role</label>
                    <select name="role_id" class="form-control">
                        <option value="" selected>Select Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                        @endforeach
                    </select>
                    <span class="error-text role_id_error"></span>
                </div>
                <div class="form-group mb-3">
                    <label>Profile Image</label>
                    <input type="file" name="profile_image" class="form-control">
                    <span class="error-text profile_image_error"></span>
                </div>
                <button type="submit" class="btn btn-primary">
                    Submit
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </form>

            <!-- Users Table -->
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

        <!-- Loader Overlay -->
        <div class="spinner-overlay">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="text-center mt-5 py-1 bg-dark">
        <p class="text-light">&copy; 2024 <a href="https://ankit-khatik.web.app" class="text-light text-decoration-none" target="_blank">Ankit Khatik❤</a>. All Rights Reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            function fetchUsers() {
                $.ajax({
                    url: "{{ route('users.get') }}",
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

            // Client-side validation
            $('#userForm').on('submit', function(e) {
                e.preventDefault();

                // Clear previous errors
                $('.error-text').text('');
                $('.form-group').removeClass('has-error');

                let formData = new FormData(this);
                let hasError = false;

                // Name validation
                let name = $('input[name="name"]').val().trim();
                if (name === '') {
                    $('.name_error').text('Name is required.');
                    $('input[name="name"]').closest('.form-group').addClass('has-error');
                    hasError = true;
                }

                // Email validation
                let email = $('input[name="email"]').val().trim();
                let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (email === '') {
                    $('.email_error').text('Email is required.');
                    $('input[name="email"]').closest('.form-group').addClass('has-error');
                    hasError = true;
                } else if (!emailPattern.test(email)) {
                    $('.email_error').text('Please enter a valid email address.');
                    $('input[name="email"]').closest('.form-group').addClass('has-error');
                    hasError = true;
                }

                // Phone validation (Indian 10-digit number)
                let phone = $('input[name="phone"]').val().trim();
                let phonePattern = /^[6-9]\d{9}$/;
                if (phone === '') {
                    $('.phone_error').text('Phone number is required.');
                    $('input[name="phone"]').closest('.form-group').addClass('has-error');
                    hasError = true;
                } else if (!phonePattern.test(phone)) {
                    $('.phone_error').text('Please enter a valid 10-digit Indian phone number.');
                    $('input[name="phone"]').closest('.form-group').addClass('has-error');
                    hasError = true;
                }

                // Description validation
                let description = $('textarea[name="description"]').val().trim();
                if (description === '') {
                    $('.description_error').text('Description is required.');
                    $('textarea[name="description"]').closest('.form-group').addClass('has-error');
                    hasError = true;
                }

                // Role validation
                let role_id = $('select[name="role_id"]').val();
                if (role_id === '') {
                    $('.role_id_error').text('Please select a role.');
                    $('select[name="role_id"]').closest('.form-group').addClass('has-error');
                    hasError = true;
                }

                if (hasError) {
                    return;
                }

                $('button[type="submit"] .spinner-border').removeClass('d-none');

                $.ajax({
                    url: "{{ route('users.post') }}",
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        fetchUsers();
                        $('#userForm')[0].reset();
                        $('button[type="submit"] .spinner-border').addClass('d-none');
                        alert(response.message);
                    },
                    error: function(xhr) {
                        $('button[type="submit"] .spinner-border').addClass('d-none');
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('.' + key + '_error').text(value[0]);
                                $('[name="' + key + '"]').closest('.form-group').addClass('has-error');
                            });
                        } else {
                            alert('An error occurred. Please try again.');
                        }
                    }
                });
            });

            // Initial fetch of users
            fetchUsers();
        });
    </script>
</body>
</html>
