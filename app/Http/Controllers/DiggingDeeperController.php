<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateCatalog\GenerateCatalogMainJob;
use App\Models\BlogPost;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DiggingDeeperController extends Controller
{
    public function collections() {
        $result = [];


        $eloquentCollection = BlogPost::withTrashed()->get();

//        dd(__Method__, $eloquentCollection, $eloquentCollection->toArray());

        $collections = collect($eloquentCollection->toArray());

//        dd(
//            get_class($eloquentCollection),
//            get_class($collections),
//            $collections
//        );

//        $result['first'] = $collections->first();
//        $result['last'] = $collections->last();
//
//        dd($result);

//        $result['where']['data'] = $collections
//            ->where('category_id', 10)
//            ->values()
//            ->keyBy('id');
//
//        $result['where']['count']= $result['where']['data']->count();
//        $result['where']['isEmpty']= $result['where']['data']->isEmpty();
//        $result['where']['isNotEmpty']= $result['where']['data']->isNotEmpty();


//        $result['where_first'] = $collections
//            ->firstWhere('created_at', '>', '2021-04-17 12:40:33');


//        $result['map']['all'] = $collections->map(function (array $item) {
//            $newItem = new \StdClass();
//            $newItem->item_id = $item['id'];
//            $newItem->item_name = $item['title'];
//            $newItem->exists = is_null($item['deleted_at']);
//
//            return $newItem;
//        });
//
//        $result['map']['not_exists']= $result['map']['all']
//            ->where('exists', false)
//            ->values()
//            ->keyBy('item_id');



        $collections->transform(function (array $item) {
            $newItem = new \StdClass();
            $newItem->item_id = $item['id'];
            $newItem->item_name = $item['title'];
            $newItem->exists = is_null($item['deleted_at']);
            $newItem->created_at = Carbon::parse($item['created_at']);

            return $newItem;
        });


//        $newItem = new \StdClass();
//        $newItem->id=9999;
//
//        $newItem2 = new \StdClass();
//        $newItem2->id=88888;

//        $collections->prepend($newItem);
//        $collections->push($newItem2);

//        $newItemFirst =  $collections->prepend($newItem)->first();
//        $newItemLast =  $collections->push($newItem2)->last();
//        $pullItem = $collections->pull(1);

//        dd(compact('collections','newItemFirst','newItemLast'));


//        $filtered = $collections->filter(function($item) {
//            $byDay = $item->created_at->isMonday() ;
//            $byDate = $item->created_at->day === 12;
//
//            $result = $byDay && $byDate;
//
//            return $result;
//        });
//
//        dd(compact('filtered'));

        $sortedSimpleCollection = collect([5,3,1,2,4])->sort()->values();
        $sortedAscCollection = $collections->sortBy('created_at');
        $sortedDescCollection = $collections->sortByDesc('item_id');

        dd(compact('sortedSimpleCollection','sortedAscCollection','sortedDescCollection'));

    }

    //
    //запустить цепочку jobs
    //php artisan queue:listen --queue=generate-catalog --tries=3 --delay=10
    public function prepareCatalog() {
        GenerateCatalogMainJob::dispatch();
    }
}
