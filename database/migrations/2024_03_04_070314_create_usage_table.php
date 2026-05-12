<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usage', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('total_user_count')->default(0);
            $table->unsignedInteger('this_week_user_count')->default(0);
            $table->unsignedInteger('last_week_user_count')->default(0);

            $table->unsignedInteger('total_word_count')->default(0);
            $table->unsignedInteger('this_week_word_count')->default(0);
            $table->unsignedInteger('last_week_word_count')->default(0);

            $table->unsignedInteger('total_image_count')->default(0);
            $table->unsignedInteger('this_week_image_count')->default(0);
            $table->unsignedInteger('last_week_image_count')->default(0);

            $table->unsignedInteger('total_sales')->default(0);
            $table->unsignedInteger('this_week_sales')->default(0);
            $table->unsignedInteger('last_week_sales')->default(0);

            $table->timestamps();
        });

        $numericExpression = static function (string $column): string {
            return DB::getDriverName() === 'pgsql'
                ? "COALESCE(SUM(CAST({$column} AS NUMERIC)), 0) as aggregate"
                : "COALESCE(SUM({$column}), 0) as aggregate";
        };

        $sumColumn = static function (string $table, string $column, ?callable $scope = null) use ($numericExpression): float {
            $query = DB::table($table);
            if ($scope !== null) {
                $scope($query);
            }

            return (float) ($query->selectRaw($numericExpression($column))->value('aggregate') ?? 0);
        };

        // Count existing users
        $totaluserCount = DB::table('users')->count();
        $totalWordCount = $sumColumn('user_openai', 'credits', static fn ($query) => $query->where('credits', '!=', 1));
        $totalImageCount = $sumColumn('user_openai', 'credits', static fn ($query) => $query->where('credits', '=', 1));
        $totalSalesCount = $sumColumn('user_orders', 'price');

        // Count users created this week
        $thisWeekUserCount = DB::table('users')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $thisWeekWordCount = $sumColumn('user_openai', 'credits', static fn ($query) => $query->where('credits', '!=', 1)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]));
        $thisWeekImageCount = $sumColumn('user_openai', 'credits', static fn ($query) => $query->where('credits', '=', 1)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]));
        $thisWeekSales = $sumColumn('user_orders', 'price', static fn ($query) => $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]));

        // Count users created last week
        $lastWeekUserCount = DB::table('users')->whereBetween('created_at', [Carbon::now()->startOfWeek()->subWeek(), Carbon::now()->endOfWeek()->subWeek()])->count();
        $lastWeekWordCount = $sumColumn('user_openai', 'credits', static fn ($query) => $query->where('credits', '!=', 1)->whereBetween('created_at', [Carbon::now()->startOfWeek()->subWeek(), Carbon::now()->endOfWeek()->subWeek()]));
        $lastWeekImageCount = $sumColumn('user_openai', 'credits', static fn ($query) => $query->where('credits', '=', 1)->whereBetween('created_at', [Carbon::now()->startOfWeek()->subWeek(), Carbon::now()->endOfWeek()->subWeek()]));
        $lastWeekSales = $sumColumn('user_orders', 'price', static fn ($query) => $query->whereBetween('created_at', [Carbon::now()->startOfWeek()->subWeek(), Carbon::now()->endOfWeek()->subWeek()]));

        // Set the initial value in settings table
        DB::table('usage')->updateOrInsert(
            [],
            [
                'total_user_count'      => $totaluserCount,
                'this_week_user_count'  => $thisWeekUserCount,
                'last_week_user_count'  => $lastWeekUserCount,
                'total_word_count'      => $totalWordCount,
                'this_week_word_count'  => $thisWeekWordCount,
                'last_week_word_count'  => $lastWeekWordCount,
                'total_image_count'     => $totalImageCount,
                'this_week_image_count' => $thisWeekImageCount,
                'last_week_image_count' => $lastWeekImageCount,
                'total_sales'           => $totalSalesCount,
                'this_week_sales'       => $thisWeekSales,
                'last_week_sales'       => $lastWeekSales,
                'created_at'            => now(),
                'updated_at'            => now(),
            ]
        );

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usage');
    }
};
