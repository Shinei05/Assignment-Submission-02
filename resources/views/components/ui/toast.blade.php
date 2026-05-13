<div
    x-data="{
        show: false,
        message: '',
        type: 'success',
        progress: 100,
        timer: null,
        progressTimer: null,
        duration: 4000,
        open(msg, t = 'success') {
            this.message = msg;
            this.type = t;
            this.show = true;
            this.progress = 100;
            clearTimeout(this.timer);
            clearInterval(this.progressTimer);
            const step = 100 / (this.duration / 50);
            this.progressTimer = setInterval(() => {
                this.progress = Math.max(0, this.progress - step);
            }, 50);
            this.timer = setTimeout(() => {
                this.show = false;
                clearInterval(this.progressTimer);
            }, this.duration);
        },
        close() {
            this.show = false;
            clearTimeout(this.timer);
            clearInterval(this.progressTimer);
        },
        init() {
            @if(session('success'))
                this.open(@js(session('success')), 'success');
            @elseif(session('error'))
                this.open(@js(session('error')), 'danger');
            @elseif(session('warning'))
                this.open(@js(session('warning')), 'warning');
            @elseif(session('status'))
                @php
                    $statusMessages = [
                        'profile-updated'          => 'Profile updated successfully.',
                        'password-updated'         => 'Password updated successfully.',
                        'verification-link-sent'   => 'Verification link sent to your email.',
                    ];
                    $statusMsg = $statusMessages[session('status')] ?? session('status');
                @endphp
                this.open(@js($statusMsg), 'success');
            @elseif($errors->any())
                this.open(@js($errors->first()), 'danger');
            @endif

            window.addEventListener('notify', e => {
                this.open(e.detail.message, e.detail.type || 'success');
            });
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform -translate-y-4 scale-95"
    x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 transform -translate-y-4 scale-95"
    class="fixed top-5 right-5 z-[9999] w-80 rounded-xl shadow-2xl overflow-hidden"
    :class="{
        'bg-success': type === 'success',
        'bg-danger':  type === 'danger',
        'bg-yellow-500': type === 'warning',
        'bg-primary': type === 'info'
    }"
    style="display: none;"
    role="alert"
>
    <div class="flex items-start gap-3 px-4 pt-4 pb-3">
        <!-- Icon -->
        <div class="shrink-0 mt-0.5">
            <template x-if="type === 'success'">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </template>
            <template x-if="type === 'danger'">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </template>
            <template x-if="type === 'warning'">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </template>
            <template x-if="type === 'info'">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </template>
        </div>

        <!-- Message -->
        <p x-text="message" class="flex-1 text-sm font-medium text-white leading-snug"></p>

        <!-- Close -->
        <button @click="close()" class="shrink-0 text-white/70 hover:text-white transition-colors mt-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <!-- Progress bar -->
    <div class="h-1 bg-white/20">
        <div
            class="h-full bg-white/50 transition-all duration-50 ease-linear"
            :style="`width: ${progress}%`"
        ></div>
    </div>
</div>
