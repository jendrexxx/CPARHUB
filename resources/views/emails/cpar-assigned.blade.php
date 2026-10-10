<!DOCTYPE html>
<html>

<body style="
    font-family: Arial, sans-serif;
    line-height: 1.6;
    text-transform: uppercase;
    margin: 0;
    padding: 0;
    background-color: #f5f5f5;
">

    @php
    $isResult = (int) $recordType === 10;

    $recordTitle = $isResult
    ? 'RESULT CONCERN'
    : 'CORRECTIVE AND PREVENTIVE ACTION REPORT (CPAR)';

    $recordNumber = $isResult
    ? ($request->result_no ?? 'N/A')
    : ($request->cpar_no ?? 'N/A');

    $reportedBy = $request->reported_by ?? 'N/A';

    $dateReported = $isResult
    ? ($request->date_reported ?? null)
    : ($request->date_open ?? null);
    @endphp

    <div style="
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #ffffff;
    ">

        <h1 style="
            font-family: Magneto, Arial;
            text-align: left;
            margin-bottom: 10px;
        ">
            CPARHub
        </h1>

        <hr style="
            border: 0;
            border-top: 1px solid #cccccc;
            margin: 15px 0;
        ">

        <p>
            DEAR
            {{ strtoupper($recipient->first_name ?? '') }}
            {{ strtoupper($recipient->last_name ?? '') }},
        </p>

        <p>
            A NEW {{ $recordTitle }} HAS BEEN ASSIGNED TO YOU.
        </p>

        <p>
            <strong>
                {{ $isResult ? 'RESULT NO.:' : 'CPAR NO.:' }}
            </strong>
            {{ strtoupper($recordNumber) }}
        </p>

        <p>
            <strong>REPORTED BY:</strong>
            {{ strtoupper($reportedBy) }}
        </p>

        <p>
            <strong>DATE REPORTED:</strong>

            @if ($dateReported)
            {{ \Carbon\Carbon::parse($dateReported)->format('F d, Y h:i A') }}
            @else
            N/A
            @endif
        </p>

        <p>
            <strong>PRIORITY LEVEL:</strong>
            {{ strtoupper($prioritylevel ?? 'N/A') }}
        </p>

        <p>
            PLEASE LOG IN TO CPARHUB TO REVIEW THIS
            {{ $isResult ? 'RESULT CONCERN' : 'CPAR' }}.
        </p>

        <p style="margin: 25px 0;">
            <a
                href="https://cpar.pmclhrhub.com/"
                target="_blank"
                style="
                    display: inline-block;
                    padding: 12px 24px;
                    background-color: #9F0712;
                    color: #ffffff;
                    text-decoration: none;
                    border-radius: 5px;
                    font-weight: bold;
                ">
                OPEN CPARHUB
            </a>
        </p>

        <p>
            OR COPY THIS LINK:
        </p>

        <p>
            <a
                href="https://cpar.pmclhrhub.com/"
                target="_blank"
                style="color: #9F0712;">
                https://cpar.pmclhrhub.com/
            </a>
        </p>

        <p>
            THANK YOU.
        </p>

        <p>
            SINCERELY,<br>
            <strong>GLENDA CASTULO</strong><br>
            HR - ADMINISTRATIVE AND OPERATIONS
        </p>

    </div>

</body>

</html>