<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'DM+Sans', sans-serif; }
    </style>
    <script src="https://kit.fontawesome.com/e727123d3e.js" crossorigin="anonymous"></script>
    <!-- Summernote Lite CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
</head>
<body class="flex h-screen bg-slate-100 font-sans">
<!-- Sidebar Overlay (for mobile) -->
<div id="sidebarOverlay" class="fixed inset-0 z-40 bg-black opacity-50 lg:hidden hidden"></div>

<!-- Sidebar (the correct dark one) -->
@include('admin.partials.sidebar')

<!-- Main content area -->
<div class="flex-1 flex flex-col overflow-hidden">
    <!-- Header -->
    @include('admin.partials.header')

    <!-- Main content -->
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50/50">
        <div class="container mx-auto px-6 py-8">
            @yield('content')
        </div>
    </main>
</div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

    Swal.mixin({
        heightAuto: false
    });
    const sidebar = document.getElementById('sidebar');
    const openBtn = document.getElementById('openSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const notifBtn = document.getElementById('toggleNotifications');
    const notifPanel = document.getElementById('notificationsPanel');
    const dropdownBtn = document.getElementById('toggleDropdown');
    const dropdownMenu = document.getElementById('dropdownMenu');

    // Your existing sidebar/notifications/dropdown code (unchanged)
    if (openBtn && sidebar && overlay) {
        openBtn.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        });
    }
    if (overlay) {
        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });
    }
    if (notifBtn && notifPanel) {
        notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            notifPanel.classList.toggle('hidden');
        });
    }
    if (dropdownBtn && dropdownMenu) {
        dropdownBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle('hidden');
        });
    }
    document.addEventListener('click', () => {
        if (notifPanel && !notifPanel.classList.contains('hidden')) {
            notifPanel.classList.add('hidden');
        }
        if (dropdownMenu && !dropdownMenu.classList.contains('hidden')) {
            dropdownMenu.classList.add('hidden');
        }
    });
</script>

@stack('js')
</body>
</html>
