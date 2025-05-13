@extends('layouts.app')

@section('title', 'Riwayat KPI')
@section('page_title', 'RIWAYAT DAN TREN KPI')

@section('styles')
<style>
    .kpi-history-container {
        background: var(--pln-accent-bg);
        border-radius: 16px;
        padding: 20px 25px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1),
                    0 5px 15px rgba(0, 123, 255, 0.1),
                    inset 0 -2px 2px rgba(255, 255, 255, 0.08);
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--pln-border);
        transition: all 0.4s ease;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }

    /* Glassmorphism effect dengan highlight gradient */
    .kpi-history-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--pln-blue), var(--pln-light-blue), var(--pln-blue));
        background-size: 200% 100%;
        animation: gradientShift 8s ease infinite;
    }

    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .history-banner {
        background: linear-gradient(135deg, var(--pln-blue), var(--pln-light-blue), #2b5797);
        color: white;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
        transition: all 0.3s ease;
        animation: fadeInDown 0.6s ease-out;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .history-banner:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 123, 255, 0.4);
    }

    .history-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.3), transparent 70%);
        z-index: 1;
    }

    .history-banner-content {
        position: relative;
        z-index: 2;
        animation: fadeInLeft 0.7s ease-out;
    }

    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .history-banner h4 {
        margin: 0;
        font-weight: 700;
        font-size: 1.6rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
    }

    .history-banner h4 i {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 1.2rem;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .history-banner:hover h4 i {
        transform: rotate(360deg);
        background: rgba(255, 255, 255, 0.3);
    }

    .history-banner .banner-subtitle {
        margin-top: 10px;
        opacity: 0.9;
        font-size: 1.05rem;
        font-weight: 400;
    }

    .history-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 0.95rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        position: relative;
        z-index: 2;
        animation: fadeInRight 0.7s ease-out;
        overflow: hidden;
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .history-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: 0.6s;
    }

    .history-badge:hover::before {
        left: 100%;
    }

    .history-badge:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
    }

    .history-badge i {
        margin-right: 8px;
        transition: transform 0.3s ease;
    }

    .history-badge:hover i {
        transform: rotate(15deg);
    }

    .link-to-current {
        background: rgba(255, 255, 255, 0.05);
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        border: 1px solid var(--pln-border);
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        animation: fadeIn 0.8s ease-out;
        position: relative;
        overflow: hidden;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .link-to-current::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(to bottom, var(--pln-blue), var(--pln-light-blue));
    }

    .link-to-current .btn {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .link-to-current .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: 0.5s;
    }

    .link-to-current .btn:hover::before {
        left: 100%;
    }

    .link-to-current .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0, 123, 255, 0.3);
    }

    .kpi-filter-container {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
        background: var(--pln-surface);
        padding: 15px 20px;
        border-radius: 12px;
        border: 1px solid var(--pln-border);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.8s ease-out;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .kpi-filter-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--pln-light-blue), var(--pln-blue));
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter-group select,
    .filter-group .form-control {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--pln-border);
        border-radius: 10px;
        padding: 8px 12px;
        color: var(--pln-text);
        transition: all 0.3s ease;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .filter-group select:focus,
    .filter-group .form-control:focus {
        border-color: var(--pln-light-blue);
        box-shadow: 0 0 0 3px rgba(0, 156, 222, 0.15);
        outline: none;
    }

    .filter-group .btn {
        border-radius: 10px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .filter-group .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: 0.5s;
    }

    .filter-group .btn:hover::before {
        left: 100%;
    }

    .filter-group .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0, 123, 255, 0.3);
    }

    .filter-group .btn i {
        transition: transform 0.3s ease;
    }

    .filter-group .btn:hover i {
        transform: rotate(15deg);
    }

    .data-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
        margin-bottom: 20px;
        animation: fadeIn 1s ease-out;
        table-layout: fixed;
    }

    .data-table th {
        background: linear-gradient(90deg, var(--pln-blue), var(--pln-light-blue));
        color: white;
        padding: 15px 15px;
        text-align: center;
        font-weight: 600;
        position: sticky;
        top: 0;
        z-index: 10;
        text-transform: uppercase;
        font-size: 13px;
        white-space: nowrap;
    }

    .data-table th:first-child {
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
        text-align: left;
        position: sticky;
        left: 0;
        z-index: 20;
    }

    .data-table th:last-child {
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    /* Gaya untuk sel tabel */
    .data-table td {
        padding: 15px;
        border: none;
        background: rgba(255, 255, 255, 0.03);
        transition: all 0.3s ease;
        vertical-align: middle;
        text-align: center;
    }

    .data-table tbody tr {
        transition: all 0.3s ease;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--pln-border);
    }

    .data-table tbody tr:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        background: rgba(255, 255, 255, 0.07);
    }

    .data-table tbody tr td:first-child {
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
        text-align: left;
        font-weight: 600;
        background: var(--pln-surface);
        position: sticky;
        left: 0;
        z-index: 5;
    }

    .data-table tbody tr td:last-child {
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    .data-table td .btn {
        border-radius: 10px;
        padding: 8px 12px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border: none;
        display: inline-flex;
        align-items: center;
        font-weight: 600;
        font-size: 13px;
        margin: 0 2px;
    }

    .data-table td .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: 0.5s;
    }

    .data-table td .btn:hover::before {
        left: 100%;
    }

    .data-table td .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .data-table td .btn i {
        margin-right: 6px;
        transition: transform 0.3s ease;
    }

    .data-table td .btn:hover i {
        transform: rotate(15deg);
    }

    .data-table tr:last-child td {
        border-bottom: none;
    }

    .data-table .text-success {
        color: #28a745 !important;
        font-weight: 600;
    }

    .data-table .text-warning {
        color: #ffc107 !important;
        font-weight: 600;
    }

    .data-table .text-danger {
        color: #dc3545 !important;
        font-weight: 600;
    }

    .data-table i.fa-check-circle {
        margin-left: 5px;
        font-size: 0.9rem;
        animation: pulse 1.5s infinite ease-in-out;
    }

    @keyframes pulse {
        0% { opacity: 0.6; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.1); }
        100% { opacity: 0.6; transform: scale(1); }
    }

    /* Styles for Statistics Cards */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
        animation: fadeInUp 0.8s ease-out;
    }

    .stat-card {
        background: var(--pln-surface);
        border-radius: 16px;
        padding: 25px;
        border: 1px solid var(--pln-border);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, var(--pln-blue), var(--pln-light-blue));
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    .stat-card-title {
        font-size: 1.05rem;
        margin-bottom: 15px;
        color: var(--pln-text-secondary);
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .stat-card-title i {
        margin-right: 10px;
        background: linear-gradient(135deg, var(--pln-blue), var(--pln-light-blue));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 1.2rem;
        transition: transform 0.3s ease;
    }

    .stat-card:hover .stat-card-title i {
        transform: rotate(15deg);
    }

    .stat-card-value {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--pln-blue);
        line-height: 1.1;
        position: relative;
        display: inline-block;
    }

    .stat-card-value::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 40px;
        height: 3px;
        background: linear-gradient(90deg, var(--pln-light-blue), transparent);
        border-radius: 2px;
        transition: width 0.3s ease;
    }

    .stat-card:hover .stat-card-value::after {
        width: 100%;
    }

    .stat-card-desc {
        font-size: 0.95rem;
        color: var(--pln-text-secondary);
        margin-top: auto;
        line-height: 1.5;
    }

    .progress-bar {
        height: 10px;
        width: 100%;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        margin-top: 15px;
        overflow: hidden;
        box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .progress-bar-filled {
        height: 100%;
        border-radius: 8px;
        transition: width 1.2s cubic-bezier(0.65, 0, 0.35, 1);
    }

    .progress-success {
        background: linear-gradient(90deg, #28a745, #20c997);
    }

    .progress-warning {
        background: linear-gradient(90deg, #ffc107, #fd7e14);
    }

    .progress-danger {
        background: linear-gradient(90deg, #dc3545, #e83e8c);
    }

    .kpi-chart-container {
        height: 400px;
        margin-top: 35px;
        margin-bottom: 35px;
        background: var(--pln-surface);
        border-radius: 16px;
        padding: 25px;
        border: 1px solid var(--pln-border);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        animation: fadeIn 1s ease-out;
    }

    .kpi-chart-container:hover {
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        transform: translateY(-5px);
    }

    .seasonal-label {
        position: absolute;
        top: -10px;
        left: 20px;
        background: linear-gradient(90deg, var(--pln-blue), var(--pln-light-blue));
        color: white;
        padding: 8px 20px;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 600;
        z-index: 5;
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        letter-spacing: 0.5px;
    }

    .export-buttons {
        margin: 30px 0;
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .export-buttons .btn {
        padding: 10px 20px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        font-weight: 600;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
        border: none;
    }

    .export-buttons .btn-success {
        background: linear-gradient(135deg, #28a745, #20c997);
    }

    .export-buttons .btn-danger {
        background: linear-gradient(135deg, #dc3545, #e83e8c);
    }

    .export-buttons .btn-secondary {
        background: linear-gradient(135deg, #6c757d, #495057);
    }

    .export-buttons .btn i {
        margin-right: 8px;
        font-size: 1.1rem;
    }

    .export-buttons .btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    /* Styles for Statistics Cards */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: var(--pln-surface);
        border-radius: 16px;
        padding: 25px;
        border: 1px solid var(--pln-border);
        box-shadow: 0 8px 20px var(--pln-shadow);
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, var(--pln-blue), var(--pln-light-blue));
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px var(--pln-shadow);
    }

    .stat-card-title {
        font-size: 1.05rem;
        margin-bottom: 15px;
        color: var(--pln-text-secondary);
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .stat-card-title i {
        margin-right: 10px;
        background: linear-gradient(135deg, var(--pln-blue), var(--pln-light-blue));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 1.2rem;
    }

    .stat-card-value {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--pln-blue);
        line-height: 1.1;
    }

    .stat-card-desc {
        font-size: 0.95rem;
        color: var(--pln-text-secondary);
        margin-top: auto;
        line-height: 1.5;
    }

    .progress-bar {
        height: 10px;
        width: 100%;
        background: var(--pln-border);
        border-radius: 8px;
        margin-top: 15px;
        overflow: hidden;
        box-shadow: inset 0 2px 5px rgba(0,0,0,0.1);
    }

    .progress-bar-filled {
        height: 100%;
        border-radius: 8px;
        transition: width 1s ease;
    }

    .progress-success {
        background: linear-gradient(90deg, #28a745, #20c997);
    }

    .progress-warning {
        background: linear-gradient(90deg, #ffc107, #fd7e14);
    }

    .progress-danger {
        background: linear-gradient(90deg, #dc3545, #e83e8c);
    }

    .stats-bidang-container {
        margin-top: 20px;
        background: var(--pln-surface);
        border-radius: 16px;
        padding: 25px;
        border: 1px solid var(--pln-border);
        box-shadow: 0 8px 20px var(--pln-shadow);
        position: relative;
        overflow: hidden;
    }

    .stats-bidang-container::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(0, 156, 222, 0.08), transparent 70%);
        border-radius: 50%;
    }

    .stats-bidang-title {
        font-size: 1.2rem;
        margin-bottom: 20px;
        font-weight: 600;
        color: var(--pln-blue);
        display: flex;
        align-items: center;
        position: relative;
    }

    .stats-bidang-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -8px;
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, var(--pln-blue), var(--pln-light-blue));
        border-radius: 3px;
    }

    .stats-bidang-title i {
        margin-right: 10px;
        background: var(--pln-light-blue);
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        box-shadow: 0 4px 8px rgba(0, 156, 222, 0.3);
    }

    .bidang-stats-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 15px;
    }

    .bidang-stat-item {
        padding: 18px;
        background: var(--pln-accent-bg);
        border-radius: 12px;
        border-left: 4px solid var(--pln-blue);
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }

    .bidang-stat-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        border-left-color: var(--pln-light-blue);
    }

    .bidang-stat-item::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 30px;
        height: 30px;
        background: linear-gradient(135deg, transparent, rgba(0, 156, 222, 0.1));
        border-radius: 0 0 12px 0;
    }

    .bidang-name {
        font-weight: 600;
        margin-bottom: 12px;
        color: var(--pln-text);
        display: flex;
        align-items: center;
        position: relative;
    }

    .bidang-name::before {
        content: '';
        display: inline-block;
        width: 8px;
        height: 8px;
        background: var(--pln-light-blue);
        border-radius: 50%;
        margin-right: 8px;
        box-shadow: 0 0 0 2px rgba(0, 156, 222, 0.2);
    }

    .bidang-metrics {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        font-size: 0.9rem;
    }

    .bidang-metric {
        background: var(--pln-surface);
        padding: 6px 10px;
        border-radius: 8px;
        white-space: nowrap;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .bidang-metric:hover {
        background: rgba(0, 156, 222, 0.1);
        transform: translateY(-2px);
    }

    .bidang-metric i {
        margin-right: 6px;
        color: var(--pln-light-blue);
        font-size: 0.8rem;
    }

    .table-responsive {
        overflow-x: auto;
        max-height: 650px;
        position: relative;
        border-radius: 12px;
        box-shadow: 0 8px 25px var(--pln-shadow);
        margin: 30px 0;
    }

    .table-responsive::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: rgba(0,0,0,0.05);
        border-radius: 10px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: var(--pln-light-blue);
        border-radius: 10px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: var(--pln-blue);
    }
</style>
@endsection

@section('content')
<div class="kpi-history-container glass-card gradient-border-top fade-in">
    <div class="history-banner fade-in-down">
        <div class="history-banner-content fade-in-left">
            <h4><i class="fas fa-chart-line"></i>Analisis Riwayat & Tren KPI</h4>
            <div class="banner-subtitle">Evaluasi performa sepanjang periode {{ $tahun }}</div>
        </div>
        <div class="history-badge fade-in-right">
            <i class="fas fa-history"></i> Tren dan perbandingan antar periode
        </div>
    </div>

    <div class="link-to-current glass-effect fade-in-up">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Halaman ini menampilkan data historis dan tren KPI sepanjang periode.</strong>
        Untuk melihat <strong>status KPI terkini</strong>, silakan kunjungi
        <a href="{{ route('kpi.index') }}" class="btn btn-sm btn-primary ms-2 shadow-hover">
            <i class="fas fa-tasks me-1"></i> Laporan Status KPI
        </a>
    </div>

    <div class="kpi-filter-container glass-effect fade-in-up">
        <div class="filter-group">
            <form method="GET" action="{{ route('kpi.history') }}" class="d-flex align-items-center">
                <label for="tahun" class="me-2">Tahun:</label>
                <select name="tahun" id="tahun" class="form-control me-2" style="width: 120px;">
                                @for($i = date('Y'); $i >= 2020; $i--)
                                    <option value="{{ $i }}" {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                <button type="submit" class="btn btn-primary shadow-hover">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </form>
        </div>

        <div class="filter-group">
            <label for="showPerPage" class="me-2">Tampilkan:</label>
            <select id="showPerPage" class="form-control" style="width: 100px;">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </div>

    @if(isset($statistics))
    <div class="stats-container fade-in-up">
        <div class="stat-card glass-card shadow-hover">
            <div class="stat-card-title">
                <i class="fas fa-chart-line"></i>Rata-rata Pencapaian
            </div>
            <div class="stat-card-value">{{ $statistics['performa_ratarata'] }}%</div>
            <div class="stat-card-desc">
                Rata-rata pencapaian semua indikator KPI sepanjang periode {{ $tahun }}
            </div>
            <div class="progress-bar">
                @php
                    $progressClass = 'progress-danger';
                    if ($statistics['performa_ratarata'] >= 80) {
                        $progressClass = 'progress-success';
                    } elseif ($statistics['performa_ratarata'] >= 60) {
                        $progressClass = 'progress-warning';
                    }
                @endphp
                <div class="progress-bar-filled {{ $progressClass }}" style="width: 0%" data-width="{{ $statistics['performa_ratarata'] }}%"></div>
            </div>
        </div>

        <div class="stat-card glass-card shadow-hover">
            <div class="stat-card-title">
                <i class="fas fa-tasks"></i>Indikator Tercapai
            </div>
            <div class="stat-card-value">{{ $statistics['indikator_tercapai'] }}/{{ $statistics['total_indikator'] }}</div>
            <div class="stat-card-desc">
                Jumlah indikator yang mencapai target (>= 80%) selama periode {{ $tahun }}
            </div>
            <div class="progress-bar">
                @php
                    $progressClass = 'progress-danger';
                    if ($statistics['persentase_tercapai'] >= 80) {
                        $progressClass = 'progress-success';
                    } elseif ($statistics['persentase_tercapai'] >= 60) {
                        $progressClass = 'progress-warning';
                    }
                @endphp
                <div class="progress-bar-filled {{ $progressClass }}" style="width: 0%" data-width="{{ $statistics['persentase_tercapai'] }}%"></div>
            </div>
        </div>

        <div class="stat-card glass-card shadow-hover">
            <div class="stat-card-title">
                <i class="fas fa-check-circle"></i>Terverifikasi
            </div>
            <div class="stat-card-value">{{ $statistics['persentase_diverifikasi'] }}%</div>
            <div class="stat-card-desc">
                Persentase data yang sudah diverifikasi dari seluruh data KPI
            </div>
            <div class="progress-bar">
                @php
                    $progressClass = 'progress-danger';
                    if ($statistics['persentase_diverifikasi'] >= 80) {
                        $progressClass = 'progress-success';
                    } elseif ($statistics['persentase_diverifikasi'] >= 60) {
                        $progressClass = 'progress-warning';
                    }
                @endphp
                <div class="progress-bar-filled {{ $progressClass }}" style="width: 0%" data-width="{{ $statistics['persentase_diverifikasi'] }}%"></div>
            </div>
        </div>

        <div class="stat-card glass-card shadow-hover">
            <div class="stat-card-title">
                <i class="fas fa-calendar-alt"></i>Tahun Data
            </div>
            <div class="stat-card-value">{{ $tahun }}</div>
            <div class="stat-card-desc">
                Analisis KPI tahun {{ $tahun }} menampilkan tren performa sepanjang periode
            </div>
        </div>
    </div>

    @if(isset($statistics['bidang_stats']))
    <div class="stats-bidang-container glass-card fade-in-up">
        <div class="stats-bidang-title">
            <i class="fas fa-building"></i>Performa per Bidang
                        </div>
        <div class="bidang-stats-list">
            @php
                // Pastikan bidang_stats selalu berupa array dan bukan objek collection
                $bidangStatsArr = is_array($statistics['bidang_stats']) ?
                    $statistics['bidang_stats'] :
                    (method_exists($statistics['bidang_stats'], 'toArray') ?
                        $statistics['bidang_stats']->toArray() :
                        (array)$statistics['bidang_stats']);
            @endphp

            @foreach($bidangStatsArr as $bidangStat)
            <div class="bidang-stat-item shadow-hover">
                <div class="bidang-name">{{ $bidangStat['bidang'] }}</div>
                <div class="bidang-metrics">
                    <span class="bidang-metric">
                        <i class="fas fa-chart-line"></i> {{ $bidangStat['performa_ratarata'] }}%
                    </span>
                    <span class="bidang-metric">
                        <i class="fas fa-tasks"></i> {{ $bidangStat['indikator_tercapai'] }}/{{ $bidangStat['total_indikator'] }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endif

    <div class="export-buttons fade-in">
        <button class="btn btn-success shadow-hover" onclick="exportToExcel()">
            <i class="fas fa-file-excel"></i> Export Excel
        </button>
        <button class="btn btn-danger shadow-hover" onclick="exportToPdf()">
            <i class="fas fa-file-pdf"></i> Export PDF
        </button>
        <a href="{{ route('kpi.index') }}" class="btn btn-secondary shadow-hover">
            <i class="fas fa-external-link-alt"></i> Lihat Status Saat Ini
        </a>
    </div>

    @if(isset($pilars))
        <div class="kpi-chart-container glass-card position-relative fade-in">
            <div class="seasonal-label"><i class="fas fa-chart-line mr-2"></i> Tren Bulanan</div>
            <canvas id="kpiTrendChart"></canvas>
            </div>

        <div class="table-responsive fade-in">
            <table class="data-table modern-table">
                        <thead>
                            <tr>
                        <th>Indikator</th>
                                <th>Jan</th>
                                <th>Feb</th>
                                <th>Mar</th>
                                <th>Apr</th>
                                <th>Mei</th>
                                <th>Jun</th>
                                <th>Jul</th>
                                <th>Agu</th>
                                <th>Sep</th>
                                <th>Okt</th>
                                <th>Nov</th>
                                <th>Des</th>
                                <th>Rata-rata</th>
                            </tr>
                        </thead>
                        <tbody>
                    @foreach($pilars as $pilar)
                        <tr>
                            <td colspan="14" style="background: #f8f9fa; font-weight: bold; color: var(--pln-blue);">
                                {{ $pilar->kode }}. {{ $pilar->nama }}
                            </td>
                        </tr>
                        @foreach($pilar->indikators as $indikator)
                            <tr>
                                <td style="padding-left: 25px;">{{ $indikator->kode }}. {{ $indikator->nama }}</td>
                                @php
                                    $total = 0;
                                    $count = 0;
                                @endphp
                                @foreach($indikator->historiData as $data)
                                    @php
                                        $nilai = $data['nilai'];
                                            $class = '';
                                            if($nilai >= 80) $class = 'text-success';
                                            elseif($nilai >= 60) $class = 'text-warning';
                                            elseif($nilai > 0) $class = 'text-danger';

                                        if($nilai > 0) {
                                            $total += $nilai;
                                            $count++;
                                        }
                                        @endphp
                                    <td class="{{ $class }}">
                                        {{ $nilai > 0 ? number_format($nilai, 2).'%' : '-' }}
                                        @if($data['diverifikasi'] && $nilai > 0)
                                            <i class="fas fa-check-circle text-success pulse" title="Terverifikasi"></i>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="font-weight-bold">
                                    {{ $count > 0 ? number_format($total / $count, 2).'%' : '-' }}
                                    <a href="{{ route('kpi.detail.riwayat', ['indikatorId' => $indikator->id, 'tahun' => $tahun]) }}" class="btn btn-sm btn-outline-primary ms-2 shadow-hover icon-spin" title="Lihat Detail">
                                        <i class="fas fa-search"></i>
                                    </a>
                                </td>
                                </tr>
                        @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>

        <div class="pagination-controls fade-in">
            <div class="showing-info">
                Menampilkan <span id="showingStart">1</span> sampai <span id="showingEnd">10</span> dari <span id="totalItems">{{ $pilars->sum(function($pilar) { return $pilar->indikators->count(); }) }}</span> indikator
            </div>
            <div class="page-buttons">
                <button class="btn-page shadow-hover" id="prevPage" onclick="changePage(-1)">
                    <i class="fas fa-chevron-left"></i> Sebelumnya
                </button>
                <button class="btn-page shadow-hover" id="nextPage" onclick="changePage(1)">
                    Selanjutnya <i class="fas fa-chevron-right"></i>
                </button>
                </div>
        </div>
    @else
        <div class="alert alert-info fade-in">
            <i class="fas fa-info-circle me-2"></i> Tidak ada data riwayat KPI untuk ditampilkan.
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animasi untuk baris tabel
        const tableRows = document.querySelectorAll('.data-table tbody tr');
        tableRows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';

            setTimeout(() => {
                row.style.transition = 'all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, 100 + (index * 50));
        });

        // Animasi untuk header tabel
        const tableHeaders = document.querySelectorAll('.data-table th');
        tableHeaders.forEach((header, index) => {
            header.style.opacity = '0';
            header.style.transform = 'translateY(-20px)';

            setTimeout(() => {
                header.style.transition = 'all 0.4s ease';
                header.style.opacity = '1';
                header.style.transform = 'translateY(0)';
            }, 50 + (index * 30));
        });

        // Animasi untuk progress bar
        const progressBars = document.querySelectorAll('.progress-bar-filled');
        setTimeout(() => {
            progressBars.forEach(bar => {
                const width = bar.getAttribute('data-width') || bar.style.width;
                bar.style.width = '0%';

                setTimeout(() => {
                    bar.style.transition = 'width 1.5s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                    bar.style.width = width;
                }, 300);
            });
        }, 500);

        // Efek ripple pada tombol
        const buttons = document.querySelectorAll('.btn, .btn-page, .history-badge');

        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                if (this.classList.contains('disabled')) return;
                if (this.type === 'submit') return;

                e.preventDefault();

                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                const circle = document.createElement('span');
                circle.classList.add('ripple');
                circle.style.left = x + 'px';
                circle.style.top = y + 'px';

                this.appendChild(circle);

                setTimeout(() => {
                    circle.remove();

                    // Navigasi ke halaman jika ini adalah link
                    if (this.tagName === 'A' && this.href) {
                        window.location.href = this.href;
                    }
                }, 600);
            });
        });

        // Animasi hover untuk stat cards
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.querySelectorAll('.stat-card-title i').forEach(icon => {
                    icon.style.transform = 'rotate(15deg)';
                });
            });

            card.addEventListener('mouseleave', function() {
                this.querySelectorAll('.stat-card-title i').forEach(icon => {
                    icon.style.transform = 'rotate(0deg)';
                });
            });
        });

        // Filter change handler
        const tahunSelect = document.getElementById('tahun');
        if (tahunSelect) {
            tahunSelect.addEventListener('change', function() {
                // Tambahkan animasi loading sebelum submit
                const formParent = this.closest('.filter-group');
                const loadingSpinner = document.createElement('div');
                loadingSpinner.classList.add('spinner-border', 'spinner-border-sm', 'text-primary', 'ms-2');
                loadingSpinner.setAttribute('role', 'status');
                formParent.appendChild(loadingSpinner);

                setTimeout(() => {
                    this.closest('form').submit();
                }, 300);
            });
        }

        // Show per page handler dengan efek visual
        const showPerPageSelect = document.getElementById('showPerPage');
        if (showPerPageSelect) {
            showPerPageSelect.addEventListener('change', function() {
                // Tambahkan animasi fadeOut ke tabel sebelum pindah halaman
                const tableContainer = document.querySelector('.table-responsive');
                tableContainer.style.transition = 'opacity 0.3s ease';
                tableContainer.style.opacity = '0.5';

                const urlParams = new URLSearchParams(window.location.search);
                urlParams.set('perPage', this.value);

                setTimeout(() => {
                    window.location.search = urlParams.toString();
                }, 300);
            });

            // Set initial value from URL
            const urlParams = new URLSearchParams(window.location.search);
            const perPage = urlParams.get('perPage');
            if (perPage) {
                showPerPageSelect.value = perPage;
            }
        }

        // Inisialisasi chart jika ada
        const chartCanvas = document.getElementById('kpiTrendChart');
        if (chartCanvas) {
            initializeChart();
        }
    });

    // Fungsi untuk inisialisasi chart dengan animasi yang lebih baik
    function initializeChart() {
        // Implementasi chart sesuai kebutuhan
        const ctx = document.getElementById('kpiTrendChart').getContext('2d');
        // Inisialisasi chart dengan data yang sesuai...
    }

    // Fungsi untuk paginasi dengan animasi
    function changePage(direction) {
        // Implementasikan paginasi dengan efek transisi halus
        const tableContainer = document.querySelector('.table-responsive');
        tableContainer.style.transition = 'opacity 0.3s ease';
        tableContainer.style.opacity = '0.5';

        setTimeout(() => {
            // Logika paginasi...
            tableContainer.style.opacity = '1';
        }, 300);
    }

    // Fungsi export data dengan animasi loading
    function exportToExcel() {
        // Tambahkan animasi loading
        const btn = event.target.closest('.btn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        btn.disabled = true;

        setTimeout(() => {
            // Implementasi export Excel
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 1000);
    }

    function exportToPdf() {
        // Tambahkan animasi loading
        const btn = event.target.closest('.btn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        btn.disabled = true;

        setTimeout(() => {
            // Implementasi export PDF
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 1000);
    }
</script>
@endsection
