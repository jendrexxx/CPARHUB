<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <title>
        CPAR - {{ $cpar_data->cpar_no ?? '' }}
    </title>

    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            padding: 20px 25px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            line-height: 1.35;
            color: #000;
        }

        .pdf {
            width: 100%;
        }

        /* =========================================================
           LOGO
        ========================================================= */

        .logo {
            width: 100%;
            text-align: center;
            margin-bottom: 8px;
        }

        .logo img {
            width: 30%;
            height: auto;
            display: inline-block;
        }

        /* =========================================================
           MAIN TABLE
        ========================================================= */

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            table-layout: fixed;
            border: 1px solid #000;
        }

        .custom-table td,
        .custom-table th {
            border: 1px solid #000;
            padding: 5px 6px;
            vertical-align: top;

            word-wrap: break-word;
            overflow-wrap: anywhere;
        }

        .custom-table tr {
            page-break-inside: avoid;
        }

        /* =========================================================
           COLUMNS
        ========================================================= */

        .col-25 {
            width: 25%;
        }

        .col-50 {
            width: 50%;
        }

        /* =========================================================
           TEXT
        ========================================================= */

        .fw-bold {
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        /* =========================================================
           DOCUMENT TITLE
        ========================================================= */

        .document-title {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            padding: 7px !important;
            background-color: #f2f2f2;
        }

        /* =========================================================
           SECTION HEADER
        ========================================================= */

        .section-header {
            text-align: center;
            padding: 6px !important;
            background-color: #f2f2f2;
        }

        .section-title {
            font-weight: bold;
            color: #dc3545;
            font-size: 11px;
        }

        .section-subtitle {
            font-size: 8px;
            font-style: italic;
            color: #000;
        }

        /* =========================================================
           CONTENT BOXES
        ========================================================= */

        .content-box {
            min-height: 35px;
            vertical-align: top;
        }

        .remarks-box {
            min-height: 45px;
            vertical-align: top;
        }

        .small-box {
            min-height: 25px;
            vertical-align: top;
        }

        /* =========================================================
           EMPTY CELL
        ========================================================= */

        .empty-cell {
            height: 25px;
        }

        /* =========================================================
           PAGE BREAK
        ========================================================= */

        .page-break-avoid {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    <div class="pdf">

        {{-- =====================================================
             LOGO
        ====================================================== --}}

        <div class="logo">

            @if (file_exists(public_path('logo/premiere_header_logo.jpeg')))

                <img
                    src="{{ public_path('logo/premiere_header_logo.jpeg') }}"
                    alt="Premiere Medical & Cardiovascular Laboratory">

            @else

                <img
                    src="{{ asset('logo/premiere_header_logo.jpeg') }}"
                    alt="Premiere Medical & Cardiovascular Laboratory">

            @endif

        </div>


        {{-- =====================================================
             MAIN TABLE
        ====================================================== --}}

        <table class="custom-table">

            <colgroup>
                <col class="col-25">
                <col class="col-25">
                <col class="col-25">
                <col class="col-25">
            </colgroup>

            <tbody>

                {{-- =================================================
                     DOCUMENT TITLE
                ================================================== --}}

                <tr>

                    <td colspan="4" class="document-title">

                        CORRECTIVE PREVENTIVE ACTION REPORT

                    </td>

                </tr>


                {{-- =================================================
                     CPAR INFORMATION
                ================================================== --}}

                <tr>

                    <td colspan="2">

                        <span class="fw-bold">
                            CPAR No:
                        </span>

                        <br>

                        {{ $cpar_data->cpar_no ?? '' }}

                    </td>


                    <td colspan="2">

                        <span class="fw-bold">
                            Date Opened:
                        </span>

                        <br>

                        @if (!empty($cpar_data->date_open))

                            {{ \Carbon\Carbon::parse($cpar_data->date_open)->format('m-d-Y h:i A') }}

                        @endif

                    </td>

                </tr>


                {{-- =================================================
                     SOURCE ORIGIN
                ================================================== --}}

                <tr>

                    <td colspan="2">

                        <span class="fw-bold">
                            Source Origin:
                        </span>

                        <br>

                        {{ $cpar_data->source_name ?? '' }}

                    </td>


                    <td colspan="2">

                        <span class="fw-bold">
                            Reported By:
                        </span>

                        <br>

                        {{ $cpar_data->reported_by ?? '' }}

                    </td>

                </tr>


                {{-- =================================================
                     COMPLAINANT INFORMATION
                ================================================== --}}

                <tr>

                    <td colspan="2">

                        <span class="fw-bold">
                            Complainant Category:
                        </span>

                        <br>

                        {{ $cpar_data->complain_name ?? '' }}

                    </td>


                    <td colspan="2">

                        <span class="fw-bold">
                            Complainant's Name:
                        </span>

                        <br>

                        {{ $cpar_data->complainant_name ?? '' }}

                    </td>

                </tr>


                {{-- =================================================
                     CONCERN CATEGORY
                ================================================== --}}

                <tr>

                    <td colspan="4">

                        <span class="fw-bold">
                            Concern Category:
                        </span>

                        <br>

                        {{ $cpar_data->concern_name ?? '' }}

                    </td>

                </tr>


                {{-- =================================================
                     CONCERN DESCRIPTION LABEL
                ================================================== --}}

                <tr>

                    <td colspan="4">

                        <span class="fw-bold">
                            Concern Description:
                        </span>

                    </td>

                </tr>


                {{-- =================================================
                     CONCERN DESCRIPTION
                ================================================== --}}

                <tr>

                    <td colspan="4" class="remarks-box">

                        {{ $cpar_data->concern_description ?? '' }}

                    </td>

                </tr>


                {{-- =================================================
                     ASSIGNED TO
                ================================================== --}}

                <tr>

                    <td colspan="4">

                        <span class="fw-bold">
                            Assigned To:
                        </span>

                        <br>

                        {{ $cpar_data->employee_name ?? '' }}

                    </td>

                </tr>


                {{-- =================================================
                     INVESTIGATION AND ACTION HEADER
                ================================================== --}}

                <tr>

                    <td colspan="4" class="section-header">

                        <span class="section-title">
                            Investigation and Action
                        </span>

                        <br>

                        <span class="section-subtitle">
                            This section is to be completed by the affected employee and Department Head
                        </span>

                    </td>

                </tr>


                {{-- =================================================
                     ROOT CAUSE LABEL
                ================================================== --}}

                <tr>

                    <td colspan="4">

                        <span class="fw-bold">
                            Root Cause of the Actual or Potential Problem:
                        </span>

                    </td>

                </tr>


                {{-- =================================================
                     ROOT CAUSE
                ================================================== --}}

                <tr>

                    <td colspan="4" class="content-box">

                        {{ $cpar_data->identified_cause ?? '' }}

                    </td>

                </tr>


                {{-- =================================================
                     ACTION TAKEN LABEL
                ================================================== --}}

                <tr>

                    <td colspan="4">

                        <span class="fw-bold">
                            Action Taken / Solution Provided:
                        </span>

                    </td>

                </tr>


                {{-- =================================================
                     ACTION TAKEN
                ================================================== --}}

                <tr>

                    <td colspan="4" class="content-box">

                        {{ $cpar_data->provided_solution ?? '' }}

                    </td>

                </tr>


                {{-- =================================================
                     RECOMMENDATION LABEL
                ================================================== --}}

                <tr>

                    <td colspan="4">

                        <span class="fw-bold">
                            Recommendation:
                        </span>

                    </td>

                </tr>


                {{-- =================================================
                     RECOMMENDATION
                ================================================== --}}

                <tr>

                    <td colspan="4" class="content-box">

                        {{ $cpar_data->recommendation ?? '' }}

                    </td>

                </tr>


                {{-- =================================================
                     ACTION DETAILS
                ================================================== --}}

                <tr>

                    <td colspan="2">

                        <span class="fw-bold">
                            Action Taken by:
                        </span>

                        <br>

                        {{ $cpar_data->action_taken_by ?? '' }}

                    </td>


                    <td>

                        <span class="fw-bold">
                            Date Completed:
                        </span>

                        <br>

                       {{ $cpar_data->date_completed }}

                    </td>


                    <td>

                        <span class="fw-bold">
                            TAT:
                        </span>

                        <br>

                        {{ $cpar_data->tat ?? 0 }}

                    </td>

                </tr>


                {{-- =================================================
                     DEPARTMENT HEAD REMARKS
                ================================================== --}}

                <tr>

                    <td colspan="4">

                        <span class="fw-bold">
                            Department Head Remarks:
                        </span>

                    </td>

                </tr>


                <tr>

                    <td colspan="4" class="remarks-box">

                        {{ $cpar_data->head_remarks ?? '' }}

                    </td>

                </tr>


                {{-- =================================================
                     HR DECISION HEADER
                ================================================== --}}

                <tr>

                    <td colspan="4" class="section-header">

                        <span class="section-title">
                            HR Decision
                        </span>

                        <br>

                        <span class="section-subtitle">
                            This section is to be completed by HR
                        </span>

                    </td>

                </tr>


                {{-- =================================================
                     HR DECISION
                ================================================== --}}

                <tr>

                    <td>

                        <span class="fw-bold">
                            HR Decision:
                        </span>

                    </td>

                    <td colspan="3">

                        {{ $cpar_data->decisionNames ?? '' }}

                    </td>

                </tr>


                {{-- =================================================
                     DISCIPLINARY CATEGORY
                ================================================== --}}

                <tr>

                    <td>

                        <span class="fw-bold">
                            Disciplinary Category:
                        </span>

                    </td>

                    <td colspan="3">

                        {{ $cpar_data->disciplineNames ?? '' }}

                    </td>

                </tr>


                {{-- =================================================
                     OFFENSE LEVEL
                ================================================== --}}

                <tr>

                    <td>

                        <span class="fw-bold">
                            Offense Level:
                        </span>

                    </td>

                    <td colspan="3">

                        {{ $cpar_data->offenseNames ?? '' }}

                    </td>

                </tr>

                {{-- =================================================
                     HR DECISION REMARKS LABEL
                ================================================== --}}

                <tr>

                    <td colspan="4">

                        <span class="fw-bold">
                            HR Decision Remarks:
                        </span>

                    </td>

                </tr>


                {{-- =================================================
                     HR DECISION REMARKS
                ================================================== --}}

                <tr>

                    <td colspan="4" class="remarks-box">

                        {{ $cpar_data->decision_remarks ?? '' }}

                    </td>

                </tr>


                {{-- =================================================
                     MANAGEMENT REMARKS
                ================================================== --}}

                @if (!empty($cpar_data->management_remarks))

                    <tr>

                        <td colspan="4">

                            <span class="fw-bold">
                                Management Remarks:
                            </span>

                        </td>

                    </tr>


                    <tr>

                        <td colspan="4" class="remarks-box">

                            {{ $cpar_data->management_remarks }}

                        </td>

                    </tr>

                @endif

            </tbody>

        </table>

    </div>

</body>

</html>