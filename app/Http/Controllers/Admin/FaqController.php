<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FaqService;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function __construct(
        protected FaqService $faqService
    ) {}

    public function index()
    {
        $faq = $this->faqService->getAllForAdmin();

        return view('pages.admin.faq.index', compact('faq'));
    }

    public function create()
    {
        return view('pages.admin.faq.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateFaq($request);

        $this->faqService->create($validated);

        return redirect()
            ->route('admin.faq.index')
            ->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $faq = $this->faqService->findById((int) $id);

        return view('pages.admin.faq.edit', compact('faq'));
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validateFaq($request);

        $faq = $this->faqService->update((int) $id, $validated);

        return redirect()
            ->route('admin.faq.edit', $faq->id)
            ->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->faqService->delete((int) $id);

        return redirect()
            ->route('admin.faq.index')
            ->with('success', 'FAQ berhasil dihapus.');
    }

    private function validateFaq(Request $request): array
    {
        return $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'jawaban' => 'required|string|max:3000',
            'status' => 'nullable|in:1',
        ]);
    }
}
