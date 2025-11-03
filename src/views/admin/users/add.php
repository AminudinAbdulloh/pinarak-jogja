<div class="content-section" id="add-admin">
    <div class="page-header">
        <h1><i class="fas fa-user-plus"></i> Tambah Admin Baru</h1>
        <p>Buat akun administrator baru untuk sistem</p>
    </div>

    <div class="content-area">
        <div class="content-header">
            <h3>Form Tambah Admin</h3>
            <a href="<?= BASEURL . '/admin/users' ?>" class="btn btn-warning">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Admin
            </a>
        </div>

        <div class="content-body">
            <?php if (!empty($_SESSION['error_message'])): ?>
                <div class="notification notification-error mb-3">
                    <i class="fas fa-exclamation-circle"></i> 
                    <span><?= htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASEURL . '/admin/users/add_user' ?>" id="addAdminForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="username">
                            <i class="fas fa-user"></i> Username *
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="username" 
                               name="username" 
                               required 
                               placeholder="Masukkan username"
                               value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                               autocomplete="off">
                        <small class="form-text">Username harus unik dan akan digunakan untuk login</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Email *
                        </label>
                        <input type="email" 
                               class="form-control" 
                               id="email" 
                               name="email" 
                               required 
                               placeholder="Masukkan alamat email"
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        <small class="form-text">Email harus valid dan unik</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="full_name">
                            <i class="fas fa-id-card"></i> Nama Lengkap *
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="full_name" 
                               name="full_name" 
                               required 
                               placeholder="Masukkan nama lengkap"
                               value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="status">
                            <i class="fas fa-toggle-on"></i> Status Akun
                        </label>
                        <select class="form-control" id="status" name="status">
                            <option value="active" <?php echo (isset($_POST['status']) && $_POST['status'] == 'active') ? 'selected' : 'selected'; ?>>Active</option>
                            <option value="inactive" <?php echo (isset($_POST['status']) && $_POST['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                        <small class="form-text">Admin dengan status inactive tidak dapat login</small>
                    </div>
                </div>

                <div class="form-divider">
                    <span><i class="fas fa-lock"></i> Keamanan Password</span>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-key"></i> Password *
                        </label>
                        <div class="password-input-wrapper">
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   placeholder="Masukkan password"
                                   minlength="6">
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="password-icon"></i>
                            </button>
                        </div>
                        <small class="form-text">Password minimal 6 karakter</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="confirm_password">
                            <i class="fas fa-key"></i> Konfirmasi Password *
                        </label>
                        <div class="password-input-wrapper">
                            <input type="password" 
                                   class="form-control" 
                                   id="confirm_password" 
                                   name="confirm_password" 
                                   required 
                                   placeholder="Masukkan ulang password"
                                   minlength="6">
                            <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                                <i class="fas fa-eye" id="confirm_password-icon"></i>
                            </button>
                        </div>
                        <small class="form-text">Pastikan password sama dengan yang diatas</small>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Admin
                    </button>
                    <button type="button" class="btn btn-warning" onclick="resetForm('addAdminForm')">
                        <i class="fas fa-undo"></i> Reset Form
                    </button>
                    <a href="<?= BASEURL . '/admin/users' ?>" class="btn btn-danger">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.form-divider {
    margin: 30px 0 20px 0;
    text-align: center;
    position: relative;
}

.form-divider::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    width: 100%;
    height: 1px;
    background: #e0e0e0;
}

.form-divider span {
    background: #fff;
    padding: 0 15px;
    position: relative;
    color: #666;
    font-weight: 500;
    font-size: 14px;
}

.form-divider span i {
    margin-right: 5px;
}

.password-input-wrapper {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    padding: 5px 10px;
}

.password-toggle:hover {
    color: #007bff;
}

.password-input-wrapper input {
    padding-right: 45px;
}
</style>

<script>
// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Validasi form sebelum submit
document.getElementById('addAdminForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Password dan Konfirmasi Password tidak sama!');
        document.getElementById('confirm_password').focus();
        return false;
    }
    
    if (password.length < 6) {
        e.preventDefault();
        alert('Password minimal 6 karakter!');
        document.getElementById('password').focus();
        return false;
    }
});

// Real-time password match validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (confirmPassword && password !== confirmPassword) {
        this.style.borderColor = '#dc3545';
    } else if (confirmPassword && password === confirmPassword) {
        this.style.borderColor = '#28a745';
    } else {
        this.style.borderColor = '';
    }
});
</script>