<div wire:poll.10s="loadNotifications">

    <div class="notification-stack">

        @if ($request_count > 0 ||$assigned_count > 0 ||$nte_cpar > 0)
        <div class="notification-container">
            <a class="notification-toast"
                aria-label="Open CPAR Notifications">

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
                    <div class="notification-arrow">
                        <flux:icon.chevron-right class="h-4 w-4" />
                    </div>

                </div>

            </a>
        </div>
        @endif

        @if ($cpar_count > 0 || $result_count > 0 || $acknowledgment_count > 0)
        <div class="notification-container">

            <a class="notification-toast"
                aria-label="Open Department Head Notifications">

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

                            @if (
                            $cpar_count > 0 ||
                            $result_count > 0
                            )

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
                    <div class="notification-arrow">
                        <flux:icon.chevron-right class="h-4 w-4" />
                    </div>

                </div>

            </a>

        </div>
        @endif

        @if ($hr_request_count > 0 || $acknowledged_cpar > 0 || $hr_decision_count > 0 || $memo_count > 0)
        <div class="notification-container">

            <a class="notification-toast"
                aria-label="Open HR Notifications">

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
                                Acknowledged{{ $acknowledged_cpar > 1 ? 's' : '' }}
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
                    <div class="notification-arrow">
                        <flux:icon.chevron-right class="h-4 w-4" />
                    </div>

                </div>

            </a>

        </div>
        @endif

        @if ($lab_request_count > 0)
        <div class="notification-container">

            <a class="notification-toast"
                aria-label="Open Laboratory Notifications">

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
                                laboratory request{{ $lab_request_count > 1 ? 's' : '' }}
                            </div>

                        </div>

                    </div>

                    {{-- Arrow --}}
                    <div class="notification-arrow">
                        <flux:icon.chevron-right class="h-4 w-4" />
                    </div>

                </div>

            </a>

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

        }

        .notification-container {
            width: 100%;
            pointer-events: auto;
        }

        .notification-toast {

            display: block;

            width: 100%;

            background: #dc2626;

            color: #ffffff;

            border-radius: 6px;

            padding: 13px 14px;

            cursor: pointer;

            user-select: none;

            text-decoration: none;

            box-shadow:
                0 4px 14px rgba(220, 38, 38, 0.45);

            transition:
                transform 0.15s ease,
                box-shadow 0.15s ease;

            animation:
                notificationBlink 1.2s infinite,
                notificationSlide 0.25s ease-out;

        }

        .notification-toast:hover {

            transform: translateX(-5px);

            box-shadow:
                0 6px 20px rgba(220, 38, 38, 0.60);

        }

        .notification-toast:active {

            transform:
                translateX(-2px) scale(0.99);

        }

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

            background: #ffffff;

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

            color: #ffffff;

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

                background: #dc2626;

                box-shadow:
                    0 4px 14px rgba(220, 38, 38, 0.45);

            }

            50% {

                background: #991b1b;

                box-shadow:
                    0 0 0 6px rgba(220, 38, 38, 0.18),

                    0 6px 20px rgba(220, 38, 38, 0.65);

            }

            100% {

                background: #dc2626;

                box-shadow:
                    0 4px 14px rgba(220, 38, 38, 0.45);

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