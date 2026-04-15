@props(['name', 'id' => null, 'options' => [], 'selected' => null, 'placeholder' => 'Select option...'])

<div x-data="{ 
        open: false, 
        value: '{{ $selected }}', 
        label: '{{ $options[$selected] ?? $placeholder }}',
        options: @js($options),
        placeholder: '{{ $placeholder }}',
        select(val, lab) {
            this.value = val;
            this.label = lab;
            this.open = false;
            $refs.hiddenInput.value = val;
            $refs.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
        },
        updateOptions(newOptions) {
            this.options = newOptions;
            if (!this.options[this.value]) {
                this.value = '';
                this.label = this.placeholder;
                $refs.hiddenInput.value = '';
            }
        }
     }" 
     @update-options-{{ $id ?? $name }}.window="updateOptions($event.detail)"
     class="relative">
    
    <input type="hidden" name="{{ $name }}" id="{{ $id ?? $name }}" x-ref="hiddenInput" value="{{ $selected }}">
    
    <button type="button" 
            @click="open = !open"
            @click.away="open = false"
            class="flex items-center justify-between w-full px-5 py-4 text-left text-slate-800 bg-slate-50 border border-slate-200 rounded-2xl focus:border-emerald-400 focus:ring-4 focus:ring-emerald-500/10 transition-all duration-300 font-bold group">
        <span x-text="label" :class="value ? 'text-slate-800' : 'text-slate-400'"></span>
        <i class="fa-solid fa-chevron-down text-xs text-slate-400 group-hover:text-emerald-500 transition-colors" :class="open ? 'rotate-180' : ''"></i>
    </button>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
         class="absolute z-[120] w-full mt-3 bg-white border border-slate-100 rounded-[2rem] shadow-2xl shadow-slate-900/10 overflow-hidden py-2"
         style="display: none; max-height: 250px; overflow-y: auto;">
        
        <div x-show="Object.keys(options).length === 0" class="px-6 py-3 text-slate-400 text-sm font-bold">
            No options available
        </div>

        <template x-for="(display, val) in options" :key="val">
            <button type="button" 
                    @click="select(val, display)"
                    class="block w-full px-6 py-3 text-left text-sm font-bold text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 transition-all"
                    :class="value == val ? 'text-emerald-600 bg-emerald-50/50' : ''">
                <span x-text="display"></span>
            </button>
        </template>
    </div>
</div>
