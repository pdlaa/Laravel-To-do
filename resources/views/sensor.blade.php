@extends('layouts.app')

@section('title', 'IoT Smart Dashboard')

@section('content')
<!-- Import modern Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="dashboard-wrapper">
    <!-- Header Section -->
    <div class="dashboard-header">
        <div>
            <span class="badge"></span>
            <h1>IoT Smart Dashboard</h1>
            <p class="subtitle"></p>
        </div>
        <div class="action-buttons">
            <a href="{{ route('sensor.create') }}" class="btn-create">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Tambah Sensor Manual
            </a>
            <form action="{{ route('logout') }}" method="POST" class="inline-form">
                @csrf
                <button type="submit" class="btn-logout">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Stats & Live Grid -->
    <div class="dashboard-grid">
        <!-- TEMP (SUHU) CARD -->
        <div class="sensor-card" id="temp-card">
            <div class="card-header">
                <div class="icon-wrapper icon-orange">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/></svg>
                </div>
                <h3>Suhu (DHT22)</h3>
                <span class="status-dot dot-active"></span>
            </div>
            <div class="card-body">
                <div class="value-display">
                    <span class="main-value" id="val-suhu">--</span>
                    <span class="unit">°C</span>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar bar-orange" id="progress-suhu" style="width: 0%"></div>
                </div>
                <p class="card-footer-text">Update terakhir: <span id="time-suhu">Belum ada data</span></p>
            </div>
        </div>

        <!-- HUMIDITY (KELEMBAPAN) CARD -->
        <div class="sensor-card" id="humid-card">
            <div class="card-header">
                <div class="icon-wrapper icon-blue">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z"/></svg>
                </div>
                <h3>Kelembapan (DHT22)</h3>
                <span class="status-dot dot-active"></span>
            </div>
            <div class="card-body">
                <div class="value-display">
                    <span class="main-value" id="val-kelembapan">--</span>
                    <span class="unit">%</span>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar bar-blue" id="progress-kelembapan" style="width: 0%"></div>
                </div>
                <p class="card-footer-text">Update terakhir: <span id="time-kelembapan">Belum ada data</span></p>
            </div>
        </div>

        <!-- SERVO ACTUATOR CARD (PUBLISHER) -->
        <div class="sensor-card">
            <div class="card-header">
                <div class="icon-wrapper icon-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89M9 11l3 3L22 4"/></svg>
                </div>
                <h3>Servo Motor</h3>
                <span class="status-badge-pub">PUBLISH</span>
            </div>
            <div class="card-body">
                <p class="card-description">Sesuaikan sudut motor servo Wokwi secara real-time:</p>
                <div class="servo-control-group">
                    <div class="dial-container">
                        <div class="servo-pointer-wrapper" id="servo-rotator">
                            <div class="servo-pointer"></div>
                        </div>
                        <span class="dial-val"><span id="servo-angle-text">90</span>°</span>
                    </div>
                    
                    <div class="slider-wrapper">
                        <input type="range" min="0" max="180" value="90" class="premium-slider" id="servo-slider">
                        <div class="slider-labels">
                            <span>0°</span>
                            <span>90°</span>
                            <span>180°</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- LCD PREVIEW & CONTROLLER CARD (PUBLISHER) -->
        <div class="sensor-card">
            <div class="card-header">
                <div class="icon-wrapper icon-cyan">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3>LCD Display 16x2</h3>
                <span class="status-badge-pub">PUBLISH</span>
            </div>
            <div class="card-body">
                <!-- Simulated LCD Display Screen -->
                <div class="simulated-lcd">
                    <div class="lcd-text-line" id="lcd-line-1">WOKWI SIMULATOR</div>
                    <div class="lcd-text-line" id="lcd-line-2">STANDBY...</div>
                </div>

                <div class="lcd-control-group">
                    <input type="text" id="lcd-input" maxlength="16" placeholder="Ketik pesan (max 16 kar)..." class="premium-input">
                    <button type="button" id="btn-lcd-send" class="btn-send">
                        <span>Kirim</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- DEVICE STATUS LIST & INTERACTIVE NETWORK TOPOLOGY -->
    <div class="devices-container">
        <!-- Left Side: Device Status Panel -->
        <div class="sensor-table-card" style="margin-top: 0; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
                    <h2 class="table-title" style="margin-bottom: 0;">Daftar Status Device</h2>
                    <div style="display: flex; gap: 8px;">
                        <button type="button" id="btn-open-wokwi-guide" class="btn-edit" style="background-color: #f8fafc; display: inline-flex; align-items: center; gap: 6px; cursor: pointer;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                            Panduan Wokwi
                        </button>
                        <button type="button" id="btn-open-add-modal" class="btn-create" style="padding: 6px 14px; font-size: 0.75rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Tambah Device
                        </button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="sensor-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>ID Device</th>
                                <th style="text-align: center;">Status</th>
                                <th style="width: 80px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="device-table-body">
                            @forelse($devices as $index => $device)
                                <tr id="device-row-{{ $device->id }}" data-id="{{ $device->id }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td style="font-weight: 600; color: #0f172a; font-family: monospace;">{{ $device->serial_number }}</td>
                                    <td style="text-align: center;">
                                        <span class="status-indicator {{ $device->last_seen && now()->diffInSeconds(\Carbon\Carbon::parse($device->last_seen)) <= 30 ? 'status-online' : 'status-offline' }}">
                                            <span class="indicator-dot"></span>
                                            <span class="indicator-text">{{ $device->last_seen && now()->diffInSeconds(\Carbon\Carbon::parse($device->last_seen)) <= 30 ? 'Online' : 'Offline' }}</span>
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <div class="action-buttons-cell" style="justify-content: center; gap: 6px;">
                                            <button type="button" class="btn-edit btn-edit-device" data-device='@json($device)' style="padding: 4px 8px; font-size: 0.7rem; cursor: pointer;" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            <button type="button" class="btn-delete btn-delete-device" data-id="{{ $device->id }}" style="padding: 4px 8px; font-size: 0.7rem; cursor: pointer;" title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="devices-empty-row">
                                    <td colspan="4" style="text-align: center; color: #64748b; padding: 30px;">
                                        Belum ada device terdaftar. Silakan tambah device baru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Side: System Topology Map -->
        <div class="sensor-table-card" style="margin-top: 0; background: #0f172a; color: #cbd5e1; border-color: #1e293b; display: flex; flex-direction: column;">
            <h2 class="table-title" style="color: white; margin-bottom: 5px;">Topologi Jaringan IoT</h2>
            <p style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 20px; margin-top: 0;">Visualisasi transmisi data perangkat Wokwi ke broker MQTT secara real-time.</p>
            
            <div class="topology-wrapper" id="topology-container" style="flex-grow: 1; display: flex; flex-direction: column; justify-content: center;">
                <div class="topology-node" id="node-wokwi">
                    <div class="node-icon icon-wokwi-pulse">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                    </div>
                    <span class="node-label">Wokwi ESP32</span>
                    <span class="node-status" id="topology-wokwi-status">Offline</span>
                </div>

                <div class="topology-path-container">
                    <div class="topology-path-line" id="path-wokwi-broker"></div>
                    <span class="path-label">Publish (QoS 0)</span>
                </div>

                <div class="topology-node" id="node-broker">
                    <div class="node-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <span class="node-label">Broker MQTT</span>
                    <span class="node-sublabel">shiftr.io</span>
                </div>

                <div class="topology-path-container">
                    <div class="topology-path-line" id="path-broker-dash"></div>
                    <span class="path-label">Live API (2s)</span>
                </div>

                <div class="topology-node" id="node-dashboard" style="border-color: rgba(16, 185, 129, 0.3); box-shadow: 0 0 15px rgba(16, 185, 129, 0.08);">
                    <div class="node-icon" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                    </div>
                    <span class="node-label">Dashboard Web</span>
                    <span class="node-status" style="background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);">Active</span>
                </div>
            </div>
        </div>
    </div>

    <!-- LIST DATA SENSOR MANUAL CARD -->
    <div class="sensor-table-card">
        <h2 class="table-title">List Data Sensor Manual</h2>
        <div class="table-responsive">
            <table class="sensor-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">No</th>
                        <th>Nama Sensor</th>
                        <th>Nilai / Data</th>
                        <th>Tanggal Ditambahkan</th>
                        <th style="width: 180px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sensors as $index => $sensor)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td style="font-weight: 600; color: #0f172a;">{{ $sensor->nama_sensor }}</td>
                            <td>
                                <span class="data-value-badge">{{ $sensor->data }}</span>
                            </td>
                            <td>{{ $sensor->created_at ? $sensor->created_at->format('d M Y H:i') : '-' }}</td>
                            <td style="text-align: center;">
                                <div class="action-buttons-cell">
                                    <a href="{{ route('sensor.edit', $sensor->id) }}" class="btn-edit">
                                        Edit
                                    </a>
                                    <form action="{{ route('sensor.delete', $sensor->id) }}" method="POST" class="inline-form" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data sensor ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #64748b; padding: 30px;">
                                Belum ada data sensor manual. Silakan tambah data baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Toast notification container -->
