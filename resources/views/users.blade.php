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
            width: 1rem;
            height: 1rem;
            border-width: 0.3rem;
        }

        .has-error .form-control {
            border-color: #dc3545;
        }

        .error-text {
            color: #dc3545;
            font-size: 0.875em;
        }

        .toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1055;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header Section -->
        <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
            <a href="{{ url('/') }}"
                class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
                <span class="fs-4">User FORM</span>
            </a>
            <ul class="nav nav-pills">
                <li class="nav-item"><a href="{{ url('/') }}" class="nav-link active" aria-current="page">Home</a>
                </li>
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
            <table class="table table-striped mt-5" id="usersTable">
                <thead class="border">
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

            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center" id="paginationLinks"></ul>
            </nav>
        </div>

        <!-- Loader Overlay -->
        <div class="spinner-overlay">
            <div class="spinner-border text-primary" role="status"></div>
        </div>

        <!-- Toaster Container -->
        <div class="toast-container">
            <div id="toastSuccess" class="toast align-items-center text-bg-success border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex bg-success">
                    <div class="toast-body text-light">Success! User created successfully.</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
            <div id="toastError" class="toast align-items-center text-bg-danger border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex bg-danger">
                    <div class="toast-body text-light">Error! Please fix the errors and try again.</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="text-center mt-5 py-1 bg-dark">
        <p class="text-light">&copy; 2024 <a href="https://ankit-khatik.web.app" class="text-light text-decoration-none"
                target="_blank">Ankit Khatik❤</a>. All Rights Reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            function fetchUsers(pageUrl = "{{ route('users.get') }}") {
                $.ajax({
                    url: pageUrl,
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
                            <td>${user.profile_image ? '<img src="' + user.profile_image + '" width="50">' : ''}</td>
                        </tr>
                    `);
                        });
                        updatePagination(response.links, response.total);
                    }
                });
            }

            function updatePagination(links, totalUsers) {
                let paginationLinks = $('#paginationLinks');
                paginationLinks.empty();

                if (totalUsers > 10) {
                    if (links.prev) {
                        paginationLinks.append(
                            `<li class="page-item"><a class="page-link" href="#" data-url="${links.prev}">Previous</a></li>`
                        );
                    }

                    for (let i = 1; i <= links.last_page; i++) {
                        let pageUrl = `/users?page=${i}`;
                        paginationLinks.append(
                            `<li class="page-item ${links.current_page == i ? 'active' : ''}"><a class="page-link" href="#" data-url="${pageUrl}">${i}</a></li>`
                        );
                    }

                    if (links.next) {
                        paginationLinks.append(
                            `<li class="page-item"><a class="page-link" href="#" data-url="${links.next}">Next</a></li>`
                        );
                    }

                    paginationLinks.show();
                } else {
                    paginationLinks.hide();
                }

                $('#paginationLinks .page-link').on('click', function(e) {
                    e.preventDefault();
                    const pageUrl = $(this).data('url');
                    window.history.pushState({
                        path: pageUrl
                    }, '', pageUrl);
                    fetchUsers(pageUrl);
                });
            }

            $('input, textarea, select').on('input change', function() {
                $(this).next('.error-text').text('');
                $(this).closest('.form-group').removeClass('has-error');
            });

            $('#userForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                let fields = [{
                        name: 'name',
                        type: 'input',
                        message: 'Name is required.'
                    },
                    {
                        name: 'email',
                        type: 'input',
                        message: 'Enter a valid email.',
                        pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/
                    },
                    {
                        name: 'phone',
                        type: 'input',
                        message: 'Enter a valid 10-digit Indian phone number.',
                        pattern: /^[6-9]\d{9}$/
                    },
                    {
                        name: 'description',
                        type: 'textarea',
                        message: 'Description is required.'
                    },
                    {
                        name: 'role_id',
                        type: 'select',
                        message: 'Please select a role.'
                    }
                ];

                for (let field of fields) {
                    let value = $(`${field.type}[name="${field.name}"]`).val().trim();
                    let isValid = field.pattern ? field.pattern.test(value) : value !== '';

                    if (!isValid) {
                        $(`${field.type}[name="${field.name}"]`).next('.error-text').text(field.message);
                        $(`${field.type}[name="${field.name}"]`).closest('.form-group').addClass(
                            'has-error').focus();
                        return;
                    }
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
                        showToast('toastSuccess');
                    },
                    error: function(xhr) {
                        $('button[type="submit"] .spinner-border').addClass('d-none');
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $(`[name="${key}"]`).next('.error-text').text(value[0]);
                                $(`[name="${key}"]`).closest('.form-group').addClass(
                                    'has-error').focus();
                            });
                        } else {
                            showToast('toastError');
                        }
                    }
                });
            });

            function showToast(id) {
                let toast = new bootstrap.Toast(document.getElementById(id));
                toast.show();
                setTimeout(() => toast.hide(), 5000);
            }

            fetchUsers();
        });
    </script>
</body>

</html>
