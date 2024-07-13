<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function inventoryReport()
    {
        $inventory = Product::select(['id', 'name', 'quantity', 'price'])->get();
        return response(
            [
                'status' => true,
                'message' => 'success.',
                'data' => $inventory
            ]
        );
    }

    public function salesReport()
    {
        $sales = Sale::select(['id', 'total_amount', 'payment_mode', 'created_at'])->get();
        return response(
            [
                'status' => true,
                'message' => 'success.',
                'data' => $sales
            ]
        );
    }

    public function detailedSalesReport()
    {
        try {
            $raw_query = 'select a.id, a.created_at, b.name, c.quantity, c.unit_price, (c.quantity * c.unit_price) as total_price, a.payment_mode from sales a join sale_details c on a.id = c.sale_id join products b on c.product_id = b.id';

            // $detail_sales_report = DB::table('sales')->selectRaw($raw_query)->get();
            // $detail_sales_report = DB::raw($raw_query);
            $detail_sales_report = DB::select($raw_query);

            return response(
                [
                    'status' => true,
                    'message' => 'success.',
                    'data' => $detail_sales_report
                ]
            );
        } catch (\Throwable $th) {
            return response(
                [
                    'status' => false,
                    'message' => 'fail.',
                    'data' => $th->getMessage()
                ]
            );
        }
    }

    public function dailySalesSummary()
    {
        $raw_query = 'select date(created_at) as sales_date, sum(total_amount) as total_sales from sales group by date(created_at) order by created_at';

        $daily_summary = DB::select($raw_query);

        return response(
            [
                'status' => true,
                'message' => 'success.',
                'data' => $daily_summary
            ]
        );
    }

    public function monthlySalesSummary()
    {
        try {
            $raw_query = "select strftime('%Y-%m',created_at) as sale_month, sum(total_amount) as total_sales from sales group by strftime('%Y-%m',created_at) order by created_at";

            $monthly_sales_summary = DB::select($raw_query);

            return response(
                [
                    'status' => true,
                    'message' => 'success.',
                    'data' => $monthly_sales_summary
                ]
            );
        } catch (\Throwable $th) {
            return response(
                [
                    'status' => false,
                    'message' => 'fail.',
                    'data' => $th->getMessage()
                ]
            );
        }
    }

    public function salesByPaymentMode()
    {
        try {
            $raw_query = "select payment_mode, count(id) as number_of_sales, sum(total_amount) as total_sales from sales";

            $sales_by_payment_mode = DB::select($raw_query);

            return response(
                [
                    'status' => true,
                    'message' => 'success.',
                    'data' => $sales_by_payment_mode
                ]
            );
        } catch (\Throwable $th) {
            return response(
                [
                    'status' => false,
                    'message' => 'fail.',
                    'data' => $th->getMessage()
                ]
            );
        }
    }

    public function bestSellingProduct()
    {
       try {
        $raw_query = "select b.name, sum(a.quantity) as total_quantity_sold from sale_details a join products b on a.product_id = b.id group by b.id order by total_quantity_sold desc limit 10";

        $best_selling_product = DB::select($raw_query);

        return response(
            [
                'status' => true,
                'message' => 'success.',
                'data' => $best_selling_product
            ]
        );
       } catch (\Throwable $th) {
        return response(
            [
                'status' => false,
                'message' => 'fail.',
                'data' => $th->getMessage()
            ]
        );
       }
    }

    public function lowStockAlert()
    {
        $low_stock = Product::select(['id', 'name', 'quantity'])->where('quantity', '<', 10)->get();

        return response(
            [
                'status' => true,
                'message' => 'success.',
                'data' => $low_stock
            ]
        );
    }

    public function salesByProduct()
    {
        $raw_query = 'select b.name, sum(a.quantity) as total_quantity_sold, sum(a.quantity * a.unit_price) as total_sales from sale_details a join products b on a.product_id = b.id group by b.id';

        $sale_by_product = DB::select($raw_query);

        return response(
            [
                'status' => true,
                'message' => 'success.',
                'data' => $sale_by_product
            ]
        );
    }

    public function dailySalesTrendAnalysis()
    {
        $raw_query = 'select date(created_at) as sale_date, sum(total_amount) as total_sales from sales group by date(created_at) order by sale_date';

        $trend_analysis = DB::select($raw_query);

        return response(
            [
                'status' => true,
                'message' => 'success.',
                'data' => $trend_analysis
            ]
        );
    }
    public function monthlySalesTrendAnalysis()
    {
        $raw_query = "select strftime( '%Y-%m',created_at) as sale_date, sum(total_amount) as total_sales from sales group by strftime( '%Y-%m',created_at) order by sale_date";

        $trend_analysis = DB::select($raw_query);

        return response(
            [
                'status' => true,
                'message' => 'success.',
                'data' => $trend_analysis
            ]
        );
    }
}