<div class="toast-container" id="toast-container"></div>

<!-- Modal Tambah Device -->
<div class="premium-modal-overlay" id="modal-add-device">
    <div class="premium-modal-card">
        <div class="modal-header">
            <h3>Tambah Device Baru</h3>
            <button type="button" class="modal-close-btn modal-close-trigger">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="form-add-device">
            <div class="modal-body">
                <div class="form-group">
                    <label for="add-name" style="font-size: 0.85rem; color: #475569; margin-bottom: 6px; font-weight: 600; display: block;">Nama Device</label>
                    <input type="text" id="add-name" name="name" placeholder="Contoh: ESP32 Ruang Tamu" required class="premium-input" style="width: 100%; box-sizing: border-box;">
                </div>
                <div class="form-group" style="margin-top: 15px;">
                    <label for="add-serial-number" style="font-size: 0.85rem; color: #475569; margin-bottom: 6px; font-weight: 600; display: block;">Serial Number</label>
                    <input type="text" id="add-serial-number" name="serial_number" placeholder="Contoh: ESP32-DHT22-WOKWI" required class="premium-input" style="width: 100%; box-sizing: border-box;">
                </div>
                <div class="form-group" style="margin-top: 15px;">
                    <label for="add-topic" style="font-size: 0.85rem; color: #475569; margin-bottom: 6px; font-weight: 600; display: block;">Topik MQTT</label>
                    <input type="text" id="add-topic" name="topic" placeholder="Contoh: iot/suhu" required class="premium-input" style="width: 100%; box-sizing: border-box;">
                </div>
                <div class="form-group" style="margin-top: 15px;">
                    <label for="add-wokwi-url" style="font-size: 0.85rem; color: #475569; margin-bottom: 6px; font-weight: 600; display: block;">Wokwi Simulator URL (Opsional)</label>
                    <input type="url" id="add-wokwi-url" name="wokwi_url" placeholder="https://wokwi.com/projects/..." class="premium-input" style="width: 100%; box-sizing: border-box;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel modal-close-trigger">Batal</button>
                <button type="submit" class="btn-create" style="padding: 8px 20px;">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Device -->
