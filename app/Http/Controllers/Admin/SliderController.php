<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\SliderService;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function __construct(
        protected SliderService $sliderService
    ) {}

    public function index()
    {
        $slider = $this->sliderService->getAllForAdmin();

        return view('pages.admin.slider.index', compact('slider'));
    }

    public function create()
    {
        return view('pages.admin.slider.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateSlider($request, true);

        $this->sliderService->create(
            $validated,
            $request->file('banner')
        );

        return redirect()
            ->route('admin.slider.index')
            ->with('success', 'Slider berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $slider = $this->sliderService->findById((int) $id);

        return view('pages.admin.slider.edit', compact('slider'));
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validateSlider($request);

        $slider = $this->sliderService->update(
            (int) $id,
            $validated,
            $request->file('banner')
        );

        return redirect()
            ->route('admin.slider.edit', $slider->id)
            ->with('success', 'Slider berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->sliderService->delete((int) $id);

        return redirect()
            ->route('admin.slider.index')
            ->with('success', 'Slider berhasil dihapus.');
    }

    private function validateSlider(Request $request, bool $bannerRequired = false): array
    {
        return $request->validate([
            'title' => 'required|string|max:150',
            'banner' => ($bannerRequired ? 'required' : 'nullable') . '|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);
    }
}
