@props(['name', 'id' => null, 'options' => [], 'selected' => [], 'placeholder' => 'Select options...'])

<div x-data="{ 
        open: false, 
        selected: @js($selected ?? []), 
        options: @js($options),
        search: '',
        get filteredOptions() {
            if (!this.search) return this.options;
            let filtered = {};
            Object.keys(this.options).forEach(key => {
                if (this.options[key].toLowerCase().includes(this.search.toLowerCase())) {
                    filtered[key] = this.options[key];
                }
            });
            return filtered;
        },
        toggle(val) {
            val = val.toString();
            if (this.selected.includes(val)) {
                this.selected = this.selected.filter(i => i !== val);
            } else {
                this.selected.push(val);
            }
            this.updateInput();
        },
        updateInput() {
            // No direct action needed as we use x-model or hidden inputs
        },
        get label() {
            if (this.selected.length === 0) return '{{ $placeholder }}';
            if (this.selected.length <= 2) {
                return this.selected.map(id => this.options[id] || id).join(', ');
            }
            return this.selected.length + ' attributes selected';
        }
     }" 
     @click.away="open = false"
     class="relative">
    
    <!-- Hidden inputs for form submission -->
    <template x-for="val in selected" :key="val">
        <input type="hidden" name="{{ $name }}[]" :value="val">
    </template>
    
    <button type="button" 
            @click="open = !open"
            class="flex items-center justify-between w-full px-5 py-4 text-left text-slate-800 bg-slate-50 border border-slate-200 rounded-2xl focus:border-emerald-400 focus:ring-4 focus:ring-emerald-500/10 transition-all duration-300 font-bold group min-h-[58px]">
        <div class="flex flex-wrap gap-1 items-center">
            <template x-if="selected.length === 0">
                <span class="text-slate-400">{{ $placeholder }}</span>
            </template>
            <template x-for="val in selected.slice(0, 3)" :key="val">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest rounded-lg shadow-sm shadow-emerald-500/20 transition-all max-w-full">
                    <span x-text="options[val] || val" class="truncate max-w-[120px]"></span>
                    <i @click.stop="toggle(val)" class="fa-solid fa-xmark cursor-pointer hover:text-emerald-100 transition-colors shrink-0"></i>
                </span>
            </template>
            <template x-if="selected.length > 3">
                <span class="text-[10px] font-black text-slate-400 bg-slate-100 px-2.5 py-1 rounded-lg uppercase tracking-widest leading-none whitespace-nowrap">
                    +<span x-text="selected.length - 3"></span> MORE
                </span>
            </template>
        </div>
        <i class="fa-solid fa-chevron-down text-xs text-slate-400 group-hover:text-emerald-500 transition-colors shrink-0" :class="open ? 'rotate-180' : ''"></i>
    </button>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
         class="absolute block z-[150] w-full mt-3 bg-white border border-slate-100 rounded-[2.5rem] shadow-2xl shadow-slate-900/15 overflow-hidden"
         style="display: none;">
        
        <div class="p-4 border-b border-slate-50">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                <input type="text" 
                       x-model="search"
                       placeholder="Search attributes..."
                       class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold focus:outline-none focus:border-emerald-300 transition-all">
            </div>
        </div>

        <div class="max-h-60 overflow-y-auto py-2 px-2 scrollbar-thin scrollbar-thumb-slate-200">
            <template x-for="(display, val) in filteredOptions" :key="val">
                <button type="button" 
                        @click="toggle(val)"
                        class="flex items-center justify-between w-full px-4 py-3 rounded-xl text-left text-sm font-bold transition-all mb-1 hover:bg-slate-50"
                        :class="selected.includes(val.toString()) ? 'bg-emerald-50 text-emerald-600' : 'text-slate-600'">
                    <span x-text="display"></span>
                    <i x-show="selected.includes(val.toString())" class="fa-solid fa-check text-xs"></i>
                </button>
            </template>
            <div x-show="Object.keys(filteredOptions).length === 0" class="px-6 py-4 text-center text-slate-400 text-xs font-bold uppercase tracking-widest">
                No matching protocols
            </div>
        </div>

        <div class="p-4 bg-slate-50/50 border-t border-slate-50 flex justify-between items-center">
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400" x-text="selected.length + ' Linked'"></span>
            <button type="button" @click="selected = []" class="text-[10px] font-black uppercase tracking-widest text-red-400 hover:text-red-500 transition-colors">Clear All</button>
        </div>
    </div>
</div>
