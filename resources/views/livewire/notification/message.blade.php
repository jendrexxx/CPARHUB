<div wire:poll.10s="loadNotifications">

    <div class="notification-stack">

        @if ($request_count > 0 || $assigned_count > 0 || $nte_cpar > 0)

        <div class="notification-container">

            <div class="notification-toast"
                aria-label="User Notifications">

                <div class="notification-content">

                    {{-- Icon --}}
                    <div class="notification-icon">
                        <flux:icon.bell class="h-5 w-5" />
                    </div>

                    {{-- Text --}}
                    <div class="notification-text">

                        <div class="notification-title">
                            User Notification
                        </div>

                        <div class="notification-message">

                            @if ($request_count > 0)
                            <div>
                                {{ $request_count }}
                                reported concern{{ $request_count > 1 ? 's' : '' }}
                            </div>
                            @endif

                            @if ($assigned_count > 0)
                            <div>
                                {{ $assigned_count }}
                                assigned concern{{ $assigned_count > 1 ? 's' : '' }}
                            </div>
                            @endif

                            @if ($nte_cpar > 0)
                            <div>
                                {{ $nte_cpar }}
                                Notice to Explain{{ $nte_cpar > 1 ? 's' : '' }}
                            </div>
                            @endif

                        </div>

                    </div>

                    {{-- Arrow --}}


                </div>

            </div>

        </div>

        @endif

        @if ($cpar_count > 0 || $result_count > 0 || $acknowledgment_count > 0)

        <div class="notification-container">

            <div class="notification-toast"
                aria-label="Department Head Notifications">

                <div class="notification-content">

                    {{-- Icon --}}
                    <div class="notification-icon">
                        <flux:icon.bell class="h-5 w-5" />
                    </div>

                    {{-- Text --}}
                    <div class="notification-text">

                        <div class="notification-title">
                            Department Head Notification
                        </div>

                        <div class="notification-message">

                            @if ($cpar_count > 0 || $result_count > 0)

                            <div>
                                {{ $cpar_count + $result_count }}
                                reported concern{{ ($cpar_count + $result_count) > 1 ? 's' : '' }}
                            </div>

                            @endif

                            @if ($acknowledgment_count > 0)

                            <div>
                                {{ $acknowledgment_count }}
                                acknowledgment{{ $acknowledgment_count > 1 ? 's' : '' }}
                            </div>

                            @endif

                        </div>

                    </div>

                    {{-- Arrow --}}


                </div>

            </div>

        </div>

        @endif

        @if ($hr_request_count > 0 || $acknowledged_cpar > 0 || $hr_decision_count > 0 || $memo_count > 0)
        <div class="notification-container">

            <div class="notification-toast"
                aria-label="HR Notifications">

                <div class="notification-content">

                    {{-- Icon --}}
                    <div class="notification-icon">
                        <flux:icon.bell class="h-5 w-5" />
                    </div>

                    {{-- Text --}}
                    <div class="notification-text">

                        <div class="notification-title">
                            HR Notification
                        </div>

                        <div class="notification-message">

                            @if ($hr_request_count > 0)

                            <div>
                                {{ $hr_request_count }}
                                Reported Concern{{ $hr_request_count > 1 ? 's' : '' }}
                            </div>

                            @endif

                            @if ($acknowledged_cpar > 0)

                            <div>
                                {{ $acknowledged_cpar }}
                                Acknowledgement{{ $acknowledged_cpar > 1 ? 's' : '' }}
                            </div>

                            @endif

                            @if ($hr_decision_count > 0)

                            <div>
                                {{ $hr_decision_count }}
                                Decision{{ $hr_decision_count > 1 ? 's' : '' }}
                            </div>

                            @endif

                            @if ($memo_count > 0)

                            <div>
                                {{ $memo_count }}
                                Memo{{ $memo_count > 1 ? 's' : '' }}
                            </div>

                            @endif

                        </div>

                    </div>

                    {{-- Arrow --}}


                </div>

            </div>

        </div>
        @endif

        @if ($lab_request_count > 0)

        <div class="notification-container">

            <div class="notification-toast"
                aria-label="Laboratory Notifications">

                <div class="notification-content">

                    {{-- Icon --}}
                    <div class="notification-icon">
                        <flux:icon.bell class="h-5 w-5" />
                    </div>

                    {{-- Text --}}
                    <div class="notification-text">

                        <div class="notification-title">
                            Laboratory Notification
                        </div>

                        <div class="notification-message">

                            <div>
                                {{ $lab_request_count }}
                                Re-Assigned request{{ $lab_request_count > 1 ? 's' : '' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        @endif

        @if ($lab_reported_count > 0)

        <div class="notification-container">

            <div class="notification-toast"
                aria-label="">

                <div class="notification-content">

                    {{-- Icon --}}
                    <div class="notification-icon">
                        <flux:icon.bell class="h-5 w-5" />
                    </div>

                    {{-- Text --}}
                    <div class="notification-text">

                        <div class="notification-title">
                            Reported Concern Notification
                        </div>

                        <div class="notification-message">

                            <div>
                                {{ $lab_reported_count }}
                                laboratory request{{ $lab_reported_count > 1 ? 's' : '' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        @endif

    </div>

    <style>
        .notification-stack {

            position: fixed;

            right: 20px;

            bottom: 20px;

            width: 360px;

            z-index: 9999;

            display: flex;

            flex-direction: column;

            gap: 10px;

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |--------------------------------------------------------------------------
            | Notification will NOT block or receive clicks.
            */
            pointer-events: none;

        }


        .notification-container {

            width: 100%;

            pointer-events: none;

        }


        .notification-toast {

            display: block;

            width: 100%;

            /*
            |--------------------------------------------------------------------------
            | TRANSPARENT BACKGROUND
            |--------------------------------------------------------------------------
            */
            background: rgba(153, 27, 27, 0.80);

            color: #ffffff;

            border-radius: 6px;

            padding: 13px 14px;

            user-select: none;

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |--------------------------------------------------------------------------
            | No cursor pointer and no click events.
            */
            cursor: default;

            pointer-events: none;

            /*
            |--------------------------------------------------------------------------
            | GLASS / TRANSPARENCY EFFECT
            |--------------------------------------------------------------------------
            */
            backdrop-filter: blur(5px);

            -webkit-backdrop-filter: blur(5px);

            box-shadow:
                0 4px 14px rgba(220, 38, 38, 0.30);

            transition:
                opacity 0.15s ease,
                background 0.15s ease;

            animation:
                notificationBlink 1.2s infinite,
                notificationSlide 0.25s ease-out;

        }


        /*
        |--------------------------------------------------------------------------
        | CONTENT
        |--------------------------------------------------------------------------
        */

        .notification-content {

            display: flex;

            align-items: center;

            gap: 12px;

        }


        .notification-icon {

            width: 38px;

            height: 38px;

            min-width: 38px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: rgba(255, 255, 255, 0.95);

            border-radius: 50%;

            color: #dc2626;

            flex-shrink: 0;

        }


        .notification-text {

            min-width: 0;

            flex: 1;

        }


        .notification-title {

            font-size: 14px;

            font-weight: 700;

            color: #ffffff;

            margin-bottom: 3px;

        }


        .notification-message {

            font-size: 12px;

            font-weight: 500;

            color: rgba(255, 255, 255, 0.95);

            line-height: 1.5;

        }


        .notification-message div {

            margin-top: 2px;

        }


        .notification-arrow {

            margin-left: auto;

            display: flex;

            align-items: center;

            justify-content: center;

            color: #ffffff;

            flex-shrink: 0;

        }

        @keyframes notificationBlink {

            0% {

                background: rgba(220, 38, 38, 0.72);

                box-shadow:
                    0 4px 14px rgba(220, 38, 38, 0.25);

            }

            50% {

                background: rgba(153, 27, 27, 0.84);

                box-shadow:
                    0 0 0 6px rgba(220, 38, 38, 0.10),
                    0 6px 20px rgba(220, 38, 38, 0.35);

            }

            100% {

                background: rgba(220, 38, 38, 0.72);

                box-shadow:
                    0 4px 14px rgba(220, 38, 38, 0.25);

            }

        }

        @keyframes notificationSlide {

            from {

                opacity: 0;

                transform: translateX(25px);

            }

            to {

                opacity: 1;

                transform: translateX(0);

            }

        }

        @media (max-width: 576px) {

            .notification-stack {

                right: 10px;

                left: 10px;

                bottom: 10px;

                width: auto;

            }

        }

        @media (prefers-reduced-motion: reduce) {

            .notification-toast {

                animation: none;

            }

        }
    </style>

</div>