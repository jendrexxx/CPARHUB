<div class="bg-white text-black">

    <table
        style="
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        ">
        <tr>
            <td
                style="
                    text-align: center;
                    vertical-align: middle;
                    border: none;
                ">
                <img
                    src="{{ public_path('logo/premierelaboratory_cover.jpg') }}"
                    alt="Premiere Medical & Cardiovascular Laboratory"
                    style="
                        width: 330px;
                        height: auto;
                        display: block;
                        margin: 0 auto;
                    ">
            </td>
        </tr>

        <tr>
            <td
                style="
                    text-align: center;
                    vertical-align: middle;
                    border: none;
                    padding-top: 6px;
                ">
                <div
                    style="
                        font-size: 13px;
                        font-weight: bold;
                        line-height: 1.4;
                    ">
                    PREMIERE {{ $branch_name }} CORRECTIVE PREVENTIVE ACTION REPORTS
                </div>

                @if (!empty($date_from) && !empty($date_to))
                <div
                    style="
                        font-size: 12px;
                        font-weight: bold;
                        line-height: 1.4;
                    ">
                    CUT-OFF PERIOD: FROM:
                    {{ \Carbon\Carbon::parse($date_from)->format('F j, Y') }}
                    TO:
                    {{ \Carbon\Carbon::parse($date_to)->format('F j, Y') }}
                </div>
                @endif
            </td>
        </tr>
    </table>

    <table
        style="
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        font-size: 6.5px;
        word-wrap: break-word;
        overflow-wrap: anywhere;
    ">
        <thead>
            <tr>

                <th style="
                width: 6%;
                border: 1px solid #222;
                padding: 5px 2px;
                text-align: center;
                vertical-align: middle;
                font-weight: bold;
                overflow-wrap: anywhere;
            ">
                    Request No.
                </th>

                <th style="
                width: 6%;
                border: 1px solid #222;
                padding: 5px 2px;
                text-align: center;
                vertical-align: middle;
                font-weight: bold;
                overflow-wrap: anywhere;
            ">
                    Date Reported
                </th>

                <th style="
                width: 7%;
                border: 1px solid #222;
                padding: 5px 2px;
                text-align: center;
                vertical-align: middle;
                font-weight: bold;
                overflow-wrap: anywhere;
            ">
                    Reported Employee
                </th>

                <th style="
                width: 7%;
                border: 1px solid #222;
                padding: 5px 2px;
                text-align: center;
                vertical-align: middle;
                font-weight: bold;
                overflow-wrap: anywhere;
            ">
                    Department
                </th>

                <th style="
                width: 7%;
                border: 1px solid #222;
                padding: 5px 2px;
                text-align: center;
                vertical-align: middle;
                font-weight: bold;
                overflow-wrap: anywhere;
            ">
                    Source Of Information
                </th>

                <th style="
                width: 8%;
                border: 1px solid #222;
                padding: 5px 2px;
                text-align: center;
                vertical-align: middle;
                font-weight: bold;
                overflow-wrap: anywhere;
            ">
                    Complainant Category
                </th>

                <th style="
                width: 7%;
                border: 1px solid #222;
                padding: 5px 2px;
                text-align: center;
                vertical-align: middle;
                font-weight: bold;
                overflow-wrap: anywhere;
            ">
                    Concern Description
                </th>

                <th style="
                width: 7%;
                border: 1px solid #222;
                padding: 5px 2px;
                text-align: center;
                vertical-align: middle;
                font-weight: bold;
                overflow-wrap: anywhere;
            ">
                    Identified Cause
                </th>

                <th style="
                width: 7%;
                border: 1px solid #222;
                padding: 5px 2px;
                text-align: center;
                vertical-align: middle;
                font-weight: bold;
                overflow-wrap: anywhere;
            ">
                    Provided Solution
                </th>

                <th style="
                width: 7%;
                border: 1px solid #222;
                padding: 5px 2px;
                text-align: center;
                vertical-align: middle;
                font-weight: bold;
                overflow-wrap: anywhere;
            ">
                    Date Completed
                </th>

                <th style="
                width: 5%;
                border: 1px solid #222;
                padding: 5px 2px;
                text-align: center;
                vertical-align: middle;
                font-weight: bold;
                overflow-wrap: anywhere;
            ">
                    TAT
                </th>

                <th style="
                width: 8%;
                border: 1px solid #222;
                padding: 5px 2px;
                text-align: center;
                vertical-align: middle;
                font-weight: bold;
                overflow-wrap: anywhere;
            ">
                    Decision
                </th>

                <th style="
                width: 8%;
                border: 1px solid #222;
                padding: 5px 2px;
                text-align: center;
                vertical-align: middle;
                font-weight: bold;
                overflow-wrap: anywhere;
            ">
                    Category
                </th>

                <th style="
                width: 5%;
                border: 1px solid #222;
                padding: 5px 2px;
                text-align: center;
                vertical-align: middle;
                font-weight: bold;
                overflow-wrap: anywhere;
            ">
                    Offense Level
                </th>

                <th style="
                width: 5%;
                border: 1px solid #222;
                padding: 5px 2px;
                text-align: center;
                vertical-align: middle;
                font-weight: bold;
                overflow-wrap: anywhere;
            ">
                    Status
                </th>

            </tr>
        </thead>

        <tbody>

            @forelse ($cparReports as $record)

            <tr>

                <td style="
                border: 1px solid #222;
                padding: 4px 2px;
                text-align: center;
                vertical-align: middle;
                overflow-wrap: anywhere;
                word-break: break-word;
            ">
                    {{ $record->record_no ?? '—' }}
                </td>

                <td style="
                border: 1px solid #222;
                padding: 4px 2px;
                text-align: center;
                vertical-align: middle;
                overflow-wrap: anywhere;
                word-break: break-word;
            ">
                    @if (!empty($record->date_open))
                    {{ \Carbon\Carbon::parse($record->date_open)->format('m/d/Y') }}
                    @else
                    —
                    @endif
                </td>

                <td style="
                border: 1px solid #222;
                padding: 4px 2px;
                text-align: center;
                vertical-align: middle;
                overflow-wrap: anywhere;
                word-break: break-word;
            ">
                    {{ $record->employee_name ?? '—' }}
                </td>

                <td style="
                border: 1px solid #222;
                padding: 4px 2px;
                text-align: center;
                vertical-align: middle;
                overflow-wrap: anywhere;
                word-break: break-word;
            ">
                    {{ $record->department_name ?? '—' }}
                </td>

                <td style="
                border: 1px solid #222;
                padding: 4px 2px;
                text-align: center;
                vertical-align: middle;
                overflow-wrap: anywhere;
                word-break: break-word;
            ">
                    {{ $record->source_name ?? '—' }}
                </td>

                <td style="
                border: 1px solid #222;
                padding: 4px 2px;
                text-align: center;
                vertical-align: middle;
                overflow-wrap: anywhere;
                word-break: break-word;
            ">
                    {{ $record->complain_name ?? '—' }}
                </td>

                <td style="
                border: 1px solid #222;
                padding: 4px 2px;
                text-align: center;
                vertical-align: middle;
                overflow-wrap: anywhere;
                word-break: break-word;
            ">
                    {{ $record->concern_description ?? '—' }}
                </td>

                <td style="
                border: 1px solid #222;
                padding: 4px 2px;
                text-align: center;
                vertical-align: middle;
                overflow-wrap: anywhere;
                word-break: break-word;
            ">
                    {{ $record->identified_cause ?? '—' }}
                </td>

                <td style="
                border: 1px solid #222;
                padding: 4px 2px;
                text-align: center;
                vertical-align: middle;
                overflow-wrap: anywhere;
                word-break: break-word;
            ">
                    {{ $record->provided_solution ?? '—' }}
                </td>

                <td style="
                border: 1px solid #222;
                padding: 4px 2px;
                text-align: center;
                vertical-align: middle;
                overflow-wrap: anywhere;
                word-break: break-word;
            ">
                    {{ $record->date_completed ?? '—' }}
                </td>

                <td style="
                border: 1px solid #222;
                padding: 4px 2px;
                text-align: center;
                vertical-align: middle;
                overflow-wrap: anywhere;
                word-break: break-word;
            ">
                    {{ $record->tat ?? '—' }}
                </td>

                <td style="
                border: 1px solid #222;
                padding: 4px 2px;
                text-align: center;
                vertical-align: middle;
                overflow-wrap: anywhere;
                word-break: break-word;
            ">
                    {{ $record->decision_name ?? '—' }}
                </td>

                <td style="
                border: 1px solid #222;
                padding: 4px 2px;
                text-align: center;
                vertical-align: middle;
                overflow-wrap: anywhere;
                word-break: break-word;
            ">
                    {{ $record->category_name ?? '—' }}
                </td>

                <td style="
                border: 1px solid #222;
                padding: 4px 2px;
                text-align: center;
                vertical-align: middle;
                overflow-wrap: anywhere;
                word-break: break-word;
            ">
                    {{ $record->offense_name ?? '—' }}
                </td>

                <td style="
                border: 1px solid #222;
                padding: 4px 2px;
                text-align: center;
                vertical-align: middle;
                overflow-wrap: anywhere;
                word-break: break-word;
            ">
                    {{ $record->status_name ?? '—' }}
                </td>

            </tr>

            @empty

            <tr>
                <td
                    colspan="15"
                    style="
                    border: 1px solid #222;
                    padding: 15px;
                    text-align: center;
                ">
                    No CPAR records found.
                </td>
            </tr>

            @endforelse

        </tbody>
    </table>

</div>