<div class="premium-modal-overlay" id="modal-edit-device">
    <div class="premium-modal-card">
        <div class="modal-header">
            <h3>Edit Device</h3>
            <button type="button" class="modal-close-btn modal-close-trigger">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="form-edit-device">
            <input type="hidden" id="edit-device-id" name="id">
            <div class="modal-body">
                <div class="form-group">
                    <label for="edit-name" style="font-size: 0.85rem; color: #475569; margin-bottom: 6px; font-weight: 600; display: block;">Nama Device</label>
                    <input type="text" id="edit-name" name="name" required class="premium-input" style="width: 100%; box-sizing: border-box;">
                </div>
                <div class="form-group" style="margin-top: 15px;">
                    <label for="edit-serial-number" style="font-size: 0.85rem; color: #475569; margin-bottom: 6px; font-weight: 600; display: block;">Serial Number</label>
                    <input type="text" id="edit-serial-number" name="serial_number" required class="premium-input" style="width: 100%; box-sizing: border-box;">
                </div>
                <div class="form-group" style="margin-top: 15px;">
                    <label for="edit-topic" style="font-size: 0.85rem; color: #475569; margin-bottom: 6px; font-weight: 600; display: block;">Topik MQTT</label>
                    <input type="text" id="edit-topic" name="topic" required class="premium-input" style="width: 100%; box-sizing: border-box;">
                </div>
                <div class="form-group" style="margin-top: 15px;">
                    <label for="edit-wokwi-url" style="font-size: 0.85rem; color: #475569; margin-bottom: 6px; font-weight: 600; display: block;">Wokwi Simulator URL (Opsional)</label>
                    <input type="url" id="edit-wokwi-url" name="wokwi_url" class="premium-input" style="width: 100%; box-sizing: border-box;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel modal-close-trigger">Batal</button>
                <button type="submit" class="btn-create" style="padding: 8px 20px;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Panduan Wokwi -->
<div class="premium-modal-overlay" id="modal-wokwi-guide">
    <div class="premium-modal-card" style="max-width: 650px;">
        <div class="modal-header">
            <h3>Integrasi Lengkap Wokwi ESP32</h3>
            <button type="button" class="modal-close-btn modal-close-trigger">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body" style="max-height: 400px; overflow-y: auto; font-size: 0.85rem; line-height: 1.6; color: #475569;">
            <p>Ikuti langkah-langkah di bawah ini untuk menghubungkan simulator Wokwi Anda ke dashboard ini:</p>
            <ol style="padding-left: 20px; margin-bottom: 15px;">
                <li>Daftarkan perangkat baru di dashboard ini dengan Serial Number tertentu (misal: <code>ESP32-DHT22-WOKWI</code>) dan topik data sensor (misal: <code>iot/suhu</code>).</li>
                <li>Gunakan pustaka <b>PubSubClient</b> dan <b>WiFi</b> di sketch Arduino/Wokwi Anda.</li>
                <li>Salin kode firmware di bawah ini dan tempelkan ke project Wokwi Anda.</li>
                <li>Sesuaikan variabel <code>client_id</code> dengan Serial Number terdaftar agar status heartbeat terdeteksi.</li>
            </ol>
            
            <div style="font-weight: 600; color: #0f172a; margin-top: 15px; display: flex; justify-content: space-between; align-items: center;">
                <span>Kode Firmware ESP32 (Arduino C):</span>
                <button type="button" id="btn-copy-wokwi-code" class="btn-edit" style="font-size: 0.7rem; padding: 4px 10px; cursor: pointer;">Copy Code</button>
            </div>
            
            <div class="guide-code-box" id="wokwi-code-text">#include &lt;WiFi.h&gt;
#include &lt;PubSubClient.h&gt;

// --- KONFIGURASI WIFI & MQTT ---
const char* ssid = "Wokwi-GUEST"; // Default SSID Wokwi
const char* password = "";
const char* mqtt_server = "padla-xw.cloud.shiftr.io";
const int mqtt_port = 1883;
const char* mqtt_user = "padla-xw";
const char* mqtt_pass = "PLNtolong";
const char* client_id = "ESP32-DHT22-WOKWI"; // Sesuaikan dengan Serial Number terdaftar

WiFiClient espClient;
PubSubClient client(espClient);
unsigned long lastMsg = 0;

void setup_wifi() {
  delay(10);
  Serial.print("Menghubungkan ke WiFi: ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi Terhubung!");
}

void callback(char* topic, byte* payload, unsigned int length) {
  String message;
  for (int i = 0; i < length; i++) {
    message += (char)payload[i];
  }
  Serial.print("Pesan masuk [");
  Serial.print(topic);
  Serial.print("]: ");
  Serial.println(message);

  // LOGIK UNTUK SERVO MOTOR
  if (String(topic) == "iot/servo") {
    int angle = message.toInt();
    Serial.print("Menggerakkan Servo ke sudut: ");
    Serial.println(angle);
  }
  
  // LOGIK UNTUK LCD DISPLAY
  if (String(topic) == "iot/lcd") {
    Serial.print("Pesan LCD Baru: ");
    Serial.println(message);
  }
}

void reconnect() {
  while (!client.connected()) {
    Serial.print("Menghubungkan ke Broker MQTT...");
    if (client.connect(client_id, mqtt_user, mqtt_pass)) {
      Serial.println("Terhubung!");
      client.subscribe("iot/servo");
      client.subscribe("iot/lcd");
      
      // Kirim heartbeat pertama kali terhubung
      client.publish("iot/status", client_id);
    } else {
      Serial.print("Gagal, status=");
      Serial.print(client.state());
      Serial.println(" Coba lagi dalam 5 detik...");
      delay(5000);
    }
  }
}

