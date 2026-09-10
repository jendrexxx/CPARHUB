<div wire:poll.10s="loadNotifications">

    @if ($cpar_count > 0 || $result_count > 0)

        <div class="notification-container">

            <a href="{{ route('dept_head_dashboard', ['open' => 'notifications']) }}"
               class="notification-toast"
               aria-label="Open Department Head Dashboard">

                <div class="notification-content">

                    {{-- Icon --}}
                    <div class="notification-icon">
                        <flux:icon.bell class="h-5 w-5" />
                    </div>

                    {{-- Text --}}
                    <div class="notification-text">

                        <div class="notification-title">
                            New Notifications
                        </div>

                        <div class="notification-message">
                            You have
                            {{ $concern_count }}
                            new notification{{ $concern_count > 1 ? 's' : '' }}.
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


    <style>

        /* =========================================================
           NOTIFICATION CONTAINER
        ========================================================== */

        .notification-container {
            position: fixed;

            right: 20px;
            bottom: 20px;

            width: 360px;

            z-index: 9999;
        }


        /* =========================================================
           NOTIFICATION TOAST
        ========================================================== */

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


        /* =========================================================
           HOVER
        ========================================================== */

        .notification-toast:hover {
            transform: translateX(-5px);

            box-shadow:
                0 6px 20px rgba(220, 38, 38, 0.60);
        }


        /* =========================================================
           ACTIVE
        ========================================================== */

        .notification-toast:active {
            transform:
                translateX(-2px)
                scale(0.99);
        }


        /* =========================================================
           CONTENT
        ========================================================== */

        .notification-content {
            display: flex;

            align-items: center;

            gap: 12px;
        }


        /* =========================================================
           ICON
        ========================================================== */

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


        /* =========================================================
           TEXT
        ========================================================== */

        .notification-text {
            min-width: 0;

            flex: 1;
        }


        /* =========================================================
           TITLE
        ========================================================== */

        .notification-title {
            font-size: 14px;

            font-weight: 700;

            color: #ffffff;

            margin-bottom: 3px;
        }


        /* =========================================================
           MESSAGE
        ========================================================== */

        .notification-message {
            font-size: 12px;

            font-weight: 500;

            color: #ffffff;

            line-height: 1.4;
        }


        /* =========================================================
           ARROW
        ========================================================== */

        .notification-arrow {
            margin-left: auto;

            display: flex;

            align-items: center;
            justify-content: center;

            color: #ffffff;

            flex-shrink: 0;
        }


        /* =========================================================
           RED BLINKING
        ========================================================== */

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


        /* =========================================================
           SLIDE-IN
        ========================================================== */

        @keyframes notificationSlide {

            from {
                opacity: 0;

                transform:
                    translateX(25px);
            }

            to {
                opacity: 1;

                transform:
                    translateX(0);
            }

        }


        /* =========================================================
           MOBILE
        ========================================================== */

        @media (max-width: 576px) {

            .notification-container {
                right: 10px;

                bottom: 10px;

                left: 10px;

                width: auto;
            }

        }


        /* =========================================================
           REDUCED MOTION
        ========================================================== */

        @media (prefers-reduced-motion: reduce) {

            .notification-toast {
                animation: none;
            }

        }

    </style>

</div>