<?php
// File này được include TỪ index.php
// $conn đã có sẵn
// Quyền 'manage_roles' đã được kiểm tra bởi index.php

$message = ''; // Thông báo thành công/lỗi

// --- BƯỚC 1: Xử lý FORM (POST) nếu người dùng nhấn LƯU ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_permissions'])) {
    
    $role_id_to_update = (int)$_POST['role_id'];
    // Lấy danh sách permission_id mà người dùng đã tick
    $selected_permissions = $_POST['permissions'] ?? []; // Mảng các permission_id

    // Bắt đầu transaction
    mysqli_begin_transaction($conn);

    try {
        // 1. Xóa tất cả quyền HIỆN TẠI của vai trò này (không tính kế thừa)
        $sql_delete = "DELETE FROM role_permissions WHERE role_id = ?";
        $stmt_delete = mysqli_prepare($conn, $sql_delete);
        mysqli_stmt_bind_param($stmt_delete, "i", $role_id_to_update);
        mysqli_stmt_execute($stmt_delete);
        mysqli_stmt_close($stmt_delete);

        // 2. Thêm lại các quyền đã được chọn
        if (!empty($selected_permissions)) {
            $sql_insert = "INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)";
            $stmt_insert = mysqli_prepare($conn, $sql_insert);
            
            foreach ($selected_permissions as $permission_id) {
                $pid = (int)$permission_id;
                mysqli_stmt_bind_param($stmt_insert, "ii", $role_id_to_update, $pid);
                mysqli_stmt_execute($stmt_insert);
            }
            mysqli_stmt_close($stmt_insert);
        }
        
        // Hoàn tất
        mysqli_commit($conn);
        $message = "<p style='color:green; font-weight:bold;'>Cập nhật quyền cho vai trò thành công!</p>";
        
        // QUAN TRỌNG: Nếu user tự cập nhật vai trò của CHÍNH MÌNH, 
        // ta cần làm mới (refresh) lại session permissions của họ.
        $current_user_role_id = mysqli_query($conn, "SELECT role_id FROM users WHERE user_id = " . (int)$_SESSION['user_id'])->fetch_assoc()['role_id'];
        if($current_user_role_id == $role_id_to_update) {
             $_SESSION['permissions'] = getUserPermissions($conn, $_SESSION['user_id']);
             $message .= "<p style='color:blue;'>Quyền của bạn đã được cập nhật. Tải lại trang để xem menu mới.</p>";
        }

    } catch (Exception $e) {
        mysqli_rollback($conn);
        $message = "<p style='color:red; font-weight:bold;'>Lỗi: " . $e->getMessage() . "</p>";
    }
}


// --- BƯỚC 2: Lấy dữ liệu để hiển thị FORM (GET) ---

// 1. Lấy tất cả các vai trò (roles)
$roles_result = mysqli_query($conn, "SELECT * FROM roles");
$all_roles = [];
while($row = mysqli_fetch_assoc($roles_result)) {
    $all_roles[] = $row;
}

// 2. Lấy tất cả các quyền (permissions)
$permissions_result = mysqli_query($conn, "SELECT * FROM permissions ORDER BY permission_name");
$all_permissions = [];
while($row = mysqli_fetch_assoc($permissions_result)) {
    $all_permissions[] = $row;
}

// 3. Xác định vai trò đang được chọn (từ ?edit_role=)
$selected_role_id = null;
$selected_role_permissions = []; // Quyền RIÊNG của vai trò này (để check checkbox)

if (isset($_GET['edit_role'])) {
    $selected_role_id = (int)$_GET['edit_role'];
    
    // Lấy các quyền MÀ vai trò này được gán TRỰC TIẾP
    $sql_current_perms = "SELECT permission_id FROM role_permissions WHERE role_id = ?";
    $stmt_current_perms = mysqli_prepare($conn, $sql_current_perms);
    mysqli_stmt_bind_param($stmt_current_perms, "i", $selected_role_id);
    mysqli_stmt_execute($stmt_current_perms);
    $result_current_perms = mysqli_stmt_get_result($stmt_current_perms);
    
    while($row = mysqli_fetch_assoc($result_current_perms)) {
        $selected_role_permissions[] = $row['permission_id'];
    }
    mysqli_stmt_close($stmt_current_perms);
}

?>

<style>
    .role-manager { display: flex; gap: 30px; }
    .role-list ul { list-style: none; padding: 0; }
    .role-list li a { display: block; padding: 8px; text-decoration: none; border: 1px solid #ccc; margin-bottom: 5px; border-radius: 4px; }
    .role-list li a:hover { background: #f0f0f0; }
    .role-list li a.active { background: #007bff; color: white; border-color: #007bff; }
    .permission-list label { display: block; margin-bottom: 8px; font-size: 1.1em; }
    .permission-list label input { margin-right: 10px; }
    .save-button { padding: 10px 20px; font-size: 1.1em; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
    .save-button:hover { background: #218838; }
</style>

<h2>Quản lý Vai trò (Bài tập 9)</h2>
<p>Chọn một vai trò để gán quyền. (Lưu ý: Quyền được kế thừa từ vai trò cha sẽ không hiển thị ở đây, chỉ có quyền gán trực tiếp.)</p>

<?php echo $message; ?>

<div class="role-manager">
    
    <!-- Cột 1: Danh sách Vai trò -->
    <div class="role-list">
        <h4>Chọn Vai trò</h4>
        <ul>
            <?php foreach ($all_roles as $role): ?>
                <li>
                    <a href="?page=manage_roles&edit_role=<?php echo $role['role_id']; ?>"
                       class="<?php echo ($selected_role_id == $role['role_id']) ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($role['role_name']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Cột 2: Danh sách Quyền (hiện nếu đã chọn vai trò) -->
    <?php if ($selected_role_id !== null): ?>
        <div class="permission-form">
            <h4>Chỉnh sửa quyền cho: <?php 
                foreach($all_roles as $r) { 
                    if($r['role_id'] == $selected_role_id) { 
                        echo htmlspecialchars($r['role_name']); 
                        break; 
                    } 
                } 
            ?></h4>
            
            <form method="POST" action="?page=manage_roles&edit_role=<?php echo $selected_role_id; ?>">
                <input type="hidden" name="role_id" value="<?php echo $selected_role_id; ?>">
                
                <div class="permission-list">
                    <?php foreach ($all_permissions as $permission): ?>
                        <label>
                            <input type="checkbox" 
                                   name="permissions[]" 
                                   value="<?php echo $permission['permission_id']; ?>"
                                   <?php 
                                   // Check vào ô nếu vai trò này có quyền đó
                                   if (in_array($permission['permission_id'], $selected_role_permissions)) {
                                       echo 'checked';
                                   }
                                   ?>
                            >
                            <?php echo htmlspecialchars($permission['permission_name']); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                
                <br>
                <button type="submit" name="save_permissions" class="save-button">Lưu thay đổi</button>
            </form>
        </div>
    <?php else: ?>
        <p><i>Hãy chọn một vai trò từ danh sách bên trái.</i></p>
    <?php endif; ?>
    
</div>