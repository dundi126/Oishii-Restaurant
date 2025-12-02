<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff User Management - Oishii Restaurant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .bg-japanese-red { background-color: #dc2626; }
        .text-japanese-red { color: #dc2626; }
        .border-japanese-red { border-color: #dc2626; }
        .hover\:bg-japanese-red:hover { background-color: #b91c1c; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    @include('partials.nav')

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Staff User Management</h1>
            <p class="text-gray-600">Create and manage staff user accounts</p>
        </div>

        <!-- Success/Error Messages -->
        <div id="alertMessage" class="hidden mb-6 p-4 rounded-lg border-l-4"></div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Side - Create/Edit Form -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4" id="formTitle">Create New Staff User</h2>
                    
                    <form id="userForm" class="space-y-4">
                        <input type="hidden" id="userId" name="user_id">
                        
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent"
                                   placeholder="John Doe">
                            <span class="text-red-500 text-xs" id="name-error"></span>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent"
                                   placeholder="john@example.com">
                            <span class="text-red-500 text-xs" id="email-error"></span>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password <span id="passwordRequired" class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent"
                                   placeholder="Enter password">
                            <span class="text-gray-500 text-xs mt-1" id="passwordHint">Leave blank to keep current password</span>
                            <span class="text-red-500 text-xs" id="password-error"></span>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirm Password <span id="passwordConfirmationRequired" class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent"
                                   placeholder="Confirm password">
                            <span class="text-red-500 text-xs" id="password_confirmation-error"></span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-3 pt-4">
                            <button type="submit" 
                                    id="submitBtn"
                                    class="flex-1 bg-japanese-red text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300">
                                <i class="fas fa-save mr-2"></i>
                                <span id="submitBtnText">Create Staff User</span>
                            </button>
                            <button type="button" 
                                    id="cancelBtn"
                                    onclick="resetForm()"
                                    class="hidden bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-300">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Side - Staff Users List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900">All Staff Users</h2>
                        <p class="text-sm text-gray-600 mt-1">Total: <span id="staffCount">{{ count($staffUsers) }}</span> staff users</p>
                    </div>

                    <div class="overflow-x-auto">
                        @if(count($staffUsers) > 0)
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="staffUsersTable" class="bg-white divide-y divide-gray-200">
                                    @foreach($staffUsers as $user)
                                        <tr id="user-row-{{ $user->id }}" class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $user->created_at->format('M d, Y') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button onclick="editUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')" 
                                                        class="text-blue-600 hover:text-blue-900 mr-4">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <button onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')" 
                                                        class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="p-12 text-center">
                                <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
                                <p class="text-gray-500 text-lg">No staff users found</p>
                                <p class="text-gray-400 text-sm mt-2">Create your first staff user using the form on the left</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 p-6">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-question text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2 text-center" id="confirmTitle">Confirm Action</h3>
            <p class="text-gray-600 mb-6 text-center" id="confirmMessage"></p>
            <div class="flex space-x-3">
                <button onclick="closeConfirmModal()" 
                        class="flex-1 bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 transition duration-300">
                    Cancel
                </button>
                <button id="confirmActionBtn"
                        class="flex-1 bg-japanese-red text-white py-2 px-4 rounded-lg hover:bg-red-700 transition duration-300">
                    Confirm
                </button>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let currentUserId = null;
        let pendingAction = null;

        // Form submission
        document.getElementById('userForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const userId = document.getElementById('userId').value;
            const isEdit = userId !== '';
            
            // Validate password for new users
            if (!isEdit && !formData.get('password')) {
                showAlert('Password is required for new users', 'error');
                return;
            }

            // Show confirmation modal
            const action = isEdit ? 'update' : 'create';
            showConfirmModal(
                isEdit ? 'Update Staff User' : 'Create Staff User',
                isEdit 
                    ? 'Are you sure you want to update this staff user?'
                    : 'Are you sure you want to create a new staff user with the following details?<br><br><strong>Name:</strong> ' + formData.get('name') + '<br><strong>Email:</strong> ' + formData.get('email'),
                () => submitForm(formData, userId, isEdit)
            );
        });

        function submitForm(formData, userId, isEdit) {
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';

            const url = isEdit 
                ? `/admin/users/${userId}`
                : '/admin/users';
            const method = isEdit ? 'PUT' : 'POST';

            // Convert FormData to JSON
            const data = {};
            formData.forEach((value, key) => {
                if (value) data[key] = value;
            });

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    showAlert(result.message, 'success');
                    if (isEdit) {
                        updateUserInTable(result.user);
                    } else {
                        addUserToTable(result.user);
                    }
                    resetForm();
                    closeConfirmModal();
                } else {
                    if (result.errors) {
                        displayErrors(result.errors);
                    } else {
                        showAlert(result.message || 'An error occurred', 'error');
                    }
                }
            })
            .catch(error => {
                showAlert('An error occurred: ' + error.message, 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        }

        function editUser(id, name, email) {
            currentUserId = id;
            document.getElementById('userId').value = id;
            document.getElementById('name').value = name;
            document.getElementById('email').value = email;
            document.getElementById('password').value = '';
            document.getElementById('password_confirmation').value = '';
            document.getElementById('password').required = false;
            document.getElementById('password_confirmation').required = false;
            document.getElementById('passwordRequired').classList.add('hidden');
            document.getElementById('passwordConfirmationRequired').classList.add('hidden');
            document.getElementById('passwordHint').classList.remove('hidden');
            document.getElementById('formTitle').textContent = 'Edit Staff User';
            document.getElementById('submitBtnText').textContent = 'Update Staff User';
            document.getElementById('cancelBtn').classList.remove('hidden');
            
            // Scroll to form
            document.querySelector('.lg\\:col-span-1').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function deleteUser(id, name) {
            showConfirmModal(
                'Delete Staff User',
                `Are you sure you want to delete the staff user "<strong>${name}</strong>"? This action cannot be undone.`,
                () => performDelete(id)
            );
        }

        function performDelete(id) {
            fetch(`/admin/users/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    showAlert(result.message, 'success');
                    document.getElementById(`user-row-${id}`).remove();
                    updateStaffCount();
                    if (document.querySelectorAll('#staffUsersTable tr').length === 0) {
                        location.reload();
                    }
                } else {
                    showAlert(result.message || 'Failed to delete user', 'error');
                }
                closeConfirmModal();
            })
            .catch(error => {
                showAlert('An error occurred: ' + error.message, 'error');
                closeConfirmModal();
            });
        }

        function resetForm() {
            document.getElementById('userForm').reset();
            document.getElementById('userId').value = '';
            document.getElementById('password').required = true;
            document.getElementById('password_confirmation').required = true;
            document.getElementById('passwordRequired').classList.remove('hidden');
            document.getElementById('passwordConfirmationRequired').classList.remove('hidden');
            document.getElementById('passwordHint').classList.add('hidden');
            document.getElementById('formTitle').textContent = 'Create New Staff User';
            document.getElementById('submitBtnText').textContent = 'Create Staff User';
            document.getElementById('cancelBtn').classList.add('hidden');
            clearErrors();
            currentUserId = null;
        }

        function addUserToTable(user) {
            const tbody = document.getElementById('staffUsersTable');
            const row = document.createElement('tr');
            row.id = `user-row-${user.id}`;
            row.className = 'hover:bg-gray-50';
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">${user.name}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-500">${user.email}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-500">${new Date(user.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button onclick="editUser(${user.id}, ${JSON.stringify(user.name)}, ${JSON.stringify(user.email)})" 
                            class="text-blue-600 hover:text-blue-900 mr-4">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button onclick="deleteUser(${user.id}, ${JSON.stringify(user.name)})" 
                            class="text-red-600 hover:text-red-900">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </td>
            `;
            tbody.insertBefore(row, tbody.firstChild);
            updateStaffCount();
        }

        function updateUserInTable(user) {
            const row = document.getElementById(`user-row-${user.id}`);
            if (row) {
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">${user.name}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">${user.email}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">${new Date(user.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button onclick="editUser(${user.id}, ${JSON.stringify(user.name)}, ${JSON.stringify(user.email)})" 
                                class="text-blue-600 hover:text-blue-900 mr-4">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button onclick="deleteUser(${user.id}, ${JSON.stringify(user.name)})" 
                                class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                `;
            }
        }

        function updateStaffCount() {
            const count = document.querySelectorAll('#staffUsersTable tr').length;
            document.getElementById('staffCount').textContent = count;
        }

        function showConfirmModal(title, message, callback) {
            document.getElementById('confirmTitle').textContent = title;
            document.getElementById('confirmMessage').innerHTML = message;
            document.getElementById('confirmModal').classList.remove('hidden');
            
            const confirmBtn = document.getElementById('confirmActionBtn');
            confirmBtn.onclick = function() {
                callback();
            };
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
        }

        function showAlert(message, type) {
            const alertDiv = document.getElementById('alertMessage');
            alertDiv.className = type === 'success' 
                ? 'bg-green-100 border-green-500 text-green-700' 
                : 'bg-red-100 border-red-500 text-red-700';
            alertDiv.classList.remove('hidden');
            alertDiv.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
            
            setTimeout(() => {
                alertDiv.classList.add('hidden');
            }, 5000);
        }

        function displayErrors(errors) {
            clearErrors();
            Object.keys(errors).forEach(key => {
                const errorElement = document.getElementById(`${key}-error`);
                if (errorElement) {
                    errorElement.textContent = errors[key][0];
                }
            });
        }

        function clearErrors() {
            document.querySelectorAll('.text-red-500.text-xs').forEach(el => {
                el.textContent = '';
            });
        }

        // Close modal when clicking outside
        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeConfirmModal();
            }
        });
    </script>
</body>
</html>

