@extends('admin.admin-layout')
@section('title', 'Transaction Reports - Admin')
@section('page-title', 'Transaction History')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h3 class="text-2xl font-black m-0 mb-1">Lịch sử giao dịch</h3>
            <p class="text-[0.8rem] text-muted font-medium">Theo dõi chi tiết các lượt gửi xe và thanh toán</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-nav-hover p-4 rounded-2xl border border-header-border mb-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <form action="{{ route('admin.reports.transactions') }}" method="GET" class="flex flex-wrap items-center gap-1 p-1 bg-dropdown-bg rounded-xl border border-header-border w-full flex-1">
                <div class="relative min-w-[200px] flex-1">
                    <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-muted"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="pl-10 m-0 py-2 w-full text-sm bg-transparent border-transparent focus:bg-nav-hover rounded-lg transition-colors" placeholder="Tìm mã thẻ hoặc biển số...">
                </div>
                
                <div class="w-[1px] h-6 bg-header-border hidden md:block"></div>

                <select name="type" onchange="this.form.submit()" class="m-0 py-2 w-[130px] text-sm bg-transparent border-transparent focus:bg-nav-hover rounded-lg transition-colors">
                    <option value="">Tất cả loại vé</option>
                    <option value="casual" {{ request('type') == 'casual' ? 'selected' : '' }}>Vé lượt</option>
                    <option value="monthly" {{ request('type') == 'monthly' ? 'selected' : '' }}>Vé tháng</option>
                </select>

                <div class="w-[1px] h-6 bg-header-border hidden md:block"></div>

                <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()"
                       class="m-0 py-2 w-[140px] text-sm bg-transparent border-transparent focus:bg-nav-hover rounded-lg transition-colors">

                <button type="submit" class="btn btn-sm btn-primary px-4 py-2 ml-1">Lọc</button>

                @if(request()->hasAny(['type', 'date', 'search']))
                    <a href="{{ route('admin.reports.transactions') }}" class="btn btn-sm px-3 py-2 ml-1 bg-transparent border-transparent text-red-500 hover:bg-red-500/10 transition-colors" title="Xóa lọc">
                        <i class="ph-bold ph-arrow-counter-clockwise"></i>
                    </a>
                @endif
            </form>
        </div>
    </div>

<x-card>
    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-left">
            <thead>
                <tr class="border-b-2 border-header-border text-muted text-sm uppercase tracking-[0.5px]">
                    <th class="p-4">Time</th>
                    <th class="p-4">Type</th>
                    <th class="p-4">Card ID</th>
                    <th class="p-4">License Plate</th>
                    <th class="p-4">Amount</th>
                    <th class="p-4">Operator</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                    <tr class="border-b border-header-border hover:bg-nav-hover transition">
                        <td class="p-4 text-main font-medium text-sm">{{$t['payment_time']}}</td>
                        <td class="p-4">
                            <span class="{{$t['bg_color']}} {{$t['text_color']}} px-2.5 py-1 rounded-[20px] text-[0.7rem] font-bold uppercase tracking-wider">
                                {{$t['type']}}
                            </span>
                        </td>
                        <td class="p-4 font-mono text-sm text-muted">{{$t['rfid_code']}}</td>
                        <td class="p-4 font-semibold text-sm">
                            <span class="bg-nav-hover px-2 py-1 rounded">{{$t['license_plate']}}</span>
                        </td>
                        <td class="p-4 text-[#10b981] font-bold">{{$t['amount']}}</td>
                        <td class="p-4 text-muted text-sm">
                            <div class="flex items-center gap-2">
                                <i class="ph ph-user-circle text-lg"></i>
                                {{$t['staff_name']}}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-muted italic bg-nav-hover rounded-xl">
                            <i class="ph ph-detective text-4xl block mb-2 opacity-20"></i>
                            Không có giao dịch nào khớp với bộ lọc của bạn.
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>
    <div class="mt-4 flex justify-end">
        {{ $transactions->links() }}
    </div>
</x-card>
@endsection
