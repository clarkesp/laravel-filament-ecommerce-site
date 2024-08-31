<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <section class="overflow-hidden bg-white py-11 font-poppins dark:bg-gray-800">
        <div class="max-w-6xl px-4 py-4 mx-auto lg:py-8 md:px-6">
            <div class="flex flex-wrap -mx-4">
                <div class="w-full mb-8 md:w-1/2 md:mb-0" x-data="{ mainImage: '{{ url('storage', $product->images[0]) }}' }">
                    <div class="sticky top-0 z-50 overflow-hidden">
                        <div class="relative mb-6 lg:mb-10" style="width: 500px; height: 500px;">
                            <img x-bind:src="mainImage" alt="{{ $product->name }}" class="object-cover w-full h-full">
                        </div>
                        <!-- Modal Section -->
{{--                        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75"--}}
{{--                             @click.away="showModal = false">--}}
{{--                            <div class="relative">--}}
{{--                                <img x-bind:src="mainImage" alt="{{ $product->name }}" class="object-contain max-w-full max-h-full">--}}
{{--                                <button @click="showModal = false" class="absolute top-2 right-2 text-white text-2xl">&times;</button>--}}
{{--                            </div>--}}
{{--                        </div>--}}

                        <div class="flex flex-wrap">
                            @foreach ($product->images as $image)
                                <div class="w-1/2 p-2 sm:w-1/4" @mouseover="mainImage='{{ url('storage', $image) }}'">
                                    <img src="{{ url('storage', $image) }}" alt="{{ $product->name }}"
                                         class="object-cover w-full h-full cursor-pointer hover:border hover:border-blue-500"
                                         style="width: 150px; height: 150px;">
                                </div>
                            @endforeach


                        </div>
                    </div>
                </div>
                <div class="w-full px-4 md:w-1/2">
                    <div class="lg:pl-20">
                        <div class="mb-8">

{{--  FOR MARKDOWN EDITOR  <div class="mb-8" [&>ul]:list-disc [&>ul]:ml">--}}

                            <h2 class="max-w-xl mb-6 text-2xl font-bold dark:text-gray-400 md:text-4xl">
                                {{ $product->name }}
                            </h2>
                            <p class="inline-block mb-6 text-4xl font-bold text-gray-700 dark:text-gray-400">
                                <span>{{ Number::currency($product->price), 'US' }}</span>
                                <span class="text-base font-normal text-gray-500 line-through dark:text-gray-400">
                                    {{ $product->price }}</span>
                            </p>
                            <p class="max-w-md text-gray-700 dark:text-gray-400" x-data="{ isExpanded: false }">
                                <span x-show="!isExpanded">{{ \Illuminate\Support\Str::words($product->description, 100) }}...</span>
                                <span x-show="isExpanded">{{ $product->description }}</span>

{{-- FOR MARKDOWN EDITOR  TO HTML     {!! Str::markdown($product->description) !!}    --}}

                                <button @click="isExpanded = !isExpanded" class="text-read-more hover:text-read-more-hover">
                                    <span x-show="!isExpanded">Read More</span>
                                    <span x-show="isExpanded">Read Less</span>
                                </button>
                            </p>


                        </div>
                        <div class="w-32 mb-8">
                            <label for="" class="w-full pb-1 text-xl font-semibold text-gray-700 border-b border-blue-300 dark:border-gray-600 dark:text-gray-400">Quantity</label>
                            <div class="relative flex flex-row w-full h-10 mt-6 bg-transparent rounded-lg">
                                <button class="w-20 h-full text-gray-600 bg-gray-300 rounded-l outline-none cursor-pointer dark:hover:bg-gray-700 dark:text-gray-400 hover:text-gray-700 dark:bg-gray-900 hover:bg-gray-400">
                                    <span class="m-auto text-2xl font-thin">-</span>
                                </button>
                                <input type="number" readonly class="flex items-center w-full font-semibold text-center text-gray-700 placeholder-gray-700 bg-gray-300 outline-none dark:text-gray-400 dark:placeholder-gray-400 dark:bg-gray-900 focus:outline-none text-md hover:text-black" placeholder="1">
                                <button class="w-20 h-full text-gray-600 bg-gray-300 rounded-r outline-none cursor-pointer dark:hover:bg-gray-700 dark:text-gray-400 dark:bg-gray-900 hover:text-gray-700 hover:bg-gray-400">
                                    <span class="m-auto text-2xl font-thin">+</span>
                                </button>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-4">
                            <button class="w-full p-4 bg-blue-500 rounded-md lg:w-2/5 dark:text-gray-200 text-gray-50 hover:bg-blue-600 dark:bg-blue-500 dark:hover:bg-blue-700">
                                Add to cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
