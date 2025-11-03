<?php // expects $user ?>
<div class="content-section" id="edit-admin">
    <div class="page-header">
        <h1><i class="fas fa-user-edit"></i> Edit Admin</h1>
        <p>Perbarui informasi administrator</p>
    </div>

    <div class="content-area">
        <div class="content-header">
            <h3>Form Edit Admin</h3>
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

            <?php if (!empty($_SESSION['success_message'])): ?>
                <div class="notification notification-success mb-3">
                    <i class="fas fa-check-circle"></i> 
                    <span><?= htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?></span>
                </div>
            <?php endif; ?>

            <!-- Grid Layout for Forms -->
            <div class="edit-grid">
                <!-- Left Column - Data Admin -->
                <div class="edit-card">
                    <div class="edit-card-header">
                        <i class="fas fa-user-circle"></i> Data Admin
                    </div>
                    <div class="edit-card-body">
                        <form method="POST" action="<?= BASEURL . '/admin/users/edit_user' ?>" id="editAdminForm">
                            <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">

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
                                           value="<?= htmlspecialchars($user['username']) ?>"
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
                                           value="<?= htmlspecialchars($user['email']) ?>">
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
                                           value="<?= htmlspecialchars($user['full_name']) ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="status">
                                        <i class="fas fa-toggle-on"></i> Status Akun
                                    </label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                    </select>
                                    <small class="form-text">Admin dengan status inactive tidak dapat login</small>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                                <a href="<?= BASEURL . '/admin/users' ?>" class="btn btn-danger">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column - Reset Password -->
                <div class="edit-card">
                    <div class="edit-card-header">
                        <i class="fas fa-key"></i> Reset Password
                    </div>
                    <div class="edit-card-body">
                        <div class="alert alert-warning mb-3">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Perhatian!</strong> Password hanya akan diubah jika Anda mengisi form di bawah ini.
                        </div>

                        <form method="POST" action="<?= BASEURL . '/admin/users/reset_password' ?>" id="resetPasswordForm">
                            <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="new_password">
                                        <i class="fas fa-key"></i> Password Baru *
                                    </label>
                                    <div class="password-input-wrapper">
                                        <input type="password" 
                                               class="form-control" 
                                               id="new_password" 
                                               name="password" 
                                               required 
                                               placeholder="Masukkan password baru"
                                               minlength="6">
                                        <button type="button" class="password-toggle" onclick="togglePassword('new_password')">
                                            <i class="fas fa-eye" id="new_password-icon"></i>
                                        </button>
                                    </div>
                                    <small class="form-text">Password minimal 6 karakter</small>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="confirm_new_password">
                                        <i class="fas fa-key"></i> Konfirmasi Password *
                                    </label>
                                    <div class="password-input-wrapper">
                                        <input type="password" 
                                               class="form-control" 
                                               id="confirm_new_password" 
                                               name="confirm_password" 
                                               required 
                                               placeholder="Masukkan ulang password baru"
                                               minlength="6">
                                        <button type="button" class="password-toggle" onclick="togglePassword('confirm_new_password')">
                                            <i class="fas fa-eye" id="confirm_new_password-icon"></i>
                                        </button>
                                    </div>
                                    <small class="form-text">Pastikan password sama dengan yang diatas</small>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-lock"></i> Reset Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.edit-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 20px;
}

@media (max-width: 992px) {
    .edit-grid {
        grid-template-columns: 1fr;
    }
}

.edit-card {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
}

.edit-card-header {
    background: linear-gradient(135deg, #4a7c22, #2d5016);
    color: #fff;
    padding: 15px 20px;
    font-weight: 600;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.edit-card-body {
    padding: 20px;
}

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
    z-index: 10;
}

.password-toggle:hover {
    color: #007bff;
}

.password-input-wrapper input {
    padding-right: 45px;
}

.alert-warning {
    background-color: #fff3cd;
    border: 1px solid #ffc107;
    color: #856404;
    padding: 12px 15px;
    border-radius: 5px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.alert-warning i {
    margin-top: 2px;
}

.alert-warning strong {
    display: block;
    margin-bottom: 5px;
}
</style>

<script>
// Store original values for reset
const originalValues = {
    username: '<?= htmlspecialchars($user['username']) ?>',
    email: '<?= htmlspecialchars($user['email']) ?>',
    full_name: '<?= htmlspecialchars($user['full_name']) ?>',
    status: '<?= $user['status'] ?>'
};

// Validasi reset password form
document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
    const password = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_new_password').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Password dan Konfirmasi Password tidak sama!');
        document.getElementById('confirm_new_password').focus();
        return false;
    }
    
    if (password.length < 6) {
        e.preventDefault();
        alert('Password minimal 6 karakter!');
        document.getElementById('new_password').focus();
        return false;
    }
    
    // Konfirmasi sebelum reset password
    if (!confirm('Apakah Anda yakin ingin mereset password untuk admin ini?')) {
        e.preventDefault();
        return false;
    }
});

// Real-time password match validation
document.getElementById('confirm_new_password').addEventListener('input', function() {
    const password = document.getElementById('new_password').value;
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