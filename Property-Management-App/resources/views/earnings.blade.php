@extends('layouts.app')

@section('title', 'Host Earnings Dashboard')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8 bg-gray-50 min-h-screen">
    {{-- Header Section with User Info --}}
    <div class="mb-8 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <img 
                src="{{ Auth::user()->profile_image ?? '/default-avatar.png' }}" 
                alt="{{ Auth::user()->name }}" 
                class="w-16 h-16 rounded-full object-cover shadow-md transform transition-transform hover:scale-110"
            >
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Welcome, {{ Auth::user()->name }}</h1>
                <p class="text-gray-500">Host Earnings Dashboard</p>
            </div>
        </div>
        
        {{-- Quick Actions --}}
        <div class="flex space-x-4">
            <button 
                x-data 
                x-on:click="$dispatch('open-modal', 'payout-method-modal')"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>Payout Settings</span>
            </button>
        </div>
    </div>

    {{-- Earnings Overview Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Total Earnings Card --}}
        <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100 transform transition-all hover:scale-105 hover:shadow-xl">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Total Earnings</h2>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-3xl font-extrabold text-green-600 mb-2 animate-[pulse_2s_infinite]">
                ${{ number_format($totalEarnings, 2) }}
            </div>
            <div class="text-sm text-gray-500 flex justify-between items-center">
                <span>Earnings this month</span>
                <span class="font-semibold text-green-600">
                    ${{ number_format($monthlyEarnings, 2) }}
                </span>
            </div>
        </div>

        {{-- Upcoming Payouts Card --}}
        <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100 transform transition-all hover:scale-105 hover:shadow-xl">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Upcoming Payouts</h2>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-2xl font-extrabold text-blue-600 mb-2">
                ${{ number_format($upcomingPayouts, 2) }}
            </div>
            <div class="text-sm text-gray-500">
                Next payout: <span class="font-semibold text-blue-600">{{ $nextPayoutDate }}</span>
            </div>
        </div>

        {{-- Property Performance Card --}}
        <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100 transform transition-all hover:scale-105 hover:shadow-xl">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Property Performance</h2>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <div class="text-lg font-semibold text-gray-700 mb-2">
                {{ $totalProperties }} Active Properties
            </div>
            <div class="text-sm text-gray-500">
                Occupancy Rate: <span class="font-semibold text-green-600">{{ number_format($occupancyRate, 2) }}%</span>
            </div>
        </div>
    </div>

    {{-- Earnings Breakdown Section --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Transaction History --}}
        <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Recent Transactions</h2>
                <select class="form-select rounded-md border-gray-300 text-sm">
                    <option>Last 30 Days</option>
                    <option>Last 3 Months</option>
                    <option>Last Year</option>
                </select>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentTransactions as $transaction)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $transaction->date }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $transaction->property_title }}</td>
                                <td class="px-6 py-4 text-right text-sm font-semibold 
                                    {{ $transaction->amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    ${{ number_format($transaction->amount, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Earnings Chart --}}
        <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Earnings Trend</h2>
            <canvas id="earningsChart" class="w-full h-64"></canvas>
        </div>
    </div>

    {{-- Payout Method Modal --}}
    <form 
        x-data="{ open: false }" 
        x-on:open-modal.window="if ($event.detail === 'payout-method-modal') open = true"
        x-show="open" 
        x-cloak 
        method="POST" 
        action="{{ route('earnings.update-payout-method') }}"
        class="fixed inset-0 z-50 flex items-center justify-center"
        x-transition>
        @csrf
        <div 
            x-show="open" 
            x-on:click.away="open = false"
            class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 m-4 transform transition-all">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Update Payout Method</h2>
            
            <div class="mb-4">
                <label for="payout_method" class="block text-sm font-medium text-gray-700">Payout Method</label>
                <select 
                    name="payout_method" 
                    id="payout_method" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                >
                    <option value="PayPal">PayPal</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Stripe">Stripe</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label for="payout_details" class="block text-sm font-medium text-gray-700">Payout Details</label>
                <input 
                    type="text" 
                    name="payout_details" 
                    id="payout_details" 
                    placeholder="Enter your payout details"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                >
            </div>
            
            <div class="flex justify-end space-x-4">
                <button 
                    type="button" 
                    x-on:click="open = false"
                    class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200 transition-colors"
                >
                    Cancel
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                >
                    Update
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('earningsChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($earningsChartData['labels']), // Use ['labels']
            datasets: [{
                label: 'Monthly Earnings',
                data: @json($earningsChartData['data']), // Use ['data']
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1,
                fill: true,
                backgroundColor: 'rgba(75, 192, 192, 0.2)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value;
                        }
                    }
                }
            }
        }
    });
});
</script>
<style>
    [x-cloak] { display: none !important; }
    .animate-pulse {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
</style>
@endpush