void setup() {
  Serial.begin(115200);
  setup_wifi();
  client.setServer(mqtt_server, mqtt_port);
  client.setCallback(callback);
}

void loop() {
  if (!client.connected()) {
    reconnect();
  }
  client.loop();

  unsigned long now = millis();
  // Kirim data sensor & heartbeat setiap 5 detik
  if (now - lastMsg &gt; 5000) {
    lastMsg = now;
    
    // Simulasi data sensor DHT22
    float suhu = 24.0 + random(0, 100) / 10.0;
    float kelembaban = 50.0 + random(0, 300) / 10.0;
    
    // Publish data sensor
    client.publish("iot/suhu", String(suhu).c_str());
    client.publish("iot/kelembaban", String(kelembaban).c_str());
    
    // Publish status/heartbeat untuk update online
    client.publish("iot/status", client_id);
    
    Serial.print("Suhu: "); Serial.print(suhu);
    Serial.print(" | Kelembaban: "); Serial.println(kelembaban);
  }
}</div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel modal-close-trigger">Tutup</button>
        </div>
    </div>
</div>

<!-- CSS Styling for the Dashboard -->
<style>
/* Font override */
.dashboard-wrapper {
    font-family: 'Outfit', sans-serif !important;
}

/* Header & Typography */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 20px;
}

.dashboard-header h1 {
    font-size: 2.2rem;
    font-weight: 700;
    margin: 6px 0 2px 0;
    color: #0f172a;
}

.subtitle {
    color: #64748b;
    font-size: 0.95rem;
    margin: 0;
}

.badge {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    padding: 4px 10px;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.05em;
    display: inline-block;
    border: 1px solid rgba(16, 185, 129, 0.2);
}

/* Custom buttons */
.action-buttons {
    display: flex;
    gap: 12px;
}

.btn-create {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    padding: 10px 18px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    border: none;
    background: linear-gradient(135deg, #004cff, #0037b3);
    color: white;
}

.btn-create:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

.btn-logout {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    padding: 10px 18px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    border: 1px solid #cbd5e1;
    background: white;
    color: #334155;
}

.btn-logout:hover {
    background: #f1f5f9;
    transform: translateY(-1px);
}

.inline-form {
    margin: 0;
    display: inline-block;
}

/* Grid Layout */
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-top: 10px;
}

/* Minimalist White Cards */
.sensor-card {
    background: white;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    padding: 24px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.2s ease;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    color: #1e293b;
    position: relative;
}

.sensor-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
    border-color: #94a3b8;
}

/* Card Header */
.card-header {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    position: relative;
    padding: 0;
    background: none;
    border: none;
}

.card-header h3 {
    margin: 0 0 0 12px;
    font-size: 1.1rem;
    font-weight: 600;
    color: #0f172a;
}

