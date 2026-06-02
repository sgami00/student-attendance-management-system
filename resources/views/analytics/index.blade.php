<x-layout>

@php
    $total = $presentCount + $absentCount + $lateCount;
    $presentPercent = $total > 0 ? round(($presentCount / $total) * 100) : 0;
    $absentPercent = $total > 0 ? round(($absentCount / $total) * 100) : 0;
    $latePercent = $total > 0 ? round(($lateCount / $total) * 100) : 0;
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap');

    .analytics-wrap {
        font-family: 'DM Sans', sans-serif;
        padding: 2rem;
        background: #f4f4f0;
        min-height: 100vh;
    }

    .analytics-header {
        margin-bottom: 2.5rem;
    }

    .analytics-header h1 {
        font-family: 'Syne', sans-serif;
        font-size: 2.6rem;
        font-weight: 800;
        color: #111;
        letter-spacing: -0.03em;
        line-height: 1;
        margin-bottom: 0.4rem;
    }

    .analytics-header p {
        font-size: 0.9rem;
        color: #888;
        font-weight: 400;
        letter-spacing: 0.01em;
    }

    /* ── Stat Cards ── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
        .stat-grid { grid-template-columns: 1fr; }
    }

    .stat-card {
        border-radius: 16px;
        padding: 1.75rem 1.75rem 1.5rem;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.12);
    }

    .stat-card.present {
        background: #1a1a1a;
        color: #fff;
    }

    .stat-card.absent {
        background: #fff;
        color: #111;
        border: 1.5px solid #e5e5e5;
    }

    .stat-card.late {
        background: #fff;
        color: #111;
        border: 1.5px solid #e5e5e5;
    }

    .stat-card-label {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        opacity: 0.5;
        margin-bottom: 1rem;
    }

    .stat-card.present .stat-card-label { opacity: 0.6; color: #fff; }

    .stat-number {
        font-family: 'Syne', sans-serif;
        font-size: 4rem;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -0.04em;
        margin-bottom: 1rem;
    }

    .stat-card.present .stat-number { color: #4ade80; }
    .stat-card.absent .stat-number { color: #f43f5e; }
    .stat-card.late .stat-number { color: #f59e0b; }

    .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.78rem;
        font-weight: 500;
        padding: 0.3rem 0.75rem;
        border-radius: 999px;
    }

    .stat-card.present .stat-badge {
        background: rgba(74, 222, 128, 0.15);
        color: #4ade80;
    }

    .stat-card.absent .stat-badge {
        background: #fff0f3;
        color: #f43f5e;
    }

    .stat-card.late .stat-badge {
        background: #fffbeb;
        color: #d97706;
    }

    .stat-card-deco {
        position: absolute;
        bottom: -20px;
        right: -20px;
        font-size: 6rem;
        opacity: 0.05;
        font-family: 'Syne', sans-serif;
        font-weight: 800;
        line-height: 1;
        pointer-events: none;
    }

    /* ── Distribution ── */
    .dist-card {
        background: #fff;
        border-radius: 16px;
        padding: 2rem;
        border: 1.5px solid #e5e5e5;
    }

    .dist-card h2 {
        font-family: 'Syne', sans-serif;
        font-size: 1.2rem;
        font-weight: 700;
        color: #111;
        letter-spacing: -0.02em;
        margin-bottom: 0.25rem;
    }

    .dist-card .dist-sub {
        font-size: 0.78rem;
        color: #aaa;
        margin-bottom: 2rem;
    }

    .bar-group {
        margin-bottom: 1.5rem;
    }

    .bar-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .bar-label {
        font-size: 0.82rem;
        font-weight: 500;
        color: #555;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .bar-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .bar-pct {
        font-family: 'Syne', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        color: #111;
    }

    .bar-track {
        background: #f0f0ec;
        border-radius: 999px;
        height: 10px;
        overflow: hidden;
    }

    .bar-fill {
        height: 100%;
        border-radius: 999px;
        transition: width 1s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .bar-fill.present { background: linear-gradient(90deg, #22c55e, #4ade80); }
    .bar-fill.absent  { background: linear-gradient(90deg, #e11d48, #f43f5e); }
    .bar-fill.late    { background: linear-gradient(90deg, #d97706, #fbbf24); }

    /* ── Total pill ── */
    .total-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #111;
        color: #fff;
        font-family: 'Syne', sans-serif;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.4rem 1rem;
        border-radius: 999px;
        margin-bottom: 2rem;
        letter-spacing: 0.04em;
    }

    .total-pill span {
        background: #4ade80;
        color: #111;
        border-radius: 999px;
        padding: 0.1rem 0.5rem;
        font-size: 0.78rem;
    }
</style>

<div class="analytics-wrap">

    {{-- Header --}}
    <div class="analytics-header">
        <h1>Attendance Analytics</h1>
        <p>Real-time breakdown of campus-wide student presence matrices.</p>
    </div>

    {{-- Total pill --}}
    <div class="total-pill">
        TOTAL RECORDS <span>{{ $total }}</span>
    </div>

    {{-- Stat Cards --}}
    <div class="stat-grid">

        <div class="stat-card present">
            <div class="stat-card-label">Total Present</div>
            <div class="stat-number">{{ $presentCount }}</div>
            <div class="stat-badge">↑ {{ $presentPercent }}% of total logs</div>
            <div class="stat-card-deco">✓</div>
        </div>

        <div class="stat-card absent">
            <div class="stat-card-label">Total Absent</div>
            <div class="stat-number">{{ $absentCount }}</div>
            <div class="stat-badge">↓ {{ $absentPercent }}% risk rate</div>
            <div class="stat-card-deco">✗</div>
        </div>

        <div class="stat-card late">
            <div class="stat-card-label">Total Late</div>
            <div class="stat-number">{{ $lateCount }}</div>
            <div class="stat-badge">→ {{ $latePercent }}% punctuality variance</div>
            <div class="stat-card-deco">!</div>
        </div>

    </div>

    {{-- Distribution --}}
    <div class="dist-card">
        <h2>Distribution Visualizer</h2>
        <p class="dist-sub">Percentage breakdown across all attendance statuses</p>

        <div class="bar-group">
            <div class="bar-meta">
                <span class="bar-label">
                    <span class="bar-dot" style="background:#4ade80;"></span>
                    Present Rate
                </span>
                <span class="bar-pct">{{ $presentPercent }}%</span>
            </div>
            <div class="bar-track">
                <div class="bar-fill present" style="width: {{ $presentPercent }}%"></div>
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
                <div class="bar-fill late" style="width: {{ $latePercent }}%"></div>
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
                <div class="bar-fill absent" style="width: {{ $absentPercent }}%"></div>
            </div>
        </div>

    </div>

    {{-- Bar Chart --}}
    <div class="dist-card" style="margin-top: 1.25rem;">
        <h2>Attendance Overview</h2>
        <p class="dist-sub">Visual count comparison per attendance status</p>

        <div class="chart-wrap">
            <div class="chart-area">
                {{-- Y-axis labels --}}
                <div class="y-axis" id="y-axis"></div>

                {{-- Bars --}}
                <div class="bars-container">
                    <div class="bar-col">
                        <div class="bar-value" style="color:#4ade80;">{{ $presentCount }}</div>
                        <div class="bar-outer">
                            <div class="bar-inner present" id="bar-present" data-val="{{ $presentCount }}" data-max="{{ $total ?: 1 }}"></div>
                        </div>
                        <div class="bar-xlabel">Present</div>
                    </div>
                    <div class="bar-col">
                        <div class="bar-value" style="color:#f43f5e;">{{ $absentCount }}</div>
                        <div class="bar-outer">
                            <div class="bar-inner absent" id="bar-absent" data-val="{{ $absentCount }}" data-max="{{ $total ?: 1 }}"></div>
                        </div>
                        <div class="bar-xlabel">Absent</div>
                    </div>
                    <div class="bar-col">
                        <div class="bar-value" style="color:#f59e0b;">{{ $lateCount }}</div>
                        <div class="bar-outer">
                            <div class="bar-inner late" id="bar-late" data-val="{{ $lateCount }}" data-max="{{ $total ?: 1 }}"></div>
                        </div>
                        <div class="bar-xlabel">Late</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    .chart-wrap { padding: 0.5rem 0 0; }
    .chart-area { display: flex; gap: 0; align-items: stretch; height: 220px; }

    .y-axis {
        display: flex;
        flex-direction: column-reverse;
        justify-content: space-between;
        padding-bottom: 28px;
        padding-right: 10px;
        min-width: 28px;
        text-align: right;
        font-size: 11px;
        color: #aaa;
    }

    .bars-container {
        flex: 1;
        display: flex;
        align-items: flex-end;
        gap: 2rem;
        padding-bottom: 0;
        border-left: 1.5px solid #e5e5e5;
        border-bottom: 1.5px solid #e5e5e5;
        padding: 0 2rem 0 1.5rem;
        position: relative;
    }

    .bar-col {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        height: 100%;
        justify-content: flex-end;
    }

    .bar-value {
        font-family: 'Syne', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .bar-outer {
        width: 100%;
        max-width: 80px;
        background: #f0f0ec;
        border-radius: 8px 8px 0 0;
        height: 160px;
        display: flex;
        align-items: flex-end;
        overflow: hidden;
    }

    .bar-inner {
        width: 100%;
        border-radius: 8px 8px 0 0;
        height: 0%;
        transition: height 1s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .bar-inner.present { background: linear-gradient(180deg, #4ade80, #22c55e); }
    .bar-inner.absent  { background: linear-gradient(180deg, #f87171, #f43f5e); }
    .bar-inner.late    { background: linear-gradient(180deg, #fbbf24, #f59e0b); }

    .bar-xlabel {
        font-size: 12px;
        font-weight: 500;
        color: #888;
        margin-top: 8px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bars = document.querySelectorAll('.bar-inner');
        const maxVal = Math.max(...Array.from(bars).map(b => parseInt(b.dataset.max)));

        bars.forEach(bar => {
            const val = parseInt(bar.dataset.val);
            const max = parseInt(bar.dataset.max);
            const pct = max > 0 ? (val / max) * 100 : 0;
            setTimeout(() => { bar.style.height = pct + '%'; }, 100);
        });

        // Y-axis ticks
        const yAxis = document.getElementById('y-axis');
        const total = parseInt({{ $total ?: 1 }});
        const steps = 4;
        for (let i = steps; i >= 0; i--) {
            const tick = document.createElement('span');
            tick.textContent = Math.round((total / steps) * i);
            yAxis.appendChild(tick);
        }
    });
</script>

</x-layout>