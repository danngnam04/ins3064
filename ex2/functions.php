<?php
// (Giả sử $conn từ db_connect.php đã được include)

/**
 * BÀI TẬP 5 & 8: Lấy TẤT CẢ quyền của user, bao gồm cả kế thừa.
 * Sử dụng vòng lặp (thay vì đệ quy) để leo lên cây kế thừa.
 *
 * @param mysqli $conn Đối tượng kết nối CSDL.
 * @param int $user_id ID của người dùng.
 * @return array Mảng chứa các chuỗi permission_name.
 */
function getUserPermissions($conn, $user_id) {
    $permissions = [];
    
    // 1. Tìm role_id của user
    $sql_role = "SELECT role_id FROM users WHERE user_id = ?";
    if ($stmt_role = mysqli_prepare($conn, $sql_role)) {
        mysqli_stmt_bind_param($stmt_role, "i", $user_id);
        mysqli_stmt_execute($stmt_role);
        $result_role = mysqli_stmt_get_result($stmt_role);
        
        if ($row_role = mysqli_fetch_assoc($result_role)) {
            $current_role_id = $row_role['role_id'];
            
            // 2. Vòng lặp để lấy quyền và leo lên vai trò cha (Bài 8)
            while ($current_role_id !== null) {
                $sql_perms = "SELECT p.permission_name, r.parent_role_id 
                              FROM roles r
                              LEFT JOIN role_permissions rp ON r.role_id = rp.role_id
                              LEFT JOIN permissions p ON rp.permission_id = p.permission_id
                              WHERE r.role_id = ?";
                              
                if ($stmt_perms = mysqli_prepare($conn, $sql_perms)) {
                    mysqli_stmt_bind_param($stmt_perms, "i", $current_role_id);
                    mysqli_stmt_execute($stmt_perms);
                    $result_perms = mysqli_stmt_get_result($stmt_perms);
                    
                    $parent_role_id = null; // Reset cho mỗi vòng lặp
                    
                    while ($row_perms = mysqli_fetch_assoc($result_perms)) {
                        if ($row_perms['permission_name'] !== null) {
                            $permissions[] = $row_perms['permission_name'];
                        }
                        $parent_role_id = $row_perms['parent_role_id'];
                    }
                    mysqli_stmt_close($stmt_perms);
                    $current_role_id = $parent_role_id; // Di chuyển lên vai trò cha
                } else {
                    $current_role_id = null; // Dừng nếu có lỗi
                }
            }
        }
        mysqli_stmt_close($stmt_role);
    }
    
    // Trả về mảng các quyền duy nhất
    return array_unique($permissions);
}

/**
 * BÀI TẬP 3: (Đã cập nhật) Kiểm tra quyền dựa trên SESSION.
 * Nhanh hơn vì chỉ kiểm tra mảng trong session, không query CSDL.
 *
 * @param string $required_permission Quyền cần kiểm tra.
 * @return bool
 */
function checkAccess($required_permission) {
    // Lấy mảng permissions từ session (được lưu lúc "đăng nhập")
    $user_permissions = $_SESSION['permissions'] ?? [];
    
    return in_array($required_permission, $user_permissions);
}

/**
 * BÀI TẬP 6: Yêu cầu quyền.
 *
 * @param string $permission Quyền yêu cầu.
 */
function requirePermission($permission) {
    if (!checkAccess($permission)) {
        header("Location: unauthorized.php");
        exit();
    }
}

/**
 * BÀI TẬP 2: Kiểm tra quyền cho một user_id CỤ THỂ.
 * Hàm này sẽ query CSDL, khác với checkAccess() dùng session.
 *
 * @param mysqli $conn Đối tượng kết nối CSDL.
 * @param int $user_id ID người dùng cần kiểm tra.
 * @param string $permission Quyền cần kiểm tra.
 * @return bool
 */
function hasPermission($conn, $user_id, $permission) {
    // Lấy tất cả quyền của user đó từ CSDL
    $all_permissions = getUserPermissions($conn, $user_id);
    
    // Kiểm tra
    return in_array($permission, $all_permissions);
}
?>