.icon-wrapper {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.icon-wrapper svg {
    width: 18px;
    height: 18px;
}

/* Icon Palette */
.icon-orange { background: rgba(249, 115, 22, 0.1); color: #ea580c; }
.icon-blue { background: rgba(59, 130, 246, 0.1); color: #2563eb; }
.icon-purple { background: rgba(168, 85, 247, 0.1); color: #7c3aed; }
.icon-cyan { background: rgba(6, 182, 212, 0.1); color: #0891b2; }

/* Status Dot & Badges */
.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-left: auto;
}

.dot-active {
    background-color: #10b981;
    box-shadow: 0 0 8px #10b981;
}

.status-badge-pub {
    margin-left: auto;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 6px;
    background: rgba(124, 58, 237, 0.1);
    color: #7c3aed;
    border: 1px solid rgba(124, 58, 237, 0.2);
}

/* Card Body Content */
.card-body {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.card-description {
    color: #64748b;
    font-size: 0.85rem;
    margin-top: 0;
    margin-bottom: 15px;
}

.value-display {
    display: flex;
    align-items: baseline;
    margin-bottom: 12px;
}

.main-value {
    font-size: 3.2rem;
    font-weight: 700;
    color: #0f172a;
    line-height: 1;
}

.unit {
    font-size: 1.4rem;
    color: #64748b;
    margin-left: 6px;
    font-weight: 500;
}

/* Progress Bars */
.progress-bar-container {
    width: 100%;
    height: 6px;
    background: #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 14px;
}

.progress-bar {
    height: 100%;
    border-radius: 10px;
    transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.bar-orange { background: linear-gradient(90deg, #f97316, #ea580c); }
.bar-blue { background: linear-gradient(90deg, #3b82f6, #2563eb); }

.card-footer-text {
    font-size: 0.75rem;
    color: #64748b;
    margin: 0;
}

/* Servo Controller Specifics */
.servo-control-group {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
    margin-top: 10px;
}

.dial-container {
    display: flex;
    align-items: center;
    gap: 20px;
}

.servo-pointer-wrapper {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    background: #f1f5f9;
    border: 2px dashed rgba(124, 58, 237, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease;
    transform: rotate(90deg);
}

.servo-pointer {
    width: 4px;
    height: 22px;
    background: #7c3aed;
    border-radius: 20px;
    position: relative;
    top: -6px;
}

.dial-val {
    font-size: 1.6rem;
    font-weight: 700;
    color: #0f172a;
}

.slider-wrapper {
    width: 100%;
}

.premium-slider {
    -webkit-appearance: none;
    appearance: none;
    width: 100%;
    height: 6px;
    border-radius: 10px;
    background: #e2e8f0;
    outline: none;
}

.premium-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #7c3aed;
    cursor: pointer;
    box-shadow: 0 0 6px rgba(124, 58, 237, 0.5);
    transition: transform 0.1s ease;
}

.premium-slider::-webkit-slider-thumb:hover {
    transform: scale(1.2);
}

.slider-labels {
    display: flex;
    justify-content: space-between;
    font-size: 0.7rem;
    color: #64748b;
    margin-top: 5px;
}

/* Simulated LCD screen */
.simulated-lcd {
    background-color: #0c4a6e;
    border: 3px solid #0f172a;
    border-radius: 8px;
    padding: 10px;
    font-family: monospace;
    font-size: 1.1rem;
    color: #38bdf8;
    text-shadow: 0 0 6px rgba(56, 189, 248, 0.6);
    letter-spacing: 0.05em;
    box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.8);
    height: 70px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    margin-bottom: 15px;
}

.lcd-text-line {
    white-space: pre;
    overflow: hidden;
    height: 18px;
    line-height: 18px;
}

.lcd-control-group {
    display: flex;
    gap: 8px;
}

.premium-input {
    background: #f8fafc;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 10px 12px;
    color: #0f172a;
    font-family: inherit;
    flex-grow: 1;
    font-size: 0.85rem;
    outline: none;
    transition: border-color 0.2s ease;
}

.premium-input:focus {
    border-color: #004cff;
}

.btn-send {
    background: linear-gradient(135deg, #004cff, #0037b3);
    border: none;
    border-radius: 8px;
    padding: 0 16px;
    color: white;
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
}

.btn-send:hover {
    opacity: 0.9;
}

/* Toast Engine */
.toast-container {
    position: fixed;
    bottom: 25px;
    right: 25px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    z-index: 9999;
}

.toast {
    background: #1e293b;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 12px 18px;
    color: white;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.85rem;
    animation: slideIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    min-width: 250px;
}

.toast-success { border-left: 4px solid #10b981; }
.toast-error { border-left: 4px solid #ef4444; }

@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

.toast-fade-out {
    animation: fadeOut 0.4s forwards;
}

@keyframes fadeOut {
    to { transform: translateY(10px); opacity: 0; }
}

/* Data Table Card Styling */
.sensor-table-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    border: 1px solid #cbd5e1;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    margin-top: 30px;
    color: #1e293b;
}

.table-title {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 20px;
    color: #0f172a;
    margin-top: 0;
}

.table-responsive {
    width: 100%;
    overflow-x: auto;
}

.sensor-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.sensor-table th {
    background-color: #f8fafc;
    color: #475569;
    font-weight: 600;
    text-align: left;
    padding: 12px 16px;
    border-bottom: 2px solid #e2e8f0;
    font-size: 0.875rem;
}

.sensor-table td {
    padding: 14px 16px;
    border-bottom: 1px solid #e2e8f0;
    color: #334155;
    font-size: 0.875rem;
}

.sensor-table tr:hover {
    background-color: #f8fafc;
}

.data-value-badge {
    background-color: #eff6ff;
    color: #1d4ed8;
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 700;
    border: 1px solid #dbeafe;
    display: inline-block;
}

.action-buttons-cell {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.btn-edit {
    display: inline-flex;
    align-items: center;
    background-color: #f1f5f9;
    color: #334155;
    padding: 6px 14px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.75rem;
    border: 1px solid #cbd5e1;
    transition: 0.2s;
}

.btn-edit:hover {
    background-color: #cbd5e1;
}

.btn-delete {
    display: inline-flex;
    align-items: center;
    background-color: #fee2e2;
    color: #b91c1c;
    padding: 6px 14px;
    border-radius: 6px;
    border: 1px solid #fca5a5;
    font-weight: 600;
    font-size: 0.75rem;
    cursor: pointer;
    transition: 0.2s;
}

.btn-delete:hover {
    background-color: #fecaca;
}

/* Change highlight effect */
.data-changed-pulse {
    animation: pulseGlow 1s ease;
}

@keyframes pulseGlow {
    0% { border-color: #cbd5e1; }
    50% { border-color: #004cff; box-shadow: 0 0 10px rgba(0, 76, 255, 0.15); }
    100% { border-color: #cbd5e1; }
}

/* Devices Grid Layout */
.devices-container {
    display: grid;
    grid-template-columns: 1.6fr 1.1fr;
    gap: 24px;
    margin-top: 30px;
}
@media (max-width: 992px) {
    .devices-container {
        grid-template-columns: 1fr;
    }
}

/* Status Indicator Badge */
.status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
}
.status-online {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.2);
}
.status-offline {
    background: rgba(148, 163, 184, 0.1);
    color: #64748b;
    border: 1px solid rgba(148, 163, 184, 0.2);
}
.status-indicator .indicator-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}
.status-online .indicator-dot {
    background-color: #10b981;
    box-shadow: 0 0 6px #10b981;
    animation: blinkGlow 1.5s infinite ease-in-out;
}
.status-offline .indicator-dot {
    background-color: #64748b;
}

@keyframes blinkGlow {
    0%, 100% { opacity: 0.5; transform: scale(1); }
    50% { opacity: 1; transform: scale(1.2); }
}

/* Topology styles */
.topology-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
    padding: 20px 10px;
    position: relative;
}
.topology-node {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    width: 140px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 15px 10px;
    transition: all 0.3s ease;
    z-index: 2;
}
.topology-node .node-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.05);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    margin-bottom: 8px;
    transition: all 0.3s ease;
}
.topology-node .node-icon svg {
    width: 22px;
    height: 22px;
}
.topology-node .node-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: white;
}
.topology-node .node-sublabel {
    font-size: 0.7rem;
    color: #64748b;
}
.topology-node .node-status {
    font-size: 0.65rem;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 4px;
    margin-top: 6px;
    background: rgba(148, 163, 184, 0.1);
    color: #94a3b8;
}

/* Path Connections */
.topology-path-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
    position: relative;
    margin: 2px 0;
}
.topology-path-line {
    width: 3px;
    height: 45px;
    background: rgba(255, 255, 255, 0.05);
    position: relative;
    overflow: hidden;
}
.path-label {
    font-size: 0.65rem;
    color: #64748b;
    margin-top: 4px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Active status styles for topology */
.topology-wrapper.is-active #node-wokwi {
    border-color: rgba(16, 185, 129, 0.3);
    box-shadow: 0 0 15px rgba(16, 185, 129, 0.08);
}
.topology-wrapper.is-active #node-wokwi .node-icon {
    background: rgba(16, 185, 129, 0.15);
    color: #10b981;
}
.topology-wrapper.is-active #topology-wokwi-status {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.topology-wrapper.is-active #node-broker {
    border-color: rgba(124, 58, 237, 0.3);
    box-shadow: 0 0 15px rgba(124, 58, 237, 0.08);
}
.topology-wrapper.is-active #node-broker .node-icon {
    background: rgba(124, 58, 237, 0.15);
    color: #a78bfa;
}

/* Pulsing electron animation on paths when active */
.topology-wrapper.is-active .topology-path-line::after {
    content: '';
    position: absolute;
    top: -20px;
    left: 0;
    width: 100%;
    height: 20px;
    background: linear-gradient(to bottom, transparent, #2563eb, #60a5fa, transparent);
    animation: flowDown 1.8s infinite linear;
}
#path-broker-dash.topology-path-line::after {
    animation-delay: 0.9s;
}

@keyframes flowDown {
    0% { top: -20px; }
    100% { top: 100%; }
}

/* Premium Modals (Glassmorphism overlay & Clean white card) */
.premium-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0.4);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
}
.premium-modal-overlay.show {
    opacity: 1;
    pointer-events: auto;
}
.premium-modal-card {
    background: white;
    border: 1px solid #cbd5e1;
    border-radius: 16px;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    transform: translateY(20px);
    transition: transform 0.3s ease;
    color: #1e293b;
    overflow: hidden;
}
.premium-modal-overlay.show .premium-modal-card {
    transform: translateY(0);
}
.modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: #0f172a;
}
.modal-close-btn {
    background: none;
    border: none;
    color: #64748b;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 4px;
    border-radius: 6px;
    transition: 0.2s;
}
.modal-close-btn:hover {
    background: #f1f5f9;
    color: #0f172a;
}
.modal-body {
    padding: 24px;
}
.modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    background: #f8fafc;
}
.btn-cancel {
    background: white;
    border: 1px solid #cbd5e1;
    color: #334155;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    transition: 0.2s;
}
.btn-cancel:hover {
    background: #f1f5f9;
}

