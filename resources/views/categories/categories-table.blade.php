<table class="border border-gray-300 w-full">
    <thead>
    <tr class="bg-gray-200">
        <th class="border p-2 md:px-4 md:py-2">SR No</th>
        <th class="border p-2 md:px-4 md:py-2">Name</th>
        @if(Auth::user()->role !== 'user')
            <th class="border p-2 md:px-4 md:py-2">Actions</th>
        @endif
    </tr>
    </thead>
    <tbody class="table-data">
    @foreach($categories as $key => $category)
        <tr class="hover:bg-gray-100" data-id="${category.id}">
            <td class="border p-2 md:px-4 md:py-2 text-center">{{ ++$key }}</td>
            <td class="border p-2 md:px-4 md:py-2"> {{ $category->name }}</td>
            @if(Auth::user()->role !== 'user')
                <td class="border p-2 md:px-4 md:py-2 text-center">
                    <button class="text-blue-600 hover:text-blue-800 view-button" data-id="{{ $category->id }}">View</button>
                    <button class="text-yellow-600 hover:text-yellow-800 mx-2 edit-button" data-id="{{ $category->id }}" data-name="{{ $category->name }}">Edit</button>
                    <form method="POST" class="inline-block delete-form" data-category-id="{{ $category->id }}">
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
