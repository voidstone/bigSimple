<?php

namespace App\Jobs\GenerateCatalog;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateCatalogMainJob extends AbstractJob
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->debug('start');

        //Сначала кешируем продукты
        GenerateCatalogCacheJob::dispatchNow();

        //Затем создаем цепочку заданий формирования файлов с ценами
        $chainPrice = $this->getChainPrices();

        //основные подзадачи
        $chainMain = [
          new GenerateCategoriesJob,//генерация категорий
          new GenerateDeliveriesJob,//генерация способов доставок
          new GeneratePointsJob,// генерация пунктов выдачи
        ];

        //Подзадачи которые должны выполняться самыми последними
        $chainLast = [
            //архивирование которые должны выполняться самыми последними
            new ArchiveUploadJob,
            //Отправка уведомлений стороннему сервису о том что можно скачать новый файл каталога товаров
            new SendPriceRequestJob,

        ];

        $chain = array_merge($chainPrice, $chainMain, $chainLast);

        GenerateGoodsFileJob::withChain($chain)->dispatch();
//        GenerateGoodsFileJob::dispatch()->chain($chain);

        $this->debug('finish');
    }

    protected function getChainPrices()
    {
        $result = [];
        $products = collect([1,2,3,4,5]);
        $fileNum =1;

        foreach ($products->chunk(1) as $chunk) {
            $result[] = new GeneratePricesFileChunkJob($chunk, $fileNum);
            $fileNum++;

        }

        return $result;
    }
}
