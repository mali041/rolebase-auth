<table class="border border-gray-300 w-full">
    <thead>
    <tr class="bg-gray-200">
        <th class="border p-2 md:px-4 md:py-2">SR No</th>
        <th class="border p-2 md:px-4 md:py-2">Title</th>
        <th class="border p-2 md:px-4 md:py-2">Description</th>
        <th class="border p-2 md:px-4 md:py-2">Author</th>
        <th class="border p-2 md:px-4 md:py-2">Categories</th>
        <th class="border p-2 md:px-4 md:py-2">Images</th>
        @if(Auth::user()->role !== 'admin')
            <th class="border p-2 md:px-4 md:py-2">Actions</th>
        @endif
    </tr>
    </thead>
    <tbody class="table-data">
    @foreach($posts as $key => $post)
        <tr class="hover:bg-gray-100" data-id="${post.id}">
            <td class="border p-2 md:px-4 md:py-2 text-center srno">{{ ++$key }}</td>
            <td class="border p-2 md:px-4 md:py-2"> {{ $post->title }}  </td>
            <td class="border p-2 md:px-4 md:py-2"> {{ $post->description }} </td>
            <td class="border p-2 md:px-4 md:py-2"> {{ $post->user->name }} </td>
            <td class="border p-2 md:px-4 md:py-2 {{ count($post->categories) > 0 ? 'md:flex-row md:h-full' : '' }}">
                @foreach($post->categories as $category)
                    <p>{{ $category->name ??  '' }}</p>
                @endforeach
            </td>
            <td class="border p-2 md:px-4 md:py-2 {{ count($post->images) > 0 ? 'md:flex md:h-full' : '' }}">
                @foreach($post->images as $image)
                    <img src="{{ asset(empty($image->name) ?  null : asset('posts/images/'.$image->name)) }}" alt="Post Image" class="rounded w-16 h-16 mb-2 md:mr-2" />
                @endforeach
            </td>
            @if(Auth::user()->role !== 'admin')
                <td class="border p-2 md:px-4 md:py-2 text-center">
                    <button class="text-blue-600 hover:text-blue-800 view-button" data-id="{{ $post->id }}">View</button>
                    <button class="text-yellow-600 hover:text-yellow-800 edit-button" data-id="{{ $post->id }}">Edit</button>
                    <form method="POST" class="inline-block delete-form" data-post-id=" {{ $post->id }} ">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="text-red-600 hover:text-red-800 delete-button">Delete</button>
                    </form>
                </td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
