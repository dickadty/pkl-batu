<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Authorization;
use App\Services\Admin\AuthService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Menampilkan halaman login admin lama.
     *
     * Login utama aplikasi menggunakan UnifiedLoginController.
     */
    public function showLogin(): View|RedirectResponse
    {
        if ($this->authService->isAdminLoggedIn()) {
            return redirect()
                ->route('admin.dashboard');
        }

        return view('pages.admin.auth.login');
    }

    /**
     * Proses login admin lama.
     */
    public function login(
        Request $request
    ): RedirectResponse {
        $credentials = $request->validate(
            [
                'username' => [
                    'required',
                    'string',
                ],
                'password' => [
                    'required',
                    'string',
                ],
            ],
            [
                'username.required' => 'Username wajib diisi.',
                'password.required' => 'Password wajib diisi.',
            ]
        );

        if (
            $this->authService->attemptLogin(
                $credentials,
                $request
            )
        ) {
            return redirect()
                ->route('admin.dashboard')
                ->with(
                    'success',
                    'Login berhasil.'
                );
        }

        return back()
            ->withErrors([
                'username' => 'Username atau password salah.',
            ])
            ->onlyInput('username');
    }

    /**
     * Menampilkan daftar akun administrator.
     */
    public function index(
        Request $request
    ): View {
        $admin = $this
            ->authService
            ->getLoggedAdmin();

        $this
            ->authService
            ->ensureAdminUtama($admin);

        $validated = $request->validate([
            'q' => [
                'nullable',
                'string',
                'max:255',
            ],
            'role' => [
                'nullable',
                'integer',
                'in:1,2',
            ],
            'ppid_pembantuid' => [
                'nullable',
                'integer',
                'exists:ppid_pembantu,id',
            ],
            'per_page' => [
                'nullable',
                'integer',
                'in:10,15,25,50,100',
            ],
        ]);

        $search = trim(
            (string) ($validated['q'] ?? '')
        );

        $role = isset($validated['role'])
            && $validated['role'] !== ''
            ? (int) $validated['role']
            : null;

        $ppidPembantuId =
            isset($validated['ppid_pembantuid'])
            && $validated['ppid_pembantuid'] !== ''
            ? (int) $validated['ppid_pembantuid']
            : null;

        $perPage = (int) (
            $validated['per_page'] ?? 15
        );

        $akunAdmin = Authorization::query()
            ->with([
                'ppidPembantu',
            ])
            ->when(
                $search !== '',
                function (
                    Builder $query
                ) use ($search): void {
                    $query->where(
                        function (
                            Builder $subQuery
                        ) use ($search): void {
                            $subQuery
                                ->where(
                                    'username',
                                    'like',
                                    '%' . $search . '%'
                                )
                                ->orWhere(
                                    'email',
                                    'like',
                                    '%' . $search . '%'
                                );
                        }
                    );
                }
            )
            ->when(
                $role !== null,
                fn(Builder $query): Builder => $query->where(
                    'role',
                    $role
                )
            )
            ->when(
                $ppidPembantuId !== null,
                fn(Builder $query): Builder => $query->where(
                    'ppid_pembantuid',
                    $ppidPembantuId
                )
            )
            ->orderBy('role')
            ->orderBy('username')
            ->paginate($perPage)
            ->withQueryString();

        $ppidPembantu = $this
            ->authService
            ->getPpidPembantuList();

        return view(
            'pages.admin.akun-admin.index',
            compact(
                'admin',
                'akunAdmin',
                'ppidPembantu'
            )
        );
    }

    /**
     * Menampilkan formulir tambah akun administrator.
     */
    public function showRegister(): View
    {
        $admin = $this
            ->authService
            ->getLoggedAdmin();

        $this
            ->authService
            ->ensureAdminUtama($admin);

        $ppidPembantu = $this
            ->authService
            ->getPpidPembantuList();

        return view(
            'pages.admin.auth.register',
            compact(
                'admin',
                'ppidPembantu'
            )
        );
    }

    /**
     * Menyimpan akun administrator baru.
     */
    public function register(
        Request $request
    ): RedirectResponse {
        $admin = $this
            ->authService
            ->getLoggedAdmin();

        $this
            ->authService
            ->ensureAdminUtama($admin);

        $validated = $request->validate(
            [
                'username' => [
                    'required',
                    'string',
                    'min:3',
                    'max:100',
                    'unique:authorization,username',
                ],
                'email' => [
                    'nullable',
                    'email',
                    'max:100',
                    'unique:authorization,email',
                ],
                'password' => [
                    'required',
                    'string',
                    'min:6',
                    'confirmed',
                ],
                'role' => [
                    'required',
                    'integer',
                    'in:1,2',
                ],
                'ppid_pembantuid' => [
                    'nullable',
                    'required_if:role,2',
                    'integer',
                    'exists:ppid_pembantu,id',
                ],
            ],
            [
                'username.required' => 'Username wajib diisi.',
                'username.min' => 'Username minimal 3 karakter.',
                'username.max' => 'Username maksimal 100 karakter.',
                'username.unique' => 'Username sudah digunakan.',

                'email.email' => 'Format email tidak valid.',
                'email.max' => 'Email maksimal 100 karakter.',
                'email.unique' => 'Email sudah digunakan.',

                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 6 karakter.',
                'password.confirmed' =>
                'Konfirmasi password tidak sesuai.',

                'role.required' => 'Role akun wajib dipilih.',
                'role.in' => 'Role akun tidak valid.',

                'ppid_pembantuid.required_if' =>
                'PPID Pembantu wajib dipilih untuk Admin Pembantu.',

                'ppid_pembantuid.exists' =>
                'PPID Pembantu yang dipilih tidak ditemukan.',
            ]
        );

        $this
            ->authService
            ->createAdminAccount(
                $admin,
                $validated
            );

        return redirect()
            ->route('admin.akun-admin.index')
            ->with(
                'success',
                'Akun admin berhasil dibuat.'
            );
    }

    /**
     * Menampilkan formulir edit akun administrator.
     */
    public function edit(
        int $id
    ): View {
        $admin = $this
            ->authService
            ->getLoggedAdmin();

        $this
            ->authService
            ->ensureAdminUtama($admin);

        $akunAdmin = Authorization::query()
            ->with([
                'ppidPembantu',
            ])
            ->findOrFail($id);

        $ppidPembantu = $this
            ->authService
            ->getPpidPembantuList();

        return view(
            'pages.admin.akun-admin.edit',
            compact(
                'admin',
                'akunAdmin',
                'ppidPembantu'
            )
        );
    }

    /**
     * Memperbarui akun administrator.
     */
    public function update(
        Request $request,
        int $id
    ): RedirectResponse {
        $admin = $this
            ->authService
            ->getLoggedAdmin();

        $this
            ->authService
            ->ensureAdminUtama($admin);

        $akunAdmin = Authorization::query()
            ->findOrFail($id);

        $validated = $request->validate(
            [
                'username' => [
                    'required',
                    'string',
                    'min:3',
                    'max:100',
                    Rule::unique(
                        'authorization',
                        'username'
                    )->ignore(
                        $akunAdmin->id,
                        'id'
                    ),
                ],
                'email' => [
                    'nullable',
                    'email',
                    'max:100',
                    Rule::unique(
                        'authorization',
                        'email'
                    )->ignore(
                        $akunAdmin->id,
                        'id'
                    ),
                ],
                'role' => [
                    'required',
                    'integer',
                    'in:1,2',
                ],
                'ppid_pembantuid' => [
                    'nullable',
                    'required_if:role,2',
                    'integer',
                    'exists:ppid_pembantu,id',
                ],
                'password' => [
                    'nullable',
                    'string',
                    'min:6',
                    'confirmed',
                ],
            ],
            [
                'username.required' => 'Username wajib diisi.',
                'username.min' => 'Username minimal 3 karakter.',
                'username.max' => 'Username maksimal 100 karakter.',
                'username.unique' => 'Username sudah digunakan.',

                'email.email' => 'Format email tidak valid.',
                'email.max' => 'Email maksimal 100 karakter.',
                'email.unique' => 'Email sudah digunakan.',

                'role.required' => 'Role akun wajib dipilih.',
                'role.in' => 'Role akun tidak valid.',

                'ppid_pembantuid.required_if' =>
                'PPID Pembantu wajib dipilih untuk Admin Pembantu.',

                'ppid_pembantuid.exists' =>
                'PPID Pembantu yang dipilih tidak ditemukan.',

                'password.min' =>
                'Password baru minimal 6 karakter.',

                'password.confirmed' =>
                'Konfirmasi password baru tidak sesuai.',
            ]
        );

        /*
         * Admin Utama terakhir tidak boleh diubah
         * menjadi Admin Pembantu.
         */
        if (
            $akunAdmin->isAdminUtama()
            && (int) $validated['role'] !== 1
        ) {
            $adminUtamaLainTersedia =
                Authorization::query()
                ->where('role', 1)
                ->where(
                    'id',
                    '!=',
                    $akunAdmin->id
                )
                ->exists();

            if (!$adminUtamaLainTersedia) {
                throw ValidationException::withMessages([
                    'role' =>
                    'Role tidak dapat diubah karena akun ini merupakan Admin Utama terakhir.',
                ]);
            }
        }

        DB::transaction(
            function () use (
                $akunAdmin,
                $validated
            ): void {
                $email = $validated['email'] ?? null;

                $akunAdmin->username = trim(
                    $validated['username']
                );

                $akunAdmin->email =
                    is_string($email)
                    && trim($email) !== ''
                    ? strtolower(trim($email))
                    : null;

                $akunAdmin->role =
                    (int) $validated['role'];

                $akunAdmin->ppid_pembantuid =
                    (int) $validated['role'] === 2
                    ? (int) $validated['ppid_pembantuid']
                    : null;

                /*
                 * Password hanya diganti jika diisi.
                 */
                if (
                    isset($validated['password'])
                    && trim(
                        (string) $validated['password']
                    ) !== ''
                ) {
                    $akunAdmin->password = Hash::make(
                        $validated['password']
                    );
                }

                $akunAdmin->save();
            }
        );

        return redirect()
            ->route('admin.akun-admin.index')
            ->with(
                'success',
                'Akun admin berhasil diperbarui.'
            );
    }

    /**
     * Menghapus akun administrator.
     */
    public function destroy(
        int $id
    ): RedirectResponse {
        $admin = $this
            ->authService
            ->getLoggedAdmin();

        $this
            ->authService
            ->ensureAdminUtama($admin);

        $akunAdmin = Authorization::query()
            ->findOrFail($id);

        /*
         * Akun yang sedang digunakan tidak boleh dihapus.
         */
        if (
            (string) $admin->id
            === (string) $akunAdmin->id
        ) {
            return redirect()
                ->route('admin.akun-admin.index')
                ->with(
                    'error',
                    'Akun yang sedang digunakan tidak dapat dihapus.'
                );
        }

        /*
         * Admin Utama terakhir tidak boleh dihapus.
         */
        if ($akunAdmin->isAdminUtama()) {
            $jumlahAdminUtama =
                Authorization::query()
                ->where('role', 1)
                ->count();

            if ($jumlahAdminUtama <= 1) {
                return redirect()
                    ->route('admin.akun-admin.index')
                    ->with(
                        'error',
                        'Admin Utama terakhir tidak dapat dihapus.'
                    );
            }
        }

        try {
            DB::transaction(
                function () use (
                    $akunAdmin
                ): void {
                    $akunAdmin->delete();
                }
            );
        } catch (QueryException $exception) {
            report($exception);

            return redirect()
                ->route('admin.akun-admin.index')
                ->with(
                    'error',
                    'Akun admin tidak dapat dihapus karena masih digunakan oleh data lain.'
                );
        }

        return redirect()
            ->route('admin.akun-admin.index')
            ->with(
                'success',
                'Akun admin berhasil dihapus.'
            );
    }

    /**
     * Logout admin lama.
     */
    public function logout(
        Request $request
    ): RedirectResponse {
        $this
            ->authService
            ->logout($request);

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Logout berhasil.'
            );
    }
}
