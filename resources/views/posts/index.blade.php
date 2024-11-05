<x-layout>
    <x-slot:title>Posts</x-slot:title>
    <x-slot:heading>Post's Page</x-slot:heading>

    <div class="mx-auto px-4 lg:px-64 max-w-full">
        <div class="mb-4 flex justify-between items-center">
            <h2 class="text-2xl text-center">Post List</h2>
            @if(Auth::user()->role !== 'admin')
                <button id="openCreatePostModal" class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700">
                    Create New Post
                </button>
            @else
                <div class="mb-4">
                    <label for="categoryFilter" class="text-sm font-medium text-gray-700">Filter by Category</label>
                    <select id="categoryFilter" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <div class="overflow-x-auto" id="posts-data">
            @include('posts.posts-table')
        </div>

        <div id="message" class="mt-4 text-center hidden"></div>
        @if(Auth::user()->role === 'admin')
            <div id="pagination" class="mt-4"> {{ $posts->links() }} </div>
        @endif

        <!-- Create Post Modal -->
        <div id="createPostModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
            <div class="modal-overlay absolute inset-0 bg-gray-900 opacity-50"></div>
            <div class="modal-content bg-white rounded-lg shadow-lg p-6 z-10 max-w-lg w-full">
                <h2 class="text-2xl font-bold mb-4">Create Post</h2>
                <form id="createPostForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" id="title" name="title"
                               class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                        <p id="titleError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" name="description"
                                  class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required></textarea>
                        <p id="descriptionError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                    <input type="hidden" id="user_id" name="user_id" value="{{ Auth::user()->id }}">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Select Categories</label>
                        <div class="mt-1">
                            <div class="flex items-center">
                                @foreach ($categories as $category)
                                    <input id="category-{{ $category->id }}"
                                           name="categories[]"
                                           type="checkbox"
                                           value="{{ $category->id }}"
                                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="category-{{ $category->id }}"
                                           class="ml-2 block text-sm text-gray-800 m-2">{{ $category->name }}</label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="images[]" class="block text-sm font-medium text-gray-700">Images</label>
                        <input type="file" id="images" name="images[]" multiple
                               class="mt-1 block w-full p-2 border border-gray-300 rounded-md"/>
                    </div>
                    <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Create
                    </button>
                    <button type="button" id="closeCreateModal" class="mt-2 bg-gray-400 text-white px-4 py-2 rounded">
                        Cancel
                    </button>
                </form>
            </div>
        </div>

        <!-- Update Post Modal -->
        <div id="updatePostModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
            <div class="modal-overlay absolute inset-0 bg-gray-900 opacity-50"></div>
            <div class="modal-content bg-white rounded-lg shadow-lg p-6 z-10 max-w-lg w-full">
                <h2 class="text-2xl font-bold mb-4">Update Post</h2>
                <form id="updatePostForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="update_post_id" name="post_id">
                    <input type="hidden" id="user_id" name="user_id" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="remove_images" id="remove_images">

                    <!-- Title Field -->
                    <div class="mb-4">
                        <label for="update_title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" id="update_title" name="title"
                               class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                    </div>

                    <!-- Description Field -->
                    <div class="mb-4">
                        <label for="update_description"
                               class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="update_description" name="description"
                                  class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required></textarea>
                    </div>

                    <!-- Categories Selection -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Select Categories</label>
                        <div class="mt-1">
                            @foreach ($categories as $category)
                                <div class="flex items-center">
                                    <input id="update_category-{{ $category->id }}" name="categories[]" type="checkbox"
                                           value="{{ $category->id }}"
                                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="update_category-{{ $category->id }}"
                                           class="ml-2 block text-sm text-gray-800">{{ $category->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Current Images with Remove Button -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Current Images</label>
                        <div id="currentImages" class="flex flex-wrap mb-2"></div>
                    </div>

                    <!-- New Images Upload -->
                    <div class="mb-4">
                        <label for="new_images" class="block text-sm font-medium text-gray-700">Add Images</label>
                        <input type="file" id="new_images" name="new_images[]" multiple
                               class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Update
                    </button>
                    <button type="button" id="closeUpdateModal" class="mt-2 bg-gray-400 text-white px-4 py-2 rounded">
                        Cancel
                    </button>
                </form>
            </div>
        </div>

        <!-- View Post Modal -->
        <div id="viewPostModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
            <div class="modal-overlay absolute inset-0 bg-gray-900 opacity-50"></div>
            <div class="modal-content bg-white rounded-lg shadow-lg p-6 z-10 max-w-lg w-full">
                <h2 class="text-2xl font-bold mb-2" id="modalPostTitle"></h2>
                <p class="mb-2 text-gray-700" id="modalPostAuthor"></p>
                <p class="mb-4" id="modalPostDescription"></p>
                <div id="modalPostCategories" class="mb-4 text-sm text-gray-500"></div>
                <div id="modalPostImages" class="flex flex-wrap"></div>
                <button id="closeViewModal" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Close</button>
            </div>
        </div>

    </div>
    <x-slot:script>
        <script>
            let baseUrl = "{{ asset('posts/images') }}";
            $(document).ready(function () {
                // Function to load Posts data
                function loadPostsData() {
                    $.ajax({
                        url: '{{ route('load-posts-data') }}',
                        type: 'GET',
                        success: function (response) {
                            $('#posts-data').html(response.html);
                        }
                    })
                }

                // Handle Create Modal
                    $('#createPostForm').on('submit', function (e) {
                        e.preventDefault();

                        $.ajax({
                            url: "{{ route('posts.store') }}",
                            type: 'POST',
                            processData: false, // Important: prevent jQuery from transforming the data
                            contentType: false,
                            data: new FormData(this),
                            success: function (response) {
                                loadPostsData();
                                $('#createPostModal').addClass('hidden');
                                $('#message').removeClass('hidden text-red-600').addClass('text-green-600').text('Post created successfully').show();

                                setTimeout(function () {
                                    $('#message').fadeOut();
                                }, 5000);
                            },
                            error: function (xhr) {
                                // Handle validation errors
                                if (xhr.responseJSON.errors) {
                                    for (const [key, value] of Object.entries(xhr.responseJSON.errors)) {
                                        $(`#${key}Error`).text(value[0]).removeClass('hidden');
                                    }
                                }
                                $('#message').removeClass('hidden text-green-600').addClass('text-red-600').text('Error creating post').show();
                            }
                        });
                    });

                    $('#openCreatePostModal').click(function () {
                        $('#createPostModal').removeClass('hidden');
                    });

                    $('#closeCreateModal').click(function () {
                        $('#createPostModal').addClass('hidden');
                    });

                // Handle Update Modal
                    $(document).on('click', '.edit-button', function () {
                        const postId = $(this).data('id');
                        $.ajax({
                            url: `/user/posts/${postId}`,
                            type: 'GET',
                            success: function (post) {
                                $('#update_post_id').val(post.id);
                                $('#update_user_id').val(post.user_id);
                                $('#update_title').val(post.title);
                                $('#update_description').val(post.description);
                                $('#update_user_id').val(post.user_id);

                                // Clear category checkboxes and re-check based on the retrieved post data
                                $('input[name="categories[]"]').prop('checked', false);
                                post.categories.forEach(category => {
                                    $(`#update_category-${category.id}`).prop('checked', true);
                                });

                                // Clear and display existing images with delete buttons
                                $('#currentImages').empty();
                                post.images.forEach(image => {
                                    $('#currentImages').append(`
                                        <div class="relative mr-2 mb-2">
                                            <img src="${baseUrl}/${image.name}" alt="Post Image" class="rounded w-16 h-16 mb-2">
                                            <button type="button" class="remove-image-button absolute top-0 right-0 bg-red-500 text-white rounded-full" data-image-id="${image.id}"> x </button>
                                        </div>
                                    `);
                                });

                                $('#updatePostModal').removeClass('hidden');
                            },
                            error: function (xhr) {
                                console.error('Error fetching post:', xhr);
                            }
                        });
                    });

                    // Close Update Modal
                    $('#closeUpdateModal').click(function () {
                        $('#updatePostModal').addClass('hidden');
                    });

                    // Remove Image Button
                    $(document).on('click', '.remove-image-button', function () {
                        const imageId = $(this).data('image-id');
                        $(this).parent().remove(); // Remove the image element

                        // Create a hidden input for the removed image
                        if (!$(`#remove_image_${imageId}`).length) {
                            $('#updatePostForm').append(`<input type="hidden" name="remove_images[]" id="remove_image_${imageId}" value="${imageId}">`);
                        }
                    });

                    // Handle Update Post Form Submission
                    $('#updatePostForm').submit(function (e) {
                        e.preventDefault();

                        const postId = $('#update_post_id').val();
                        const formData = new FormData(this);  // Collects all form inputs, including files

                        $.ajax({
                            url: `/user/posts/${postId}`,
                            type: 'POST',
                            data: formData,
                            contentType: false,  // Required for FormData
                            processData: false,  // Required for FormData
                            success: function (response) {
                                loadPostsData();
                                $('#updatePostModal').addClass('hidden');
                            },
                            error: function (xhr) {
                                console.error('Error updating post:', xhr);
                            }
                        });
                    });

                //Handle View Modal
                    $(document).on('click', '.view-button', function (e) {
                        e.preventDefault();
                        const postId = $(this).data('id');

                        $.ajax({
                            url: `/user/posts/${postId}`,
                            type: 'GET',
                            success: function (post) {
                                console.log(post);
                                $('#modalPostTitle').text(`Title: ${post.title}`);
                                $('#modalPostAuthor').text(`Author: ${post.user.name}`);
                                $('#modalPostDescription').text(`Description: ${post.description}`);

                                $('#modalPostCategories').empty();
                                const categoriesList = post.categories.map(category => category.name).join(' | ');
                                $('#modalPostCategories').text(`Categories: ${categoriesList}`);

                                $('#modalPostImages').empty();
                            post.images.map(image => {
                                $('#modalPostImages').append(`
                                <img src="${baseUrl}/${image.name}" alt="Post Image" class="rounded w-16 h-16 mb-2 mr-2" />
                            `);
                            });

                            // Show modal
                            $('#viewPostModal').removeClass('hidden');
                        },
                        error: function (xhr) {
                            console.error('Error fetching post:', xhr);
                        }
                    });
                });

                // Close View modal
                $('#closeViewModal').click(function () {
                    $('#viewPostModal').addClass('hidden');
                });

                // Handle Delete
                $(document).on('click', '.delete-button', function (e) {
                    e.preventDefault();

                    const postId = $(this).closest('.delete-form').data('post-id');
                    const form = $(this).closest('form');

                    if (confirm('Are you sure you want to delete this post?')) {
                        $.ajax({
                            url: `/user/posts/${postId}`,
                            type: 'POST',
                            data: {
                                '_token': $('input[name="_token"]').val(),
                                '_method': 'DELETE'
                            },
                            success: function (response) {
                                loadPostsData();
                                $('#message').removeClass('hidden text-red-600').addClass('text-green-600').text('Post deleted successfully').show();

                                setTimeout(function () {
                                    $('#message').fadeOut();
                                }, 5000);
                            },
                            error: function (xhr) {
                                $('#message').removeClass('hidden text-green-600').addClass('text-red-600').text('Error: Unable to delete Post').show();

                                setTimeout(function () {
                                    $('#message').fadeOut();
                                }, 5000);
                            }
                        });
                    }
                });
            });

            // Pagination
            $(document).on('click', '#pagination a', function(e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];

                $.ajax({
                    url:`/admin/posts?page=${page}`,
                    success:function(res) {
                        $('#posts-data').html($(res).find('#posts-data').html());
                        $('#pagination').html($(res).find('#pagination').html());
                    },
                    error: function(xhr) {
                        console.error('Pagination error:', xhr);
                    }
                })
            });

            // Filter By Category
            function categoryFilter(categoryId = '') {
                $.ajax({
                    url: `/admin/load-posts-data`,
                    type: 'GET',
                    data: { category: categoryId },
                    success: function(response) {
                        $('#posts-data').html(response.html);

                        if ($('#pagination').length) {
                            $('#pagination').html('');
                        }
                    },
                });
            }

            // Update category filter event handler
            $('#categoryFilter').change(function() {
                let categoryId = $(this).val();
                categoryFilter(categoryId);
            });
        </script>
    </x-slot:script>
</x-layout>
