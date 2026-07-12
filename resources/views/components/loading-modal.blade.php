<!-- 80s Chrome Effect Loading Modal -->
<div id="retro-loading-modal" class="fixed inset-0 z-[100] bg-black bg-opacity-80 backdrop-blur-sm hidden items-center justify-center p-4 transition-opacity duration-300">
    <div class="retro-drive-container shadow-2xl relative w-full max-w-2xl h-[400px] rounded-xl overflow-hidden border-2 border-retro-cyan bg-retro-bg">
        
        <!-- The animated perspective highway grid -->
        <div class="grid-floor absolute bottom-0 w-full h-[30%]"></div>

        <!-- Bulky chrome title text -->
        <div class="absolute inset-0 flex items-center justify-center flex-col">
            <h1 id="retro-loading-text" class="text-center text-white font-arcade uppercase tracking-widest text-2xl sm:text-3xl md:text-4xl px-4 drop-shadow-[0_0_15px_rgba(0,243,255,0.8)]">Loading...</h1>
        </div>
    </div>
</div>


<script>
    window.showRetroLoading = function(message = 'Loading...') {
        const modal = document.getElementById('retro-loading-modal');
        const textElement = document.getElementById('retro-loading-text');
        
        if (textElement) {
            textElement.textContent = message;
        }
        
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Small delay to ensure display:flex is applied before opacity transition (if we add opacity transition later)
            setTimeout(() => {
                modal.classList.add('opacity-100');
            }, 10);
        }
    };

    window.hideRetroLoading = function() {
        const modal = document.getElementById('retro-loading-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.classList.remove('opacity-100');
        }
    };

    // Auto-attach to forms that want loading
    document.addEventListener('DOMContentLoaded', () => {
        const loadingForms = document.querySelectorAll('form[data-loading-message]');
        loadingForms.forEach(form => {
            form.addEventListener('submit', function() {
                const message = this.getAttribute('data-loading-message') || 'Loading...';
                window.showRetroLoading(message);
            });
        });
    });
</script>
