    <nav>
        <div class="w-100">
          <div class="bg-slate-200 shadow">
            <div class="container mx-auto px-4 py-1">
              <div class="flex items-center justify-between py-4">
                <img class="h-10 w-10" src="{{ asset('images/rba.png') }}" alt="Flick Logo">

                <div class="hidden sm:flex sm:items-center">
                @if(!Auth::user())
                  <x-nav-link href="/" :active="request()->is('/')">Home</x-nav-link>
                @else
                  <x-nav-link href="/" :active="request()->is('/')">Home</x-nav-link>
                  @if(Auth::user()->role == 'admin')
                  <x-nav-link href="/categories" :active="request()->is('categories')">Categories</x-nav-link>
                  <x-nav-link href="{{ route('admin.posts') }}" :active="request()->is('posts')">Posts</x-nav-link>
                  @else
                  <x-nav-link href="{{ route('user.categories') }}" :active="request()->is('categories')">Categories</x-nav-link>
                  <x-nav-link href="{{ route('posts.index')}}" :active="request()->is('posts')">Posts</x-nav-link>
                  @endif
                @endif
                </div>

                <div class="hidden sm:flex sm:items-center">
                  @if(!Auth::user())
                    <a href="{{ route('signin') }}" class="mr-4 text-sm font-semibold text-gray-800 hover:text-purple-600">Sign in</a>
                    <a href="{{ route('signup') }}" class="rounded-lg border px-4 py-2 text-sm font-semibold text-gray-800 hover:border-purple-600 hover:text-purple-600">Sign up</a>
                  @else
                    {{ Auth::user()->role}}
                    <a href="{{ route('signout') }}" class="mr-4 text-sm font-semibold text-gray-800 hover:text-purple-600">Sign out</a>
                  @endif
                </div>

                <!-- Toggle Button SVG -->
                <div class="cursor-pointer sm:hidden" id="menu-toggle">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-900" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M12.9499909,17 C12.7183558,18.1411202 11.709479,19 10.5,19 C9.29052104,19 8.28164422,18.1411202 8.05000906,17 L3.5,17 C3.22385763,17 3,16.7761424 3,16.5 C3,16.2238576 3.22385763,16 3.5,16 L8.05000906,16 C8.28164422,14.8588798 9.29052104,14 10.5,14 C11.709479,14 12.7183558,14.8588798 12.9499909,16 L20.5,16 C20.7761424,16 21,16.2238576 21,16.5 C21,16.7761424 20.7761424,17 20.5,17 L12.9499909,17 Z M18.9499909,12 C18.7183558,13.1411202 17.709479,14 16.5,14 C15.290521,14 14.2816442,13.1411202 14.0500091,12 L3.5,12 C3.22385763,12 3,11.7761424 3,11.5 C3,11.2238576 3.22385763,11 3.5,11 L14.0500091,11 C14.2816442,9.85887984 15.290521,9 16.5,9 C17.709479,9 18.7183558,9.85887984 18.9499909,11 L20.5,11 C20.7761424,11 21,11.2238576 21,11.5 C21,11.7761424 20.7761424,12 20.5,12 L18.9499909,12 Z M9.94999094,7 C9.71835578,8.14112016 8.70947896,9 7.5,9 C6.29052104,9 5.28164422,8.14112016 5.05000906,7 L3.5,7 C3.22385763,7 3,6.77614237 3,6.5 C3,6.22385763 3.22385763,6 3.5,6 L5.05000906,6 C5.28164422,4.85887984 6.29052104,4 7.5,4 C8.70947896,4 9.71835578,4.85887984 9.94999094,6 L20.5,6 C20.7761424,6 21,6.22385763 21,6.5 C21,6.77614237 20.7761424,7 20.5,7 L9.94999094,7 Z M7.5,8 C8.32842712,8 9,7.32842712 9,6.5 C9,5.67157288 8.32842712,5 7.5,5 C6.67157288,5 6,5.67157288 6,6.5 C6,7.32842712 6.67157288,8 7.5,8 Z M16.5,13 C17.3284271,13 18,12.3284271 18,11.5 C18,10.6715729 17.3284271,10 16.5,10 C15.6715729,10 15,10.6715729 15,11.5 C15,12.3284271 15.6715729,13 16.5,13 Z M10.5,18 C11.3284271,18 12,17.3284271 12,16.5 C12,15.6715729 11.3284271,15 10.5,15 C9.67157288,15 9,15.6715729 9,16.5 C9,17.3284271 9.67157288,18 10.5,18 Z" />
                  </svg>
                </div>
              </div>

              <!-- Mobile view code -->
              <div class="block mb-2 p-2 sm:hidden rounded-xl" id="mobile-menu" style="display: none;">
                <div class="flex flex-col m-4">
                @if(!Auth::user())
                  <x-nav-link href="/" :active="request()->is('/')" style="margin-bottom: .5rem;">Home</x-nav-link>
                @else
                  <x-nav-link href="/" :active="request()->is('/')">Home</x-nav-link>
                  @if(Auth::user()->role == 'admin')
                  <x-nav-link href="/categories" :active="request()->is('categories')" style="margin-bottom: .5rem;">Categories</x-nav-link>
                  <x-nav-link href="{{ route('admin.posts') }}" :active="request()->is('posts')" style="margin-bottom: .5rem;">Posts</x-nav-link>
                  @else
                  <x-nav-link href="{{ route('user.categories') }}" :active="request()->is('categories')" style="margin-bottom: .5rem;">Categories</x-nav-link>
                  <x-nav-link href="/posts" :active="request()->is('posts')" style="margin-bottom: .5rem;">Posts</x-nav-link>
                  @endif
                  <div class="flex items-center justify-between border-t-2 border-gray-300 pt-2">
                    @if(!Auth::user())
                      <a href="{{ route('signin') }}" class="mr-4 text-sm font-semibold text-gray-800 hover:text-purple-600">Sign in</a>
                      <a href="{{ route('signup') }}" class="rounded-lg border px-4 py-2 text-sm font-semibold text-gray-800 hover:border-purple-600 hover:text-purple-600">Sign up</a>
                    @else
                      <a href="{{ route('signout') }}" class="mr-4 text-sm font-semibold text-gray-800 hover:text-purple-600">Sign out</a>
                    @endif
                @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </nav>

    <script>
        const mobileMenu = document.getElementById('mobile-menu');

        // Toggle mobile menu
        document.getElementById('menu-toggle').addEventListener('click', function() {
          if (mobileMenu.style.display === 'none' || mobileMenu.style.display === '') {
            mobileMenu.style.display = 'block';
          } else {
            mobileMenu.style.display = 'none';
          }
        });

        // Close the mobile menu on desktop screen resize
        window.addEventListener('resize', function() {
        if (window.innerWidth >= 640) {
          mobileMenu.style.display = 'none';
        }
        });
    </script>
