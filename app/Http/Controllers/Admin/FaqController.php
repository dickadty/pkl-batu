<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FaqService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function __construct(
        protected FaqService $faqService
    ) {}

    /**
     * Menampilkan daftar FAQ.
     */
    public function index(): View
    {
        $faq = $this->faqService->getAllForAdmin();

        return view(
            'pages.admin.faq.index',
            compact('faq')
        );
    }

    /**
     * Menampilkan halaman tambah FAQ.
     */
    public function create(): View
    {
        return view('pages.admin.faq.create');
    }

    /**
     * Menyimpan FAQ baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateFaq($request);

        $faq = $this->faqService->create($validated);

        return redirect()
            ->route(
                'admin.faq.show',
                $faq->id
            )
            ->with(
                'success',
                'FAQ berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan detail FAQ.
     */
    public function show(int $id): View
    {
        $faq = $this->faqService->findById($id);

        return view(
            'pages.admin.faq.show',
            compact('faq')
        );
    }

    /**
     * Menampilkan halaman edit FAQ.
     */
    public function edit(int $id): View
    {
        $faq = $this->faqService->findById($id);

        return view(
            'pages.admin.faq.edit',
            compact('faq')
        );
    }

    /**
     * Memperbarui FAQ.
     */
    public function update(
        Request $request,
        int $id
    ): RedirectResponse {
        $validated = $this->validateFaq($request);

        $faq = $this->faqService->update(
            $id,
            $validated
        );

        return redirect()
            ->route(
                'admin.faq.show',
                $faq->id
            )
            ->with(
                'success',
                'FAQ berhasil diperbarui.'
            );
    }

    /**
     * Menghapus FAQ.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->faqService->delete($id);

        return redirect()
            ->route('admin.faq.index')
            ->with(
                'success',
                'FAQ berhasil dihapus.'
            );
    }

    /**
     * Validasi data FAQ.
     */
    private function validateFaq(Request $request): array
    {
        return $request->validate([
            'pertanyaan' => [
                'required',
                'string',
                'max:255',
            ],

            'jawaban' => [
                'required',
                'string',
                'max:3000',
            ],

            'status' => [
                'nullable',
                'in:1',
            ],
        ]);
    }
}
