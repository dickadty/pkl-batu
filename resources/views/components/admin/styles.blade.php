<style>
    :root{
    --sidebar-width: 300px;
    --topbar-height: 60px;
}

    body {
        background: #e9e9e9;
        font-family: font-family: 'Open Sans', sans-serif;

    }

    .admin-wrapper {
        display: flex;
        min-height: 100vh;
    }

    

    .brand-panel {
        background: linear-gradient(135deg, #f44336, #d81b60, #ff9800);
        color: white;
        padding: 18px 16px;
        min-height: 145px;
    }

    .brand-title {
        font-size: 27px;
        font-weight: bold;
        margin-top: 8px;
    }

    .brand-subtitle {
        font-size: 11px;
        line-height: 1.2;
        font-weight: normal;
    }

    .user-panel {
        font-size: 14px;
        margin-top: 20px;
    }

    .nav-label {
        background: #eeeeee;
        color: #333;
        font-size: 13px;
        font-weight: bolder;
        padding: 8px 16px;
    }

    .sidebar {
        width: var(--sidebar-width);
        top: 70px;
        background: #ffffff;
        border-right: 1px solid #ddd;
        position: fixed;
        height:calc(100vh - var(--topbar-height));
        bottom: 0;
        left: 0;
        overflow-y: auto;
    }

    .sidebar-menu {
        padding: 12px 0 90px;
    }

    .sidebar-menu a {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #222;
        text-decoration: none;
        padding: 12px 18px;
        font-weight: bolder;
        font-size: 17px;
    }

    .sidebar-menu a:hover,
    .sidebar-menu a.active {
        color: #f92828;
        background: #fafafa;
    }

    .sidebar-menu .submenu {
        padding-left: 44px;
    }

    .sidebar-menu .submenu a {
        font-weight: 600;
        font-size: 15px;
        padding: 8px 12px;
    }

    .sidebar-footer {
        position: absolute;
        bottom: 0;
        width: 100%;
        padding: 14px;
        font-size: 12px;
        color: #444444;
        background: #fff;
        border-top: 1px solid #eee;
    }

    .main {
        margin-left: var(--sidebar-width);
        width: calc(100% - var(--sidebar-width));
        margin-top: var(--topbar-height);
        min-height: calc(100vh - var(--topbar-height));
    }

    .topbar {
        height: 77px;
        background: #ff1f1f;
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 24px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        position:fixed;
        top:0;
        left:0;
        right:0;
        z-index:1000;
    }

    .topbar-title {
        font-weight: bold;
        font-size: 20px;
    }

    .topbar-title span {
        color: #ffff00;
    }

    .content {
        padding: 28px;
    }

    .breadcrumb-custom {
        color: #666;
        font-size: 19px;
        margin-bottom: 14px;
        text-transform: uppercase;
    }

    .panel-card {
        background: white;
        border-radius: 2px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.12);
        overflow: hidden;
    }

    .panel-card-header {
        padding: 18px 20px;
        font-size: 20px;
        font-weight: 500;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .section-title {
        background: #08b8c8;
        color: white;
        text-align: center;
        font-weight: bold;
        padding: 14px;
    }

    .form-material {
        padding: 26px 22px;
    }

    .form-material label {
        font-weight: bold;
        font-size: 17px;
        color: #444;
        margin-bottom: 6px;
    }

    .form-material .form-control,
    .form-material .form-select {
        border: none;
        border-bottom: 1px solid #ddd;
        border-radius: 0;
        box-shadow: none;
        padding-left: 0;
        font-size: 15px;
    }

    .form-material .form-control:focus,
    .form-material .form-select:focus {
        border-bottom: 2px solid #08b8c8;
        box-shadow: none;
    }

    .btn-red {
        background: #f92828;
        color: white;
        border: none;
    }

    .btn-red:hover {
        background: #d81f1f;
        color: white;
    }
    
</style>