/* Code guide custom scrollbar */
.guide-code-box {
    background: #0f172a;
    color: #cbd5e1;
    padding: 16px;
    border-radius: 8px;
    font-family: monospace;
    font-size: 0.75rem;
    line-height: 1.5;
    overflow-x: auto;
    max-height: 250px;
    border: 1px solid #1e293b;
    margin-top: 10px;
    margin-bottom: 15px;
    white-space: pre;
    position: relative;
}
.guide-copy-btn {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(255, 255, 255, 0.07);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.65rem;
    cursor: pointer;
    transition: 0.2s;
    font-family: sans-serif;
    font-weight: 600;
    z-index: 10;
}
.guide-copy-btn:hover {
    background: rgba(255, 255, 255, 0.15);
}
</style>

<!-- Javascript for dashboard logic and live update polling -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // -------------------------------------------------------------
    // CSRF Utility Setup for Fetch Calls
    // -------------------------------------------------------------
    const csrfToken = '{{ csrf_token() }}';

    // -------------------------------------------------------------
    // Toast Notification Manager
    // -------------------------------------------------------------
    const toastContainer = document.getElementById('toast-container');
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        
        const icon = type === 'success' 
            ? `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#10b981" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>`
            : `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#ef4444" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>`;

        toast.innerHTML = `${icon} <span>${message}</span>`;
        toastContainer.appendChild(toast);

        // Auto destroy toast after 3 seconds
        setTimeout(() => {
            toast.classList.add('toast-fade-out');
            setTimeout(() => toast.remove(), 400);
        }, 3000);
    }

    // -------------------------------------------------------------
    // MQTT Publisher Callbacks (Servo & LCD)
    // -------------------------------------------------------------
    async function publishMqtt(topic, message) {
        try {
            const response = await fetch('/mqtt/publish', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ topic, message })
            });
            const data = await response.json();
            if (data.success) {
                showToast(`Success: Published to '${topic}'`);
            } else {
                showToast(data.message || 'Error occurred', 'error');
            }
        } catch (err) {
            showToast('Failed to publish. Check connection.', 'error');
            console.error(err);
        }
    }

    // -- Servo slider input
    const servoSlider = document.getElementById('servo-slider');
    const servoAngleText = document.getElementById('servo-angle-text');
    const servoRotator = document.getElementById('servo-rotator');

    // Debounce publisher to not flood Wokwi/Broker on sliding
    let servoTimeout;
    servoSlider.addEventListener('input', function() {
        const val = this.value;
        servoAngleText.textContent = val;
        // Visual Rotation (Wokwi map 0-180 to rotators)
        servoRotator.style.transform = `rotate(${val}deg)`;

        clearTimeout(servoTimeout);
        servoTimeout = setTimeout(() => {
            publishMqtt('servo', val);
        }, 250); // 250ms debounce
    });

    // -- LCD Text send
    const lcdInput = document.getElementById('lcd-input');
    const btnLcdSend = document.getElementById('btn-lcd-send');
    const lcdLine1 = document.getElementById('lcd-line-1');
    const lcdLine2 = document.getElementById('lcd-line-2');

    btnLcdSend.addEventListener('click', function() {
        const text = lcdInput.value.trim();
        if (text === '') {
            showToast('LCD text cannot be empty', 'error');
            return;
        }

        // Simulate on display
        lcdLine1.textContent = "SENDING TO LCD...";
        lcdLine2.textContent = text.toUpperCase();

        publishMqtt('lcd', text);

        setTimeout(() => {
            lcdLine1.textContent = text.toUpperCase();
            lcdLine2.textContent = "SENT OK ✓";
        }, 800);
    });

    // Handle enter key press on input
    lcdInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            btnLcdSend.click();
        }
    });

    // -------------------------------------------------------------
    // Live Subscriber Polling (DHT Data)
    // -------------------------------------------------------------
    const valSuhu = document.getElementById('val-suhu');
    const progressSuhu = document.getElementById('progress-suhu');
    const timeSuhu = document.getElementById('time-suhu');
    const cardSuhu = document.getElementById('temp-card');

    const valHumid = document.getElementById('val-kelembapan');
    const progressHumid = document.getElementById('progress-kelembapan');
    const timeHumid = document.getElementById('time-kelembapan');
    const cardHumid = document.getElementById('humid-card');

    // Cache values to check for changes and pulse
    let currentSuhu = null;
    let currentKelembapan = null;

    async function pollSensorData() {
        try {
            const response = await fetch('/api/sensors/latest');
            const data = await response.json();

            // Update Temperature
            if (data.suhu !== null) {
                const suhuNum = parseFloat(data.suhu).toFixed(1);
                valSuhu.textContent = suhuNum;
                // Temperature progress calculation (map 0-50°C to percentage)
                const pct = Math.min(Math.max((suhuNum / 50) * 100, 0), 100);
                progressSuhu.style.width = `${pct}%`;
                timeSuhu.textContent = `${data.suhu_updated} (${data.suhu_relative})`;

                // Trigger highlight animation if value changed
                if (currentSuhu !== suhuNum) {
                    if (currentSuhu !== null) {
                        cardSuhu.classList.add('data-changed-pulse');
                        setTimeout(() => cardSuhu.classList.remove('data-changed-pulse'), 1000);
                        showToast(`New Temp reading: ${suhuNum}°C`);
                    }
                    currentSuhu = suhuNum;
                }
            }

            // Update Humidity
            if (data.kelembapan !== null) {
                const humidNum = parseFloat(data.kelembapan).toFixed(1);
                valHumid.textContent = humidNum;
                // Humidity progress percentage
                progressHumid.style.width = `${humidNum}%`;
                timeHumid.textContent = `${data.kelembapan_updated} (${data.kelembapan_relative})`;

                // Trigger highlight animation if value changed
                if (currentKelembapan !== humidNum) {
                    if (currentKelembapan !== null) {
                        cardHumid.classList.add('data-changed-pulse');
                        setTimeout(() => cardHumid.classList.remove('data-changed-pulse'), 1000);
                        showToast(`New Humidity reading: ${humidNum}%`);
                    }
                    currentKelembapan = humidNum;
                }
            }
        } catch (err) {
            console.error('Error fetching sensor data:', err);
        }
    }

    // Initial fetch and set interval
    pollSensorData();
    setInterval(pollSensorData, 2000); // Poll every 2 seconds

    // -------------------------------------------------------------
    // Device Management & Modals Logic
    // -------------------------------------------------------------
    const addModal = document.getElementById('modal-add-device');
    const editModal = document.getElementById('modal-edit-device');
    const guideModal = document.getElementById('modal-wokwi-guide');
    
    // Open Add Modal
    document.getElementById('btn-open-add-modal').addEventListener('click', () => {
        addModal.classList.add('show');
    });
    
    // Open Wokwi Guide Modal
    document.getElementById('btn-open-wokwi-guide').addEventListener('click', () => {
        guideModal.classList.add('show');
    });
    
    // Close modals
    document.querySelectorAll('.modal-close-trigger').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.premium-modal-overlay').forEach(modal => {
                modal.classList.remove('show');
            });
        });
    });

    // Handle Copy Wokwi Code
    const btnCopyCode = document.getElementById('btn-copy-wokwi-code');
    const wokwiCodeText = document.getElementById('wokwi-code-text');
    if (btnCopyCode && wokwiCodeText) {
        btnCopyCode.addEventListener('click', () => {
            navigator.clipboard.writeText(wokwiCodeText.textContent).then(() => {
                btnCopyCode.textContent = 'Copied!';
                showToast('ESP32 code copied to clipboard!');
                setTimeout(() => {
                    btnCopyCode.textContent = 'Copy Code';
                }, 2000);
            }).catch(err => {
                showToast('Failed to copy code', 'error');
                console.error(err);
            });
        });
    }

    // Submit Add Device Form (AJAX)
    const formAddDevice = document.getElementById('form-add-device');
    formAddDevice.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(formAddDevice);
        const data = Object.fromEntries(formData.entries());
        
        try {
            const response = await fetch('/device', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            if (result.success) {
                showToast(result.message || 'Device added successfully!');
                formAddDevice.reset();
                addModal.classList.remove('show');
                pollDevicesData(); // Refresh immediately
            } else {
                showToast(result.message || 'Validation error', 'error');
            }
        } catch (err) {
            showToast('Failed to save device. Check unique serial number.', 'error');
            console.error(err);
        }
    });

    // Bind Edit Action Buttons (Event Delegation because table updates dynamically)
    const deviceTableBody = document.getElementById('device-table-body');
    deviceTableBody.addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-edit-device')) {
            const deviceData = JSON.parse(e.target.getAttribute('data-device'));
            
            // Populate form
            document.getElementById('edit-device-id').value = deviceData.id;
            document.getElementById('edit-name').value = deviceData.name;
            document.getElementById('edit-serial-number').value = deviceData.serial_number;
            document.getElementById('edit-topic').value = deviceData.topic;
            document.getElementById('edit-wokwi-url').value = deviceData.wokwi_url || '';
            
            editModal.classList.add('show');
        }
    });

    // Submit Edit Device Form (AJAX)
    const formEditDevice = document.getElementById('form-edit-device');
    formEditDevice.addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('edit-device-id').value;
        const formData = new FormData(formEditDevice);
        const data = Object.fromEntries(formData.entries());
        
        try {
            const response = await fetch(`/device/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            if (result.success) {
                showToast(result.message || 'Device updated successfully!');
                editModal.classList.remove('show');
                pollDevicesData(); // Refresh immediately
            } else {
                showToast(result.message || 'Validation error', 'error');
            }
        } catch (err) {
            showToast('Failed to update device', 'error');
            console.error(err);
        }
    });

    // Bind Delete Action Buttons
    deviceTableBody.addEventListener('click', async (e) => {
        if (e.target.classList.contains('btn-delete-device')) {
            const id = e.target.getAttribute('data-id');
            if (confirm('Apakah Anda yakin ingin menghapus device ini?')) {
                try {
                    const response = await fetch(`/device/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    const result = await response.json();
                    if (result.success) {
                        showToast(result.message || 'Device deleted successfully!');
                        pollDevicesData(); // Refresh immediately
                    } else {
                        showToast(result.message || 'Failed to delete device', 'error');
                    }
                } catch (err) {
                    showToast('Failed to delete device', 'error');
                    console.error(err);
                }
            }
        }
    });

    // -------------------------------------------------------------
    // Live Device Status Polling & Topology Update
    // -------------------------------------------------------------
    const topologyContainer = document.getElementById('topology-container');
    const topologyWokwiStatus = document.getElementById('topology-wokwi-status');

    async function pollDevicesData() {
        try {
            const response = await fetch('/api/devices/status');
            const data = await response.json();
            
            if (data.success && data.devices) {
                const devices = data.devices;
                
                if (devices.length === 0) {
                    deviceTableBody.innerHTML = `
                        <tr id="devices-empty-row">
                            <td colspan="4" style="text-align: center; color: #64748b; padding: 30px;">
                                Belum ada device terdaftar. Silakan tambah device baru.
                            </td>
                        </tr>
                    `;
                    topologyContainer.classList.remove('is-active');
                    topologyWokwiStatus.textContent = 'Offline';
                    topologyWokwiStatus.style.background = 'rgba(148, 163, 184, 0.1)';
                    topologyWokwiStatus.style.color = '#94a3b8';
                    topologyWokwiStatus.style.border = '1px solid rgba(148, 163, 184, 0.2)';
                    return;
                }
                
                let html = '';
                let hasOnlineDevice = false;
                
                devices.forEach((device, index) => {
                    if (device.is_online) hasOnlineDevice = true;
                    
                    const statusClass = device.is_online ? 'status-online' : 'status-offline';
                    const statusText = device.is_online ? 'Online' : 'Offline';
                    const deviceJson = JSON.stringify(device).replace(/'/g, "&#39;");
                    
                    html += `
                        <tr id="device-row-${device.id}" data-id="${device.id}">
                            <td>${index + 1}</td>
                            <td style="font-weight: 600; color: #0f172a; font-family: monospace;">${escapeHtml(device.serial_number)}</td>
                            <td style="text-align: center;">
                                <span class="status-indicator ${statusClass}">
                                    <span class="indicator-dot"></span>
                                    <span class="indicator-text">${statusText}</span>
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <div class="action-buttons-cell" style="justify-content: center; gap: 6px;">
                                    <button type="button" class="btn-edit btn-edit-device" data-device='${deviceJson}' style="padding: 4px 8px; font-size: 0.7rem; cursor: pointer;" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button type="button" class="btn-delete btn-delete-device" data-id="${device.id}" style="padding: 4px 8px; font-size: 0.7rem; cursor: pointer;" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                
                deviceTableBody.innerHTML = html;
                
                // Update Topology animation state
                if (hasOnlineDevice) {
                    topologyContainer.classList.add('is-active');
                    topologyWokwiStatus.textContent = 'Online';
                    topologyWokwiStatus.style.background = 'rgba(16, 185, 129, 0.2)';
                    topologyWokwiStatus.style.color = '#10b981';
                    topologyWokwiStatus.style.border = '1px solid rgba(16, 185, 129, 0.3)';
                } else {
                    topologyContainer.classList.remove('is-active');
                    topologyWokwiStatus.textContent = 'Offline';
                    topologyWokwiStatus.style.background = 'rgba(148, 163, 184, 0.1)';
                    topologyWokwiStatus.style.color = '#94a3b8';
                    topologyWokwiStatus.style.border = '1px solid rgba(148, 163, 184, 0.2)';
                }
            }
        } catch (err) {
            console.error('Error fetching device statuses:', err);
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.toString().replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Initial fetch and set interval for devices
    pollDevicesData();
    setInterval(pollDevicesData, 2000);
});
</script>
@endsection