<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Notes d'étudiants</title>
    <style>
        /* Reset & base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            line-height: 1.4;
        }

        /* Page layout */
        @page {
            size: A4 landscape;
            margin: 15mm 12mm 20mm 12mm;
        }

        /* Header */
        .header-table {
            width: 100%;
            border: none;
            margin-bottom: 6px;
        }

        .header-table td {
            vertical-align: middle;
            border: none;
            padding: 0;
        }

        .school-name {
            font-size: 18px;
            font-weight: bold;
            color: #1a3c6e;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .school-subtitle {
            font-size: 10px;
            color: #555;
            margin-top: 2px;
        }

        .header-right {
            text-align: right;
            font-size: 10px;
            color: #555;
        }

        /* Title bar */
        .title-bar {
            background-color: #1a3c6e;
            color: #fff;
            text-align: center;
            padding: 8px 0;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        /* Filter info */
        .filter-info {
            text-align: center;
            font-size: 10px;
            color: #555;
            margin-bottom: 10px;
            padding: 4px 0;
            border-bottom: 1px solid #ddd;
        }

        .filter-info span {
            margin: 0 10px;
            font-weight: bold;
            color: #1a3c6e;
        }

        /* Data table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .data-table thead th {
            background-color: #1a3c6e;
            color: #fff;
            padding: 7px 5px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid #13305a;
        }

        .data-table tbody td {
            padding: 5px 5px;
            border: 1px solid #ccc;
            font-size: 10px;
            vertical-align: middle;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f4f6fa;
        }

        .data-table tbody tr:nth-child(odd) {
            background-color: #fff;
        }

        /* Note column */
        .col-note {
            width: 100px;
            text-align: center;
            font-weight: bold;
            color: #1a3c6e;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }

        .footer .page-number:after {
            content: counter(page);
        }

        /* Summary row */
        .summary-table {
            width: 100%;
            margin-top: 10px;
            border: none;
        }

        .summary-table td {
            border: none;
            padding: 3px 8px;
            font-size: 10px;
        }

        .summary-label {
            font-weight: bold;
            color: #1a3c6e;
        }

        .summary-value {
            background-color: #f4f6fa;
            border: 1px solid #ddd;
            padding: 3px 10px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>
    {{-- Header --}}
    <table class="header-table">
        <tr>
            <td style="width: 70%;">
                <div class="school-name">{{ config('app.name') }}</div>
                <div class="school-subtitle">Notes d'étudiants — Relevé de notes</div>
            </td>
            <td class="header-right">
                <div><strong>Période :</strong> {{ $periodeNom ?? '—' }}</div>
                <div><strong>Matière :</strong> {{ $matiereNom ?? '—' }}</div>
            </td>
        </tr>
    </table>

    {{-- Title --}}
    <div class="title-bar">Notes d'étudiants</div>

    {{-- Filter info --}}
    @if($promotionNom || $groupeNom)
        <div class="filter-info">
            @if($promotionNom)
                Promotion : <span>{{ $promotionNom }}</span>
            @endif
            @if($groupeNom)
                Groupe : <span>{{ $groupeNom }}</span>
            @endif
        </div>
    @endif

    {{-- Data table --}}
    <table class="data-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th class="col-note">Note</th>
                <th>Observation</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                @php($record = $records[$student->id] ?? null)
                <tr>
                    <td><strong>{{ $student->nom }}</strong></td>
                    <td><strong>{{ $student->prenom }}</strong></td>
                    <td class="col-note">{{ $record?->note ?? '—' }}</td>
                    <td>{{ $record?->observation ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 20px; color: #888;">
                        Aucun étudiant trouvé
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Summary --}}
    <table class="summary-table">
        <tr>
            <td class="summary-label">Total étudiants :</td>
            <td class="summary-value">{{ $totalEtudiants }}</td>
            <td class="summary-label">Avec note :</td>
            <td class="summary-value">{{ $totalAvecNote }}</td>
            <td class="summary-label">Sans note :</td>
            <td class="summary-value">{{ $totalEtudiants - $totalAvecNote }}</td>
        </tr>
    </table>

    {{-- Footer --}}
    <div class="footer">
        {{ config('app.name') }} — Généré le {{ now()->format('d/m/Y à H:i') }}
        &nbsp;|&nbsp; Page <span class="page-number"></span>
    </div>
</body>

</html>