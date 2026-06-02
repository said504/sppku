<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'SPP System')); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-blue: #1E3A5F;
            --accent-blue: #3B82F6;
            --orange: #F97316;
            --success: #10B981;
            --warning: #F59E0B;
            --bg-color: #F0F4FF;
            --card-bg: rgba(255, 255, 255, 0.85);
            --border-color: rgba(0, 0, 0, 0.05);
            --sidebar-width: 260px;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-color);
            margin: 0;
            padding: 0;
            color: #333;
            display: flex;
            min-height: 100vh;
        }
        
        /* Layout Structure */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            border-right: 1px solid var(--border-color);
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 100;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
            width: calc(100% - var(--sidebar-width));
        }

        /* Sidebar Elements */
        .sidebar-header {
            padding: 1.5rem;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-blue);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .nav-list {
            list-style: none;
            padding: 1rem;
            margin: 0;
            flex: 1;
        }
        .nav-item { margin-bottom: 0.5rem; }
        .nav-link {
            display: block;
            padding: 0.75rem 1rem;
            color: #666;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .nav-link:hover, .nav-link.active {
            background: rgba(59, 130, 246, 0.1);
            color: var(--accent-blue);
        }
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid var(--border-color);
        }

        /* Topbar & Hamburger */
        .topbar {
            display: none;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: white;
            border-bottom: 1px solid var(--border-color);
        }
        .hamburger {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--primary-blue);
        }

        /* UI Components */
        .glass-card {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            margin-bottom: 1.5rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            text-align: center;
            border-top: 4px solid var(--accent-blue);
        }
        .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            margin-top: 0.5rem;
        }
        .btn {
            background: var(--accent-blue);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
        }
        .btn:hover { box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4); transform: translateY(-2px); }
        .btn-orange { background: var(--orange); }
        .btn-orange:hover { box-shadow: 0 4px 12px rgba(249, 115, 22, 0.4); }
        .btn-danger { background: #dc2626; }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        th { font-weight: 600; color: var(--primary-blue); }
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .badge-tunggakan { background: #fee2e2; color: #dc2626; }
        .badge-lunas { background: #d1fae5; color: #059669; }
        .badge-menunggu { background: #fef3c7; color: #d97706; }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
                box-shadow: 10px 0 30px rgba(0,0,0,0.1);
            }
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 1rem;
            }
            .topbar {
                display: flex;
            }
            .close-sidebar {
                display: block !important;
            }
        }
        .close-sidebar {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <!-- Mobile Topbar -->
    <div class="topbar">
        <div style="font-weight: 800; color: var(--primary-blue); font-size: 1.25rem;">SPP System</div>
        <button class="hamburger" onclick="toggleSidebar()">☰</button>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            SPP System
            <button class="close-sidebar" onclick="toggleSidebar()">✕</button>
        </div>
        
        <ul class="nav-list">
            <?php if(auth()->user()->role === 'admin'): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>" style="display: flex; align-items: center; gap: 0.75rem;">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.students')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.students') ? 'active' : ''); ?>" style="display: flex; align-items: center; gap: 0.75rem;">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Data Siswa
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.tunggakan')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.tunggakan') ? 'active' : ''); ?>" style="display: flex; align-items: center; gap: 0.75rem;">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Tunggakan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.rules')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.rules') ? 'active' : ''); ?>" style="display: flex; align-items: center; gap: 0.75rem;">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Rule Engine
                    </a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('parent.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('parent.dashboard') ? 'active' : ''); ?>" style="display: flex; align-items: center; gap: 0.75rem;">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('parent.invoices')); ?>" class="nav-link <?php echo e(request()->routeIs('parent.invoices') ? 'active' : ''); ?>" style="display: flex; align-items: center; gap: 0.75rem;">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Tagihan Saya
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('parent.history')); ?>" class="nav-link <?php echo e(request()->routeIs('parent.history') ? 'active' : ''); ?>" style="display: flex; align-items: center; gap: 0.75rem;">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Riwayat
                    </a>
                </li>
            <?php endif; ?>
        </ul>

        <div class="sidebar-footer">
            <div style="margin-bottom: 1rem; font-size: 0.9rem;">
                Login sebagai:<br>
                <strong><?php echo e(auth()->user()->name); ?></strong>
            </div>
            <form action="<?php echo e(route('logout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-danger" style="width: 100%; padding: 0.5rem;">Logout</button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\Mytz data enginer\SPP_SYSTEM\resources\views/layouts/app.blade.php ENDPATH**/ ?>