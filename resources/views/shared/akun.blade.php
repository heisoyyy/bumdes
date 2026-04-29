@php
    $user       = auth()->user();
    $role       = $user->role;
    $akunRoute  = match($role) {
        'pengurus'    => 'pengurus.akun',
        'kepala_desa' => 'kepaladesa.akun',
        'masyarakat'  => 'masyarakat.akun',
    };

    // Cek tab aktif dari session atau query string
    $activeTab = session('tab', request()->query('tab', 'profil'));
@endphp

<div class="row g-4">

    {{-- SIDEBAR PROFIL --}}
    <div class="col-xl-3 col-md-4">

        {{-- Kartu Profil --}}
        <div class="card text-center mb-3">
            <div class="card-body py-4">

                {{-- Avatar --}}
                <div class="position-relative d-inline-block mb-3">
                    @if($user->foto_profil)
                        <img src="{{ $user->foto_profil_url }}"
                             alt="Foto Profil"
                             style="width:90px;height:90px;border-radius:50%;object-fit:cover;
                                    border:3px solid #0d6efd;">
                    @else
                        <div style="width:90px;height:90px;border-radius:50%;
                                    background:linear-gradient(135deg,#0d6efd,#198754);
                                    display:flex;align-items:center;justify-content:center;
                                    color:white;font-size:36px;font-weight:700;
                                    border:3px solid #0d6efd;margin:0 auto;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif

                    {{-- Status aktif --}}
                    <div style="position:absolute;bottom:3px;right:3px;
                                width:16px;height:16px;border-radius:50%;
                                background:{{ $user->is_active === 'aktif' ? '#198754' : '#dc3545' }};
                                border:2px solid white;"></div>
                </div>

                <h6 style="font-size:15px;font-weight:600;color:#2d3748;margin-bottom:3px;">
                    {{ $user->name }}
                </h6>
                <div style="font-size:12px;color:#718096;margin-bottom:5px;">
                    {{ '@' . $user->username }}
                </div>
                <span class="badge"
                      style="background:linear-gradient(135deg,#0d6efd,#198754);
                             font-size:11px;padding:5px 12px;border-radius:20px;">
                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                </span>

                @if($user->foto_profil)
                <div class="mt-3">
                    <form action="{{ route($akunRoute . '.hapus-foto') }}"
                          method="POST"
                          onsubmit="return confirm('Hapus foto profil?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="btn btn-sm btn-outline-danger"
                                style="font-size:11px;">
                            <i class="bi bi-trash me-1"></i>Hapus Foto
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>

        {{-- Info Akun --}}
        <div class="card">
            <div class="card-body p-3">
                <div style="font-size:11px;color:#718096;font-weight:600;
                            text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;">
                    Info Akun
                </div>

                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:32px;height:32px;background:#eff6ff;border-radius:8px;
                                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-envelope" style="color:#0d6efd;font-size:14px;"></i>
                    </div>
                    <div>
                        <div style="font-size:10px;color:#a0aec0;">Email</div>
                        <div style="font-size:12px;color:#2d3748;font-weight:500;word-break:break-all;">
                            {{ $user->email }}
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:32px;height:32px;background:#f0fdf4;border-radius:8px;
                                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-phone" style="color:#198754;font-size:14px;"></i>
                    </div>
                    <div>
                        <div style="font-size:10px;color:#a0aec0;">No HP</div>
                        <div style="font-size:12px;color:#2d3748;font-weight:500;">
                            {{ $user->no_hp ?? 'Belum diisi' }}
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:32px;height:32px;background:#fefce8;border-radius:8px;
                                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-clock" style="color:#ca8a04;font-size:14px;"></i>
                    </div>
                    <div>
                        <div style="font-size:10px;color:#a0aec0;">Login Terakhir</div>
                        <div style="font-size:12px;color:#2d3748;font-weight:500;">
                            {{ $user->last_login ? $user->last_login->format('d/m/Y H:i') : 'Belum pernah' }}
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <div style="width:32px;height:32px;background:#f0fdf4;border-radius:8px;
                                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-calendar" style="color:#198754;font-size:14px;"></i>
                    </div>
                    <div>
                        <div style="font-size:10px;color:#a0aec0;">Bergabung</div>
                        <div style="font-size:12px;color:#2d3748;font-weight:500;">
                            {{ $user->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- KONTEN UTAMA --}}
    <div class="col-xl-9 col-md-8">

        {{-- Tab Navigation --}}
        <ul class="nav nav-pills mb-4 gap-2" id="akunTab">
            <li class="nav-item">
                <button class="nav-link {{ $activeTab === 'profil' ? 'active' : '' }}"
                        onclick="switchTab('profil')"
                        style="border-radius:10px;font-size:13.5px;padding:9px 18px;">
                    <i class="bi bi-person me-2"></i>Edit Profil
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link {{ $activeTab === 'password' ? 'active' : '' }}"
                        onclick="switchTab('password')"
                        style="border-radius:10px;font-size:13.5px;padding:9px 18px;">
                    <i class="bi bi-shield-lock me-2"></i>Ganti Password
                </button>
            </li>
        </ul>

        {{-- TAB PROFIL --}}
        <div id="tab-profil" class="{{ $activeTab !== 'profil' ? 'd-none' : '' }}">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">
                        <i class="bi bi-person-gear me-2 text-primary"></i>
                        Edit Informasi Profil
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route($akunRoute . '.update-profil') }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            {{-- Upload Foto --}}
                            <div class="col-12">
                                <label class="form-label">Foto Profil</label>
                                <div class="d-flex align-items-center gap-3">
                                    {{-- Preview --}}
                                    <div id="fotoPreview">
                                        @if($user->foto_profil)
                                            <img src="{{ $user->foto_profil_url }}"
                                                 id="previewImg"
                                                 style="width:70px;height:70px;border-radius:12px;
                                                        object-fit:cover;border:2px solid #0d6efd;">
                                        @else
                                            <div id="previewDefault"
                                                 style="width:70px;height:70px;border-radius:12px;
                                                        background:linear-gradient(135deg,#0d6efd,#198754);
                                                        display:flex;align-items:center;justify-content:center;
                                                        color:white;font-size:28px;font-weight:700;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <input type="file"
                                               name="foto_profil"
                                               id="fotoInput"
                                               class="form-control @error('foto_profil') is-invalid @enderror"
                                               accept="image/jpeg,image/png,image/jpg"
                                               onchange="previewFoto(this)"
                                               style="max-width:300px;">
                                        <div style="font-size:11px;color:#718096;margin-top:4px;">
                                            Format JPG, PNG. Maksimal 2MB.
                                        </div>
                                        @error('foto_profil')
                                        <div class="text-danger" style="font-size:12px;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Nama --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $user->name) }}"
                                       placeholder="Nama lengkap"
                                       required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Username --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    Username <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"
                                          style="border-radius:8px 0 0 8px;
                                                 border:1.5px solid #e2e8f0;
                                                 border-right:none;
                                                 background:#f8fafc;
                                                 font-size:13px;color:#718096;">
                                        @
                                    </span>
                                    <input type="text"
                                           name="username"
                                           class="form-control @error('username') is-invalid @enderror"
                                           value="{{ old('username', $user->username) }}"
                                           placeholder="username"
                                           style="border-radius:0 8px 8px 0;border-left:none;"
                                           required>
                                    @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"
                                          style="border-radius:8px 0 0 8px;
                                                 border:1.5px solid #e2e8f0;
                                                 border-right:none;
                                                 background:#f8fafc;">
                                        <i class="bi bi-envelope" style="color:#718096;"></i>
                                    </span>
                                    <input type="email"
                                           name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $user->email) }}"
                                           placeholder="email@contoh.com"
                                           style="border-radius:0 8px 8px 0;border-left:none;"
                                           required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- No HP --}}
                            <div class="col-md-6">
                                <label class="form-label">No HP</label>
                                <div class="input-group">
                                    <span class="input-group-text"
                                          style="border-radius:8px 0 0 8px;
                                                 border:1.5px solid #e2e8f0;
                                                 border-right:none;
                                                 background:#f8fafc;">
                                        <i class="bi bi-phone" style="color:#718096;"></i>
                                    </span>
                                    <input type="text"
                                           name="no_hp"
                                           class="form-control"
                                           value="{{ old('no_hp', $user->no_hp) }}"
                                           placeholder="08xxxxxxxxxx"
                                           style="border-radius:0 8px 8px 0;border-left:none;"
                                           maxlength="15">
                                </div>
                            </div>

                        </div>

                        {{-- Tombol --}}
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                            </button>
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        {{-- TAB PASSWORD --}}
        <div id="tab-password" class="{{ $activeTab !== 'password' ? 'd-none' : '' }}">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">
                        <i class="bi bi-shield-lock me-2 text-warning"></i>
                        Ganti Password
                    </h6>
                </div>
                <div class="card-body">

                    {{-- Info keamanan --}}
                    <div class="alert d-flex align-items-center gap-2 mb-4"
                         style="background:#fffbeb;border:1px solid #fcd34d;border-radius:10px;">
                        <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                        <div style="font-size:13px;color:#92400e;">
                            Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol
                            untuk password yang lebih aman.
                        </div>
                    </div>

                    <form action="{{ route($akunRoute . '.update-password') }}"
                          method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            {{-- Password Lama --}}
                            <div class="col-md-8">
                                <label class="form-label">
                                    Password Lama <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"
                                          style="border-radius:8px 0 0 8px;
                                                 border:1.5px solid #e2e8f0;
                                                 border-right:none;
                                                 background:#f8fafc;">
                                        <i class="bi bi-lock" style="color:#718096;"></i>
                                    </span>
                                    <input type="password"
                                           name="password_lama"
                                           id="passLama"
                                           class="form-control @error('password_lama') is-invalid @enderror"
                                           placeholder="Masukkan password lama"
                                           style="border-radius:0;border-left:none;border-right:none;"
                                           required>
                                    <span class="input-group-text"
                                          onclick="togglePass('passLama','eyeLama')"
                                          style="border-radius:0 8px 8px 0;
                                                 border:1.5px solid #e2e8f0;
                                                 border-left:none;cursor:pointer;
                                                 background:#f8fafc;">
                                        <i class="bi bi-eye" id="eyeLama" style="color:#718096;"></i>
                                    </span>
                                    @error('password_lama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Divider --}}
                            <div class="col-12">
                                <hr style="border-color:#e2e8f0;margin:5px 0;">
                            </div>

                            {{-- Password Baru --}}
                            <div class="col-md-8">
                                <label class="form-label">
                                    Password Baru <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"
                                          style="border-radius:8px 0 0 8px;
                                                 border:1.5px solid #e2e8f0;
                                                 border-right:none;
                                                 background:#f8fafc;">
                                        <i class="bi bi-lock-fill" style="color:#0d6efd;"></i>
                                    </span>
                                    <input type="password"
                                           name="password_baru"
                                           id="passBaru"
                                           class="form-control @error('password_baru') is-invalid @enderror"
                                           placeholder="Min. 8 karakter"
                                           style="border-radius:0;border-left:none;border-right:none;"
                                           oninput="checkStrength(this.value)"
                                           required>
                                    <span class="input-group-text"
                                          onclick="togglePass('passBaru','eyeBaru')"
                                          style="border-radius:0 8px 8px 0;
                                                 border:1.5px solid #e2e8f0;
                                                 border-left:none;cursor:pointer;
                                                 background:#f8fafc;">
                                        <i class="bi bi-eye" id="eyeBaru" style="color:#718096;"></i>
                                    </span>
                                    @error('password_baru')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Password Strength --}}
                                <div id="strengthContainer" class="mt-2 d-none">
                                    <div class="d-flex gap-1 mb-1">
                                        <div class="strength-bar" id="bar1"
                                             style="height:4px;flex:1;border-radius:2px;background:#e2e8f0;"></div>
                                        <div class="strength-bar" id="bar2"
                                             style="height:4px;flex:1;border-radius:2px;background:#e2e8f0;"></div>
                                        <div class="strength-bar" id="bar3"
                                             style="height:4px;flex:1;border-radius:2px;background:#e2e8f0;"></div>
                                        <div class="strength-bar" id="bar4"
                                             style="height:4px;flex:1;border-radius:2px;background:#e2e8f0;"></div>
                                    </div>
                                    <div id="strengthText"
                                         style="font-size:11px;color:#718096;"></div>
                                </div>

                                {{-- Persyaratan Password --}}
                                <div class="mt-2" id="passwordRules">
                                    <div id="rule-length" style="font-size:11px;color:#a0aec0;">
                                        <i class="bi bi-x-circle me-1"></i>Minimal 8 karakter
                                    </div>
                                    <div id="rule-upper" style="font-size:11px;color:#a0aec0;">
                                        <i class="bi bi-x-circle me-1"></i>Mengandung huruf besar
                                    </div>
                                    <div id="rule-number" style="font-size:11px;color:#a0aec0;">
                                        <i class="bi bi-x-circle me-1"></i>Mengandung angka
                                    </div>
                                    <div id="rule-special" style="font-size:11px;color:#a0aec0;">
                                        <i class="bi bi-x-circle me-1"></i>Mengandung karakter spesial (!@#$%)
                                    </div>
                                </div>
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="col-md-8">
                                <label class="form-label">
                                    Konfirmasi Password Baru <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"
                                          style="border-radius:8px 0 0 8px;
                                                 border:1.5px solid #e2e8f0;
                                                 border-right:none;
                                                 background:#f8fafc;">
                                        <i class="bi bi-lock-fill" style="color:#198754;"></i>
                                    </span>
                                    <input type="password"
                                           name="password_baru_confirmation"
                                           id="passKonfirmasi"
                                           class="form-control"
                                           placeholder="Ulangi password baru"
                                           style="border-radius:0;border-left:none;border-right:none;"
                                           oninput="checkMatch()"
                                           required>
                                    <span class="input-group-text"
                                          onclick="togglePass('passKonfirmasi','eyeKonfirmasi')"
                                          style="border-radius:0 8px 8px 0;
                                                 border:1.5px solid #e2e8f0;
                                                 border-left:none;cursor:pointer;
                                                 background:#f8fafc;">
                                        <i class="bi bi-eye" id="eyeKonfirmasi" style="color:#718096;"></i>
                                    </span>
                                </div>
                                <div id="matchText" style="font-size:11px;margin-top:4px;"></div>
                            </div>

                        </div>

                        {{-- Tombol --}}
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-shield-check me-1"></i>Perbarui Password
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    // Switch Tab
    function switchTab(tab) {
        document.getElementById('tab-profil').classList.add('d-none');
        document.getElementById('tab-password').classList.add('d-none');
        document.getElementById('tab-' + tab).classList.remove('d-none');

        document.querySelectorAll('.nav-link').forEach(el => el.classList.remove('active'));
        event.target.classList.add('active');
    }

    // Preview Foto
    function previewFoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('fotoPreview');
                preview.innerHTML = `
                    <img src="${e.target.result}"
                         style="width:70px;height:70px;border-radius:12px;
                                object-fit:cover;border:2px solid #0d6efd;">
                `;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Toggle Password Visibility
    function togglePass(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }

    // Password Strength Checker
    function checkStrength(password) {
        const container = document.getElementById('strengthContainer');
        container.classList.remove('d-none');

        const rules = {
            length:  password.length >= 8,
            upper:   /[A-Z]/.test(password),
            number:  /[0-9]/.test(password),
            special: /[!@#$%^&*(),.?":{}|<>]/.test(password),
        };

        // Update rules UI
        updateRule('rule-length',  rules.length,  'Minimal 8 karakter');
        updateRule('rule-upper',   rules.upper,   'Mengandung huruf besar');
        updateRule('rule-number',  rules.number,  'Mengandung angka');
        updateRule('rule-special', rules.special, 'Mengandung karakter spesial (!@#$%)');

        // Hitung score
        const score = Object.values(rules).filter(Boolean).length;

        const bars    = ['bar1','bar2','bar3','bar4'];
        const colors  = ['#dc3545','#fd7e14','#ffc107','#198754'];
        const labels  = ['Sangat Lemah','Lemah','Sedang','Kuat'];

        bars.forEach((bar, i) => {
            document.getElementById(bar).style.background =
                i < score ? colors[score - 1] : '#e2e8f0';
        });

        const strengthText = document.getElementById('strengthText');
        if (password.length === 0) {
            strengthText.textContent = '';
        } else {
            strengthText.textContent  = 'Kekuatan: ' + labels[score - 1];
            strengthText.style.color  = colors[score - 1];
        }
    }

    function updateRule(id, passed, text) {
        const el = document.getElementById(id);
        el.style.color = passed ? '#198754' : '#a0aec0';
        el.innerHTML   = `<i class="bi ${passed ? 'bi-check-circle-fill' : 'bi-x-circle'} me-1"></i>${text}`;
    }

    // Password Match Checker
    function checkMatch() {
        const baru       = document.getElementById('passBaru').value;
        const konfirmasi = document.getElementById('passKonfirmasi').value;
        const matchText  = document.getElementById('matchText');

        if (konfirmasi.length === 0) {
            matchText.textContent = '';
            return;
        }

        if (baru === konfirmasi) {
            matchText.innerHTML   = '<i class="bi bi-check-circle-fill me-1"></i>Password cocok';
            matchText.style.color = '#198754';
        } else {
            matchText.innerHTML   = '<i class="bi bi-x-circle me-1"></i>Password tidak cocok';
            matchText.style.color = '#dc3545';
        }
    }
</script>
@endpush