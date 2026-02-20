<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Stripe Routes
|--------------------------------------------------------------------------
|
| Stripe アカウント・同期・決済
|
*/

Route::prefix('stripe/accounts')->group(function () {
    Route::get('list', \App\Http\Actions\Api\StripeAccount\GetAllStripeAccountAction::class);
    Route::post('create', \App\Http\Actions\Api\StripeAccount\CreateStripeAccountAction::class);
    Route::post('test-connection', \App\Http\Actions\Api\StripeAccount\TestStripeConnectionAction::class);
    Route::get('customers/subscriptions', \App\Http\Actions\Api\StripeAccount\GetCustomerSubscriptionsAction::class);
    Route::get('customers/search', \App\Http\Actions\Api\Stripe\SearchStripeCustomersAction::class);
    Route::get('dashboard-stats/all', \App\Http\Actions\Api\Stripe\GetAllDashboardStatsAction::class);
    Route::post('dashboard-stats/refresh-all', \App\Http\Actions\Api\Stripe\RefreshAllDashboardStatsAction::class);
    Route::post('backfill-all', \App\Http\Actions\Api\Stripe\BackfillAllStripeDataAction::class);

    // Sync Monitoring
    Route::get('sync-settings', \App\Http\Actions\Api\Stripe\GetSyncSettingsAction::class);
    Route::put('sync-settings', \App\Http\Actions\Api\Stripe\UpdateSyncSettingsAction::class);
    Route::get('sync-stats', \App\Http\Actions\Api\Stripe\GetSyncStatsAction::class);
    Route::get('sync-queue', \App\Http\Actions\Api\Stripe\GetSyncQueueAction::class);
    Route::delete('sync-queue/{id}', \App\Http\Actions\Api\Stripe\DeleteSyncQueueJobAction::class)->whereNumber('id');
    Route::post('sync-queue/{id}/execute', \App\Http\Actions\Api\Stripe\ExecuteSyncQueueJobAction::class)->whereNumber('id');
    Route::put('sync-queue/{id}/reschedule', \App\Http\Actions\Api\Stripe\RescheduleSyncQueueJobAction::class)->whereNumber('id');
    Route::post('sync-queue/{id}/cancel', \App\Http\Actions\Api\Stripe\CancelSyncQueueJobAction::class)->whereNumber('id');
    Route::get('sync-history', \App\Http\Actions\Api\Stripe\GetSyncHistoryAction::class);
    Route::get('db-stats', \App\Http\Actions\Api\Stripe\GetDbStatsAction::class);
    Route::post('sync-queue/enqueue-all', \App\Http\Actions\Api\Stripe\EnqueueSyncAllAccountsAction::class);
    Route::post('sync-queue/process', \App\Http\Actions\Api\Stripe\ProcessSyncQueueAction::class);

    // Dynamic routes
    Route::get('{id}', \App\Http\Actions\Api\StripeAccount\GetStripeAccountDetailAction::class)->whereNumber('id');
    Route::put('{id}', \App\Http\Actions\Api\StripeAccount\UpdateStripeAccountDetailAction::class)->whereNumber('id');
    Route::delete('{id}', \App\Http\Actions\Api\StripeAccount\DeleteStripeAccountAction::class)->whereNumber('id');
    Route::get('{id}/products', \App\Http\Actions\Api\StripeAccount\GetStripeProductsAction::class)->whereNumber('id');
    Route::get('{id}/prices', \App\Http\Actions\Api\StripeAccount\GetStripePricesAction::class)->whereNumber('id');
    Route::get('{id}/payment-links', \App\Http\Actions\Api\StripeAccount\GetStripePaymentLinksAction::class)->whereNumber('id');
    Route::get('{id}/transactions', \App\Http\Actions\Api\StripeAccount\GetStripeTransactionsAction::class)->whereNumber('id');
    Route::get('{id}/transactions/export', \App\Http\Actions\Api\StripeAccount\ExportStripeTransactionsAction::class)->whereNumber('id');
    Route::get('{id}/dashboard-stats', \App\Http\Actions\Api\Stripe\GetDashboardStatsAction::class)->whereNumber('id');
    Route::post('{id}/dashboard-stats/refresh', \App\Http\Actions\Api\Stripe\RefreshDashboardStatsAction::class)->whereNumber('id');
    Route::post('{id}/backfill', \App\Http\Actions\Api\Stripe\BackfillStripeDataAction::class)->whereNumber('id');
    Route::post('{id}/sync', \App\Http\Actions\Api\Stripe\IncrementalSyncStripeDataAction::class)->whereNumber('id');
    Route::get('{id}/sync-status', \App\Http\Actions\Api\Stripe\GetSyncStatusAction::class)->whereNumber('id');
    Route::get('{id}/sync-errors', \App\Http\Actions\Api\Stripe\GetSyncErrorsAction::class)->whereNumber('id');
    Route::post('{id}/sync-errors/resolve', \App\Http\Actions\Api\Stripe\ResolveSyncErrorAction::class)->whereNumber('id');
    Route::get('{id}/sync-jobs', \App\Http\Actions\Api\Stripe\GetSyncJobsAction::class)->whereNumber('id');
    Route::get('{id}/sync-jobs/running', \App\Http\Actions\Api\Stripe\GetRunningJobsAction::class)->whereNumber('id');
    Route::post('{id}/sync-jobs/{jobId}/cancel', \App\Http\Actions\Api\Stripe\CancelSyncJobAction::class)->whereNumber('id')->whereNumber('jobId');
    Route::get('{id}/sync-progress', \App\Http\Actions\Api\Stripe\GetSyncProgressAction::class)->whereNumber('id');
    Route::post('{id}/sync-payment-methods', \App\Http\Actions\Api\Stripe\SyncPaymentMethodsAction::class)->whereNumber('id');
    Route::post('{id}/sync-subscription-items', \App\Http\Actions\Api\Stripe\SyncSubscriptionItemsAction::class)->whereNumber('id');
});
