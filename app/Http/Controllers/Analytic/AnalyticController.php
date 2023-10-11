<?php

namespace App\Http\Controllers\Analytic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Status;
use App\Models\Category;
use App\Models\Transaction;

class AnalyticController extends Controller
{
    
    // Order Statistics 
    public function orderStatistics(Request $request)
    {
        
        /*
        * Get all sales of lifetime where status is Complelted 
        */
        $status = Status::where('name', 'Completed')->first();
        $orders = Order::with('cart.products.product.categories')->where('status_id', $status->id)->get();
        $totalSales = $orders->count();
        $totalSalesPrice = $orders->sum('total_price');

        /*
        * Get top 5 categories by order sales and total sales of each category
        */
        $categories = Category::all();
        $topCategories = [];
        foreach ($categories as $category) {
            $totalCategorySales = 0;
            $count_sales = 0;
            foreach ($orders as $order) {
                foreach ($order->cart->products as $product) {
                    foreach ($product->product->categories as $productCategory) {
                        if ($productCategory->id == $category->id) {
                            $totalCategorySales += $product->unit_price * $product->quantity;
                            $count_sales++;
                        }
                    }
                }
            }

            $topCategories[] = [
                'category' => $category->name,
                'count_sales' => $count_sales,
                'total_sales' => $totalCategorySales
            ];
        }

        // Get Top 5
        usort($topCategories, function ($a, $b) {
            return $b['total_sales'] <=> $a['total_sales'];
        });
        $topCategories = array_slice($topCategories, 0, 5);

        // Orders percent weekly
        $orders = Order::where('created_at', '>=', now()->subDays(7))->get();
        $totalOrdersPrice = $orders->sum('total_price');
        $totalOrdersCost = $orders->sum('total_cost');
        $totalOrdersProfit = $totalOrdersPrice - $totalOrdersCost;
        // manage division by zero
        if ($totalOrdersCost == 0) {
            $totalOrdersCost = 1;
        }
        $totalOrdersProfitPercentage = ($totalOrdersProfit / $totalOrdersCost) * 100;

        return response()->json([
            'weekly_profit' => $totalOrdersProfitPercentage,
            'total_orders' => $totalSales,
            'total_orders_price' => $totalSalesPrice,
            'top_categories' => $topCategories,
        ]);
    }

    public function generalStatistics(Request $request)
    {
        // cart.products.product.categories
        // Calculate 24h Profit, Sales, Purchases, Transactions
        $status = Status::where('name', 'Completed')->first();
        $orders = Order::with([
            'cart' => [
                'products' => [
                    'product' => [
                        'categories'
                    ]
                ]
            ]
        ])->where('status_id', $status->id)->where('created_at', '>=', now()->subDays(1))->get();
      
        
        $totalSalesPrice = $orders->sum('total_price');
        $totalSalesCost = $orders->sum('total_cost');
        $totalSalesProfit = $totalSalesPrice - $totalSalesCost;
        // manage division by zero
        if ($totalSalesCost == 0) {
            $totalSalesCost = 1;
        }

        // Sum total transactions amount
        $transactions = Transaction::where('created_at', '>=', now()->subDays(1))->sum('amount');
        
        // Previous Day (between 24 and 48 hours)
        $previous_orders = Order::with([
            'cart' => [
                'products' => [
                    'product' => [
                        'categories'
                    ]
                ]
            ]
        ])->where('status_id', $status->id)->where('created_at', '>=', now()->subDays(2))->where('created_at', '<=', now()->subDays(1))->get();
        $totalSalesPricePreviousDay = $previous_orders->sum('total_price');
        $totalSalesCostPreviousDay = $previous_orders->sum('total_cost');
        $totalSalesProfitPreviousDay = $totalSalesPricePreviousDay - $totalSalesCostPreviousDay;
        // manage division by zero
        if ($totalSalesCostPreviousDay == 0) {
                $totalSalesCostPreviousDay = 1;
        }

        // Sum previous transactions
        $transactionsPreviousDay = Transaction::where('created_at', '>=', now()->subDays(2))->where('created_at', '<=', now()->subDays(1))->sum('amount');

        // Compare previous and current
        $percentage_profit = ($totalSalesProfit - $totalSalesProfitPreviousDay) / $totalSalesCostPreviousDay * 100;
        $percentage_sales = ($totalSalesPrice - $totalSalesPricePreviousDay) / $totalSalesPricePreviousDay * 100;

        
        // Check if it is the first day 
        if ($transactionsPreviousDay == 0) { 
            // Set the trend percentage to 100% 
            $percentage_transactions = 100; 
        } 
        else { 
            // Calculate the trend percentage using the formula 
            $percentage_transactions = ($transactions * 100) / $transactionsPreviousDay; 
        }

        // Round the trend percentage to two decimals 
        $percentage_transactions = round($percentage_transactions, 2);

        return response()->json([
            'profit' => [
                'total_profit' => $totalSalesProfit,
                'percentage_24h' => $percentage_profit
            ],
            'sales' => [
                'total_sales' => $totalSalesPrice,
                'percentage_24h' => $percentage_sales
            ],
            'transactions' => [
                'total_transactions' => $transactions,
                'percentage_24h' => $percentage_transactions
            ]
        ]);
    }
}
