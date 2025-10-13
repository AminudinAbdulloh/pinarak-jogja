<div class="content-section active" id="dashboard">
    <div class="page-header">
        <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
        <p>Selamat datang di panel admin - Kelola semua konten website Anda</p>
    </div>

    <div class="dashboard-cards">
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-number"><?= $data['stats']['total_events'] ?? 0 ?></div>
                    <div class="card-title">Total Event</div>
                </div>
                <div class="card-icon">
                    <i class="fas fa-calendar"></i>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-number"><?= $data['stats']['upcoming_events'] ?? 0 ?></div>
                    <div class="card-title">Event Akan Datang</div>
                </div>
                <div class="card-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-number"><?= $data['stats']['completed_events'] ?? 0 ?></div>
                    <div class="card-title">Event Selesai</div>
                </div>
                <div class="card-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-number"><?= $data['stats']['total_articles'] ?? 0 ?></div>
                    <div class="card-title">Total Artikel</div>
                </div>
                <div class="card-icon">
                    <i class="fas fa-newspaper"></i>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-number"><?= $data['stats']['published_articles'] ?? 0 ?></div>
                    <div class="card-title">Artikel Terbit</div>
                </div>
                <div class="card-icon">
                    <i class="fas fa-eye"></i>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-number"><?= $data['stats']['draft_articles'] ?? 0 ?></div>
                    <div class="card-title">Draft Artikel</div>
                </div>
                <div class="card-icon">
                    <i class="fas fa-edit"></i>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-number"><?= $data['stats']['total_users'] ?? 0 ?></div>
                    <div class="card-title">Total User</div>
                </div>
                <div class="card-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
</div>