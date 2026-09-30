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
    $isResult = $recordType == 10;

    $recordTitle = $isResult
    ? 'RESULT CONCERN'
    : 'CORRECTIVE AND PREVENTIVE ACTION REPORT (CPAR)';

    $recordNumber = $isResult
    ? ($record->result_no ?? 'N/A')
    : ($record->cpar_no ?? 'N/A');

    $dateOpened = $isResult
    ? ($record->date_reported ?? null)
    : ($record->date_open ?? null);
    @endphp

    <div style="
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #ffffff;
    ">

        <div style="margin-bottom: 15px;">

            <img
                src="https://cpar.pmclhrhub.com/logo/premierelaboratory_cover-removebg-preview.png"
                alt="CPARHUB"
                width="220"
                style="
                    display: block;
                    width: 220px;
                    max-width: 100%;
                    height: auto;
                    border: 0;
                    outline: none;
                    text-decoration: none;
                ">

        </div>

        <hr style="
            border: 0;
            border-top: 1px solid #cccccc;
            margin: 15px 0;
        ">

        <p>
            DEAR
            {{ strtoupper($deptHead->first_name ?? '') }}
            {{ strtoupper($deptHead->last_name ?? '') }},
        </p>

        <p>
            A NEW {{ $recordTitle }} HAS BEEN ASSIGNED TO YOU.
        </p>

        <p>
            <strong>
                {{ $isResult ? 'RESULT NO.' : 'CPAR NO.' }}:
            </strong>

            {{ strtoupper($recordNumber) }}
        </p>

        <p>
            <strong>REPORTED BY:</strong>
            {{ strtoupper($record->reported_by ?? 'N/A') }}
        </p>

        <p>
            <strong>
                {{ $isResult ? 'DATE REPORTED:' : 'DATE OPENED:' }}
            </strong>

            {{ $dateOpened
                ? \Carbon\Carbon::parse($dateOpened)->format('F d, Y h:i A')
                : 'N/A'
            }}
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

            PREMIERE MEDICAL &amp; CARDIOVASCULAR LABORATORY INC.
        </p>

    </div>

</body>

</html>