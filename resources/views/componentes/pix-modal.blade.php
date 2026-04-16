{{-- Modal PIX --}}
<div id="pix-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="relative max-w-md w-full rounded-2xl border border-base-300 bg-base-100 shadow-2xl p-6 space-y-4">
        <button id="close-pix" type="button" class="btn btn-sm btn-circle absolute right-3 top-3">✕</button>
        <div class="text-sm font-semibold text-primary">Pagamento PIX</div>
        <div class="rounded-xl border border-dashed border-base-300 bg-base-200/60 p-8 text-center text-base-content/60">QR Code aqui</div>
        <div class="text-sm space-y-1">
            <div class="font-semibold">Chave PIX:</div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <div id="pix-key" class="text-base break-all">inscricao@erac61.com.br</div>
                <button
                    type="button"
                    class="btn btn-sm btn-outline btn-primary w-full sm:w-auto"
                    data-copy-button
                    data-copy-text="inscricao@erac61.com.br"
                    aria-label="Copiar chave PIX"
                    title="Copiar chave PIX"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <rect x="9" y="9" width="10" height="10" rx="2"></rect>
                        <path d="M5 15V7a2 2 0 0 1 2-2h8"></path>
                    </svg>
                </button>
            </div>
        
        </div>
        <div class="rounded-xl border border-primary/30 bg-primary/10 p-3 text-sm font-semibold text-primary">
            <div class="text-center">Após pagar, envie o comprovante para</div>
            <div class="mt-2 flex flex-col sm:flex-row sm:items-center sm:justify-center gap-3">
                <span class="underline break-all">comprovantes@erac61.com.br</span>
                <button
                    type="button"
                    class="btn btn-sm btn-outline btn-primary w-full sm:w-auto"
                    data-copy-button
                    data-copy-text="comprovantes@erac61.com.br"
                    aria-label="Copiar e-mail do comprovante"
                    title="Copiar e-mail do comprovante"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <rect x="9" y="9" width="10" height="10" rx="2"></rect>
                        <path d="M5 15V7a2 2 0 0 1 2-2h8"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
