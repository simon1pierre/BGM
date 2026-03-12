@php
    $exception = $exception ?? null;
@endphp

@auth
    <div class="mt-6 bg-slate-900 text-slate-100 rounded-xl p-5 text-xs">
        <div class="font-semibold text-slate-200 mb-2">Admin Error Details</div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <div><span class="text-slate-400">URL:</span> {{ request()->fullUrl() }}</div>
                <div><span class="text-slate-400">Method:</span> {{ request()->method() }}</div>
                <div><span class="text-slate-400">Route:</span> {{ optional(request()->route())->getName() ?? 'N/A' }}</div>
                <div><span class="text-slate-400">User:</span> {{ auth()->user()->email ?? auth()->id() }}</div>
            </div>
            <div>
                <div><span class="text-slate-400">Exception:</span> {{ $exception ? class_basename($exception) : 'N/A' }}</div>
                <div><span class="text-slate-400">Message:</span> {{ $exception?->getMessage() ?? 'N/A' }}</div>
                <div><span class="text-slate-400">File:</span> {{ $exception?->getFile() ?? 'N/A' }}</div>
                <div><span class="text-slate-400">Line:</span> {{ $exception?->getLine() ?? 'N/A' }}</div>
            </div>
        </div>
        <div class="text-slate-400 mt-3">If this persists, capture a screenshot and report to the development team.</div>
    </div>
@endauth







