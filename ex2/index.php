<?php
// Bắt đầu session để lưu trữ vai trò của người dùng
session_start();

// --- BÀI TẬP 1: Định nghĩa vai trò và quyền ---
// (Chúng ta sẽ sử dụng cái này cho Bài tập 3)
$roles = [
    'admin' => ['view_user', 'create_user', 'edit_user', 'delete_user', 'edit_own_profile'],
    'user'  => ['view_user', 'edit_own_profile'],
    'guest' => ['view_user']
];

// --- BÀI TẬP 3: Hàm kiểm tra quyền dựa trên SESSION ---
/**
 * Kiểm tra xem người dùng hiện tại (trong session) có quyền hay không.
 * @param string $required_permission Quyền cần kiểm tra.
 * @return bool Trả về true nếu có quyền, false nếu không.
 */
function checkAccess($required_permission) {
    global $roles;
    
    // Lấy vai trò từ session, nếu không có thì mặc định là 'guest'
    $user_role = $_SESSION['user_role'] ?? 'guest';
    
    // Kiểm tra xem vai trò có tồn tại và có quyền đó không
    if (isset($roles[$user_role])) {
        return in_array($required_permission, $roles[$user_role]);
    }
    
    return false;
}

// --- BÀI TẬP 6: Hàm yêu cầu quyền truy cập ---
/**
 * Yêu cầu một quyền cụ thể. Nếu không có, chuyển hướng đến trang 'unauthorized.php'.
 * Hàm này PHẢI được gọi trước khi bất kỳ mã HTML nào được gửi đi.
 * @param string $permission Quyền yêu cầu.
 */
function requirePermission($permission) {
    if (!checkAccess($permission)) {
        header("Location: unauthorized.php"); // Chuyển hướng
        exit(); // Dừng thực thi script
    }
}

// --- LOGIC XỬ LÝ (Đăng nhập / Đăng xuất / Điều hướng) ---

// 1. Xử lý "Đăng nhập"
if (isset($_GET['login_as'])) {
    $role = $_GET['login_as'];
    if (array_key_exists($role, $roles)) {
        $_SESSION['user_role'] = $role;
    }
    // Chuyển hướng về trang chủ sau khi đăng nhập
    header("Location: index.php");
    exit();
}

// 2. Xử lý "Đăng xuất"
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: index.php");
    exit();
}

// 3. Lấy trang hiện tại
$page = $_GET['page'] ?? 'home';

// --- BẢO VỆ TRANG (Áp dụng Bài tập 6) ---
// Kiểm tra quyền TRƯỚC KHI hiển thị bất kỳ nội dung nào
switch ($page) {
    case 'admin_panel':
        // Chỉ admin (có quyền 'delete_user') mới vào được
        requirePermission('delete_user');
        break;
    case 'profile':
        // Cả admin và user (có quyền 'edit_own_profile') đều vào được
        requirePermission('edit_own_profile');
        break;
    case 'users':
        // Tất cả các vai trò (admin, user, guest) đều có quyền 'view_user'
        requirePermission('view_user');
        break;
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Phân quyền PHP</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; }
        .container { max-width: 800px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        nav { background: #333; padding: 10px; border-radius: 5px; }
        nav ul { list-style: none; padding: 0; margin: 0; display: flex; }
        nav ul li { margin-right: 15px; }
        nav ul li a { color: white; text-decoration: none; font-weight: bold; }
        nav ul li a:hover { text-decoration: underline; }
        .login-info { margin: 15px 0; padding: 10px; background: #eee; border-radius: 5px; }
        .content { margin-top: 20px; }
        .login-links a { margin-right: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Demo Phân quyền vai trò (RBAC)</h1>

        <div class="login-info">
            <!-- Hiển thị vai trò hiện tại -->
            <p><strong>Vai trò hiện tại của bạn:</strong> <?php echo htmlspecialchars($_SESSION['user_role'] ?? 'guest'); ?></p>
            
            <!-- Link "Đăng nhập" -->
            <div class="login-links">
                <span>Đăng nhập với tư cách:</span>
                <a href="?login_as=admin">Admin</a> |
                <a href="?login_as=user">User</a> |
                <a href="?login_as=guest">Guest</a>
            </div>
            
            <!-- Link "Đăng xuất" -->
            <?php if (isset($_SESSION['user_role'])): ?>
                <p><a href="?action=logout">Đăng xuất</a></p>
            <?php endif; ?>
        </div>

        <!-- --- BÀI TẬP 7: Menu động dựa trên vai trò --- -->
        <nav>
            <ul>
                <li><a href="?page=home">Trang chủ</a></li>
                
                <?php if (checkAccess('view_user')): ?>
                    <li><a href="?page=users">Xem Người dùng</a></li>
                <?php endif; ?>
                
                <?php if (checkAccess('edit_own_profile')): ?>
                    <li><a href="?page=profile">Hồ sơ của tôi</a></li>
                <?php endif; ?>
                
                <?php if (checkAccess('delete_user')): ?>
                    <li><a href="?page=admin_panel">Bảng điều khiển Admin</a></li>
                <?php endif; ?>
            </ul>
        </nav>

        <!-- Hiển thị nội dung trang -->
        <div class="content">
            <?php
            switch ($page) {
                case 'users':
                    echo "<h2>Trang Người dùng</h2><p>Nội dung này hiển thị cho bất kỳ ai có quyền 'view_user' (admin, user, guest).</p>";
                    break;
                case 'profile':
                    echo "<h2>Hồ sơ của tôi</h2><p>Nội dung này hiển thị cho bất kỳ ai có quyền 'edit_own_profile' (admin, user).</p>";
                    break;
                case 'admin_panel':
                    echo "<h2>Bảng điều khiển Admin</h2><p>Nội dung này CHỈ hiển thị cho ai có quyền 'delete_user' (chỉ admin).</p><button>Xóa Người dùng (Demo)</button>";
                    break;
                default:
                    echo "<h2>Trang chủ</h2><p>Chào mừng đến với trang demo.</p>";
                    break;
            }
            ?>
        </div>
    </div>
</body>
</html>