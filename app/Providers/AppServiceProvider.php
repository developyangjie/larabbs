<?php

namespace App\Providers;

use App\Handlers\EsEngine;
use App\Models\Link;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Encore\Admin\Config\Config;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\EngineManager;
use Elasticsearch\ClientBuilder as ElasticBuilder;
use Vanry\Scout\Highlighter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
	{
		\App\Models\User::observe(\App\Observers\UserObserver::class);
		\App\Models\Reply::observe(\App\Observers\ReplyObserver::class);
		\App\Models\Topic::observe(\App\Observers\TopicObserver::class);
        \App\Models\Link::observe(\App\Observers\LinkObserver::class);

        //
        \Carbon\Carbon::setLocale("zh");

        Config::load();  // 加上这一行

        resolve(EngineManager::class)->extend('es', function($app) {
            return new EsEngine(ElasticBuilder::create()
                ->setHosts(config('scout.elasticsearch.hosts'))
                ->build(),
                config('scout.elasticsearch.index')
            );
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if(app()->isLocal()){
            $this->app->register(\VIACreative\SudoSu\ServiceProvider::class);
        }

        if($this->app->environment() !== "production"){
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }
}
