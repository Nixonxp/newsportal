<?php

namespace App\Services\NewsPartner;

use App;
use App\Exceptions\ServiceException;
use App\Jobs\ProccessSendAdminEmail;
use App\Jobs\SendNotifyNewsChiefEditor;
use App\Services\NewsPartner\Interfaces\NewsApiDataManagerInterface;
use App\Services\NewsPost\NewsPostService;
use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

class SyncManager
{
    public const LIMIT_MAX_NEWS_CATEGORY = 10;

    public function handle(bool $showResult = false): bool
    {
        try {
            $result = $this->syncHeadlinesNewsCategories();
            $this->toLog($result, $showResult);

            if (!empty($result['created'])) {
                SendNotifyNewsChiefEditor::dispatch((object)[
                    'message' => __('admin.partner_news_sync_success_new'),
                    'id' => $result['created']['id']
                ])->delay(10);
            }

        } catch (ServiceException $exception) {
            echo $exception . PHP_EOL;
        } catch (Throwable $exception) {
            Log::channel('database')->critical($exception);
            ProccessSendAdminEmail::dispatch((object)[
                'message' => __('admin.partner_news_sync_error')
            ])->delay(5);
            echo 'Error from sync service, please contact with administrator' . PHP_EOL;
        }

        return true;
    }

    /**
     * @return array
     * @throws Throwable
     */
    protected function syncHeadlinesNewsCategories(): array
    {
        $managerClass = $this->getDataManagerFromConfig();
        $manager = (new SyncNewsService(
            App::make($managerClass::class),
            App::make(NewsPostService::class))
        );
        return $manager->getNewsByAllCategories(self::LIMIT_MAX_NEWS_CATEGORY)->sync();
    }

    /**
     * @return NewsApiDataManagerInterface
     * @throws Throwable
     */
    protected function getDataManagerFromConfig(): NewsApiDataManagerInterface
    {
        $dataManagerName = config('news.default') . 'DataManager';
        $dataManageClass = __NAMESPACE__ . '\\DataManagers\\' . ucwords($dataManagerName);

        throw_if(!class_exists($dataManageClass), Exception::class,
            "Service manager [{$dataManageClass}] not found");

        return new $dataManageClass;
    }

    /**
     * @param array $result
     * @param bool $showResult
     * @return void
     */
    protected function toLog(array $result, bool $showResult): void
    {
        $message = !empty($result)
            ? 'Partner news update successful, result: ' . implode_r_key(', ', $result)
            : 'Partner news update process is successful, data is not need updated';

        if ($showResult) {
            echo $message . PHP_EOL;
        }

        Log::channel('database')->info($message);
    }
}
