<div class="rounded-3xl border bg-white p-6">


    <h3 class="text-xl font-bold text-green-950">

        Halaman Terpopuler

    </h3>



    <div class="mt-5 space-y-4">


        @forelse($popularPages as $page)
            <div class="flex justify-between gap-4">


                <p class="truncate text-sm text-green-900">

                    {{ $page->url }}

                </p>



                <span class="font-bold text-emerald-600">

                    {{ number_format($page->total) }}

                </span>



            </div>


        @empty


            <p class="text-sm text-gray-500">

                Belum ada data.

            </p>
        @endforelse



    </div>



</div>
