<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <title>CPAR - {{ $cpar_data->cpar_no }}</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 20px 25px;
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #000;
        }

        .logo {
            width: 100%;
            text-align: center;
            margin-bottom: 8px;
        }

        .logo img {
            width: 30%;
            height: auto;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .custom-table td,
        .custom-table th {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }

        .fw-bold {
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-red {
            color: #dc3545;
        }

        .fst-italic {
            font-style: italic;
        }

        .vertical-top {
            vertical-align: top;
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

        .label {
            font-weight: bold;
        }

        .content-box {
            height: 40px;
            vertical-align: top;
        }

        .remarks-box {
            height: 45px;
            vertical-align: top;
        }

        .small-box {
            height: 35px;
            vertical-align: top;
        }

        .hr-title {
            font-weight: bold;
            color: #dc3545;
            font-size: 11px;
        }

        .hr-label {
            font-weight: bold;
        }

        .page-break-avoid {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    <div class="pdf">

        <div class="logo">

            @if(file_exists(public_path('logo/premiere_header_logo.jpeg')))

            <img
                src="{{ public_path('logo/premiere_header_logo.jpeg') }}"
                alt="Premiere Medical & Cardiovascular Laboratory">

            @else

            <img
                src="{{ asset('logo/premiere_header_logo.jpeg') }}"
                alt="Premiere Medical & Cardiovascular Laboratory">

            @endif

        </div>

        <table class="custom-table">

            <tbody>

                {{-- TITLE --}}
                <tr class="text-center">
                    <td colspan="4">
                        <span class="fw-bold">
                            CORRECTIVE PREVENTIVE ACTION REPORT
                        </span>
                    </td>
                </tr>


                {{-- CPAR NUMBER --}}
                <tr>
                    <td colspan="4">
                        <span class="fw-bold">
                            CPAR No:
                        </span>

                        {{ $cpar_data->cpar_no }}
                    </td>
                </tr>


                {{-- DATE OPENED --}}
                <tr>
                    <td style="width: 25%;">
                        <span class="fw-bold">
                            Date Opened:
                        </span>
                    </td>

                    <td colspan="3">
                        {{ \Carbon\Carbon::parse($cpar_data->date_open)->format('m-d-y h:i A') }}
                    </td>
                </tr>


                {{-- SOURCE ORIGIN --}}
                <tr>
                    <td>
                        <span class="fw-bold">
                            Source Origin:
                        </span>
                    </td>

                    <td colspan="3">
                        {{ $cpar_data->source_name ?? '' }}
                    </td>
                </tr>


                {{-- REPORTED BY --}}
                <tr>
                    <td>
                        <span class="fw-bold">
                            Reported By:
                        </span>
                    </td>

                    <td colspan="3">
                        {{ $cpar_data->reported_by ?? '' }}
                    </td>
                </tr>


                {{-- COMPLAINANT CATEGORY --}}
                <tr>
                    <td>
                        <span class="fw-bold">
                            Complainant Category:
                        </span>
                    </td>

                    <td colspan="3">
                        {{ $cpar_data->complain_name ?? '' }}
                    </td>
                </tr>


                {{-- COMPLAINANT NAME --}}
                <tr>
                    <td>
                        <span class="fw-bold">
                            Complainant's Name:
                        </span>
                    </td>

                    <td colspan="3">
                        {{ $cpar_data->complainant_name ?? '' }}
                    </td>
                </tr>

                <tr>
                    <td>
                        <span class="fw-bold">
                            Concern Category:
                        </span>
                    </td>

                    <td colspan="3">
                        {{ $cpar_data->concern_name ?? '' }}
                    </td>
                </tr>


                {{-- CONCERN DESCRIPTION LABEL --}}
                <tr>
                    <td colspan="4">
                        <span class="fw-bold">
                            Concern Description:
                        </span>
                    </td>
                </tr>


                {{-- CONCERN DESCRIPTION --}}
                <tr>
                    <td
                        colspan="4"
                        class="vertical-top"
                        style="height: 40px;">
                        {{ $cpar_data->concern_description ?? '' }}
                    </td>
                </tr>

                {{-- ASSIGNMENT --}}
                <tr>
                    <td>
                        <span class="fw-bold">
                            Assigned to:
                        </span>
                    </td>

                    <td colspan="3">
                        {{ $cpar_data->employee_name ?? '' }}
                    </td>
                </tr>

                <tr class="text-center">

                    <td colspan="4">

                        <span class="section-title">
                            Investigation and Action
                        </span>

                        <br>

                        <span class="section-subtitle">
                            This section to be completed by the affected employee and Department Head
                        </span>

                    </td>

                </tr>

                {{-- ROOT CAUSE LABEL --}}
                <tr>

                    <td colspan="4">

                        <span class="fw-bold">
                            Root cause of the actual or potential problem:
                        </span>

                    </td>

                </tr>


                {{-- ROOT CAUSE --}}
                <tr>

                    <td
                        colspan="4"
                        class="content-box">

                        {{ $cpar_data->identified_cause ?? '' }}

                    </td>

                </tr>

                {{-- ACTION TAKEN LABEL --}}
                <tr>

                    <td colspan="4">

                        <span class="fw-bold">
                            Action Taken / Solution Provided:
                        </span>

                    </td>

                </tr>


                {{-- ACTION TAKEN --}}
                <tr>

                    <td
                        colspan="4"
                        class="content-box">

                        {{ $cpar_data->provided_solution ?? '' }}

                    </td>

                </tr>

                {{-- ACTION DETAILS --}}
                <tr>

                    <td colspan="2">

                        <span class="fw-bold">
                            Action Taken by:
                        </span>

                        {{ $cpar_data->action_taken_by ?? '' }}

                    </td>


                    <td>

                        <span class="fw-bold">
                            Date Completed:
                        </span>

                        {{ $cpar_data->date_completed ?? '' }}

                    </td>


                    <td>

                        <span class="fw-bold">
                            TAT:
                        </span>

                        {{ $cpar_data->tat ?? 0 }}

                    </td>

                </tr>

                <tr>
                    <td colspan="4">
                        <span class="fw-bold">
                            Department Head Remarks:
                        </span>
                    </td>
                </tr>

                <tr>
                    <td
                        colspan="4"
                        class="remarks-box">
                        {{ $cpar_data->head_remarks ?? '' }}
                    </td>
                </tr>

                <tr class="text-center">
                    <td colspan="4">
                        <span class="section-title">
                            HR Decision
                        </span>
                        <br>
                        <span class="section-subtitle">
                            This section to be completed by HR
                        </span>
                    </td>
                </tr>

                {{-- HR DECISION --}}
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

                {{-- DISCIPLINARY CATEGORY --}}
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

                {{-- OFFENSE LEVEL --}}
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

                {{-- HR DECISION REMARKS --}}
                <tr>
                    <td colspan="4">
                        <span class="fw-bold">
                            HR Decision Remarks:
                        </span>
                    </td>
                </tr>


                <tr>
                    <td
                        colspan="4"
                        class="remarks-box">
                        {{ $cpar_data->decision_remarks ?? '' }}
                    </td>
                </tr>


                {{-- MANAGEMENT REMARKS --}}
                @if(!empty($cpar_data->management_remarks))
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