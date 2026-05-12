<?php

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
        $templates = [
            [
                'title' => 'Successful Subscription',
                'subject' => 'Successful Subscription Email',
                'content' => '<div style="padding: 0 19px">\r\n    <h1>Hello, {user_name}!</h1>\r\n    <h2>You have successfully subscribed to {site_name}!</h2>\r\n\r\n    <p>Thank you for subscribing to our {plan_name} plan. Your subscription is now active.</p>\r\n      <p>Thank you for choosing {site_name}.</p>\r\n    <p>Click <a href="{login_url}">here</a> to login to your account.</p>\r\n    <p>Click <a href="{site_url}">here</a> to visit our site.</p>\r\n</div>\r\n\r\n<br>\r\n\r\n<a href="{login_url}" class="btn btn-lg btn-block btn-round">\r\n    Login to Your Account\r\n</a>\r\n\r\n<p class="need-help-p">Need help? <a href="{site_url}">Contact us.</a></p>',
                'slug' => 'subscription-successful',
            ],
            [
                'title' => 'Successful Payment',
                'subject' => 'Successful Payment Email',
                'content' => '<div style="padding: 0 19px">\r\n    <h1>Hello, {user_name}!</h1>\r\n    <h2>Your payment was successful!</h2>\r\n\r\n    <p>Thank you for your payment. Your payment has been successfully processed for our {plan_name} plan.</p>\r\n    <ul>\r\n    <p>Thank you for choosing {site_name}.</p>\r\n    <p>Click <a href="{login_url}">here</a> to login to your account.</p>\r\n    <p>Click <a href="{site_url}">here</a> to visit our site.</p>\r\n</div>\r\n\r\n<br>\r\n\r\n<a href="{login_url}" class="btn btn-lg btn-block btn-round">\r\n    Login to Your Account\r\n</a>\r\n\r\n<p class="need-help-p">Need help? <a href="{site_url}">Contact us.</a></p>',
                'slug' => 'payment-successful',
            ],
        ];

        foreach ($templates as $template) {
            DB::table('email_templates')->updateOrInsert(
                ['slug' => $template['slug']],
                [
                    'title' => $template['title'],
                    'subject' => $template['subject'],
                    'content' => $template['content'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_templates', function (Blueprint $table) {
            //
        });
    }
};
