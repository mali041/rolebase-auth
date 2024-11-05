<x-layout>
    <x-slot:title>Categories</x-slot:title>
    <x-slot:heading>Categories Page</x-slot:heading>

    <div class="mx-auto px-4 lg:px-64 max-w-full">
        <div class="mb-4 flex justify-between items-center">
            <h2 class="text-2xl text-center">Category List</h2>
            @if(Auth::user()->role !== 'user')
                <button id="openCreateModal" class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700">
                    Create New Category
                </button>
            @endif
        </div>

        <div class="overflow-x-auto" id="categories-data">
            @include('categories.categories-table')
        </div>

        <div id="message" class="mt-4 text-center hidden"></div>

        <!-- Create Category Modal -->
        <div id="createCategoryModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
            <div class="modal-overlay absolute inset-0 bg-gray-900 opacity-50"></div>
            <div class="modal-content bg-white rounded-lg shadow-lg p-6 z-10 max-w-lg w-full">
                <h2 class="text-2xl font-bold mb-4">Create Category</h2>
                <form id="createCategoryForm">
                    @csrf
                    <div class="mb-4">
                        <label for="createName" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" id="createName" name="name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                        <p id="createError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                    <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Create
                    </button>
                    <button type="button" id="closeCreateModal" class="mt-2 bg-gray-400 text-white px-4 py-2 rounded">Cancel</button>
                </form>
            </div>
        </div>

        <!-- Update Category Modal -->
        <div id="updateCategoryModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
            <div class="modal-overlay absolute inset-0 bg-gray-900 opacity-50"></div>
            <div class="modal-content bg-white rounded-lg shadow-lg p-6 z-10 max-w-lg w-full">
                <h2 class="text-2xl font-bold mb-4">Update Category</h2>
                <form id="updateCategoryForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="updateCategoryId">
                    <div class="mb-4">
                        <label for="updateName" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" id="updateName" name="name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                        <p id="updateError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                    <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Update
                    </button>
                    <button type="button" id="closeUpdateModal" class="mt-2 bg-gray-400 text-white px-4 py-2 rounded">Cancel</button>
                </form>
            </div>
        </div>

        <!-- Category View Modal -->
        <div id="categoryViewModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
            <div class="modal-overlay absolute inset-0 bg-gray-900 opacity-50"></div>
            <div class="modal-content bg-white rounded-lg shadow-lg p-6 z-10 max-w-lg w-full">
                <h2 class="text-2xl font-bold mb-4">Category Details</h2>
                <p id="categoryName" class="text-lg"></p>
                <button id="closeViewModal" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Close</button>
            </div>
        </div>
    </div>

    <x-slot:script>
        <script>
            $(document).ready(function() {
                // Function to load categories data
                function loadCategoriesData() {
                    $.ajax({
                        url: '{{ route('load-categories-data') }}',
                        type: 'GET',
                        success: function (response) {
                            $('#categories-data').html(response.html);
                        }
                    })
                }

                // Function to create a category
                function createCategory(name) {
                    $.ajax({
                        url: '{{ route('categories.store') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            name: name
                        },
                        success: function(response) {
                            loadCategoriesData();
                            $('#createCategoryModal').addClass('hidden');
                            $('#createName').val('');
                            $('#message').removeClass('hidden text-red-600').addClass('text-green-600').text('Category created successfully').show();
                            addCategory(response);

                            setTimeout(() => { $('#message').fadeOut(); }, 5000);
                        },
                        error: function(xhr) {
                            $('#createError').text(xhr.responseJSON.message).removeClass('hidden');
                        }
                    });
                }

                // Function to update a category
                function updateCategory(categoryId, name) {
                    $.ajax({
                        url: `/categories/${categoryId}`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'PUT',
                            name: name
                        },
                        success: function(response) {
                            loadCategoriesData();
                            $('#updateCategoryModal').addClass('hidden');
                            $('#message').removeClass('hidden text-red-600').addClass('text-green-600').text('Category updated successfully').show();
                            updateCategoryInList(response);
                            setTimeout(() => { $('#message').fadeOut(); }, 5000);
                        },
                        error: function(xhr) {
                            $('#updateError').text(xhr.responseJSON.message).removeClass('hidden');
                        }
                    });
                }

                // Open Create Category Modal
                $('#openCreateModal').click(function() {
                    $('#createCategoryModal').removeClass('hidden');
                });

                // Close Create Modal
                $('#closeCreateModal').click(function() {
                    $('#createCategoryModal').addClass('hidden');
                });

                // Handle Create Category
                $('#createCategoryForm').submit(function(e) {
                    e.preventDefault();
                    const name = $('#createName').val();
                    createCategory(name); // Call create function
                });

                // Open Update Category Modal
                $(document).on('click', '.edit-button', function() {
                    const categoryId = $(this).data('id');
                    const categoryName = $(this).data('name');

                    $('#updateCategoryId').val(categoryId);
                    $('#updateName').val(categoryName);
                    $('#updateCategoryModal').removeClass('hidden');
                });

                // Close Update Modal
                $('#closeUpdateModal').click(function() {
                    $('#updateCategoryModal').addClass('hidden');
                });

                // Handle Update Category
                $('#updateCategoryForm').submit(function(e) {
                    e.preventDefault();
                    const categoryId = $('#updateCategoryId').val();
                    const name = $('#updateName').val();
                    updateCategory(categoryId, name); // Call update function
                });

                // View Category Details
                $(document).on('click', '.view-button', function() {
                    const categoryId = $(this).data('id');
                    $.ajax({
                        url: `/categories/${categoryId}`,
                        type: 'GET',
                        success: function(data) {
                            $('#categoryName').text(data.category.name);
                            $('#categoryViewModal').removeClass('hidden');
                        },
                        error: function() {
                            alert('Error fetching category details');
                        }
                    });
                });

                // Close View modal
                $('#closeViewModal').click(function() {
                    $('#categoryViewModal').addClass('hidden');
                });

                // Handle DELETE
                $(document).on('click', '.delete-button', function(e) {
                    e.preventDefault();
                    const categoryId = $(this).closest('.delete-form').data('category-id');
                    const form = $(this).closest('form');

                    if (confirm('Are you sure you want to delete this post?')) {
                        $.ajax({
                            url: `/categories/${categoryId}`,
                            type: 'POST',
                            data: {
                                '_token': $('input[name="_token"]').val(),
                                '_method': 'DELETE'
                            },
                            success: function(response) {
                                loadCategoriesData();
                                $('#message').removeClass('hidden text-red-600').addClass('text-green-600').text('Category deleted successfully').show();
                                setTimeout(function() {
                                    $('#message').fadeOut();
                                }, 5000);
                            },
                            error: function(xhr) {
                                $('#message').removeClass('hidden text-green-600').addClass('text-red-600').text('Error: Unable to delete Category').show();
                                setTimeout(function() {
                                    $('#message').fadeOut();
                                }, 5000);
                            }
                        });
                    }
                });
            });
        </script>
    </x-slot:script>
</x-layout>
