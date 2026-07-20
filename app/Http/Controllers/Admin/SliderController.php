<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\SliderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SliderController extends Controller
{
    public function __construct(
        protected SliderService $sliderService
    ) {}

    /**
     * Menampilkan daftar slider.
     */
    public function index(): View
    {
        $slider = $this->sliderService->getAllForAdmin();

        return view(
            'pages.admin.slider.index',
            compact('slider')
        );
    }

    /**
     * Menampilkan halaman tambah slider.
     */
    public function create(): View
    {
        return view('pages.admin.slider.create');
    }

    /**
     * Menyimpan slider baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateSlider(
            $request,
            true
        );

        $slider = $this->sliderService->create(
            $validated,
            $request->file('banner')
        );

        return redirect()
            ->route(
                'admin.slider.show',
                $slider->id
            )
            ->with(
                'success',
                'Slider berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan detail slider.
     */
    public function show(int $id): View
    {
        $slider = $this->sliderService->findById($id);

        return view(
            'pages.admin.slider.show',
            compact('slider')
        );
    }

    /**
     * Menampilkan halaman edit slider.
     */
    public function edit(int $id): View
    {
        $slider = $this->sliderService->findById($id);

        return view(
            'pages.admin.slider.edit',
            compact('slider')
        );
    }

    /**
     * Memperbarui slider.
     */
    public function update(
        Request $request,
        int $id
    ): RedirectResponse {
        $validated = $this->validateSlider($request);

        $slider = $this->sliderService->update(
            $id,
            $validated,
            $request->file('banner')
        );

        return redirect()
            ->route(
                'admin.slider.show',
                $slider->id
            )
            ->with(
                'success',
                'Slider berhasil diperbarui.'
            );
    }

    /**
     * Menghapus slider.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->sliderService->delete($id);

        return redirect()
            ->route('admin.slider.index')
            ->with(
                'success',
                'Slider berhasil dihapus.'
            );
    }

    /**
     * Validasi data slider.
     */
    private function validateSlider(
        Request $request,
        bool $bannerRequired = false
    ): array {
        return $request->validate([
            'title' => [
                'required',
                'string',
                'max:150',
            ],

            'banner' => [
                $bannerRequired
                    ? 'required'
                    : 'nullable',

                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:3072',
            ],
        ]);
    }
}
