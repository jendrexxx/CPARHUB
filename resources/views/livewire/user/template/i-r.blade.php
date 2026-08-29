<div>

    @if ($show)

        {{-- ==========================================
            INCIDENT REPORT
        =========================================== --}}

        <div class="incident-report-paper">

            {{-- LOGO --}}
            <div class="text-center">

                <img
                    src="{{ asset('logo/premiere_logo.png') }}"
                    alt="Premiere Medical and Cardiovascular Laboratory"
                    class="mx-auto h-auto w-[380px] object-contain"
                >

                <h1 class="mt-8 text-[16px] font-bold">
                    INCIDENT REPORT
                </h1>

            </div>


            {{-- DATE / SUBJECT --}}
            <div class="mt-10 space-y-2 text-[13px]">

                <div class="flex">
                    <div class="w-[170px] font-bold">
                        DATE
                    </div>

                    <div class="w-[20px] font-bold">
                        :
                    </div>

                    <div>
                        August 11, 2026
                    </div>
                </div>


                <div class="flex">
                    <div class="w-[170px] font-bold">
                        SUBJECT
                    </div>

                    <div class="w-[20px] font-bold">
                        :
                    </div>

                    <div class="font-medium">
                        Incident Report
                    </div>
                </div>

            </div>


            {{-- MAIN TABLE --}}
            <table class="incident-table mt-8">

                <thead>
                    <tr>
                        <th>
                            CONCERNS/ISSUES
                        </th>

                        <th>
                            SOLUTION PROVIDED
                        </th>

                        <th>
                            COMMENTS/SUGGESTION
                        </th>
                    </tr>
                </thead>

                <tbody>

                    <tr>

                        <td class="incident-content">
                            Enter concerns/issues here.
                        </td>

                        <td class="incident-content">
                            Enter solution provided here.
                        </td>

                        <td class="incident-content">
                            Enter comments/suggestion here.
                        </td>

                    </tr>


                    {{-- SIGNATURES --}}
                    <tr class="signature-row">

                        <td>
                            <div class="font-bold">
                                Prepared by:
                            </div>

                            <div>
                                ____________________
                            </div>

                            <div>
                                IT Department
                            </div>
                        </td>


                        <td>
                            <div class="font-bold">
                                Noted by:
                            </div>

                            <div>
                                ____________________
                            </div>

                            <div>
                                Department Head
                            </div>
                        </td>


                        <td>
                            <div class="font-bold">
                                Submitted to:
                            </div>

                            <div>
                                ____________________
                            </div>

                            <div>
                                Management
                            </div>
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    @endif


    <style>
        .incident-report-paper {
            width: 8.27in;
            min-height: 11.69in;

            margin: 0 auto;
            padding: 0.45in 0.4in;

            background: white;
            color: black;

            box-sizing: border-box;
        }

        .incident-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 12px;
        }

        .incident-table th,
        .incident-table td {
            border: 1px solid black;
        }

        .incident-table th {
            height: 43px;
            padding: 6px;

            text-align: center;
            vertical-align: middle;

            font-size: 13px;
            font-weight: 700;
        }

        .incident-content {
            height: 240px;
            padding: 6px;

            vertical-align: top;
            white-space: normal;
        }

        .signature-row td {
            height: 64px;
            padding: 5px;

            vertical-align: top;

            font-size: 11px;
            line-height: 1.25;
        }


        @media print {

            @page {
                size: A4 portrait;
                margin: 0;
            }

            html,
            body {
                width: 210mm;
                height: 297mm;

                margin: 0 !important;
                padding: 0 !important;

                background: white !important;
            }

            body * {
                visibility: hidden !important;
            }

            .incident-report-paper,
            .incident-report-paper * {
                visibility: visible !important;
            }

            .incident-report-paper {
                position: absolute;

                left: 0;
                top: 0;

                width: 210mm;
                min-height: 297mm;

                margin: 0;

                padding: 12mm 10mm;

                background: white;
                color: black;

                box-sizing: border-box;
            }

            .incident-table {
                page-break-inside: avoid;
            }

            .incident-table tr {
                page-break-inside: avoid;
            }
        }
    </style>


    <script>
        document.addEventListener('livewire:init', () => {

            Livewire.on('start-print', () => {

                setTimeout(() => {
                    window.print();
                }, 300);

            });

        });
    </script>

</div>