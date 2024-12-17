<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Earnings;
use App\Models\Payments;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class EarningsController extends Controller
{
    /**
     * Display the host earnings dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Total Earnings
        $totalEarnings = $this->calculateTotalEarnings($user);
        
        // Monthly Earnings
        $monthlyEarnings = $this->calculateMonthlyEarnings($user);
        
        // Upcoming Payouts
        $upcomingPayouts = $this->calculateUpcomingPayouts($user);
        
        // Next Payout Date
        $nextPayoutDate = $this->getNextPayoutDate($user);
        
        // Property Performance
        $totalProperties = $this->getTotalProperties($user);
        $occupancyRate = $this->calculateOccupancyRate($user);
        
        // Recent Transactions
        $recentTransactions = $this->getRecentTransactions($user);
        
        // Earnings Chart Data
        $earningsChartData = $this->getEarningsChartData($user);

        return view('earnings', compact(
            'totalEarnings', 
            'monthlyEarnings', 
            'upcomingPayouts', 
            'nextPayoutDate',
            'totalProperties',
            'occupancyRate',
            'recentTransactions',
            'earningsChartData'
        ));
    }

    /**
     * Calculate total earnings for a host
     */
    protected function calculateTotalEarnings(User $user)
    {
        return Earnings::where('user_id', $user->id)->sum('amount');
    }

    /**
     * Calculate earnings for the current month
     */
    protected function calculateMonthlyEarnings(User $user)
    {
        return Earnings::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
    }

    /**
     * Calculate upcoming payouts
     */
    protected function calculateUpcomingPayouts(User $user)
    {
        // Logic to calculate upcoming payouts based on pending bookings or payments
        return Booking::whereHas('property', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('status', 'confirmed')
        ->whereDate('check_out_date', '<=', now()->addDays(30))
        ->sum('total_price');
    }

    /**
     * Get next payout date
     */
    protected function getNextPayoutDate(User $user)
    {
        // Assuming payouts are processed monthly
        return now()->addMonth()->startOfMonth()->format('d M Y');
    }

    /**
     * Get total number of host's properties
     */
    protected function getTotalProperties(User $user)
    {
        return Property::where('user_id', $user->id)->count();
    }

    /**
     * Calculate property occupancy rate
     */
    protected function calculateOccupancyRate(User $user)
    {
        $properties = Property::where('user_id', $user->id)->get();
        
        if ($properties->isEmpty()) {
            return 0;
        }

        $totalBookings = Booking::whereIn('property_id', $properties->pluck('id'))
            ->where('status', 'confirmed')
            ->count();

        $totalPossibleBookings = $properties->count() * Carbon::now()->daysInMonth;

        return $totalBookings > 0 
            ? ($totalBookings / $totalPossibleBookings) * 100 
            : 0;
    }

    /**
     * Get recent transactions
     */
    protected function getRecentTransactions(User $user)
    {
        return Booking::whereHas('property', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with('property')
        ->latest()
        ->take(10)
        ->get()
        ->map(function($booking) {
            return (object)[
                'date' => $booking->created_at->format('d M Y'),
                'property_title' => $booking->property->title,
                'amount' => $booking->total_price
            ];
        });
    }

    /**
     * Get earnings chart data for the last 6 months
     */
    protected function getEarningsChartData(User $user)
    {
        $earnings = Earnings::where('user_id', $user->id)
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total_earnings')
            ->whereRaw('created_at >= ?', [now()->subMonths(6)->startOfMonth()])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = [];
        $data = [];

        // Generate last 6 months labels and ensure data for all months
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('M');
            $labels[] = $month;
            
            $monthEarning = $earnings->firstWhere('month', now()->subMonths($i)->month);
            $data[] = $monthEarning ? $monthEarning->total_earnings : 0;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Update payout method
     */
    public function updatePayoutMethod(Request $request)
    {
        $validatedData = $request->validate([
            'payout_method' => 'required|in:PayPal,Bank Transfer,Stripe',
            'payout_details' => 'required|string|max:255'
        ]);

        $user = Auth::user();
        $user->update([
            'payout_method' => $validatedData['payout_method'],
            'payout_details' => $validatedData['payout_details']
        ]);

        return redirect()->back()->with('success', 'Payout method updated successfully');
    }
}























