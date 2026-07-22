<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code - Table {{ $diningTable->number }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            body { 
                background: white !important; 
                display: block !important;
                min-h-screen: auto !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .no-print { display: none !important; }
            .print-container { 
                box-shadow: none !important; 
                border: 1px dashed #ccc !important; /* Add dashed border for cutting guide */
                margin: 0 !important;
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen py-10">

    <!-- Action Buttons (Hidden when printing) -->
    <div class="no-print mb-8 flex gap-4">
        <button onclick="window.print()" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Poster QR
        </button>
        <button onclick="window.close()" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg shadow transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            Tutup
        </button>
    </div>

    <!-- Sticker Container (60x85mm - Extra Compact Table Sticker) -->
    <div class="print-container bg-white w-[60mm] min-h-[85mm] shadow-2xl rounded-lg overflow-hidden flex flex-col relative border border-gray-200">
        
        <!-- Top Banner -->
        <div class="bg-emerald-600 h-1.5 w-full absolute top-0 left-0"></div>

        <div class="flex-1 flex flex-col items-center justify-center p-1 text-center mt-1">
            
            <!-- Cafe Name / Logo Placeholder -->
            <div class="mb-1">
                <h1 class="text-[11px] font-extrabold text-gray-900 tracking-tight leading-none">{{ config('app.name', 'Cafe POS') }}</h1>
                <p class="text-gray-500 mt-0.5 text-[6px] font-medium leading-none">Scan untuk Memesan & Membayar</p>
            </div>

            <!-- Table Number Badge -->
            <div class="mb-1">
                <span class="bg-emerald-100 text-emerald-800 text-[8px] px-2 py-0.5 rounded-full font-bold shadow-sm border border-emerald-200 leading-none inline-block">
                    MEJA {{ $diningTable->number }}
                </span>
            </div>

            <!-- QR Code Box -->
            <div class="bg-white rounded-sm relative mb-1 flex items-center justify-center">
                @if($diningTable->qr_code)
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(95)->margin(0)->generate($diningTable->qr_code) !!}
                @else
                    <div class="w-[95px] h-[95px] flex items-center justify-center bg-gray-50 text-gray-400 text-[7px] text-center p-1">
                        QR Code Belum Tersedia
                    </div>
                @endif
            </div>

            <!-- Instructions -->
            <div class="flex items-center justify-center gap-1 text-gray-600 font-bold text-[6px] uppercase tracking-tighter">
                <span>Scan QR</span>
                <svg class="w-1.5 h-1.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                <span>Pesan</span>
                <svg class="w-1.5 h-1.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                <span>Bayar</span>
            </div>

        </div>

        <!-- Bottom Banner -->
        <div class="bg-gray-50 py-1 text-center border-t border-gray-100 mt-auto">
            <p class="text-gray-400 font-medium text-[5px] leading-none">Powered by {{ config('app.name') }}</p>
        </div>
    </div>

</body>
</html>
