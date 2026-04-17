<div id="toast-container" class="fixed top-6 right-6 z-[9999] flex flex-col gap-3 pointer-events-none min-w-[320px]"></div>

<style>
@keyframes slideInRight {
    0% { transform: translateX(120%); opacity: 0; }
    100% { transform: translateX(0); opacity: 1; }
}
@keyframes fadeOutRight {
    0% { transform: translateX(0); opacity: 1; }
    100% { transform: translateX(120%); opacity: 0; }
}
.toast-enter {
    animation: slideInRight 0.4s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
}
.toast-leave {
    animation: fadeOutRight 0.3s cubic-bezier(0.6, -0.28, 0.735, 0.045) forwards;
}
.toast-item {
    pointer-events: auto;
    background: var(--glass-bg);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid var(--glass-border);
    color: var(--text-main);
    box-shadow: 0 10px 40px -10px rgba(0,0,0,0.2);
}
</style>

<script>
window.Toast = {
    show: function(message, type = 'success', duration = 4000) {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        
        const icons = {
            'success': '<i class="ph-fill ph-check-circle"></i>',
            'error': '<i class="ph-fill ph-x-circle"></i>',
            'warning': '<i class="ph-fill ph-warning"></i>',
            'info': '<i class="ph-fill ph-info"></i>'
        };

        const colors = {
            'success': 'text-[#10b981]',
            'error': 'text-[#ef4444]',
            'warning': 'text-[#f59e0b]',
            'info': 'text-[#3b82f6]'
        };

        const bgColors = {
            'success': 'bg-[#10b981]/10',
            'error': 'bg-[#ef4444]/10',
            'warning': 'bg-[#f59e0b]/10',
            'info': 'bg-[#3b82f6]/10'
        };

        const iconHtml = icons[type] || icons['info'];
        const colorClass = colors[type] || colors['info'];
        const bgClass = bgColors[type] || bgColors['info'];

        toast.className = `toast-item toast-enter flex items-start gap-3.5 px-5 py-4 rounded-2xl relative overflow-hidden`;

        toast.innerHTML = `
            <div class="${colorClass} ${bgClass} rounded-full p-1.5 flex items-center justify-center text-xl shrink-0 mt-0.5">
                ${iconHtml}
            </div>
            <div class="flex-1 flex flex-col justify-center min-h-[32px]">
                <p class="m-0 text-[0.95rem] font-medium leading-relaxed">${message}</p>
            </div>
            <button class="shrink-0 text-[var(--text-muted)] hover:text-[var(--text-main)] transition-colors cursor-pointer pt-1 bg-transparent border-none" onclick="window.Toast.close(this.closest('.toast-item'))">
                <i class="ph ph-x text-lg"></i>
            </button>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-black/5 dark:bg-white/5">
                <div class="h-full bg-current opacity-70 ${colorClass}" style="width: 100%; transition: width ${duration}ms linear;" data-progress></div>
            </div>
        `;

        container.appendChild(toast);

        setTimeout(() => {
            const progress = toast.querySelector('[data-progress]');
            if(progress) progress.style.width = '0%';
        }, 10);

        if (duration > 0) {
            setTimeout(() => {
                this.close(toast);
            }, duration);
        }
    },
    
    close: function(toastElement) {
        if (!toastElement) return;
        toastElement.classList.remove('toast-enter');
        toastElement.classList.add('toast-leave');
        setTimeout(() => {
            if (toastElement.parentElement) {
                toastElement.remove();
            }
        }, 300);
    }
};

document.addEventListener('DOMContentLoaded', () => {
    @if(session()->has('toast_success')) window.Toast.show("{!! addslashes(session('toast_success')) !!}", 'success'); @endif
    @if(session()->has('toast_error')) window.Toast.show("{!! addslashes(session('toast_error')) !!}", 'error'); @endif
    @if(session()->has('toast_info')) window.Toast.show("{!! addslashes(session('toast_info')) !!}", 'info'); @endif
    @if(session()->has('toast_warning')) window.Toast.show("{!! addslashes(session('toast_warning')) !!}", 'warning'); @endif

    @if(session()->has('success')) window.Toast.show("{!! addslashes(session('success')) !!}", 'success'); @endif
    @if(session()->has('error')) window.Toast.show("{!! addslashes(session('error')) !!}", 'error'); @endif
    @if(session()->has('info')) window.Toast.show("{!! addslashes(session('info')) !!}", 'info'); @endif
    @if(session()->has('warning')) window.Toast.show("{!! addslashes(session('warning')) !!}", 'warning'); @endif
});
</script>
