<div>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <style>
            @page {
                margin: 1in;
            }

            body {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12pt;
                line-height: 1.5;
                color: #000;
            }

            .memo-content {
                white-space: pre-wrap;
                word-wrap: break-word;
            }
        </style>
    </head>

    <body>
        <div class="memo-content">{{ $content }}</div>
    </body>

    </html>
    <flux:button
        type="button"
        variant="primary"
        wire:click="printMemo">
        Print
    </flux:button>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('open-print-preview', (event) => {
                const url = event.url ?? event[0]?.url ?? '';
                window.open(url, '_blank');
            });
        });
    </script>
</div>