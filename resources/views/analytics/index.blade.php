{{-- File: resources/views/analytics/index.blade.php --}}
<x-layout>

@php
    $total = $presentCount + $absentCount + $lateCount;
    $presentPercent = $total > 0 ? round(($presentCount / $total) * 100) : 0;
    $absentPercent  = $total > 0 ? round(($absentCount  / $total) * 100) : 0;
    $latePercent    = $total > 0 ? round(($lateCount    / $total) * 100) : 0;
@endphp

<style>
    /* ── Match dashboard background ── */
    body { background: #ecfdf5; }

    .mesh-bg { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; overflow: hidden; background: #ecfdf5; }
    .mesh-bg .blob { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.45; animation: floatBlob linear infinite; }
    /* Emerald/green blobs — matches student portal + dashboard vibe */
    .blob-1 { width: 600px; height: 600px; background: radial-gradient(circle, #6ee7b7, #10b981); top: -150px; left: -100px; animation-duration: 18s; }
    .blob-2 { width: 500px; height: 500px; background: radial-gradient(circle, #a7f3d0, #34d399); top: 200px; right: -100px; animation-duration: 22s; animation-delay: -6s; }
    .blob-3 { width: 400px; height: 400px; background: radial-gradient(circle, #bae6fd, #38bdf8); bottom: 0; left: 30%; animation-duration: 26s; animation-delay: -12s; }
    .blob-4 { width: 350px; height: 350px; background: radial-gradient(circle, #d9f99d, #84cc16); bottom: 100px; right: 20%; animation-duration: 20s; animation-delay: -4s; }
    @keyframes floatBlob {
        0%   { transform: translate(0, 0) scale(1); }
        25%  { transform: translate(40px, -30px) scale(1.05); }
        50%  { transform: translate(-20px, 50px) scale(0.95); }
        75%  { transform: translate(-40px, -20px) scale(1.03); }
        100% { transform: translate(0, 0) scale(1); }
    }

    /* ── Page shell ── */
    .analytics-wrap {
        padding: 2rem;
        min-height: 100vh;
        position: relative;
        z-index: 1;
        max-width: 1100px;
        margin: 0 auto;
    }

    /* ── Header ── */
    .page-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .page-header h1 {
        font-size: 1.875rem;
        font-weight: 700;
        color: #064e3b;
        letter-spacing: normal;
        line-height: 1.2;
        margin-bottom: 0.35rem;
    }
    .page-header p { font-size: 0.88rem; color: #6b7280; }

    /* Total pill — emerald */
    .total-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #064e3b;
        color: #fff;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.4rem 1rem;
        border-radius: 999px;
        letter-spacing: normal;
        white-space: nowrap;
    }
    .total-pill .pill-count {
        background: #93c5fd;
        color: #1e3a8a;
        border-radius: 999px;
        padding: 0.1rem 0.55rem;
        font-size: 0.78rem;
        font-weight: 700;
    }

    /* ── Stat cards ── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.1rem;
        margin-bottom: 1.25rem;
    }
    @media (max-width: 640px) { .stat-grid { grid-template-columns: 1fr; } }

    .stat-card {
        border-radius: 18px;
        padding: 1.6rem 1.6rem 1.4rem;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(0,0,0,0.10); }

    /* Present card — blue */
    .stat-card.present {
        background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 60%, #2563eb 100%);
        color: #fff;
    }
    .stat-card.absent,
    .stat-card.late {
        background: rgba(255,255,255,0.80);
        backdrop-filter: blur(12px);
        color: #111;
        border: 1.5px solid rgba(16,185,129,0.15);
    }

    .stat-card-label {
        font-size: 0.68rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        opacity: 0.55;
        margin-bottom: 0.9rem;
    }
    .stat-card.present .stat-card-label { opacity: 0.7; color: #bfdbfe; }

    .stat-number {
        font-size: 3.5rem;
        font-weight: 700;
        line-height: 1;
        letter-spacing: normal;
        margin-bottom: 0.9rem;
    }
    .stat-card.present .stat-number { color: #93c5fd; }
    .stat-card.absent  .stat-number { color: #f43f5e; }
    .stat-card.late    .stat-number { color: #f59e0b; }

    .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.76rem;
        font-weight: 500;
        padding: 0.3rem 0.75rem;
        border-radius: 999px;
    }
    .stat-card.present .stat-badge { background: rgba(147,197,253,0.18); color: #93c5fd; }
    .stat-card.absent  .stat-badge { background: #fff0f3; color: #f43f5e; }
    .stat-card.late    .stat-badge { background: #fffbeb; color: #d97706; }

    .stat-deco {
        position: absolute;
        bottom: -18px; right: -18px;
        font-size: 5.5rem;
        opacity: 0.07;
        font-weight: 800;
        line-height: 1;
        pointer-events: none;
        user-select: none;
    }

    /* ── Bottom two-column cards ── */
    .bottom-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.1rem;
    }
    @media (max-width: 768px) { .bottom-grid { grid-template-columns: 1fr; } }

    .info-card {
        background: rgba(255,255,255,0.82);
        backdrop-filter: blur(12px);
        border-radius: 18px;
        padding: 1.75rem 1.75rem 1.5rem;
        border: 1.5px solid rgba(16,185,129,0.12);
    }
    .info-card h2 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #064e3b;
        letter-spacing: normal;
        margin-bottom: 0.25rem;
    }
    .info-card .card-sub { font-size: 0.77rem; color: #9ca3af; margin-bottom: 1.75rem; }

    /* ── Distribution bars ── */
    .bar-group { margin-bottom: 1.4rem; }
    .bar-meta { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.45rem; }
    .bar-label { font-size: 0.82rem; font-weight: 500; color: #4b5563; display: flex; align-items: center; gap: 0.45rem; }
    .bar-dot   { width: 8px; height: 8px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
    .bar-pct   { font-size: 0.92rem; font-weight: 700; color: #064e3b; }
    .bar-track { background: #d1fae5; border-radius: 999px; height: 9px; overflow: hidden; }
    .bar-fill  { height: 100%; border-radius: 999px; transition: width 1s cubic-bezier(0.16,1,0.3,1); width: 0; }
    .bar-fill.present { background: linear-gradient(90deg, #1d4ed8, #60a5fa); }
    .bar-fill.absent  { background: linear-gradient(90deg, #e11d48, #f43f5e); }
    .bar-fill.late    { background: linear-gradient(90deg, #d97706, #fbbf24); }

    /* ── Bar Chart ── */
    .chart-wrap { padding-top: 0.25rem; }
    .chart-area { display: flex; align-items: stretch; height: 200px; }
    .y-axis {
        display: flex;
        flex-direction: column-reverse;
        justify-content: space-between;
        padding-bottom: 28px;
        padding-right: 10px;
        min-width: 26px;
        text-align: right;
        font-size: 10px;
        color: #9ca3af;
    }
    .bars-container {
        flex: 1;
        display: flex;
        align-items: flex-end;
        gap: 1.5rem;
        padding: 0 1.25rem 0 1rem;
        border-left: 1.5px solid #d1fae5;
        border-bottom: 1.5px solid #d1fae5;
    }
    .bar-col { flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end; }
    .bar-value { font-size: 1.05rem; font-weight: 700; margin-bottom: 6px; }
    .bar-outer { width: 100%; max-width: 72px; background: #d1fae5; border-radius: 8px 8px 0 0; height: 148px; display: flex; align-items: flex-end; overflow: hidden; }
    .bar-inner { width: 100%; border-radius: 8px 8px 0 0; height: 0%; transition: height 1s cubic-bezier(0.16,1,0.3,1); }
    .bar-inner.present { background: linear-gradient(180deg, #60a5fa, #1d4ed8); }
    .bar-inner.absent  { background: linear-gradient(180deg, #f87171, #f43f5e); }
    .bar-inner.late    { background: linear-gradient(180deg, #fbbf24, #f59e0b); }
    .bar-xlabel { font-size: 11px; font-weight: 500; color: #6b7280; margin-top: 8px; }

    /* ── Zero-state ── */
    .zero-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 3rem 1rem;
        color: #9ca3af;
        font-size: 0.88rem;
        text-align: center;
    }
    .zero-state i { font-size: 2.5rem; opacity: 0.4; }
</style>

{{-- Animated mesh background --}}
<div class="mesh-bg">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
    <div class="blob blob-4"></div>
</div>

<div class="analytics-wrap">

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div>
            <h1>Attendance Analytics</h1>
            <p>Real-time breakdown of campus-wide student presence matrices.</p>
        </div>
        <div class="total-pill">
            TOTAL RECORDS <span class="pill-count">{{ $total }}</span>
        </div>
    </div>

    {{-- ── Stat Cards ── --}}
    <div class="stat-grid">

        <div class="stat-card present">
            <div class="stat-card-label">Total Present</div>
            <div class="stat-number">{{ $presentCount }}</div>
            <div class="stat-badge">↑ {{ $presentPercent }}% of total logs</div>
            <div class="stat-deco">✓</div>
        </div>

        <div class="stat-card absent">
            <div class="stat-card-label">Total Absent</div>
            <div class="stat-number">{{ $absentCount }}</div>
            <div class="stat-badge">↓ {{ $absentPercent }}% risk rate</div>
            <div class="stat-deco">✗</div>
        </div>

        <div class="stat-card late">
            <div class="stat-card-label">Total Late</div>
            <div class="stat-number">{{ $lateCount }}</div>
            <div class="stat-badge">→ {{ $latePercent }}% punctuality variance</div>
            <div class="stat-deco">!</div>
        </div>

    </div>

    {{-- ── Bottom Row: Distribution + Chart ── --}}
    <div class="bottom-grid">

        {{-- Distribution Visualizer --}}
        <div class="info-card">
            <h2>Distribution Visualizer</h2>
            <p class="card-sub">Percentage breakdown across all attendance statuses</p>

            @if($total > 0)
                <div class="bar-group">
                    <div class="bar-meta">
                        <span class="bar-label">
                            <span class="bar-dot" style="background:#3b82f6;"></span>
                            Present Rate
                        </span>
                        <span class="bar-pct">{{ $presentPercent }}%</span>
                    </div>
                    <div class="bar-track">
                        <div class="bar-fill present" data-width="{{ $presentPercent }}"></div>
                    </div>
                </div>

                <div class="bar-group">
                    <div class="bar-meta">
                        <span class="bar-label">
                            <span class="bar-dot" style="background:#fbbf24;"></span>
                            Late Rate
                        </span>
                        <span class="bar-pct">{{ $latePercent }}%</span>
                    </div>
                    <div class="bar-track">
                        <div class="bar-fill late" data-width="{{ $latePercent }}"></div>
                    </div>
                </div>

                <div class="bar-group">
                    <div class="bar-meta">
                        <span class="bar-label">
                            <span class="bar-dot" style="background:#f43f5e;"></span>
                            Absent Rate
                        </span>
                        <span class="bar-pct">{{ $absentPercent }}%</span>
                    </div>
                    <div class="bar-track">
                        <div class="bar-fill absent" data-width="{{ $absentPercent }}"></div>
                    </div>
                </div>
            @else
                <div class="zero-state">
                    <i class="fa-solid fa-chart-bar"></i>
                    No attendance records yet.
                </div>
            @endif
        </div>

        {{-- Bar Chart --}}
        <div class="info-card">
            <h2>Attendance Overview</h2>
            <p class="card-sub">Visual count comparison per attendance status</p>

            @if($total > 0)
                <div class="chart-wrap">
                    <div class="chart-area">
                        <div class="y-axis" id="y-axis"></div>
                        <div class="bars-container">
                            <div class="bar-col">
                                <div class="bar-value" style="color:#3b82f6;">{{ $presentCount }}</div>
                                <div class="bar-outer">
                                    <div class="bar-inner present"
                                         data-val="{{ $presentCount }}"
                                         data-max="{{ $total ?: 1 }}"></div>
                                </div>
                                <div class="bar-xlabel">Present</div>
                            </div>
                            <div class="bar-col">
                                <div class="bar-value" style="color:#f43f5e;">{{ $absentCount }}</div>
                                <div class="bar-outer">
                                    <div class="bar-inner absent"
                                         data-val="{{ $absentCount }}"
                                         data-max="{{ $total ?: 1 }}"></div>
                                </div>
                                <div class="bar-xlabel">Absent</div>
                            </div>
                            <div class="bar-col">
                                <div class="bar-value" style="color:#f59e0b;">{{ $lateCount }}</div>
                                <div class="bar-outer">
                                    <div class="bar-inner late"
                                         data-val="{{ $lateCount }}"
                                         data-max="{{ $total ?: 1 }}"></div>
                                </div>
                                <div class="bar-xlabel">Late</div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="zero-state">
                    <i class="fa-solid fa-chart-simple"></i>
                    No data to visualize.
                </div>
            @endif
        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Animate distribution bars
    document.querySelectorAll('.bar-fill[data-width]').forEach(el => {
        const w = el.dataset.width;
        setTimeout(() => { el.style.width = w + '%'; }, 120);
    });

    // Animate chart bars
    document.querySelectorAll('.bar-inner[data-val]').forEach(bar => {
        const val = parseInt(bar.dataset.val);
        const max = parseInt(bar.dataset.max);
        const pct = max > 0 ? (val / max) * 100 : 0;
        setTimeout(() => { bar.style.height = pct + '%'; }, 120);
    });

    // Build Y-axis
    const yAxis = document.getElementById('y-axis');
    if (yAxis) {
        const total = {{ $total ?: 1 }};
        const steps = 4;
        for (let i = steps; i >= 0; i--) {
            const tick = document.createElement('span');
            tick.textContent = Math.round((total / steps) * i);
            yAxis.appendChild(tick);
        }
    }
});
</script>

</x-layout>