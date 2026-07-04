<div class="topbar">
    <div class="topbar-title">
        PANEL <span>ADMINISTRASI</span>
    </div>

    <div class="d-flex align-items-center gap-4 fs-5">
        <i class="bi bi-search"></i>
        <i class="bi bi-bell-fill"></i>

        <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit"
                    class="btn p-0 border-0 text-white fs-5"
                    onclick="return confirm('Logout dari panel admin?')">
                <i class="bi bi-power"></i>
            </button>
        </form>
    </div>
</div>