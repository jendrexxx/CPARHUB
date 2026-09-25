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
            font-size: 7.5px;
        ">

        <thead>
            <tr>

                <th
                    style="
                        width: 6.5%;
                        border: 1px solid #222;
                        padding: 7px 4px;
                        text-align: center;
                        vertical-align: middle;
                        font-weight: bold;
                    ">
                    Request No.
                </th>

                <th
                    style="
                        width: 6.5%;
                        border: 1px solid #222;
                        padding: 7px 4px;
                        text-align: center;
                        vertical-align: middle;
                        font-weight: bold;
                    ">
                    Date Reported
                </th>

                <th
                    style="
                        width: 5.5%;
                        border: 1px solid #222;
                        padding: 7px 4px;
                        text-align: center;
                        vertical-align: middle;
                        font-weight: bold;
                    ">
                    Reported Employee
                </th>

                <th
                    style="
                        width: 7%;
                        border: 1px solid #222;
                        padding: 7px 4px;
                        text-align: center;
                        vertical-align: middle;
                        font-weight: bold;
                    ">
                    Department
                </th>

                <th
                    style="
                        width: 6%;
                        border: 1px solid #222;
                        padding: 7px 4px;
                        text-align: center;
                        vertical-align: middle;
                        font-weight: bold;
                    ">
                    Identified Cause
                </th>

                <th
                    style="
                        width: 7%;
                        border: 1px solid #222;
                        padding: 7px 4px;
                        text-align: center;
                        vertical-align: middle;
                        font-weight: bold;
                    ">
                    Provided Solution
                </th>

                <th
                    style="
                        width: 7%;
                        border: 1px solid #222;
                        padding: 7px 4px;
                        text-align: center;
                        vertical-align: middle;
                        font-weight: bold;
                    ">
                    Date Completed
                </th>

                <th
                    style="
                        width: 7%;
                        border: 1px solid #222;
                        padding: 7px 4px;
                        text-align: center;
                        vertical-align: middle;
                        font-weight: bold;
                    ">
                    Tat
                </th>

                <th
                    style="
                        width: 7%;
                        border: 1px solid #222;
                        padding: 7px 4px;
                        text-align: center;
                        vertical-align: middle;
                        font-weight: bold;
                    ">
                    Decision Name
                </th>

                <th
                    style="
                        width: 8%;
                        border: 1px solid #222;
                        padding: 7px 4px;
                        text-align: center;
                        vertical-align: middle;
                        font-weight: bold;
                    ">
                    Category Name
                </th>

                <th
                    style="
                        width: 6%;
                        border: 1px solid #222;
                        padding: 7px 4px;
                        text-align: center;
                        vertical-align: middle;
                        font-weight: bold;
                    ">
                    Offense Level
                </th>

                <th
                    style="
                        width: 7%;
                        border: 1px solid #222;
                        padding: 7px 4px;
                        text-align: center;
                        vertical-align: middle;
                        font-weight: bold;
                    ">
                    Status
                </th>

            </tr>
        </thead>


        <tbody>

            @forelse ($cparReports as $record)

            <tr>

                {{-- CPAR NO --}}
                <td
                    style="
                            border: 1px solid #222;
                            padding: 8px 4px;
                            text-align: center;
                            vertical-align: middle;
                        ">
                    {{ $record->cpar_no ?? $record->record_no ?? '—' }}
                </td>

                {{-- DATE REPORTED --}}
                <td
                    style="
                            border: 1px solid #222;
                            padding: 8px 4px;
                            text-align: center;
                            vertical-align: middle;
                        ">
                    @if (!empty($record->date_open))
                    {{ \Carbon\Carbon::parse($record->date_open)->format('m/d/Y') }}
                    @else
                    —
                    @endif
                </td>


                {{-- --}}
                <td
                    style="
                            border: 1px solid #222;
                            padding: 8px 4px;
                            text-align: center;
                            vertical-align: middle;
                        ">
                    {{ $record->employee_name ?? '—' }}
                </td>

                {{-- DEPARTMENT --}}
                <td
                    style="
                            border: 1px solid #222;
                            padding: 8px 4px;
                            text-align: center;
                            vertical-align: middle;
                        ">
                    {{ $record->department_name ?? $record->department ?? '—' }}
                </td>


                {{-- identified_cause --}}
                <td
                    style="
                            border: 1px solid #222;
                            padding: 8px 4px;
                            text-align: center;
                            vertical-align: middle;
                        ">
                    {{ $record->identified_cause ?? '—' }}
                </td>


                {{-- provided_solution --}}
                <td
                    style="
                            border: 1px solid #222;
                            padding: 8px 4px;
                            text-align: center;
                            vertical-align: middle;
                        ">
                    {{ $record->provided_solution ?? '—' }}
                </td>


                {{-- COMPLAINANT CATEGORY --}}
                <td
                    style="
                            border: 1px solid #222;
                            padding: 8px 4px;
                            text-align: center;
                            vertical-align: middle;
                        ">
                    {{ $record->date_completed ?? '—' }}
                </td>


                {{-- COMPLAINANT NAME --}}
                <td
                    style="
                            border: 1px solid #222;
                            padding: 8px 4px;
                            text-align: center;
                            vertical-align: middle;
                        ">
                    {{ $record->tat ?? '—' }}
                </td>


                {{-- Decision --}}
                <td
                    style="
                            border: 1px solid #222;
                            padding: 8px 4px;
                            text-align: center;
                            vertical-align: middle;
                        ">
                    {{ $record->decision_name ?? '—' }}
                </td>


                {{-- Category --}}
                <td
                    style="
                            border: 1px solid #222;
                            padding: 8px 4px;
                            text-align: center;
                            vertical-align: middle;
                        ">
                    {{ $record->category_name ?? '—' }}
                </td>

                {{-- offense --}}
                <td
                    style="
                            border: 1px solid #222;
                            padding: 8px 4px;
                            text-align: center;
                            vertical-align: middle;
                        ">
                    {{ $record->offense_name ?? '—' }}
                </td>


                {{-- SOLUTION NAME --}}
                <td
                    style="
                            border: 1px solid #222;
                            padding: 8px 4px;
                            text-align: center;
                            vertical-align: middle;
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