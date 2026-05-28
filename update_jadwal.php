/* ===== GLOBAL STYLES ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

body {
    font-family: 'Inter', sans-serif;
    background: #f4f8fc;
    color: #334758;
    line-height: 1.6;
}

/* ===== NAVBAR ===== */
.navbar {
    background: linear-gradient(135deg, #0f2027 0%, #1c92d2 45%, #2c5364 100%);
    padding: 24px 20px;
    color: white;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

.nav-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
    flex-wrap: wrap;
    gap: 12px;
}

.nav-top h2 {
    margin: 0;
    font-size: 26px;
    font-weight: 700;
}

.nav-top small {
    display: block;
    color: rgba(255, 255, 255, 0.8);
    font-size: 13px;
}

.nav-top a {
    padding: 10px 18px;
    background: rgba(255, 255, 255, 0.15);
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.25);
}

.nav-top a:hover {
    background: rgba(255, 255, 255, 0.25);
    border-color: rgba(255, 255, 255, 0.35);
}

.nav-menu {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    font-size: 14px;
}

.nav-menu a {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 12px;
    color: white;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.25s ease;
    border: 1px solid rgba(255, 255, 255, 0.15);
    background: rgba(255, 255, 255, 0.08);
}

.nav-menu a:hover,
.nav-menu a.active {
    background: rgba(255, 255, 255, 0.18);
    border-color: rgba(255, 255, 255, 0.3);
}

.nav-menu a i {
    font-size: 16px;
}

/* ===== CONTAINER ===== */
.container {
    padding: 24px;
    max-width: 1200px;
    margin: 0 auto;
}

/* ===== CARDS ===== */
.card {
    background: white;
    padding: 28px;
    border-radius: 18px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    margin-bottom: 24px;
    border: 1px solid rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

.card h2 {
    margin: 0 0 20px;
    font-size: 24px;
    color: #0f2027;
    font-weight: 700;
}

.card h3 {
    margin: 0 0 20px;
    font-size: 20px;
    color: #0f2027;
    font-weight: 700;
}

.card h4 {
    margin: 0 0 12px;
    font-size: 16px;
    color: #0f2027;
    font-weight: 700;
}

.card p {
    color: #556b86;
    line-height: 1.6;
    margin: 8px 0;
}

/* ===== BUTTONS ===== */
button,
.btn,
.button {
    padding: 12px 20px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, #1c92d2, #0f456c);
    color: white;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-family: 'Inter', sans-serif;
    font-weight: 700;
    font-size: 14px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(28, 146, 210, 0.2);
}

button:hover,
.btn:hover,
.button:hover {
    background: linear-gradient(135deg, #0f456c, #1c92d2);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(28, 146, 210, 0.3);
}

button:active,
.btn:active,
.button:active {
    transform: translateY(0);
}

button.secondary,
.btn.secondary,
.button.secondary {
    background: rgba(28, 146, 210, 0.1);
    color: #1c92d2;
    box-shadow: none;
    border: 1.5px solid #1c92d2;
}

button.secondary:hover,
.btn.secondary:hover,
.button.secondary:hover {
    background: rgba(28, 146, 210, 0.15);
}

/* ===== STATUS BADGES ===== */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-success {
    background: #d4f4dd;
    color: #2d7a3e;
}

.status-pending {
    background: #cfe2ff;
    color: #084298;
}

.status-warning {
    background: #fff3d6;
    color: #c17d11;
}

.status-error {
    background: #ffe6e6;
    color: #c92a2a;
}

/* ===== ALERTS ===== */
.alert {
    padding: 14px 18px;
    border-radius: 12px;
    margin-bottom: 24px;
    border-left: 4px solid;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.alert i {
    margin-top: 2px;
    font-size: 16px;
}

.alert.success {
    background: #d4f4dd;
    color: #2d7a3e;
    border-color: #2d7a3e;
}

.alert.warning {
    background: #fff3d6;
    color: #c17d11;
    border-color: #c17d11;
}

.alert.error {
    background: #ffe6e6;
    color: #c92a2a;
    border-color: #c92a2a;
}

.alert.info {
    background: #e7f5ff;
    color: #1971c2;
    border-color: #1971c2;
}

/* ===== TABLES ===== */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 16px;
}

thead {
    background: linear-gradient(135deg, #1c92d2, #0f456c);
    color: white;
}

th {
    padding: 14px;
    text-align: left;
    font-weight: 700;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

td {
    padding: 12px 14px;
    border-bottom: 1px solid #f0f4f9;
    color: #556b86;
}

tbody tr:hover {
    background: #f9fbfd;
}

tbody tr:last-child td {
    border-bottom: none;
}

/* ===== FORMS ===== */
input,
textarea,
select {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid #dce5f0;
    border-radius: 12px;
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    color: #334758;
    transition: all 0.3s ease;
    background: white;
    margin-bottom: 12px;
}

input::placeholder,
textarea::placeholder {
    color: #a0afc0;
}

input:focus,
textarea:focus,
select:focus {
    outline: none;
    border-color: #1c92d2;
    box-shadow: 0 0 0 4px rgba(28, 146, 210, 0.1);
}

label {
    display: block;
    color: #334758;
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 8px;
}

/* ===== GRID ===== */
.grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
    margin-bottom: 28px;
}

.grid.three {
    grid-template-columns: repeat(3, 1fr);
}

.grid.four {
    grid-template-columns: repeat(4, 1fr);
}

/* ===== UTILITY ===== */
.section-title {
    font-size: 22px;
    font-weight: 700;
    color: #0f2027;
    margin: 32px 0 20px;
}

.empty-state {
    text-align: center;
    color: #7a8fa0;
    padding: 40px 20px;
}

.empty-state i {
    font-size: 48px;
    color: #dce5f0;
    margin-bottom: 12px;
}

.empty-state p {
    margin: 10px 0;
    font-size: 15px;
}

.empty-state small {
    color: #a0afc0;
    display: block;
    margin-top: 8px;
}

.text-center {
    text-align: center;
}

.mt-2 {
    margin-top: 16px;
}

.mt-4 {
    margin-top: 24px;
}

.mb-2 {
    margin-bottom: 16px;
}

.mb-4 {
    margin-bottom: 24px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .container {
        padding: 16px;
    }

    .navbar {
        padding: 18px 16px;
    }

    .nav-top h2 {
        font-size: 22px;
    }

    .nav-menu {
        gap: 8px;
        font-size: 13px;
    }

    .nav-menu a {
        padding: 8px 12px;
        font-size: 12px;
    }

    .grid,
    .grid.three,
    .grid.four {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .card {
        padding: 18px;
        margin-bottom: 16px;
    }

    .card h2 {
        font-size: 20px;
    }

    .card h3 {
        font-size: 18px;
    }

    button,
    .btn,
    .button {
        width: 100%;
        padding: 12px 16px;
        font-size: 14px;
    }

    input,
    textarea,
    select {
        padding: 12px 14px;
        font-size: 16px;
    }

    table {
        font-size: 13px;
    }

    th,
    td {
        padding: 10px 8px;
    }

    .section-title {
        font-size: 18px;
        margin: 24px 0 16px;
    }
}

@media (max-width: 480px) {
    .navbar {
        padding: 16px 12px;
    }

    .nav-top h2 {
        font-size: 20px;
    }

    .card {
        padding: 16px;
    }

    .card h2 {
        font-size: 18px;
    }

    .card h3 {
        font-size: 16px;
    }

    button,
    .btn,
    .button {
        padding: 11px 14px;
        font-size: 13px;
    }
}
